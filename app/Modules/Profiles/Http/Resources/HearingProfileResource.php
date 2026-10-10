<?php

namespace App\Modules\Profiles\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HearingProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'notes'             => $this->notes,
            'is_active'         => $this->is_active,
            'source_session_id' => $this->source_session_id,
            'created_at'        => $this->created_at->toIso8601String(),
            'updated_at'        => $this->updated_at->toIso8601String(),
            'thresholds'        => HearingThresholdResource::collection($this->whenLoaded('thresholds')),
            'threshold_count'   => $this->when(! $this->relationLoaded('thresholds'), fn () => $this->thresholds()->count()),
        ];
    }
}
