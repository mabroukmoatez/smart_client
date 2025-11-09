<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactImportJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'selected_file_ids',
        'selected_tags',
        'new_tags',
        'scheduled_at',
        'started_at',
        'completed_at',
        'total_contacts',
        'total_imported',
        'total_failed',
        'total_pending',
        'status',
    ];

    protected $casts = [
        'selected_file_ids' => 'array',
        'selected_tags' => 'array',
        'new_tags' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the import job.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contact import logs for this job.
     */
    public function contactLogs(): HasMany
    {
        return $this->hasMany(ContactImportLog::class, 'import_job_id');
    }

    /**
     * Get the uploaded files for this import job.
     */
    public function uploadedFiles()
    {
        return UploadedFile::whereIn('id', $this->selected_file_ids ?? [])->get();
    }

    /**
     * Calculate completion percentage.
     */
    public function getCompletionPercentageAttribute(): float
    {
        if ($this->total_contacts === 0) {
            return 0;
        }

        $processed = $this->total_imported + $this->total_failed;
        return round(($processed / $this->total_contacts) * 100, 2);
    }

    /**
     * Calculate success rate.
     */
    public function getSuccessRateAttribute(): float
    {
        $processed = $this->total_imported + $this->total_failed;

        if ($processed === 0) {
            return 0;
        }

        return round(($this->total_imported / $processed) * 100, 2);
    }

    /**
     * Check if import is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if import is processing.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if import is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if import has failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get all tags (selected + new).
     */
    public function getAllTagsAttribute(): array
    {
        return array_merge(
            $this->selected_tags ?? [],
            $this->new_tags ?? []
        );
    }
}
