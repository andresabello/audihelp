<?php

namespace App\Modules\Profiles\Models;

use App\Modules\Profiles\Enums\EarSide;
use App\Modules\Profiles\Enums\ThresholdResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HearingThreshold extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'profile_id',
        'ear',
        'frequency_hz',
        'level_dbfs',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'ear'          => EarSide::class,
            'response'     => ThresholdResponse::class,
            'frequency_hz' => 'integer',
            'level_dbfs'   => 'float',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(HearingProfile::class, 'profile_id');
    }
}
