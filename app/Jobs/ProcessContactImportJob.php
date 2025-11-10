<?php

namespace App\Jobs;

use App\Models\ContactImportJob;
use App\Models\ContactImportLog;
use App\Models\UploadedFile;
use App\Services\HighLevelApiService;
use App\Services\FileProcessingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessContactImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour
    public $tries = 3; // Retry up to 3 times
    public $backoff = 60; // Wait 60 seconds between retries

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContactImportJob $importJob
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        HighLevelApiService $highLevelApi,
        FileProcessingService $fileProcessor
    ): void
    {
        try {
            // Only update to processing on first attempt
            if ($this->attempts() === 1) {
                $this->importJob->update([
                    'status' => 'processing',
                    'started_at' => now(),
                ]);
            }

            Log::info('Contact Import: Starting', [
                'job_id' => $this->importJob->id,
                'total_contacts' => $this->importJob->total_contacts,
                'attempt' => $this->attempts(),
            ]);

            // Get all tags to apply
            $tags = $this->importJob->all_tags;

            // Process each file
            foreach ($this->importJob->uploadedFiles() as $file) {
                $this->processFile($file, $tags, $highLevelApi, $fileProcessor);
            }

            // Update final status
            $this->importJob->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            Log::info('Contact Import: Completed', [
                'job_id' => $this->importJob->id,
                'total_imported' => $this->importJob->total_imported,
                'total_failed' => $this->importJob->total_failed,
            ]);

        } catch (Exception $e) {
            Log::error('Contact Import: Failed', [
                'job_id' => $this->importJob->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attempt' => $this->attempts(),
                'max_tries' => $this->tries,
            ]);

            // Only mark as failed if this was the last attempt
            if ($this->attempts() >= $this->tries) {
                $this->importJob->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                ]);
            }

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        Log::error('Contact Import: Job permanently failed after all retries', [
            'job_id' => $this->importJob->id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);

        $this->importJob->update([
            'status' => 'failed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Process contacts from a file.
     */
    private function processFile(
        UploadedFile $file,
        array $tags,
        HighLevelApiService $highLevelApi,
        FileProcessingService $fileProcessor
    ): void
    {
        try {
            // Read CSV file
            $contacts = $fileProcessor->readCsvFile($file->converted_csv_path);

            Log::info('Contact Import: File read complete', [
                'job_id' => $this->importJob->id,
                'file_id' => $file->id,
                'file_path' => $file->converted_csv_path,
                'total_rows_read' => count($contacts),
                'first_row' => $contacts[0] ?? null,
            ]);

            if (empty($contacts)) {
                Log::warning('Contact Import: CSV file is empty', [
                    'job_id' => $this->importJob->id,
                    'file_id' => $file->id,
                    'file_path' => $file->converted_csv_path,
                ]);
                return;
            }

            $processedCount = 0;
            foreach ($contacts as $index => $row) {
                // Skip header row
                if ($index === 0) {
                    Log::debug('Contact Import: Skipping header row', [
                        'job_id' => $this->importJob->id,
                        'header_row' => $row,
                    ]);
                    continue;
                }

                Log::debug('Contact Import: Processing row', [
                    'job_id' => $this->importJob->id,
                    'index' => $index,
                    'row_data' => $row,
                ]);

                $this->processContact($row, $file, $tags, $highLevelApi);
                $processedCount++;

                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second
            }

            Log::info('Contact Import: File processing complete', [
                'job_id' => $this->importJob->id,
                'file_id' => $file->id,
                'rows_processed' => $processedCount,
            ]);

        } catch (Exception $e) {
            Log::error('Contact Import: Failed to process file', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Process a single contact.
     */
    private function processContact(
        array $row,
        UploadedFile $file,
        array $tags,
        HighLevelApiService $highLevelApi
    ): void
    {
        $phone = $row['normalized_phone'] ?? $row['phone'] ?? null;
        $name = $row['name'] ?? null;
        $email = $row['email'] ?? null;

        // Log available columns for debugging
        Log::debug('Contact Import: Processing row', [
            'job_id' => $this->importJob->id,
            'available_columns' => array_keys($row),
            'phone' => $phone,
            'name' => $name,
            'email' => $email,
        ]);

        // Skip if no phone
        if (empty($phone)) {
            // Create log entry for missing phone
            ContactImportLog::create([
                'import_job_id' => $this->importJob->id,
                'uploaded_file_id' => $file->id,
                'contact_phone' => null,
                'contact_name' => $name,
                'contact_data' => $row,
                'assigned_tags' => $tags,
                'status' => 'failed',
                'error_message' => 'Phone number is missing or empty. Available columns: ' . implode(', ', array_keys($row)),
            ]);

            $this->importJob->increment('total_failed');
            $this->importJob->decrement('total_pending');

            Log::warning('Contact Import: Skipped row - no phone number', [
                'job_id' => $this->importJob->id,
                'row_data' => $row,
            ]);
            return;
        }

        try {
            // Prepare contact data
            $contactData = [
                'phone' => $phone,
            ];

            if ($name) {
                $contactData['name'] = $name;
            }

            if ($email) {
                $contactData['email'] = $email;
            }

            // Create/update contact in HighLevel
            $result = $highLevelApi->upsertContact($contactData, $tags);

            // Determine if created or updated
            $action = $result['_action'] ?? 'unknown';

            // Log success
            ContactImportLog::create([
                'import_job_id' => $this->importJob->id,
                'uploaded_file_id' => $file->id,
                'contact_phone' => $phone,
                'contact_name' => $name,
                'highlevel_contact_id' => $result['id'] ?? null,
                'contact_data' => array_merge($contactData, ['action' => $action]),
                'assigned_tags' => $tags,
                'api_response' => $result,
                'status' => 'sent', // Using 'sent' for backward compatibility
                'imported_at' => now(),
            ]);

            $this->importJob->increment('total_imported');
            $this->importJob->decrement('total_pending');

            Log::info('Contact Import: Contact processed', [
                'job_id' => $this->importJob->id,
                'phone' => $phone,
                'action' => $action,
                'contact_id' => $result['id'] ?? null,
            ]);

        } catch (Exception $e) {
            // Log failure
            ContactImportLog::create([
                'import_job_id' => $this->importJob->id,
                'uploaded_file_id' => $file->id,
                'contact_phone' => $phone,
                'contact_name' => $name,
                'contact_data' => ['phone' => $phone, 'name' => $name, 'email' => $email],
                'assigned_tags' => $tags,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $this->importJob->increment('total_failed');
            $this->importJob->decrement('total_pending');

            Log::error('Contact Import: Failed to import contact', [
                'job_id' => $this->importJob->id,
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
