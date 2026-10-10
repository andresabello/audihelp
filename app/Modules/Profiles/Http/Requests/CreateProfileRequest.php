<?php

namespace App\Modules\Profiles\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'notes'             => ['nullable', 'string', 'max:2000'],
            'source_session_id' => ['nullable', 'uuid', 'exists:hearing_test_sessions,id'],
        ];
    }
}
