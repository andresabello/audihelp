<?php

namespace App\Modules\HearingTests\Http\Requests;

use App\Modules\HearingTests\Enums\EarSide;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StartSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ear_tested' => ['required', new Enum(EarSide::class)],
            'notes'      => ['nullable', 'string', 'max:2000'],
        ];
    }
}
