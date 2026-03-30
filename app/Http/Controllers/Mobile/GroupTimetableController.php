<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupTimetable;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Groups\StoreGroupRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Timetables\UpdateTimetableEntriesRequest;
use App\Services\TimetableService;

class GroupTimetableController extends Controller
{
    protected $timetableService;

    public function __construct(TimetableService $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    /**
     * POST /groups/{group}/timetables
     */
    public function store(Request $request, Group $group)
    {
        Gate::authorize('update', $group);

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
        ]);

        $timetable = $this->timetableService->createGroupTimetable($group->id, $request->event_id, $request->name, $request->user());

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'), 201);
    }

    /**
     * GET /groups/{group}/timetables
     */
    public function index(Request $request, Group $group)
    {
        Gate::authorize('view', $group);

        return response()->json($group->timetables()->with('entries.stage', 'entries.act.artists')->get());
    }

    /**
     * GET /groups/{group}/timetables/{timetable}
     */
    public function show(Request $request, Group $group, GroupTimetable $timetable)
    {
        Gate::authorize('view', $timetable);

        $timetable = $this->timetableService->getGroupTimetableWithAttendance($group->id, $timetable->id, $request->user());

        return response()->json($timetable);
    }

    /**
     * PUT /groups/{group}/timetables/{timetable}/entries
     */
    public function updateEntries(UpdateTimetableEntriesRequest $request, Group $group, GroupTimetable $timetable)
    {
        Gate::authorize('manage', $timetable);

        $this->timetableService->updateGroupTimetableEntries($timetable, $request->entry_ids, $request->user());

        return response()->json($timetable->load('entries.stage', 'entries.act.artists'));
    }

    /**
     * DELETE /groups/{group}/timetables/{timetable}
     */
    public function destroy(Request $request, Group $group, GroupTimetable $timetable)
    {
        Gate::authorize('manage', $timetable);

        $timetable->delete();

        return response()->json(['message' => 'Group timetable removed']);
    }

    /**
     * POST /groups/{group}/timetables/{timetable}/entries/{entry}/toggle-attend
     */
    public function toggleAttend(Request $request, Group $group, GroupTimetable $timetable, TimetableEntry $entry)
    {
        Gate::authorize('view', $timetable);
        
        $isAttending = $this->timetableService->toggleGroupEntryAttendance($timetable, $entry->id, $request->user());

        return response()->json([
            'is_attending' => $isAttending,
            'count' => $timetable->attendingUsers()->where('timetable_entry_id', $entry->id)->count()
        ]);
    }

    /**
     * GET /groups/{group}/timetables/{timetable}/entries/{entry}/attendance
     */
    public function getAttendance(Request $request, Group $group, GroupTimetable $timetable, TimetableEntry $entry)
    {
        Gate::authorize('view', $timetable);
        
        $users = $this->timetableService->getGroupEntryAttendees($timetable, $entry->id);

        return response()->json($users);
    }
}
