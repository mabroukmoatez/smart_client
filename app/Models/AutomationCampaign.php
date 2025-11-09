<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutomationCampaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'template_id',
        'template_name',
        'selected_file_ids',
        'scheduled_at',
        'started_at',
        'completed_at',
        'total_recipients',
        'total_sent',
        'total_failed',
        'total_pending',
        'status',
    ];

    protected $casts = [
        'selected_file_ids' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_recipients' => 'integer',
        'total_sent' => 'integer',
        'total_failed' => 'integer',
        'total_pending' => 'integer',
    ];

    /**
     * Get the user that owns the campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the message logs for this campaign.
     */
    public function messageLogs(): HasMany
    {
        return $this->hasMany(MessageLog::class, 'campaign_id');
    }

    /**
     * Get the selected files for this campaign.
     */
    public function selectedFiles()
    {
        return UploadedFile::whereIn('id', $this->selected_file_ids ?? [])->get();
    }

    /**
     * Check if campaign is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if campaign is in progress.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if campaign is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get completion percentage.
     */
    public function getCompletionPercentageAttribute(): float
    {
        if ($this->total_recipients === 0) {
            return 0;
        }

        return round(($this->total_sent + $this->total_failed) / $this->total_recipients * 100, 2);
    }

    /**
     * Get success rate.
     */
    public function getSuccessRateAttribute(): float
    {
        $completed = $this->total_sent + $this->total_failed;

        if ($completed === 0) {
            return 0;
        }

        return round($this->total_sent / $completed * 100, 2);
    }
}
