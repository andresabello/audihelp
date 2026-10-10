<?php

namespace App\Modules\Profiles\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HearingThresholdResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'ear'          => $this->ear,
            'frequency_hz' => $this->frequency_hz,
            'level_dbfs'   => $this->level_dbfs,
            'response'     => $this->response,
            'created_at'   => $this->created_at->toIso8601String(),
        ];
    }
}
