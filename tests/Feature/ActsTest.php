<?php

use App\Models\Act;
use App\Models\Artist;
use App\Models\Festival;
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

test('can link an act to a festival', function () {
    $act = Act::factory()->create();
    $festival = Festival::factory()->create();
    $date = now()->addMonth()->toDateTimeString();

    $response = $this->postJson(route('api.acts.festivals.attach', $act), [
        'festival_id' => $festival->id,
        'announcement_date' => $date
    ]);

    $response->assertStatus(200);
    $this->assertTrue($act->festivals()->where('festival_id', $festival->id)->exists());
    $this->assertDatabaseHas('festival_acts', [
        'act_id' => $act->id,
        'festival_id' => $festival->id,
        'announcement_date' => $date
    ]);
});
