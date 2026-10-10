<?php

use App\Models\User;
use App\Modules\DspPresets\Models\DspPreset;
use Laravel\Passport\Passport;

it('can create a DSP preset', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $response = $this->postJson('/api/v1/dsp-presets', [
        'name'   => 'Low freq boost',
        'config' => [
            'eq' => [
                ['freq' => 500, 'gain_db' => 6, 'q' => 1.4, 'type' => 'peak'],
            ],
            'limiter' => ['ceiling_dbfs' => -3],
        ],
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Low freq boost')
        ->assertJsonPath('data.config.limiter.ceiling_dbfs', -3);
});

it('can activate a preset', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $preset = DspPreset::factory()->create(['user_id' => $user->id]);

    $this->postJson("/api/v1/dsp-presets/{$preset->id}/activate")
        ->assertOk()
        ->assertJsonPath('data.is_active', true);
});
