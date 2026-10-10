<?php

namespace App\Modules\Profiles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HearingProfile extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'notes',
        'is_active',
        'source_session_id',
    ];

    protected function casts(): array
    {
        return [
            'notes'     => 'encrypted',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thresholds(): HasMany
    {
        return $this->hasMany(HearingThreshold::class, 'profile_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
