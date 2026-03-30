<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Event;
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

    public function update(UpdateAttendanceRequest $request, $eventId)
    {
        $attendance = $this->attendanceService->updateAttendance($request->user(), $eventId, $request->status);
        return new AttendanceResource($attendance);
    }

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
        return \App\Http\Resources\UserResource::collection($attendees);
    }
}
