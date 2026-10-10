<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\HearingTests\Enums\EarSide;
use App\Modules\HearingTests\Enums\SessionStatus;
use App\Modules\HearingTests\Models\HearingTestSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HearingTestSession>
 */
class HearingTestSessionFactory extends Factory
{
    protected $model = HearingTestSession::class;

    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'ear_tested' => fake()->randomElement(EarSide::cases())->value,
            'notes'      => null,
            'status'     => SessionStatus::InProgress->value,
            'started_at' => now(),
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'status'       => SessionStatus::Completed->value,
            'completed_at' => now(),
        ]);
    }
}
