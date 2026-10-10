<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\DspPresets\Models\DspPreset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DspPreset>
 */
class DspPresetFactory extends Factory
{
    protected $model = DspPreset::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'name'        => fake()->words(3, true),
            'description' => fake()->sentence(),
            'config'      => [
                'eq'      => [],
                'limiter' => ['ceiling_dbfs' => -3],
                'gain_db' => 0,
            ],
            'is_active' => false,
        ];
    }
}
