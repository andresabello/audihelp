<?php

use App\Modules\Accounts\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function () {

    // Dev shortcut — only available in local environment, never in production
    if (app()->environment('local')) {
        Route::post('dev-token', [AuthController::class, 'devToken']);
    }

    // Google OAuth — enabled via GOOGLE_LOGIN_ENABLED=true in .env
    if (config('auth.google_login_enabled')) {
        Route::get('google', [AuthController::class, 'redirect']);
        Route::get('google/callback', [AuthController::class, 'callback']);
    }

    Route::middleware('auth.dev')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
