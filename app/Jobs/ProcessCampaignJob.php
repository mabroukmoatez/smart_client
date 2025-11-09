<?php

namespace App\Jobs;

use App\Models\AutomationCampaign;
use App\Models\MessageLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public AutomationCampaign $campaign
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if campaign is still scheduled
        if ($this->campaign->status !== 'scheduled') {
            Log::info("Campaign {$this->campaign->id} is not scheduled, skipping.");
            return;
        }

        // Update campaign status
        $this->campaign->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);

        Log::info("Processing campaign {$this->campaign->id}");

        // Get all pending message logs
        $pendingMessages = MessageLog::where('campaign_id', $this->campaign->id)
            ->where('status', 'pending')
            ->get();

        // Dispatch individual send jobs with rate limiting
        foreach ($pendingMessages as $index => $messageLog) {
            // Calculate delay for rate limiting
            // Send 10 messages per minute = 1 message every 6 seconds
            $delay = now()->addSeconds($index * 6);

            SendWhatsAppMessageJob::dispatch($messageLog)
                ->delay($delay);
        }

        Log::info("Dispatched {$pendingMessages->count()} message jobs for campaign {$this->campaign->id}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Campaign {$this->campaign->id} processing failed", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        $this->campaign->update([
            'status' => 'failed',
        ]);
    }
}
