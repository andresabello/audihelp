<?php

namespace App\Modules\LiveSessions;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LiveSessionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__.'/routes.php');
    }
}
