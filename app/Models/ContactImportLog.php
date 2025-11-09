<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_job_id',
        'uploaded_file_id',
        'contact_phone',
        'contact_name',
        'highlevel_contact_id',
        'contact_data',
        'assigned_tags',
        'api_response',
        'status',
        'error_message',
        'retry_count',
        'last_retry_at',
        'imported_at',
    ];

    protected $casts = [
        'contact_data' => 'array',
        'assigned_tags' => 'array',
        'api_response' => 'array',
        'last_retry_at' => 'datetime',
        'imported_at' => 'datetime',
    ];

    /**
     * Get the import job that owns this log.
     */
    public function importJob(): BelongsTo
    {
        return $this->belongsTo(ContactImportJob::class, 'import_job_id');
    }

    /**
     * Get the uploaded file for this log.
     */
    public function uploadedFile(): BelongsTo
    {
        return $this->belongsTo(UploadedFile::class);
    }

    /**
     * Check if contact was imported successfully.
     */
    public function isImported(): bool
    {
        return $this->status === 'sent'; // Keep 'sent' for backward compatibility, or change to 'imported'
    }

    /**
     * Check if import failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if can retry import.
     */
    public function canRetry(): bool
    {
        return $this->status === 'failed' && $this->retry_count < 3;
    }
}
