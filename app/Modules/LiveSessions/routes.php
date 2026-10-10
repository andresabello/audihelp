<?php

use App\Modules\LiveSessions\Http\Controllers\LiveSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.dev')->prefix('v1')->group(function () {
    Route::get('live-sessions', [LiveSessionController::class, 'index']);
    Route::post('live-sessions', [LiveSessionController::class, 'store']);
    Route::get('live-sessions/{liveSession}', [LiveSessionController::class, 'show']);
    Route::put('live-sessions/{liveSession}', [LiveSessionController::class, 'update']);
    Route::post('live-sessions/{liveSession}/end', [LiveSessionController::class, 'end']);
    Route::delete('live-sessions/{liveSession}', [LiveSessionController::class, 'destroy']);
});
