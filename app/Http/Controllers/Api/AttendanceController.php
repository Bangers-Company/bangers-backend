<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Services\AttendanceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * PUT /events/{eventId}/attendance
     */
    public function update(UpdateAttendanceRequest $request, $eventId)
    {
        $attendance = $this->attendanceService->updateAttendance($request->user(), $eventId, $request->status);
        return new AttendanceResource($attendance);
    }

    /**
     * DELETE /events/{eventId}/attendance
     */
    public function destroy(Request $request, $eventId)
    {
        $this->attendanceService->removeAttendance($request->user(), $eventId);

        return response()->json(['message' => 'Attendance removed']);
    }

    /**
     * GET /events/{eventId}/attendees
     */
    public function index($eventId)
    {
        $attendees = $this->attendanceService->getAttendees($eventId);
        return UserResource::collection($attendees);
    }

    /**
     * GET /users/{id}/events
     */
    public function userEvents($id)
    {
        $events = $this->attendanceService->getUserEvents($id);
        return EventResource::collection($events);
    }
}
