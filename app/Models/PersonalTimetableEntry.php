<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonalTimetableEntry extends Pivot
{
    protected $table = 'personal_timetable_entries';

    protected $fillable = [
        'timetable_id',
        'timetable_entry_id',
        'time_range',
        'is_attending',
    ];

    public $timestamps = false;
}
