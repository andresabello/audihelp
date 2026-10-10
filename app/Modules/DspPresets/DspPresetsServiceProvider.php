<?php

namespace App\Modules\DspPresets;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DspPresetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__.'/routes.php');
    }
}
