<?php

namespace App\Modules\HearingTests\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HearingTestSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'ear_tested'   => $this->ear_tested,
            'status'       => $this->status,
            'notes'        => $this->notes,
            'started_at'   => $this->started_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'created_at'   => $this->created_at->toIso8601String(),
            'trials'       => HearingTestTrialResource::collection($this->whenLoaded('trials')),
            'trial_count'  => $this->when(! $this->relationLoaded('trials'), fn () => $this->trials()->count()),
        ];
    }
}
