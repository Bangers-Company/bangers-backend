<?php

use App\Models\Act;
use App\Models\Artist;
use App\Models\Stage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list acts', function () {
    Act::factory()->count(3)->create();

    $response = $this->getJson(route('api.acts.index'));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('can create an act', function () {
    $data = [
        'name' => 'The Big Show',
        'description' => 'A massive performance',
    ];

    $response = $this->postJson(route('api.acts.store'), $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'The Big Show');

    $this->assertDatabaseHas('acts', ['name' => 'The Big Show']);
});

test('can link an artist to an act', function () {
    $act = Act::factory()->create();
    $artist = Artist::factory()->create();

    $response = $this->postJson(route('api.acts.artists.attach', $act), [
        'artist_id' => $artist->id
    ]);

    $response->assertStatus(200);
    $this->assertTrue($act->artists()->where('artist_id', $artist->id)->exists());
});

test('can unlink an artist from an act', function () {
    $act = Act::factory()->create();
    $artist = Artist::factory()->create();
    $act->artists()->attach($artist->id);

    $response = $this->deleteJson(route('api.acts.artists.detach', $act), [
        'artist_id' => $artist->id
    ]);

    $response->assertStatus(200);
    $this->assertFalse($act->artists()->where('artist_id', $artist->id)->exists());
});

test('can link an act to a stage', function () {
    $act = Act::factory()->create();
    $stage = Stage::factory()->create();

    $response = $this->postJson(route('api.acts.stages.attach', $act), [
        'stage_id' => $stage->id,
    ]);

    $response->assertStatus(200);
    $this->assertTrue($act->stages()->where('stage_id', $stage->id)->exists());
    $this->assertDatabaseHas('stage_acts', [
        'act_id' => $act->id,
        'stage_id' => $stage->id,
    ]);
});

test('can unlink an act from a stage', function () {
    $act = Act::factory()->create();
    $stage = Stage::factory()->create();
    $act->stages()->attach($stage->id);

    $response = $this->deleteJson(route('api.acts.stages.detach', $act), [
        'stage_id' => $stage->id,
    ]);

    $response->assertStatus(200);
    $this->assertFalse($act->stages()->where('stage_id', $stage->id)->exists());
    $this->assertDatabaseMissing('stage_acts', [
        'act_id' => $act->id,
        'stage_id' => $stage->id,
    ]);
});
