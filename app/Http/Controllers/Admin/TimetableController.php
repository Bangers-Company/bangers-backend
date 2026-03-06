<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTimetable;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TimetableController extends Controller
{
    /**
     * GET /admin/timetables
     */
    public function index()
    {
        return response()->json(EventTimetable::withCount('entries')->get());
    }

    /**
     * GET /admin/timetables/{id}
     */
    public function show($id)
    {
        $timetable = EventTimetable::with(['entries.stage', 'entries.act.artists'])->findOrFail($id);
        return response()->json($timetable);
    }

    /**
     * POST /admin/timetables
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id|unique:event_timetables,event_id',
            'name' => 'required|string|max:255',
            'is_official' => 'boolean',
            'is_public' => 'boolean',
        ]);

        $timetable = EventTimetable::create([
            'id' => Str::uuid(),
            'event_id' => $request->event_id,
            'name' => $request->name,
            'is_official' => $request->input('is_official', true),
            'is_public' => $request->input('is_public', false),
        ]);

        return response()->json($timetable, 201);
    }

    /**
     * PUT /admin/timetables/{id}
     * Update entries and meta data.
     */
    public function update(Request $request, $id)
    {
        $timetable = EventTimetable::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'is_public' => 'boolean',
            'entries' => 'array',
            'entries.*.stage_id' => 'required|exists:stages,id',
            'entries.*.act_id' => 'required|exists:acts,id',
            'entries.*.start_time' => 'required|date',
            'entries.*.end_time' => 'required|date|after:entries.*.start_time',
        ]);

        if ($request->has('name')) $timetable->name = $request->name;
        if ($request->has('is_public')) $timetable->is_public = $request->is_public;

        DB::transaction(function () use ($timetable, $request) {
            $timetable->save();

            if ($request->has('entries')) {
                // Check for overlaps within the request itself for the same stage
                $entriesByStage = [];
                foreach ($request->entries as $e) {
                    $stageId = $e['stage_id'];
                    $start = new \DateTime($e['start_time']);
                    $end = new \DateTime($e['end_time']);

                    if (!isset($entriesByStage[$stageId])) $entriesByStage[$stageId] = [];

                    foreach ($entriesByStage[$stageId] as $existing) {
                        if ($start < $existing['end'] && $end > $existing['start']) {
                            abort(409, "Overlap detected for stage {$stageId} between acts in request.");
                        }
                    }
                    $entriesByStage[$stageId][] = ['start' => $start, 'end' => $end];
                }

                // Delete existing entries and recreate
                $timetable->entries()->delete();

                foreach ($request->entries as $eData) {
                    $timetable->entries()->create([
                        'id' => Str::uuid(),
                        'stage_id' => $eData['stage_id'],
                        'act_id' => $eData['act_id'],
                        'start_time' => $eData['start_time'],
                        'end_time' => $eData['end_time'],
                        'version' => $timetable->entries()->withTrashed()->count() + 1
                    ]);
                }
            }
        });

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'));
    }

    /**
     * PATCH /admin/timetables/{id}/publish
     */
    public function publish(Request $request, $id)
    {
        $timetable = EventTimetable::findOrFail($id);
        $timetable->update(['is_public' => $request->input('is_public', true)]);

        return response()->json($timetable);
    }

    /**
     * DELETE /admin/timetables/{id}
     */
    public function destroy($id)
    {
        $timetable = EventTimetable::findOrFail($id);
        $timetable->delete();

        return response()->json(['message' => 'Timetable deleted']);
    }
}
