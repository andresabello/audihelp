<?php

use App\Models\User;
use App\Modules\HearingTests\Models\HearingTestSession;
use Laravel\Passport\Passport;

it('can start a hearing test session', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $response = $this->postJson('/api/v1/hearing-tests', [
        'ear_tested' => 'both',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.ear_tested', 'both')
        ->assertJsonPath('data.status', 'in_progress');
});

it('can record a trial', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $session = HearingTestSession::factory()->create(['user_id' => $user->id]);

    $response = $this->postJson("/api/v1/hearing-tests/{$session->id}/trials", [
        'ear'          => 'left',
        'frequency_hz' => 1000,
        'level_dbfs'   => -40.0,
        'response'     => 'barely_hear',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.frequency_hz', 1000)
        ->assertJsonPath('data.response', 'barely_hear');
});

it('can complete a session', function () {
    $user = User::factory()->create();
    Passport::actingAs($user);

    $session = HearingTestSession::factory()->create(['user_id' => $user->id]);

    $response = $this->postJson("/api/v1/hearing-tests/{$session->id}/complete");

    $response->assertOk()
        ->assertJsonPath('data.status', 'completed');
});

it('cannot access another user session', function () {
    $user  = User::factory()->create();
    $other = User::factory()->create();
    Passport::actingAs($user);

    $session = HearingTestSession::factory()->create(['user_id' => $other->id]);

    $this->getJson("/api/v1/hearing-tests/{$session->id}")->assertForbidden();
});
