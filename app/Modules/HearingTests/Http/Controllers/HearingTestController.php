<?php

namespace App\Modules\HearingTests\Http\Controllers;

use App\Models\AuditLog;
use App\Modules\HearingTests\Enums\EarSide;
use App\Modules\HearingTests\Enums\PatternType;
use App\Modules\HearingTests\Enums\TrialResponse;
use App\Modules\HearingTests\Http\Requests\RecordTrialRequest;
use App\Modules\HearingTests\Http\Requests\StartSessionRequest;
use App\Modules\HearingTests\Http\Resources\HearingTestSessionResource;
use App\Modules\HearingTests\Http\Resources\HearingTestTrialResource;
use App\Modules\HearingTests\Models\HearingTestSession;
use App\Modules\HearingTests\Services\HearingTestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class HearingTestController extends Controller
{
    public function __construct(private readonly HearingTestService $service) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $sessions = $request->user()
            ->hearingTestSessions()
            ->latest()
            ->paginate(20);

        return HearingTestSessionResource::collection($sessions);
    }

    public function store(StartSessionRequest $request): HearingTestSessionResource
    {
        $session = $this->service->startSession(
            user: $request->user(),
            earTested: EarSide::from($request->ear_tested),
            notes: $request->notes,
        );

        AuditLog::record('hearing_test.started', $request->user(), entityType: HearingTestSession::class, entityId: $session->id);

        return new HearingTestSessionResource($session);
    }

    public function show(Request $request, HearingTestSession $hearingTest): HearingTestSessionResource
    {
        abort_unless($hearingTest->user_id === $request->user()->id, 403);

        return new HearingTestSessionResource($hearingTest->load('trials'));
    }

    public function recordTrial(RecordTrialRequest $request, HearingTestSession $hearingTest): HearingTestTrialResource
    {
        abort_unless($hearingTest->user_id === $request->user()->id, 403);
        abort_if($hearingTest->status->value === 'completed', 422, 'Session is already completed.');

        $trial = $this->service->recordTrial(
            session: $hearingTest,
            ear: EarSide::from($request->ear),
            frequencyHz: $request->frequency_hz,
            levelDbfs: $request->level_dbfs,
            response: TrialResponse::from($request->response),
            patternType: PatternType::from($request->input('pattern_type', PatternType::Tone->value)),
        );

        return new HearingTestTrialResource($trial);
    }

    public function complete(Request $request, HearingTestSession $hearingTest): HearingTestSessionResource
    {
        abort_unless($hearingTest->user_id === $request->user()->id, 403);

        $session = $this->service->completeSession($hearingTest);

        AuditLog::record('hearing_test.completed', $request->user(), entityType: HearingTestSession::class, entityId: $session->id);

        return new HearingTestSessionResource($session->load('trials'));
    }

    public function destroy(Request $request, HearingTestSession $hearingTest): JsonResponse
    {
        abort_unless($hearingTest->user_id === $request->user()->id, 403);

        $hearingTest->delete();

        return response()->json(null, 204);
    }
}
