<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventTimetable;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventTimetableFactory extends Factory
{
    protected $model = EventTimetable::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => $this->faker->word(),
            'is_official' => true,
            'is_public' => true,
        ];
    }
}
