<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
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
        'clearance_level',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
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
            'two_factor_confirmed_at' => 'datetime',
            'clearance_level' => 'integer',
        ];
    }

    /**
     * Get the user's clearance level name
     */
    public function getClearanceNameAttribute(): string
    {
        return match($this->clearance_level ?? 1) {
            1 => 'Level 1 - Basic',
            2 => 'Level 2 - Operator',
            3 => 'Level 3 - Analyst',
            4 => 'Level 4 - Senior',
            5 => 'Level 5 - Lead',
            default => 'Unknown',
        };
    }

    /**
     * Check if user has minimum clearance level
     */
    public function hasClearance(int $level): bool
    {
        return ($this->clearance_level ?? 1) >= $level;
    }

    /**
     * Check if user is admin (clearance level 5)
     */
    public function isAdmin(): bool
    {
        return $this->hasClearance(5);
    }

    /**
     * Check if user has enabled two-factor authentication
     */
    public function hasTwoFactorAuth(): bool
    {
        return !empty($this->two_factor_secret) &&
               !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Send email verification notification
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
}
