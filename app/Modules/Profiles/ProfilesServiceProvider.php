<?php

namespace App\Modules\Profiles;

use App\Modules\Profiles\Services\ProfileService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProfilesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(ProfileService::class);
    }

    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__.'/routes.php');
    }
}
