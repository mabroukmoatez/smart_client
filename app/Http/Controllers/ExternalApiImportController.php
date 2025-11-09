<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Services\ExternalApiService;
use App\Services\FileProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Exception;

class ExternalApiImportController extends Controller
{
    public function __construct(
        private ExternalApiService $externalApi,
        private FileProcessingService $fileProcessor
    ) {}

    /**
     * Show import from external API page.
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->external_api_connected) {
            return redirect()->route('settings.index')
                ->with('warning', 'Please connect your external API first.');
        }

        return view('external-api.import');
    }

    /**
     * Fetch and preview clients from external API.
     */
    public function preview(Request $request)
    {
        $user = auth()->user();

        if (!$user->external_api_connected) {
            return back()->withErrors(['error' => 'External API not connected.']);
        }

        try {
            $apiUrl = $user->external_api_url;
            $apiToken = Crypt::decryptString($user->external_api_token);

            // Fetch clients from external API
            $clients = $this->externalApi->fetchClients($apiUrl, $apiToken);

            // Normalize client data
            $normalizedClients = [];
            $skippedCount = 0;

            foreach ($clients as $client) {
                $normalized = $this->externalApi->normalizeClientData($client);

                if ($normalized) {
                    $normalizedClients[] = $normalized;
                } else {
                    $skippedCount++;
                }
            }

            if (empty($normalizedClients)) {
                throw new Exception('No valid clients found. All clients are missing phone numbers.');
            }

            // Store preview data in session
            session([
                'external_api_preview' => [
                    'clients' => $normalizedClients,
                    'total_count' => count($clients),
                    'valid_count' => count($normalizedClients),
                    'skipped_count' => $skippedCount,
                ],
            ]);

            return redirect()->route('external-api.confirm');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show preview and confirmation page.
     */
    public function confirm()
    {
        $previewData = session('external_api_preview');

        if (!$previewData) {
            return redirect()->route('external-api.index')
                ->withErrors(['error' => 'No preview data found. Please fetch clients first.']);
        }

        // Get first 10 for preview
        $preview = array_slice($previewData['clients'], 0, 10);

        return view('external-api.confirm', [
            'preview' => $preview,
            'totalCount' => $previewData['total_count'],
            'validCount' => $previewData['valid_count'],
            'skippedCount' => $previewData['skipped_count'],
        ]);
    }

    /**
     * Import clients from external API and create file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'filename' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $previewData = session('external_api_preview');

        if (!$previewData) {
            return redirect()->route('external-api.index')
                ->withErrors(['error' => 'Session expired. Please fetch clients again.']);
        }

        try {
            $clients = $previewData['clients'];

            // Create CSV file
            $csvFileName = 'external_api_' . time() . '.csv';
            $userId = auth()->id();
            $csvPath = "uploads/{$userId}/converted/{$csvFileName}";

            // Build CSV content
            $headers = ['phone', 'name', 'email'];
            $rows = array_map(function ($client) {
                return [
                    $client['phone'],
                    $client['name'] ?? '',
                    $client['email'] ?? '',
                ];
            }, $clients);

            $csvContent = $this->arrayToCsv(array_merge([$headers], $rows));
            Storage::put($csvPath, $csvContent);

            // Create uploaded file record
            $uploadedFile = UploadedFile::create([
                'user_id' => $userId,
                'original_filename' => $request->filename . '.csv',
                'original_file_path' => $csvPath, // Same as CSV since it's API import
                'original_mime_type' => 'text/csv',
                'original_file_size' => strlen($csvContent),
                'converted_csv_path' => $csvPath,
                'row_count' => count($clients),
                'column_mapping' => [
                    'phone_column' => 'phone',
                    'name_column' => 'name',
                ],
                'notes' => ($request->notes ?? '') . "\n\nImported from External API: " . auth()->user()->external_api_url,
            ]);

            // Clear session
            session()->forget('external_api_preview');

            return redirect()->route('files.preview', $uploadedFile)
                ->with('success', "Successfully imported {$previewData['valid_count']} clients from your external API!");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Convert array to CSV string.
     */
    private function arrayToCsv(array $data): string
    {
        $handle = fopen('php://temp', 'r+');

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }
}
