<?php

namespace Database\Factories;

use App\Models\Act;
use App\Models\EventTimetable;
use App\Models\Stage;
use App\Models\TimetableEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimetableEntryFactory extends Factory
{
    protected $model = TimetableEntry::class;

    public function definition(): array
    {
        return [
            'timetable_id' => EventTimetable::factory(),
            'stage_id' => Stage::factory(),
            'act_id' => Act::factory(),
            'start_time' => now()->addHours(1),
            'end_time' => now()->addHours(2),
            'version' => 1,
        ];
    }
}
