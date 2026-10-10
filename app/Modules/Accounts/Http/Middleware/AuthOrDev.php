<?php

namespace App\Modules\Accounts\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Drop-in replacement for auth:api.
 *
 * When GOOGLE_LOGIN_ENABLED=false the app has no login flow, so this
 * middleware auto-authenticates every request as the local dev user instead
 * of rejecting it with 401.  In all other environments it behaves exactly
 * like the standard auth:api guard.
 */
class AuthOrDev
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! config('auth.google_login_enabled') && app()->environment('local')) {
            // Auto-authenticate as the dev user — create if not exists
            $user = User::firstOrCreate(
                ['email' => 'dev@audihelp.local'],
                ['name'  => 'Dev User'],
            );

            Auth::setUser($user);

            return $next($request);
        }

        // Production / login-enabled path — standard Passport guard
        if (! Auth::guard('api')->check()) {
            throw new AuthenticationException();
        }

        Auth::setUser(Auth::guard('api')->user());

        return $next($request);
    }
}
