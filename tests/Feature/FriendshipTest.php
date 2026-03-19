<?php

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can check friendship status', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    // 1. None
    $response = $this->actingAs($user)->getJson("/api/mobile/friends/{$other->id}/status");
    $response->assertStatus(200)->assertJson(['status' => 'none']);

    // 2. Pending (Sent)
    Friendship::create([
        'user_id_1' => min($user->id, $other->id),
        'user_id_2' => max($user->id, $other->id),
        'status' => 'pending',
        'requested_by' => $user->id
    ]);
    
    $response = $this->actingAs($user)->getJson("/api/mobile/friends/{$other->id}/status");
    $response->assertStatus(200)->assertJson(['status' => 'pending_sent']);

    // 3. Pending (Received)
    $response = $this->actingAs($other)->getJson("/api/mobile/friends/{$user->id}/status");
    $response->assertStatus(200)->assertJson(['status' => 'pending_received']);

    // 4. Accepted
    Friendship::where('requested_by', $user->id)->update(['status' => 'accepted']);
    $response = $this->actingAs($user)->getJson("/api/mobile/friends/{$other->id}/status");
    $response->assertStatus(200)->assertJson(['status' => 'accepted']);

    // 5. Self
    $response = $this->actingAs($user)->getJson("/api/mobile/friends/{$user->id}/status");
    $response->assertStatus(200)->assertJson(['status' => 'self']);
});
