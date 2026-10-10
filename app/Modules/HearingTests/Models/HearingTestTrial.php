<?php

namespace App\Modules\HearingTests\Models;

use App\Modules\HearingTests\Enums\EarSide;
use App\Modules\HearingTests\Enums\PatternType;
use App\Modules\HearingTests\Enums\TrialResponse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HearingTestTrial extends Model
{
    use HasUuids, HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'session_id',
        'ear',
        'frequency_hz',
        'level_dbfs',
        'pattern_type',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'ear'          => EarSide::class,
            'pattern_type' => PatternType::class,
            'response'     => TrialResponse::class,
            'frequency_hz' => 'integer',
            'level_dbfs'   => 'float',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(HearingTestSession::class, 'session_id');
    }
}
