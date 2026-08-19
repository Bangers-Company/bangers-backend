<?php

use App\Models\User;
use App\Models\Group;
use App\Events\FriendRequestSent;
use App\Events\FriendshipAccepted;
use App\Events\GroupInvitationSent;
use App\Services\FriendshipService;
use App\Services\GroupService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FriendRequestNotification;
use App\Notifications\FriendRequestAcceptedNotification;
use App\Notifications\GroupInvitationNotification;

test('authenticated user can register device token', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/mobile/v1/user/device-tokens', [
            'token' => 'fcm_test_token_12345',
            'device_type' => 'android',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('message', 'Device token registered successfully');

    $this->assertDatabaseHas('user_device_tokens', [
        'user_id' => $user->id,
        'token' => 'fcm_test_token_12345',
        'device_type' => 'android',
    ]);
});

test('authenticated user can unregister device token', function () {
    $user = User::factory()->create();
    $user->deviceTokens()->create([
        'token' => 'fcm_test_token_to_delete',
        'device_type' => 'ios',
    ]);

    $response = $this->actingAs($user)
        ->deleteJson('/api/mobile/v1/user/device-tokens', [
            'token' => 'fcm_test_token_to_delete',
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('user_device_tokens', [
        'user_id' => $user->id,
        'token' => 'fcm_test_token_to_delete',
    ]);
});

test('sending friend request dispatches event and notifies target user', function () {
    Notification::fake();
    Event::fake([FriendRequestSent::class]);

    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $service = app(FriendshipService::class);
    $friendship = $service->sendRequest($userA, $userB->id);

    Event::assertDispatched(FriendRequestSent::class, function ($event) use ($friendship) {
        return $event->friendship->id === $friendship->id;
    });
});

test('accepting friend request dispatches event and notifies requester', function () {
    Event::fake([FriendshipAccepted::class]);

    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $service = app(FriendshipService::class);
    $service->sendRequest($userA, $userB->id);
    $friendship = $service->acceptRequest($userB, $userA->id);

    Event::assertDispatched(FriendshipAccepted::class, function ($event) use ($friendship) {
        return $event->friendship->id === $friendship->id;
    });
});

test('inviting user to group dispatches GroupInvitationSent event', function () {
    Event::fake([GroupInvitationSent::class]);

    $owner = User::factory()->create();
    $invitedUser = User::factory()->create();

    $groupService = app(GroupService::class);
    $group = $groupService->createGroup($owner, [
        'name' => 'Festival Crew',
        'user_ids' => [$invitedUser->id],
    ]);

    Event::assertDispatched(GroupInvitationSent::class);
});

test('user can list and mark notifications as read', function () {
    $user = User::factory()->create();
    $sender = User::factory()->create();

    // Send notification
    $user->notify(new FriendRequestNotification($sender));

    $response = $this->actingAs($user)
        ->getJson('/api/mobile/v1/notifications');

    $response->assertStatus(200)
        ->assertJsonPath('unread_count', 1);

    $notificationId = $response->json('data.0.id');

    $readResponse = $this->actingAs($user)
        ->patchJson("/api/mobile/v1/notifications/{$notificationId}/read");

    $readResponse->assertStatus(200);

    $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
});
