<?php

namespace App\Http\Controllers;

use App\Models\ContactImportJob;
use App\Models\UploadedFile;
use App\Services\HighLevelApiService;
use App\Jobs\ProcessContactImportJob;
use Illuminate\Http\Request;
use Exception;

class ContactImportController extends Controller
{
    public function __construct(
        private HighLevelApiService $highLevelApi
    ) {}

    /**
     * Display import creation page.
     */
    public function index()
    {
        $user = auth()->user();

        // Check if HighLevel is connected
        if (!$user->highlevel_connected) {
            return redirect()->route('settings.index')
                ->withErrors(['error' => 'Please connect your HighLevel account first.']);
        }

        // Get user's uploaded files
        $files = $user->uploadedFiles()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get HighLevel tags
        try {
            $tags = $this->highLevelApi->getTags();
        } catch (Exception $e) {
            $tags = [];
            session()->flash('warning', 'Could not load tags from HighLevel: ' . $e->getMessage());
        }

        return view('contact-import.index', compact('files', 'tags'));
    }

    /**
     * Store a new import job.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file_ids' => 'required|array|min:1',
            'file_ids.*' => 'exists:uploaded_files,id',
            'selected_tags' => 'nullable|array',
            'new_tags' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        // Process new tags
        $newTagsArray = [];
        if ($request->new_tags) {
            $newTagsArray = array_map('trim', explode(',', $request->new_tags));
            $newTagsArray = array_filter($newTagsArray);
        }

        // Calculate total contacts
        $totalContacts = UploadedFile::whereIn('id', $request->file_ids)
            ->sum('row_count');

        // Create import job
        $importJob = ContactImportJob::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'selected_file_ids' => $request->file_ids,
            'selected_tags' => $request->selected_tags ?? [],
            'new_tags' => $newTagsArray,
            'total_contacts' => $totalContacts,
            'total_pending' => $totalContacts,
            'total_imported' => 0,
            'total_failed' => 0,
            'status' => 'pending',
        ]);

        // Dispatch job to process the import
        ProcessContactImportJob::dispatch($importJob);

        return redirect()->route('contact-import.show', $importJob)
            ->with('success', 'Import job created successfully! Processing will begin shortly.');
    }

    /**
     * Display list of import jobs.
     */
    public function list()
    {
        $user = auth()->user();

        $importJobs = $user->contactImportJobs()
            ->latest()
            ->paginate(10);

        return view('contact-import.list', compact('importJobs'));
    }

    /**
     * Show specific import job details.
     */
    public function show(ContactImportJob $importJob)
    {
        // Check authorization
        if ($importJob->user_id !== auth()->id()) {
            abort(403);
        }

        $importJob->load('contactLogs');

        return view('contact-import.show', compact('importJob'));
    }

    /**
     * Cancel an import job.
     */
    public function cancel(ContactImportJob $importJob)
    {
        // Check authorization
        if ($importJob->user_id !== auth()->id()) {
            abort(403);
        }

        if ($importJob->status === 'completed' || $importJob->status === 'failed') {
            return back()->withErrors(['error' => 'Cannot cancel a completed or failed import.']);
        }

        $importJob->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Import job cancelled successfully.');
    }
}
