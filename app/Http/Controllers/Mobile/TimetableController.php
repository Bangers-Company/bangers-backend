<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTimetable;
use Illuminate\Http\Request;

use App\Services\TimetableService;

class TimetableController extends Controller
{
    protected $timetableService;

    public function __construct(TimetableService $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    /**
     * GET /events/{event_id}/timetable
     * Returns the official, public timetable for an event.
     */
    public function show(Request $request, $eventId)
    {
        $timetable = $this->timetableService->getOfficialTimetable($eventId, $request->user());

        if (!$timetable) {
            abort(404, 'Timetable not found.');
        }

        return response()->json($timetable);
    }

    /**
     * POST /events/{event_id}/timetable/entries/{entry_id}/toggle-attend
     */
    public function toggleAttend(Request $request, $eventId, $entryId)
    {
        $status = $this->timetableService->toggleFavorite($request->user(), $entryId);
        
        return response()->json([
            'is_attending' => $status,
            'message' => $status ? 'Now attending' : 'No longer attending'
        ]);
    }
}
