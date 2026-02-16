<?php

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('can upload media', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('banner.jpg');

    $response = $this->postJson(route('api.media.store'), [
        'file' => $file,
        'type' => 'festival_banner'
    ]);

    $response->assertStatus(201);

    $id = $response->json('id');
    $storageKey = $response->json('storage_key');

    Storage::disk('public')->assertExists($storageKey);
    $this->assertDatabaseHas('media', ['id' => $id, 'type' => 'festival_banner']);
});

test('can show media info', function () {
    $media = Media::factory()->create();

    $response = $this->getJson(route('api.media.show', $media));

    $response->assertStatus(200)
        ->assertJsonPath('id', $media->id);
});

test('can delete media', function () {
    Storage::fake('public');
    
    // Manually create media with a valid storage key for the fake disk
    $media = Media::factory()->create(['storage_key' => 'media/test.jpg']);
    Storage::disk('public')->put('media/test.jpg', 'fake content');

    $response = $this->deleteJson(route('api.media.destroy', $media));

    $response->assertStatus(204);
    Storage::disk('public')->assertMissing('media/test.jpg');
    $this->assertSoftDeleted('media', ['id' => $media->id]);
});
