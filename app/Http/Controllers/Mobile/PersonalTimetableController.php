<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\PersonalTimetable;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalTimetableController extends Controller
{
    /**
     * POST /personal-timetables
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();

        // One personal timetable per event
        if (PersonalTimetable::where('user_id', $user->id)->where('event_id', $request->event_id)->exists()) {
            return response()->json(['message' => 'You already have a personal timetable for this event.'], 409);
        }

        $timetable = PersonalTimetable::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'event_id' => $request->event_id,
            'name' => $request->name,
        ]);

        // Automatically populate with ALL official entries
        $officialEntries = TimetableEntry::whereHas('timetable', function ($q) use ($request) {
            $q->where('event_id', $request->event_id);
        })->get();

        foreach ($officialEntries as $entry) {
            $timetable->entries()->attach($entry->id, [
                'time_range' => "[{$entry->start_time}, {$entry->end_time})",
                'is_attending' => false,
            ]);
        }

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'), 201);
    }

    /**
     * GET /personal-timetables/{event_id}
     */
    public function show(Request $request, $eventId)
    {
        $timetable = PersonalTimetable::with(['entries.stage', 'entries.act.artists'])
            ->where('user_id', $request->user()->id)
            ->where('event_id', $eventId)
            ->firstOrFail();

        return response()->json($timetable);
    }

    /**
     * PUT /personal-timetables/{id}/entries
     * Batch update entries with overlap check.
     */
    public function updateEntries(Request $request, $id)
    {
        $timetable = PersonalTimetable::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'entry_ids' => 'required|array',
            'entry_ids.*' => 'exists:timetable_entries,id',
        ]);

        $entries = TimetableEntry::whereIn('id', $request->entry_ids)->get();

        // Validate entries belong to the same event
        // (TimetableEntry -> EventTimetable -> Event)
        foreach ($entries as $entry) {
            if ($entry->timetable->event_id !== $timetable->event_id) {
                return response()->json(['message' => "Entry {$entry->id} does not belong to this event."], 400);
            }
        }

        try {
            DB::transaction(function () use ($timetable, $entries) {
                // Clear existing
                $timetable->entries()->detach();

                // Add new ones - the database EXCLUDE constraint will handle the overlap check
                foreach ($entries as $entry) {
                    $timetable->entries()->attach($entry->id, [
                        'time_range' => "[{$entry->start_time}, {$entry->end_time})",
                    ]);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23P01') { // exclusion_violation
                return response()->json(['message' => 'Cannot add overlapping entries to your timetable.'], 409);
            }
            throw $e;
        }

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'));
    }

    /**
     * DELETE /personal-timetables/{id}
     */
    public function destroy(Request $request, $id)
    {
        $timetable = PersonalTimetable::where('user_id', $request->user()->id)->findOrFail($id);
        $timetable->delete();

        return response()->json(['message' => 'Personal timetable removed']);
    }

    /**
     * POST /personal-timetables/{id}/entries/{entry_id}/toggle-attend
     */
    public function toggleAttend(Request $request, $id, $entryId)
    {
        $timetable = PersonalTimetable::where('user_id', $request->user()->id)->findOrFail($id);
        
        $entry = $timetable->entries()->where('timetable_entry_id', $entryId)->firstOrFail();
        
        $newStatus = !$entry->pivot->is_attending;
        
        $timetable->entries()->updateExistingPivot($entryId, [
            'is_attending' => $newStatus,
        ]);

        return response()->json([
            'is_attending' => $newStatus,
            'message' => $newStatus ? 'Now attending' : 'No longer attending'
        ]);
    }
}
