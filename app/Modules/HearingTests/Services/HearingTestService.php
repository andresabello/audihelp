<?php

namespace App\Modules\HearingTests\Services;

use App\Models\User;
use App\Modules\HearingTests\Enums\EarSide;
use App\Modules\HearingTests\Enums\PatternType;
use App\Modules\HearingTests\Enums\SessionStatus;
use App\Modules\HearingTests\Enums\TrialResponse;
use App\Modules\HearingTests\Models\HearingTestSession;
use App\Modules\HearingTests\Models\HearingTestTrial;

class HearingTestService
{
    public function startSession(User $user, EarSide $earTested, ?string $notes = null): HearingTestSession
    {
        return HearingTestSession::create([
            'user_id'    => $user->id,
            'ear_tested' => $earTested,
            'notes'      => $notes,
            'status'     => SessionStatus::InProgress,
            'started_at' => now(),
        ]);
    }

    public function recordTrial(
        HearingTestSession $session,
        EarSide $ear,
        int $frequencyHz,
        float $levelDbfs,
        TrialResponse $response,
        PatternType $patternType = PatternType::Tone,
    ): HearingTestTrial {
        return HearingTestTrial::create([
            'session_id'   => $session->id,
            'ear'          => $ear,
            'frequency_hz' => $frequencyHz,
            'level_dbfs'   => $levelDbfs,
            'pattern_type' => $patternType,
            'response'     => $response,
        ]);
    }

    public function completeSession(HearingTestSession $session): HearingTestSession
    {
        $session->update([
            'status'       => SessionStatus::Completed,
            'completed_at' => now(),
        ]);

        return $session->fresh();
    }

    public function abandonSession(HearingTestSession $session): HearingTestSession
    {
        $session->update(['status' => SessionStatus::Abandoned]);

        return $session->fresh();
    }
}
