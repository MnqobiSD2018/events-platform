<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'team',
        'department',
        'employee_role',
        'privacy_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'privacy_settings' => 'array',
        ];
    }

    public const TYPE_COMPANY_ADMIN = 'company_admin';

    public const TYPE_EMPLOYEE = 'employee';

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function announcementReads(): HasMany
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function trackerConnections(): HasMany
    {
        return $this->hasMany(TrackerConnection::class);
    }

    public function trackerSyncImports(): HasMany
    {
        return $this->hasMany(TrackerSyncImport::class);
    }

    public function isEmployee(): bool
    {
        return $this->user_type === self::TYPE_EMPLOYEE;
    }

    public function isCompanyAdmin(): bool
    {
        return $this->user_type === self::TYPE_COMPANY_ADMIN;
    }
}
