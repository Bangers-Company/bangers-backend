<?php

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Event::factory()->create(['name' => 'Tomorrowland', 'location' => 'Belgium']);
    Artist::factory()->create(['name' => 'Martin Garrix']);
    Act::factory()->create(['name' => 'The Garrix Show']);
});

test('can search by name', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson(route('api.mobile.v1.search.mobile', ['query' => 'Tomorrow']));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.events.data')
        ->assertJsonPath('data.events.data.0.name', 'Tomorrowland');
});

test('can filter by entities', function () {
    $user = User::factory()->create();
    // Only search artists
    $response = $this->actingAs($user)->getJson(route('api.mobile.v1.search.mobile', [
        'query' => 'Garrix',
        'entities' => 'artists'
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.artists.data')
        ->assertJsonMissingPath('data.events')
        ->assertJsonMissingPath('data.acts');
});

test('can search by location', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson(route('api.mobile.v1.search.mobile', ['query' => 'Tomorrow']));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.events.data')
        ->assertJsonPath('data.events.data.0.location', 'Belgium');
});

test('it validates the search query length', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson(route('api.mobile.v1.search.mobile', [
        'query' => str_repeat('a', 101)
    ]));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['query']);
});
