<?php

namespace App\Modules\LiveSessions\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'preset_id'        => $this->preset_id,
            'profile_id'       => $this->profile_id,
            'notes'            => $this->notes,
            'started_at'       => $this->started_at?->toIso8601String(),
            'ended_at'         => $this->ended_at?->toIso8601String(),
            'duration_seconds' => $this->duration_seconds,
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}
