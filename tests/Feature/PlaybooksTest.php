<?php

use App\Models\User;

test('playbooks page requires authentication', function () {
    $response = $this->get('/playbooks');
    $response->assertRedirect('/login');
});

test('authenticated users can access playbooks page', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/playbooks');
    $response->assertStatus(200);
});

test('playbooks page shows playbook statistics', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/playbooks');
    $response->assertSee('Total Playbooks');
    $response->assertSee('Active');
    $response->assertSee('Success Rate');
});

test('users can view playbook details', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/playbooks/PB-001');
    $response->assertStatus(200);
    $response->assertSee('Malware Response');
});

test('playbook detail shows steps', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/playbooks/PB-001');
    $response->assertSee('Playbook Steps');
});

test('playbook detail shows execution history', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/playbooks/PB-001');
    $response->assertSee('Execution History');
});

test('users can execute playbook', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->post('/playbooks/PB-001/execute', [
        'target' => 'test-target',
        'parameters' => [],
    ]);

    $response->assertStatus(200);
});
