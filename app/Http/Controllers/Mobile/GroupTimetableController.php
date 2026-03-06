<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupTimetable;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupTimetableController extends Controller
{
    /**
     * POST /groups/{group_id}/timetables
     */
    public function store(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
        ]);

        $timetable = GroupTimetable::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'group_id' => $groupId,
            'event_id' => $request->event_id,
            'name' => $request->name,
        ]);

        return response()->json($timetable, 201);
    }

    /**
     * GET /groups/{group_id}/timetables
     */
    public function index(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        return response()->json($group->timetables()->get());
    }

    /**
     * GET /groups/{group_id}/timetables/{id}
     */
    public function show(Request $request, $groupId, $id)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        $timetable = GroupTimetable::with(['entries.stage', 'entries.act.artists', 'entries.pivot.added_by'])
            ->where('group_id', $groupId)
            ->findOrFail($id);

        return response()->json($timetable);
    }

    /**
     * PUT /groups/{group_id}/timetables/{id}/entries
     */
    public function updateEntries(Request $request, $groupId, $id)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        $timetable = GroupTimetable::where('group_id', $groupId)->findOrFail($id);

        $request->validate([
            'entry_ids' => 'required|array',
            'entry_ids.*' => 'exists:timetable_entries,id',
        ]);

        $entries = TimetableEntry::whereIn('id', $request->entry_ids)->get();

        foreach ($entries as $entry) {
            if ($entry->timetable->event_id !== $timetable->event_id) {
                abort(400, "Entry {$entry->id} belongs to a different event.");
            }
        }

        DB::transaction(function () use ($timetable, $entries, $request) {
            $timetable->entries()->detach();
            foreach ($entries as $entry) {
                $timetable->entries()->attach($entry->id, [
                    'added_by' => $request->user()->id,
                ]);
            }
        });

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'));
    }

    /**
     * DELETE /groups/{group_id}/timetables/{id}
     */
    public function destroy(Request $request, $groupId, $id)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        $timetable = GroupTimetable::where('group_id', $groupId)->findOrFail($id);
        $timetable->delete();

        return response()->json(['message' => 'Group timetable removed']);
    }
}
