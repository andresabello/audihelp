<?php

namespace App\Modules\LiveSessions\Models;

use App\Models\User;
use App\Modules\DspPresets\Models\DspPreset;
use App\Modules\Profiles\Models\HearingProfile;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveSession extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'preset_id',
        'profile_id',
        'notes',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'notes'      => 'encrypted',
            'started_at' => 'datetime',
            'ended_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preset(): BelongsTo
    {
        return $this->belongsTo(DspPreset::class, 'preset_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(HearingProfile::class, 'profile_id');
    }

    public function getDurationSecondsAttribute(): ?int
    {
        if ($this->started_at && $this->ended_at) {
            return (int) $this->started_at->diffInSeconds($this->ended_at);
        }

        return null;
    }
}
