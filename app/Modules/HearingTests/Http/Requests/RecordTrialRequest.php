<?php

namespace App\Modules\HearingTests\Http\Requests;

use App\Modules\HearingTests\Enums\EarSide;
use App\Modules\HearingTests\Enums\PatternType;
use App\Modules\HearingTests\Enums\TrialResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RecordTrialRequest extends FormRequest
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
            'pattern_type' => ['sometimes', new Enum(PatternType::class)],
            'response'     => ['required', new Enum(TrialResponse::class)],
        ];
    }
}
