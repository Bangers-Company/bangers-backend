<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
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

    public function destroy(Request $request, $eventId)
    {
        $user = $request->user();
        $user->attendedEvents()->detach($eventId);
        return response()->json(['message' => 'Attendance removed']);
    }
}
