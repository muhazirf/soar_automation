<?php

use App\Models\User;

test('alerts page requires authentication', function () {
    $response = $this->get('/alerts');
    $response->assertRedirect('/login');
});

test('authenticated users can access alerts page', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/alerts');
    $response->assertStatus(200);
});

test('alerts page shows alert statistics', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/alerts');
    $response->assertSee('Open');
    $response->assertSee('Critical');
    $response->assertSee('High');
});

test('users can view individual alert details', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/alerts/ALT-001');
    $response->assertStatus(200);
});

test('users can update alert status', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->post('/alerts/ALT-001/status', [
        'status' => 'investigating',
    ]);

    $response->assertStatus(302);
});

test('users can escalate alerts', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->post('/alerts/ALT-001/escalate');
    $response->assertStatus(302);
});
