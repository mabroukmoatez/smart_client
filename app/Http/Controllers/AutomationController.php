<?php

namespace App\Http\Controllers;

use App\Models\AutomationCampaign;
use App\Models\MessageLog;
use App\Models\UploadedFile;
use App\Services\HighLevelApiService;
use App\Services\FileProcessingService;
use App\Services\PhoneNormalizationService;
use App\Jobs\ProcessCampaignJob;
use Illuminate\Http\Request;
use Exception;

class AutomationController extends Controller
{
    public function __construct(
        private HighLevelApiService $highLevelApi,
        private FileProcessingService $fileProcessor,
        private PhoneNormalizationService $phoneNormalizer
    ) {}

    /**
     * Display automation dashboard.
     */
    public function index()
    {
        $campaigns = auth()->user()->automationCampaigns()
            ->latest()
            ->paginate(10);

        return view('automation.index', compact('campaigns'));
    }

    /**
     * Show create campaign form.
     */
    public function create()
    {
        // Get user's uploaded files
        $files = auth()->user()->uploadedFiles()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get WhatsApp templates from HighLevel
        try {
            $templates = $this->highLevelApi->getWhatsAppTemplates();
        } catch (Exception $e) {
            $templates = [];
            session()->flash('warning', 'Could not load templates: ' . $e->getMessage());
        }

        return view('automation.create', compact('files', 'templates'));
    }

    /**
     * Calculate statistics for selected files.
     */
    public function calculateStats(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:uploaded_files,id',
        ]);

        $files = UploadedFile::whereIn('id', $request->file_ids)
            ->where('user_id', auth()->id())
            ->get();

        $totalRecipients = 0;
        $validPhones = 0;
        $invalidPhones = 0;

        foreach ($files as $file) {
            $data = $this->fileProcessor->readCsvWithMapping(
                $file->converted_csv_path,
                $file->column_mapping
            );

            $totalRecipients += count($data);

            foreach ($data as $row) {
                $normalized = $this->phoneNormalizer->normalize($row['phone']);
                if ($normalized) {
                    $validPhones++;
                } else {
                    $invalidPhones++;
                }
            }
        }

        return response()->json([
            'total_recipients' => $totalRecipients,
            'valid_phones' => $validPhones,
            'invalid_phones' => $invalidPhones,
            'files_count' => $files->count(),
        ]);
    }

    /**
     * Store new campaign.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file_ids' => 'required|array|min:1',
            'file_ids.*' => 'exists:uploaded_files,id',
            'template_id' => 'nullable|string',
            'template_name' => 'required|string',
            'scheduled_at' => 'required|date|after:now',
        ]);

        try {
            // Verify files belong to user
            $files = UploadedFile::whereIn('id', $request->file_ids)
                ->where('user_id', auth()->id())
                ->get();

            if ($files->count() !== count($request->file_ids)) {
                throw new Exception('Some files are not accessible.');
            }

            // Calculate total recipients
            $totalRecipients = 0;
            foreach ($files as $file) {
                $data = $this->fileProcessor->readCsvWithMapping(
                    $file->converted_csv_path,
                    $file->column_mapping
                );

                // Count valid phones only
                foreach ($data as $row) {
                    if ($this->phoneNormalizer->normalize($row['phone'])) {
                        $totalRecipients++;
                    }
                }
            }

            // Create campaign
            $campaign = AutomationCampaign::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'template_id' => $request->template_id ?? $request->template_name, // Use template_name as fallback
                'template_name' => $request->template_name,
                'selected_file_ids' => $request->file_ids,
                'scheduled_at' => $request->scheduled_at,
                'total_recipients' => $totalRecipients,
                'total_pending' => $totalRecipients,
                'status' => 'scheduled',
            ]);

            // Create message logs for all recipients
            $this->createMessageLogs($campaign, $files);

            // Dispatch job to process campaign at scheduled time
            ProcessCampaignJob::dispatch($campaign)
                ->delay($request->scheduled_at);

            return redirect()->route('automation.show', $campaign)
                ->with('success', 'Campaign created and scheduled successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show campaign details.
     */
    public function show(AutomationCampaign $campaign)
    {
        // Check authorization
        if ($campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $campaign->load('messageLogs');

        // Get statistics
        $stats = [
            'total' => $campaign->total_recipients,
            'sent' => $campaign->total_sent,
            'failed' => $campaign->total_failed,
            'pending' => $campaign->total_pending,
            'completion_percentage' => $campaign->completion_percentage,
            'success_rate' => $campaign->success_rate,
        ];

        // Get recent message logs
        $recentLogs = $campaign->messageLogs()
            ->latest()
            ->limit(50)
            ->get();

        return view('automation.show', compact('campaign', 'stats', 'recentLogs'));
    }

    /**
     * Cancel a campaign.
     */
    public function cancel(AutomationCampaign $campaign)
    {
        // Check authorization
        if ($campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($campaign->status, ['draft', 'scheduled'])) {
            return back()->withErrors(['error' => 'Cannot cancel a campaign that is already processing or completed.']);
        }

        $campaign->update(['status' => 'cancelled']);

        return redirect()->route('automation.index')
            ->with('success', 'Campaign cancelled successfully!');
    }

    /**
     * Create message logs for campaign.
     */
    private function createMessageLogs(AutomationCampaign $campaign, $files): void
    {
        foreach ($files as $file) {
            $data = $this->fileProcessor->readCsvWithMapping(
                $file->converted_csv_path,
                $file->column_mapping
            );

            foreach ($data as $row) {
                $normalizedPhone = $this->phoneNormalizer->normalize($row['phone']);

                // Skip invalid phones
                if (!$normalizedPhone) {
                    continue;
                }

                MessageLog::create([
                    'campaign_id' => $campaign->id,
                    'uploaded_file_id' => $file->id,
                    'recipient_phone' => $normalizedPhone,
                    'recipient_name' => $row['name'],
                    'template_id' => $campaign->template_id,
                    'status' => 'pending',
                ]);
            }
        }
    }
}
