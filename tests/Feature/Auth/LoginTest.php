<?php

use App\Models\User;
use function Pest\Laravel\postJson;

beforeEach(function () {
    \Illuminate\Support\Facades\Cache::flush();
});

test('user can login with correct credentials', function () {
    $user = User::factory()->admin()->create([
        'password' => 'password123',
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'accessToken',
            'refreshToken',
            'user' => ['id', 'email', 'username'],
        ]);
});

test('user cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);
});

test('login requires email and password', function () {
    $response = postJson('/api/v1/auth/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

test('login is rate limited', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 6; $i++) {
        postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);
    }

    $response = postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong',
    ]);

    $response->assertStatus(429);
});
