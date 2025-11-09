<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'original_filename',
        'original_file_path',
        'original_mime_type',
        'original_file_size',
        'converted_csv_path',
        'row_count',
        'column_mapping',
        'notes',
    ];

    protected $casts = [
        'column_mapping' => 'array',
        'original_file_size' => 'integer',
        'row_count' => 'integer',
    ];

    /**
     * Get the user that owns the file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the message logs for this file.
     * @deprecated DISABLED - Automation feature has been removed.
     */
    // public function messageLogs(): HasMany
    // {
    //     return $this->hasMany(MessageLog::class);
    // }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->original_file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get phone column name from mapping.
     */
    public function getPhoneColumnAttribute(): ?string
    {
        return $this->column_mapping['phone_column'] ?? null;
    }

    /**
     * Get name column name from mapping.
     */
    public function getNameColumnAttribute(): ?string
    {
        return $this->column_mapping['name_column'] ?? null;
    }
}
