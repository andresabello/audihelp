<?php

use App\Modules\HearingTests\Http\Controllers\HearingTestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.dev')->prefix('v1')->group(function () {
    Route::get('hearing-tests', [HearingTestController::class, 'index']);
    Route::post('hearing-tests', [HearingTestController::class, 'store']);
    Route::get('hearing-tests/{hearingTest}', [HearingTestController::class, 'show']);
    Route::post('hearing-tests/{hearingTest}/trials', [HearingTestController::class, 'recordTrial']);
    Route::post('hearing-tests/{hearingTest}/complete', [HearingTestController::class, 'complete']);
    Route::delete('hearing-tests/{hearingTest}', [HearingTestController::class, 'destroy']);
});
