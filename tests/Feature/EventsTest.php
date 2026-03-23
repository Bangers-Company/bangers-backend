<?php

use App\Models\Event;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = \App\Models\User::factory()->admin()->create();
    \Laravel\Sanctum\Sanctum::actingAs($this->admin);
});

test('can list events', function () {
    Event::factory()->count(3)->create();

    $response = $this->getJson(route('api.v1.events.index'));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('can create an event', function () {
    $media = Media::factory()->create();
    $data = [
        'name' => 'Test Event',
        'description' => 'A great event',
        'location' => 'Test City',
        'start_date' => now()->addMonth()->toDateString(),
        'end_date' => now()->addMonth()->addDays(3)->toDateString(),
        'banner_media_id' => $media->id,
    ];

    $response = $this->postJson(route('api.v1.events.store'), $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Test Event');

    $this->assertDatabaseHas('events', ['name' => 'Test Event']);
});

test('can show an event', function () {
    $event = Event::factory()->create();

    $response = $this->getJson(route('api.v1.events.show', $event));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $event->id);
});

test('can update an event', function () {
    $event = Event::factory()->create(['name' => 'Old Name']);

    $response = $this->putJson(route('api.v1.events.update', $event), [
        'name' => 'New Name'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'New Name');

    $this->assertDatabaseHas('events', ['id' => $event->id, 'name' => 'New Name']);
});

test('can delete an event', function () {
    $event = Event::factory()->create();

    $response = $this->deleteJson(route('api.v1.events.destroy', $event));

    $response->dump()->assertStatus(204);
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
    ]);
    $this->assertNotNull(Event::withTrashed()->find($event->id)->deleted_at);
});
