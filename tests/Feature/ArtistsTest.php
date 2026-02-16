<?php

use App\Models\Artist;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list artists', function () {
    Artist::factory()->count(3)->create();

    $response = $this->getJson(route('api.artists.index'));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('can create an artist', function () {
    $media = Media::factory()->create();
    $data = [
        'name' => 'Test Artist',
        'bio' => 'Artist biography',
        'genre' => 'Electronic',
        'image_media_id' => $media->id,
    ];

    $response = $this->postJson(route('api.artists.store'), $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Test Artist');

    $this->assertDatabaseHas('artists', ['name' => 'Test Artist']);
});

test('can show an artist', function () {
    $artist = Artist::factory()->create();

    $response = $this->getJson(route('api.artists.show', $artist));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $artist->id);
});

test('can update an artist', function () {
    $artist = Artist::factory()->create(['name' => 'Old Artist']);

    $response = $this->putJson(route('api.artists.update', $artist), [
        'name' => 'New Artist'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'New Artist');

    $this->assertDatabaseHas('artists', ['id' => $artist->id, 'name' => 'New Artist']);
});

test('can delete an artist', function () {
    $artist = Artist::factory()->create();

    $response = $this->deleteJson(route('api.artists.destroy', $artist));

    $response->assertStatus(204);
    $this->assertSoftDeleted('artists', ['id' => $artist->id]);
});
