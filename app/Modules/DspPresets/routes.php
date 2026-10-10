<?php

use App\Modules\DspPresets\Http\Controllers\DspPresetController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.dev')->prefix('v1')->group(function () {
    Route::get('dsp-presets', [DspPresetController::class, 'index']);
    Route::post('dsp-presets', [DspPresetController::class, 'store']);
    Route::get('dsp-presets/{dspPreset}', [DspPresetController::class, 'show']);
    Route::put('dsp-presets/{dspPreset}', [DspPresetController::class, 'update']);
    Route::delete('dsp-presets/{dspPreset}', [DspPresetController::class, 'destroy']);
    Route::post('dsp-presets/{dspPreset}/activate', [DspPresetController::class, 'activate']);
});
