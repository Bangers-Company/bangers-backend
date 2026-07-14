<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\GroupTimetable;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupTimetableFactory extends Factory
{
    protected $model = GroupTimetable::class;

    public function definition(): array
    {
        return [
            'group_id' => \App\Models\Group::factory(),
            'event_id' => \App\Models\Event::factory(),
            'name' => $this->faker->word(),
        ];
    }
}
