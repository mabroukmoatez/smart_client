<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'highlevel_api_token',
        'highlevel_location_id',
        'highlevel_connected',
        'highlevel_connected_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'highlevel_connected' => 'boolean',
            'highlevel_connected_at' => 'datetime',
        ];
    }

    /**
     * Get the uploaded files for the user.
     */
    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(UploadedFile::class);
    }

    /**
     * Get the automation campaigns for the user.
     * @deprecated Use contactImportJobs() instead
     */
    public function automationCampaigns(): HasMany
    {
        return $this->hasMany(AutomationCampaign::class);
    }

    /**
     * Get the contact import jobs for the user.
     */
    public function contactImportJobs(): HasMany
    {
        return $this->hasMany(ContactImportJob::class);
    }
}
