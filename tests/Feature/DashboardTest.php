<?php

use App\Models\User;

test('dashboard redirects unauthenticated users', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can access dashboard', function () {
    $user = User::factory()->create([
        'clearance_level' => 4,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
});

test('dashboard displays required sections', function () {
    $user = User::factory()->create([
        'clearance_level' => 4,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertSee('Dashboard');
    $response->assertSee('Alert Trends');
    $response->assertSee('Recent Alerts');
    $response->assertSee('Quick Actions');
});

test('users with different clearance levels can access dashboard', function () {
    foreach ([1, 2, 3, 4, 5] as $level) {
        $user = User::factory()->create(['clearance_level' => $level]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }
});
