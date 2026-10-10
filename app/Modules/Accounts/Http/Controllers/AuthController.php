<?php

namespace App\Modules\Accounts\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function devToken(): JsonResponse
    {
        abort_unless(app()->environment('local'), 403);

        $user = User::firstOrCreate(
            ['email' => 'dev@audihelp.local'],
            ['name' => 'Dev User', 'google_id' => null],
        );

        $token = $user->createToken('dev')->accessToken;

        return response()->json(['token' => $token, 'user' => $this->userPayload($user)]);
    }

    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json(['url' => $url]);
    }

    public function callback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            ['name' => $googleUser->getName(), 'google_id' => $googleUser->getId(), 'avatar' => $googleUser->getAvatar()],
        );

        if (! $user->google_id) {
            $user->update(['google_id' => $googleUser->getId(), 'avatar' => $googleUser->getAvatar()]);
        }

        $token = $user->createToken('mobile')->accessToken;

        AuditLog::record('auth.google_login', $user);

        return response()->json([
            'token' => $token,
            'user'  => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'avatar' => $user->avatar],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'avatar' => $user->avatar,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        AuditLog::record('auth.logout', $request->user());

        return response()->json(['message' => 'Logged out.']);
    }
}
