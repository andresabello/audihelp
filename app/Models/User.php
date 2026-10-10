<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'google_id',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function hearingTestSessions(): HasMany
    {
        return $this->hasMany(\App\Modules\HearingTests\Models\HearingTestSession::class);
    }

    public function hearingProfiles(): HasMany
    {
        return $this->hasMany(\App\Modules\Profiles\Models\HearingProfile::class);
    }

    public function dspPresets(): HasMany
    {
        return $this->hasMany(\App\Modules\DspPresets\Models\DspPreset::class);
    }

    public function liveSessions(): HasMany
    {
        return $this->hasMany(\App\Modules\LiveSessions\Models\LiveSession::class);
    }
}
