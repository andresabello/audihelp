<?php

namespace App\Modules\HearingTests;

use App\Modules\HearingTests\Services\HearingTestService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HearingTestsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(HearingTestService::class);
    }

    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__.'/routes.php');
    }
}
