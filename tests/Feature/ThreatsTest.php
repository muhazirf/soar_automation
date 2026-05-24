<?php

use App\Models\User;

test('threats page requires authentication', function () {
    $response = $this->get('/threats');
    $response->assertRedirect('/login');
});

test('authenticated users can access threats page', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats');
    $response->assertStatus(200);
});

test('threats page shows threat statistics', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats');
    $response->assertSee('Active Threats');
    $response->assertSee('Total IOCs');
    $response->assertSee('Active Feeds');
});

test('threats page shows feed status', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats');
    $response->assertSee('Feed Status');
});

test('users can filter threats by type', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats?type=APT');
    $response->assertStatus(200);
});

test('users can filter threats by severity', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats?severity=critical');
    $response->assertStatus(200);
});

test('users can view threat details', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats/THR-001');
    $response->assertStatus(200);
});

test('threat detail shows IOCs', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats/THR-001');
    $response->assertSee('Indicators of Compromise');
});

test('threat detail shows MITRE ATT&CK techniques', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/threats/THR-001');
    $response->assertSee('MITRE ATT&CK');
});
