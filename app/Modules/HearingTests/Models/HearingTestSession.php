<?php

namespace App\Modules\HearingTests\Models;

use App\Models\User;
use App\Modules\HearingTests\Enums\EarSide;
use App\Modules\HearingTests\Enums\SessionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HearingTestSession extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ear_tested',
        'notes',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'ear_tested'   => EarSide::class,
            'status'       => SessionStatus::class,
            'notes'        => 'encrypted',
            'started_at'   => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trials(): HasMany
    {
        return $this->hasMany(HearingTestTrial::class, 'session_id');
    }
}
