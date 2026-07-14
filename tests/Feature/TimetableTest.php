<?php

use App\Models\User;
use App\Models\Group;
use App\Models\Event;
use App\Models\GroupTimetable;
use App\Models\TimetableEntry;
use App\Services\GroupService;
use App\Services\TimetableService;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('user can create a group timetable', function () {
    $user = User::factory()->create();
    $group = app(GroupService::class)->createGroup($user, ['name' => 'Test Group']);
    $event = Event::factory()->create();

    \Laravel\Sanctum\Sanctum::actingAs($user);

    postJson("/api/mobile/v1/groups/{$group->id}/timetables", [
        'event_id' => $event->id,
        'name' => 'Our Festival Plan',
    ])
        ->assertStatus(201)
        ->assertJsonPath('name', 'Our Festival Plan');

    assertDatabaseHas('group_timetables', [
        'group_id' => $group->id,
        'event_id' => $event->id,
        'name' => 'Our Festival Plan',
    ]);
});

test('user can add entries to group timetable', function () {
    $user = User::factory()->create();
    $group = app(GroupService::class)->createGroup($user, ['name' => 'Test Group']);
    $event = Event::factory()->create();
    $timetable = app(TimetableService::class)->createGroupTimetable($group->id, $event->id, 'Plan', $user);
    
    $officialTimetable = \App\Models\EventTimetable::factory()->create(['event_id' => $event->id, 'is_official' => true]);
    $entry = TimetableEntry::factory()->create(['timetable_id' => $officialTimetable->id]);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    putJson("/api/mobile/v1/groups/{$group->id}/timetables/{$timetable->id}/entries", [
        'entry_ids' => [$entry->id],
    ])
        ->dump()
        ->assertStatus(200);

    assertDatabaseHas('group_timetable_entries', [
        'group_timetable_id' => $timetable->id,
        'timetable_entry_id' => $entry->id,
    ]);
});

test('user can toggle attendance in group timetable', function () {
    $user = User::factory()->create();
    $group = app(GroupService::class)->createGroup($user, ['name' => 'Test Group']);
    $event = Event::factory()->create();
    $timetable = app(TimetableService::class)->createGroupTimetable($group->id, $event->id, 'Plan', $user);
    
    $officialTimetable = \App\Models\EventTimetable::factory()->create(['event_id' => $event->id, 'is_official' => true]);
    $entry = TimetableEntry::factory()->create(['timetable_id' => $officialTimetable->id]);
    
    // Add entry first
    app(TimetableService::class)->updateGroupTimetableEntries($timetable, [$entry->id], $user);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    // Toggle ON
    postJson("/api/mobile/v1/groups/{$group->id}/timetables/{$timetable->id}/entries/{$entry->id}/toggle-attend")
        ->assertStatus(200)
        ->assertJsonPath('is_attending', true);

    assertDatabaseHas('group_timetable_attendance', [
        'group_timetable_id' => $timetable->id,
        'user_id' => $user->id,
        'timetable_entry_id' => $entry->id,
    ]);

    // Toggle OFF
    postJson("/api/mobile/v1/groups/{$group->id}/timetables/{$timetable->id}/entries/{$entry->id}/toggle-attend")
        ->assertStatus(200)
        ->assertJsonPath('is_attending', false);

    assertDatabaseMissing('group_timetable_attendance', [
        'group_timetable_id' => $timetable->id,
        'user_id' => $user->id,
        'timetable_entry_id' => $entry->id,
    ]);
});

test('user can view attendance list', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $group = app(GroupService::class)->createGroup($owner, ['name' => 'Test Group']);
    app(GroupService::class)->addMember($group, $member->id);
    app(GroupService::class)->acceptInvitation($group, $member->id);
    
    $event = Event::factory()->create();
    $timetable = app(TimetableService::class)->createGroupTimetable($group->id, $event->id, 'Plan', $owner);
    
    $officialTimetable = \App\Models\EventTimetable::factory()->create(['event_id' => $event->id, 'is_official' => true]);
    $entry = TimetableEntry::factory()->create(['timetable_id' => $officialTimetable->id]);
    
    app(TimetableService::class)->updateGroupTimetableEntries($timetable, [$entry->id], $owner);
    app(TimetableService::class)->toggleGroupEntryAttendance($timetable, $entry->id, $member);

    \Laravel\Sanctum\Sanctum::actingAs($owner);

    getJson("/api/mobile/v1/groups/{$group->id}/timetables/{$timetable->id}/entries/{$entry->id}/attendance")
        ->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonPath('0.id', $member->id);
});
