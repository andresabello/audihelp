<?php

use App\Modules\Profiles\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.dev')->prefix('v1')->group(function () {
    Route::get('profiles', [ProfileController::class, 'index']);
    Route::post('profiles', [ProfileController::class, 'store']);
    Route::get('profiles/{profile}', [ProfileController::class, 'show']);
    Route::put('profiles/{profile}', [ProfileController::class, 'update']);
    Route::delete('profiles/{profile}', [ProfileController::class, 'destroy']);
    Route::post('profiles/{profile}/activate', [ProfileController::class, 'activate']);
    Route::post('profiles/{profile}/thresholds', [ProfileController::class, 'addThreshold']);
});
