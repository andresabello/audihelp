<?php

namespace App\Modules\Profiles\Services;

use App\Models\User;
use App\Modules\Profiles\Models\HearingProfile;
use App\Modules\Profiles\Models\HearingThreshold;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Create a blank profile, optionally pre-populated from a hearing test session.
     */
    public function create(User $user, string $name, ?string $notes = null, ?string $sourceSessionId = null): HearingProfile
    {
        return DB::transaction(function () use ($user, $name, $notes, $sourceSessionId) {
            $profile = HearingProfile::create([
                'user_id'           => $user->id,
                'name'              => $name,
                'notes'             => $notes,
                'is_active'         => false,
                'source_session_id' => $sourceSessionId,
            ]);

            if ($sourceSessionId) {
                $this->importFromSession($profile, $sourceSessionId);
            }

            return $profile;
        });
    }

    /**
     * Import best (lowest detectable) thresholds per frequency/ear from a test session.
     */
    private function importFromSession(HearingProfile $profile, string $sessionId): void
    {
        // Pull trials from the session ordered by level; take the lowest detected level per freq/ear
        $trials = \App\Modules\HearingTests\Models\HearingTestTrial::query()
            ->where('session_id', $sessionId)
            ->whereIn('response', ['hear_clearly', 'barely_hear', 'feel'])
            ->orderBy('frequency_hz')
            ->orderBy('level_dbfs')
            ->get()
            ->unique(fn ($t) => $t->ear->value.'-'.$t->frequency_hz);

        foreach ($trials as $trial) {
            HearingThreshold::create([
                'profile_id'   => $profile->id,
                'ear'          => $trial->ear,
                'frequency_hz' => $trial->frequency_hz,
                'level_dbfs'   => $trial->level_dbfs,
                'response'     => $trial->response,
            ]);
        }
    }

    public function setActive(HearingProfile $profile): HearingProfile
    {
        return DB::transaction(function () use ($profile) {
            // Deactivate all other profiles for this user
            HearingProfile::where('user_id', $profile->user_id)
                ->where('id', '!=', $profile->id)
                ->update(['is_active' => false]);

            $profile->update(['is_active' => true]);

            return $profile->fresh();
        });
    }

    public function addThreshold(HearingProfile $profile, array $data): HearingThreshold
    {
        return HearingThreshold::create([
            'profile_id'   => $profile->id,
            'ear'          => $data['ear'],
            'frequency_hz' => $data['frequency_hz'],
            'level_dbfs'   => $data['level_dbfs'],
            'response'     => $data['response'],
        ]);
    }
}
