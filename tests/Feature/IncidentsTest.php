<?php

use App\Models\User;

test('incidents page requires authentication', function () {
    $response = $this->get('/incidents');
    $response->assertRedirect('/login');
});

test('authenticated users can access incidents page', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/incidents');
    $response->assertStatus(200);
});

test('users can create incident', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/incidents/create');
    $response->assertStatus(200);
});

test('users can store incident', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->post('/incidents', [
        'title' => 'Test Incident',
        'description' => 'Test description',
        'severity' => 'high',
        'type' => 'malware',
        'assigned_to' => $user->name,
    ]);

    $response->assertStatus(302);
});

test('users can view incident details', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/incidents/INC-001');
    $response->assertStatus(200);
});

test('users can edit incident', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->get('/incidents/INC-001/edit');
    $response->assertStatus(200);
});

test('users can update incident', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->put('/incidents/INC-001', [
        'title' => 'Updated Incident',
        'description' => 'Updated description',
        'severity' => 'critical',
        'type' => 'ransomware',
        'assigned_to' => $user->name,
    ]);

    $response->assertStatus(302);
});

test('users can delete incident', function () {
    $user = User::factory()->create(['clearance_level' => 2]);

    $response = $this->actingAs($user)->delete('/incidents/INC-001');
    $response->assertStatus(302);
});
