<?php

namespace App\Modules\LiveSessions\Http\Controllers;

use App\Models\AuditLog;
use App\Modules\LiveSessions\Http\Resources\LiveSessionResource;
use App\Modules\LiveSessions\Models\LiveSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class LiveSessionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $sessions = $request->user()
            ->liveSessions()
            ->latest()
            ->paginate(20);

        return LiveSessionResource::collection($sessions);
    }

    public function store(Request $request): LiveSessionResource
    {
        $validated = $request->validate([
            'preset_id'  => ['nullable', 'uuid', 'exists:dsp_presets,id'],
            'profile_id' => ['nullable', 'uuid', 'exists:hearing_profiles,id'],
            'notes'      => ['nullable', 'string', 'max:5000'],
        ]);

        $session = LiveSession::create([
            'user_id'    => $request->user()->id,
            'preset_id'  => $validated['preset_id'] ?? null,
            'profile_id' => $validated['profile_id'] ?? null,
            'notes'      => $validated['notes'] ?? null,
            'started_at' => now(),
        ]);

        AuditLog::record('live_session.started', $request->user(), entityType: LiveSession::class, entityId: $session->id);

        return new LiveSessionResource($session);
    }

    public function show(Request $request, LiveSession $liveSession): LiveSessionResource
    {
        abort_unless($liveSession->user_id === $request->user()->id, 403);

        return new LiveSessionResource($liveSession);
    }

    public function update(Request $request, LiveSession $liveSession): LiveSessionResource
    {
        abort_unless($liveSession->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'notes'      => ['nullable', 'string', 'max:5000'],
            'preset_id'  => ['nullable', 'uuid', 'exists:dsp_presets,id'],
            'profile_id' => ['nullable', 'uuid', 'exists:hearing_profiles,id'],
        ]);

        $liveSession->update($validated);

        return new LiveSessionResource($liveSession->fresh());
    }

    public function end(Request $request, LiveSession $liveSession): LiveSessionResource
    {
        abort_unless($liveSession->user_id === $request->user()->id, 403);
        abort_if($liveSession->ended_at !== null, 422, 'Session already ended.');

        $liveSession->update(['ended_at' => now()]);

        AuditLog::record('live_session.ended', $request->user(), entityType: LiveSession::class, entityId: $liveSession->id);

        return new LiveSessionResource($liveSession->fresh());
    }

    public function destroy(Request $request, LiveSession $liveSession): JsonResponse
    {
        abort_unless($liveSession->user_id === $request->user()->id, 403);

        $liveSession->delete();

        return response()->json(null, 204);
    }
}
