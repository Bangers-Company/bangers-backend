<?php

use App\Models\User;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

test('user can register with valid data', function () {
    $response = postJson('/api/v1/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'dob' => '1990-01-01',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'accessToken',
            'refreshToken',
            'user' => ['id', 'email', 'username'],
        ]);

    assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'username' => 'johndoe',
    ]);
});

test('registration requires strong password', function () {
    $response = postJson('/api/v1/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'abc',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('registration prevents duplicate email', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    $response = postJson('/api/v1/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'johndoe2',
        'email' => 'duplicate@example.com',
        'password' => 'Password123!',
        'dob' => '1990-01-01',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
