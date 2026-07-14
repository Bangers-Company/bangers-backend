<?php

use App\Models\User;
use App\Models\Event;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\assertDatabaseHas;

test('user can log event attendance', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    \Laravel\Sanctum\Sanctum::actingAs($user);

    \Pest\Laravel\putJson("/api/mobile/v1/events/{$event->id}/attendance", [
        'status' => 'going',
    ])
        ->assertStatus(200);

    assertDatabaseHas('user_event_attendance', [
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'going',
    ]);
});

test('user can get event attendees', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();
    
    // Set attendance
    app(\App\Services\AttendanceService::class)->updateAttendance($user, $event->id, 'going');

    \Laravel\Sanctum\Sanctum::actingAs($user);

    getJson("/api/mobile/v1/events/{$event->id}/attendees")
        ->assertStatus(200)
        ->assertJsonFragment(['id' => $user->id]);
});
