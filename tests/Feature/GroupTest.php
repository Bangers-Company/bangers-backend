<?php

use App\Models\User;
use App\Models\Group;
use App\Services\GroupService;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('user can create a group', function () {
    $user = User::factory()->create();

    \Laravel\Sanctum\Sanctum::actingAs($user);

    postJson("/api/mobile/v1/groups", [
        'name' => 'My Test Group',
        'description' => 'A group for testing',
    ])
        ->assertStatus(201)
        ->assertJsonPath('name', 'My Test Group');

    assertDatabaseHas('groups', [
        'name' => 'My Test Group',
        'owner_id' => $user->id,
    ]);
});

test('user can update their group', function () {
    $user = User::factory()->create();
    $service = app(GroupService::class);
    $group = $service->createGroup($user, ['name' => 'Original Name']);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    putJson("/api/mobile/v1/groups/{$group->id}", [
        'name' => 'Updated Group Name',
    ])
        ->assertStatus(200);

    assertDatabaseHas('groups', [
        'id' => $group->id,
        'name' => 'Updated Group Name',
    ]);
});

test('user cannot update someone else\'s group', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $service = app(GroupService::class);
    $group = $service->createGroup($owner, ['name' => 'Owner Group']);

    \Laravel\Sanctum\Sanctum::actingAs($other);
    
    // Debug: check gate inspection
    // \Log::info('Gate Inspection', ['allowed' => \Illuminate\Support\Facades\Gate::inspect('update', $group)->allowed()]);

    putJson("/api/mobile/v1/groups/{$group->id}", [
        'name' => 'Stolen Group',
    ])
        ->assertStatus(403);
});

test('user can delete their group', function () {
    $user = User::factory()->create();
    $service = app(GroupService::class);
    $group = $service->createGroup($user, ['name' => 'To Delete']);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    deleteJson("/api/mobile/v1/groups/{$group->id}")
        ->assertStatus(200);

    assertDatabaseMissing('groups', ['id' => $group->id]);
});

test('user can invite a member to a group', function () {
    $owner = User::factory()->create();
    $invitee = User::factory()->create();
    $service = app(GroupService::class);
    $group = $service->createGroup($owner, ['name' => 'Invite Group']);

    \Laravel\Sanctum\Sanctum::actingAs($owner);

    postJson("/api/mobile/v1/groups/{$group->id}/members", [
        'user_id' => $invitee->id,
    ])
        ->assertStatus(200);

    assertDatabaseHas('group_members', [
        'group_id' => $group->id,
        'user_id' => $invitee->id,
        'invitation_status' => 'pending',
    ]);
});

test('user can leave a group', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();
    $service = app(GroupService::class);
    $group = $service->createGroup($owner, ['name' => 'Leave Group']);
    $service->addMember($group, $user->id);
    $service->acceptInvitation($group, $user->id);

    \Laravel\Sanctum\Sanctum::actingAs($user);

    deleteJson("/api/mobile/v1/groups/{$group->id}/members/{$user->id}")
        ->assertStatus(200);

    assertDatabaseMissing('group_members', [
        'group_id' => $group->id,
        'user_id' => $user->id,
    ]);
});
