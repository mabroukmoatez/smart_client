<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'uploaded_file_id',
        'recipient_phone',
        'recipient_name',
        'template_id',
        'message_content',
        'highlevel_message_id',
        'api_response',
        'status',
        'error_message',
        'retry_count',
        'last_retry_at',
        'sent_at',
    ];

    protected $casts = [
        'api_response' => 'array',
        'retry_count' => 'integer',
        'last_retry_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the campaign that owns the message log.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AutomationCampaign::class, 'campaign_id');
    }

    /**
     * Get the uploaded file that owns the message log.
     */
    public function uploadedFile(): BelongsTo
    {
        return $this->belongsTo(UploadedFile::class);
    }

    /**
     * Check if message was sent successfully.
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if message failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if message is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if message can be retried.
     */
    public function canRetry(): bool
    {
        return $this->isFailed() && $this->retry_count < 3;
    }
}
