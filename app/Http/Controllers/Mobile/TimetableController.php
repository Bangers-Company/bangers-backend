<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTimetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    /**
     * GET /events/{event_id}/timetable
     * Returns the official, public timetable for an event.
     */
    public function show($eventId)
    {
        $timetable = EventTimetable::with(['entries' => function ($query) {
                $query->with(['stage', 'act.artists'])
                      ->whereNull('deleted_at')
                      ->orderBy('start_time');
            }])
            ->where('event_id', $eventId)
            ->where('is_official', true)
            ->where('is_public', true)
            ->firstOrFail();

        return response()->json($timetable);
    }
}
