<?php

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Event::factory()->create(['name' => 'Tomorrowland', 'location' => 'Belgium']);
    Artist::factory()->create(['name' => 'Martin Garrix']);
    Act::factory()->create(['name' => 'The Garrix Show']);
});

test('can search by name', function () {
    $response = $this->getJson(route('api.search', ['query' => 'Tomorrow']));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.events.data')
        ->assertJsonPath('data.events.data.0.name', 'Tomorrowland')
        ->assertJsonStructure([
            'data' => [
                'events' => ['data', 'meta', 'links']
            ]
        ]);
});

test('can filter by entities', function () {
    // Only search artists
    $response = $this->getJson(route('api.search', [
        'query' => 'Garrix',
        'entities' => 'artists'
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.artists.data')
        ->assertJsonMissingPath('data.events')
        ->assertJsonMissingPath('data.acts');
});

test('can search by location', function () {
    $response = $this->getJson(route('api.search', ['location' => 'Belgium']));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.events.data')
        ->assertJsonPath('data.events.data.0.location', 'Belgium');
});
