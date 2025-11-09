<?php

namespace App\Jobs;

use App\Models\MessageLog;
use App\Services\HighLevelApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MessageLog $messageLog
    ) {}

    /**
     * Execute the job.
     */
    public function handle(HighLevelApiService $highLevelApi): void
    {
        try {
            // Skip if already sent
            if ($this->messageLog->status === 'sent') {
                return;
            }

            Log::info("Sending WhatsApp message", [
                'message_log_id' => $this->messageLog->id,
                'phone' => $this->messageLog->recipient_phone,
                'template_id' => $this->messageLog->template_id,
            ]);

            // Prepare template data
            $templateData = [];
            if ($this->messageLog->recipient_name) {
                $templateData['name'] = $this->messageLog->recipient_name;
            }

            // Send message via HighLevel API
            $response = $highLevelApi->sendTemplateMessage(
                $this->messageLog->recipient_phone,
                $this->messageLog->template_id,
                $templateData
            );

            // Update message log
            $this->messageLog->update([
                'status' => 'sent',
                'highlevel_message_id' => $response['messageId'] ?? $response['id'] ?? null,
                'api_response' => $response,
                'sent_at' => now(),
            ]);

            // Update campaign statistics
            $campaign = $this->messageLog->campaign;
            $campaign->increment('total_sent');
            $campaign->decrement('total_pending');

            // Check if campaign is completed
            if ($campaign->total_pending === 0) {
                $campaign->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            Log::info("WhatsApp message sent successfully", [
                'message_log_id' => $this->messageLog->id,
                'highlevel_message_id' => $this->messageLog->highlevel_message_id,
            ]);
        } catch (Exception $e) {
            $this->handleFailure($e);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->handleFailure($exception);
    }

    /**
     * Handle message sending failure.
     */
    private function handleFailure(\Throwable $exception): void
    {
        Log::error("Failed to send WhatsApp message", [
            'message_log_id' => $this->messageLog->id,
            'phone' => $this->messageLog->recipient_phone,
            'error' => $exception->getMessage(),
            'attempt' => $this->attempts(),
        ]);

        // Update message log
        $this->messageLog->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
            'retry_count' => $this->attempts(),
            'last_retry_at' => now(),
        ]);

        // Update campaign statistics
        $campaign = $this->messageLog->campaign;
        $campaign->increment('total_failed');
        $campaign->decrement('total_pending');

        // Check if all messages have been processed (sent or failed)
        if ($campaign->total_pending === 0) {
            $campaign->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }
    }
}
