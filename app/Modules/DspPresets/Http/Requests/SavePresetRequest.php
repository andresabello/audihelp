<?php

namespace App\Modules\DspPresets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePresetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                          => ['required', 'string', 'max:255'],
            'description'                   => ['nullable', 'string', 'max:1000'],

            // EQ bands — array of parametric EQ nodes
            'config.eq'                     => ['nullable', 'array'],
            'config.eq.*.freq'              => ['required_with:config.eq', 'numeric', 'min:20', 'max:20000'],
            'config.eq.*.gain_db'           => ['required_with:config.eq', 'numeric', 'min:-24', 'max:24'],
            'config.eq.*.q'                 => ['required_with:config.eq', 'numeric', 'min:0.1', 'max:10'],
            'config.eq.*.type'              => ['required_with:config.eq', 'string', 'in:peak,lowshelf,highshelf,lowpass,highpass,notch'],

            // Compressor
            'config.compressor'             => ['nullable', 'array'],
            'config.compressor.threshold_dbfs' => ['numeric', 'min:-60', 'max:0'],
            'config.compressor.ratio'          => ['numeric', 'min:1', 'max:20'],
            'config.compressor.attack_ms'      => ['numeric', 'min:0', 'max:500'],
            'config.compressor.release_ms'     => ['numeric', 'min:0', 'max:5000'],
            'config.compressor.makeup_gain_db' => ['nullable', 'numeric', 'min:0', 'max:24'],

            // Limiter (safety — required field)
            'config.limiter'                => ['nullable', 'array'],
            'config.limiter.ceiling_dbfs'   => ['numeric', 'min:-24', 'max:0'],

            // Frequency transformation
            'config.freq_transform'         => ['nullable', 'array'],
            'config.freq_transform.enabled' => ['boolean'],
            'config.freq_transform.shift_hz' => ['nullable', 'numeric'],

            // Envelope / amplitude modulation
            'config.modulation'             => ['nullable', 'array'],
            'config.modulation.enabled'     => ['boolean'],
            'config.modulation.depth'       => ['nullable', 'numeric', 'min:0', 'max:1'],
            'config.modulation.rate_hz'     => ['nullable', 'numeric', 'min:0.1', 'max:20'],

            // Master gain
            'config.gain_db'                => ['nullable', 'numeric', 'min:-24', 'max:24'],
        ];
    }
}
