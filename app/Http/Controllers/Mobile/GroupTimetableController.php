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

        // Automatically populate with ALL official entries
        $officialEntries = TimetableEntry::whereHas('timetable', function ($q) use ($request) {
            $q->where('event_id', $request->event_id)->where('is_official', true);
        })->get();

        foreach ($officialEntries as $entry) {
            $timetable->entries()->attach($entry->id, [
                'added_by' => $request->user()->id,
            ]);
        }

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'), 201);
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

        return response()->json($group->timetables()->with('entries.stage', 'entries.act.artists')->get());
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

        $timetable = GroupTimetable::with([
            'entries.stage', 
            'entries.act.artists',
            'attendingUsers.profileMedia'
        ])
            ->where('group_id', $groupId)
            ->findOrFail($id);

        $userId = $request->user()->id;
        $attendingUsers = $timetable->attendingUsers;

        $timetable->entries->map(function ($entry) use ($attendingUsers, $userId) {
            $entryAttendees = $attendingUsers->where('pivot.timetable_entry_id', $entry->id);
            
            $entry->pivot->is_attending = $entryAttendees->contains('id', $userId);
            $entry->pivot->attending_count = $entryAttendees->count();
            
            $entry->attendees = $entryAttendees->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => trim($u->first_name . ' ' . $u->last_name),
                    'profile_photo_path' => $u->profileMedia ? $u->profileMedia->url : null,
                ];
            })->values();

            return $entry;
        });

        // Hide attendingUsers from main JSON response to reduce payload size
        $timetable->makeHidden('attendingUsers');

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

    /**
     * POST /groups/{group_id}/timetables/{id}/entries/{entry_id}/toggle-attend
     */
    public function toggleAttend(Request $request, $groupId, $id, $entryId)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        $timetable = GroupTimetable::where('group_id', $groupId)->findOrFail($id);
        
        $userId = $request->user()->id;
        $isAttending = $timetable->attendingUsers()
            ->where('user_id', $userId)
            ->where('timetable_entry_id', $entryId)
            ->exists();

        if ($isAttending) {
            $timetable->attendingUsers()
                ->wherePivot('timetable_entry_id', $entryId)
                ->detach($userId);
        } else {
            $timetable->attendingUsers()->attach($userId, [
                'timetable_entry_id' => $entryId,
            ]);
        }

        return response()->json([
            'is_attending' => !$isAttending,
            'count' => $timetable->attendingUsers()->where('timetable_entry_id', $entryId)->count()
        ]);
    }

    /**
     * GET /groups/{group_id}/timetables/{id}/entries/{entry_id}/attendance
     */
    public function getAttendance(Request $request, $groupId, $id, $entryId)
    {
        $group = Group::findOrFail($groupId);
        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        $timetable = GroupTimetable::where('group_id', $groupId)->findOrFail($id);
        
        $users = $timetable->attendingUsers()
            ->where('timetable_entry_id', $entryId)
            ->get(['users.id', 'users.first_name', 'users.last_name', 'users.profile_media_id']);

        $users->map(function ($u) {
            $u->name = trim($u->first_name . ' ' . $u->last_name);
            $u->profile_photo_path = $u->profileMedia ? $u->profileMedia->url : null;
            return $u;
        });

        return response()->json($users);
    }
}
