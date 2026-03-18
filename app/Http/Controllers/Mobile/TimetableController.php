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
    public function show(Request $request, $eventId)
    {
        $user = $request->user();
        
        $timetable = EventTimetable::with(['entries' => function ($query) use ($user) {
                $query->with(['stage', 'act.artists'])
                      ->whereNull('deleted_at')
                      ->orderBy('start_time');
                
                if ($user) {
                    $query->select('*')
                          ->selectSub(function ($q) use ($user) {
                              $q->from('user_timetable_favorites')
                                ->where('user_id', $user->id)
                                ->whereColumn('timetable_entry_id', 'timetable_entries.id')
                                ->selectRaw('1');
                          }, 'is_attending');
                }
            }])
            ->where('event_id', $eventId)
            ->where('is_official', true)
            ->where('is_public', true)
            ->firstOrFail();

        // Convert is_attending to boolean for each entry
        foreach ($timetable->entries as $entry) {
            $entry->is_attending = (bool) $entry->is_attending;
        }

        return response()->json($timetable);
    }

    /**
     * POST /events/{event_id}/timetable/entries/{entry_id}/toggle-attend
     */
    public function toggleAttend(Request $request, $eventId, $entryId)
    {
        $user = $request->user();
        
        $favorite = \App\Models\UserTimetableFavorite::where('user_id', $user->id)
            ->where('timetable_entry_id', $entryId)
            ->first();
            
        if ($favorite) {
            $favorite->delete();
            $status = false;
        } else {
            \App\Models\UserTimetableFavorite::create([
                'user_id' => $user->id,
                'timetable_entry_id' => $entryId,
                'created_at' => now(),
            ]);
            $status = true;
        }
        
        return response()->json([
            'is_attending' => $status,
            'message' => $status ? 'Now attending' : 'No longer attending'
        ]);
    }
}
