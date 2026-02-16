<?php

use App\Models\Festival;
use App\Models\Stage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test("can list stages", function () {
    Stage::factory()->count(3)->create();

    $response = $this->getJson(route("api.stages.index"));

    $response->assertStatus(200)->assertJsonCount(3, "data");
});

test("can create a stage", function () {
    $festival = Festival::factory()->create();
    $data = [
        "festival_id" => $festival->id,
        "name" => "Main Stage",
        "description" => "The biggest arena",
    ];

    $response = $this->postJson(route("api.stages.store"), $data);

    $response->assertStatus(201)->assertJsonPath("data.name", "Main Stage");

    $this->assertDatabaseHas("stages", [
        "name" => "Main Stage",
        "festival_id" => $festival->id,
    ]);
});

test("can update a stage", function () {
    $stage = Stage::factory()->create(["name" => "Old Stage"]);

    $response = $this->putJson(route("api.stages.update", $stage), [
        "name" => "New Stage",
    ]);

    $response->assertStatus(200)->assertJsonPath("data.name", "New Stage");

    $this->assertDatabaseHas("stages", [
        "id" => $stage->id,
        "name" => "New Stage",
    ]);
});
