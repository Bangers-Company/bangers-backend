<?php

use App\Models\Festival;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list festivals', function () {
    Festival::factory()->count(3)->create();

    $response = $this->getJson(route('api.festivals.index'));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('can create a festival', function () {
    $media = Media::factory()->create();
    $data = [
        'name' => 'Test Festival',
        'description' => 'A great festival',
        'location' => 'Test City',
        'start_date' => now()->addMonth()->toDateString(),
        'end_date' => now()->addMonth()->addDays(3)->toDateString(),
        'banner_media_id' => $media->id,
    ];

    $response = $this->postJson(route('api.festivals.store'), $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Test Festival');

    $this->assertDatabaseHas('festivals', ['name' => 'Test Festival']);
});

test('can show a festival', function () {
    $festival = Festival::factory()->create();

    $response = $this->getJson(route('api.festivals.show', $festival));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $festival->id);
});

test('can update a festival', function () {
    $festival = Festival::factory()->create(['name' => 'Old Name']);

    $response = $this->putJson(route('api.festivals.update', $festival), [
        'name' => 'New Name'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'New Name');

    $this->assertDatabaseHas('festivals', ['id' => $festival->id, 'name' => 'New Name']);
});

test('can delete a festival', function () {
    $festival = Festival::factory()->create();

    $response = $this->deleteJson(route('api.festivals.destroy', $festival));

    $response->assertStatus(204);
    $this->assertSoftDeleted('festivals', ['id' => $festival->id]);
});
