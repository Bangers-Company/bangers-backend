<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->company() . " Event",
            "description" => $this->faker->paragraph(),
            "location" => $this->faker->city(),
            "start_date" => $this->faker->dateTimeBetween(
                "+1 month",
                "+2 months",
            ),
            "end_date" => $this->faker->dateTimeBetween(
                "+2 months",
                "+3 months",
            ),
            "banner_media_id" => \App\Models\Media::factory(),
        ];
    }
}
