<?php

use App\Modules\Accounts\AccountsServiceProvider;
use App\Modules\DspPresets\DspPresetsServiceProvider;
use App\Modules\HearingTests\HearingTestsServiceProvider;
use App\Modules\LiveSessions\LiveSessionsServiceProvider;
use App\Modules\Profiles\ProfilesServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        AccountsServiceProvider::class,
        HearingTestsServiceProvider::class,
        ProfilesServiceProvider::class,
        DspPresetsServiceProvider::class,
        LiveSessionsServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleApi('api');
        $middleware->alias([
            'auth.dev' => \App\Modules\Accounts\Http\Middleware\AuthOrDev::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Return JSON for all API requests
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Map invalid UUID route bindings → 404 instead of 500
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $_e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Not found.'], 404);
            }
        });
    })->create();
