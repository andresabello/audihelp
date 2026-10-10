<?php

namespace App\Modules\Profiles\Http\Requests;

use App\Modules\Profiles\Enums\EarSide;
use App\Modules\Profiles\Enums\ThresholdResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AddThresholdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ear'          => ['required', new Enum(EarSide::class)],
            'frequency_hz' => ['required', 'integer', 'min:20', 'max:20000'],
            'level_dbfs'   => ['required', 'numeric', 'min:-96', 'max:0'],
            'response'     => ['required', new Enum(ThresholdResponse::class)],
        ];
    }
}
