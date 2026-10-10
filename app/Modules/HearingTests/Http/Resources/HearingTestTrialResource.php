<?php

namespace App\Modules\HearingTests\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HearingTestTrialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'ear'          => $this->ear,
            'frequency_hz' => $this->frequency_hz,
            'level_dbfs'   => $this->level_dbfs,
            'pattern_type' => $this->pattern_type,
            'response'     => $this->response,
            'created_at'   => $this->created_at->toIso8601String(),
        ];
    }
}
