<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * PUT /events/{eventId}/attendance
     */
    public function update(Request $request, $eventId)
    {
        $request->validate([
            'status' => 'required|in:going,interested'
        ]);

        $user = $request->user();
        $event = Event::findOrFail($eventId);

        $user->attendedEvents()->syncWithoutDetaching([
            $eventId => ['status' => $request->status]
        ]);

        $attendance = $user->attendedEvents()->where('event_id', $eventId)->first()->pivot;
        return new AttendanceResource($attendance);
    }

    /**
     * DELETE /events/{eventId}/attendance
     */
    public function destroy(Request $request, $eventId)
    {
        $user = $request->user();
        $user->attendedEvents()->detach($eventId);

        return response()->json(['message' => 'Attendance removed']);
    }

    /**
     * GET /events/{eventId}/attendees
     */
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        return UserResource::collection($event->attendees()->with('roles')->paginate());
    }

    /**
     * GET /users/{id}/events
     */
    public function userEvents($id)
    {
        $user = User::findOrFail($id);
        return EventResource::collection($user->attendedEvents()->paginate());
    }
}
