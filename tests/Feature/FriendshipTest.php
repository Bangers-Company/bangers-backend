<?php

use App\Models\User;
use App\Models\Friendship;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('user can send a friend request', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create(['is_public' => false]);

    \Laravel\Sanctum\Sanctum::actingAs($sender);

    postJson("/api/mobile/v1/friends/{$recipient->id}")
        ->assertStatus(201);

    [$id1, $id2] = [min($sender->id, $recipient->id), max($sender->id, $recipient->id)];

    assertDatabaseHas('friendships', [
        'user_id_1' => $id1,
        'user_id_2' => $id2,
        'requested_by' => $sender->id,
        'status' => 'pending',
    ]);
});

test('user can accept a friend request', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    
    [$id1, $id2] = [min($sender->id, $recipient->id), max($sender->id, $recipient->id)];
    
    Friendship::create([
        'user_id_1' => $id1,
        'user_id_2' => $id2,
        'requested_by' => $sender->id,
        'status' => 'pending',
    ]);

    \Laravel\Sanctum\Sanctum::actingAs($recipient);
    putJson("/api/mobile/v1/friends/{$sender->id}/accept")
        ->assertStatus(200);

    assertDatabaseHas('friendships', [
        'user_id_1' => $id1,
        'user_id_2' => $id2,
        'status' => 'accepted',
    ]);
});

test('user can reject a friend request', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    
    [$id1, $id2] = [min($sender->id, $recipient->id), max($sender->id, $recipient->id)];
    
    Friendship::create([
        'user_id_1' => $id1,
        'user_id_2' => $id2,
        'requested_by' => $sender->id,
        'status' => 'pending',
    ]);

    \Laravel\Sanctum\Sanctum::actingAs($recipient);
    putJson("/api/mobile/v1/friends/{$sender->id}/reject")
        ->assertStatus(200);

    assertDatabaseMissing('friendships', [
        'user_id_1' => $id1,
        'user_id_2' => $id2,
    ]);
});

test('user can unfriend', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    [$id1, $id2] = [min($user1->id, $user2->id), max($user1->id, $user2->id)];
    
    Friendship::create([
        'user_id_1' => $id1,
        'user_id_2' => $id2,
        'requested_by' => $user1->id,
        'status' => 'accepted',
    ]);

    \Laravel\Sanctum\Sanctum::actingAs($user1);
    deleteJson("/api/mobile/v1/friends/{$user2->id}")
        ->assertStatus(200);

    assertDatabaseMissing('friendships', [
        'user_id_1' => $id1,
        'user_id_2' => $id2,
    ]);
});
