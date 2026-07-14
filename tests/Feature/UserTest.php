<?php

use App\Models\User;
use function Pest\Laravel\getJson;
use function Pest\Laravel\actingAs;

test('user can view their own full profile including PII', function () {
    $user = User::factory()->create([
        'email' => 'me@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    getJson("/api/mobile/v1/users/{$user->id}")
        ->assertStatus(200)
        ->assertJsonFragment(['email' => 'me@example.com'])
        ->assertJsonFragment(['first_name' => 'John']);
});

test('user cannot view another users PII', function () {
    $user = User::factory()->create();
    $other = User::factory()->create([
        'email' => 'other@example.com',
        'first_name' => 'Jane',
    ]);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    getJson("/api/mobile/v1/users/{$other->id}")
        ->assertStatus(200)
        ->assertJsonMissing(['email' => 'other@example.com'])
        ->assertJsonMissing(['first_name' => 'Jane']);
});
