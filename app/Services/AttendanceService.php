<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\AttendanceUpdated;

class AttendanceService
{
    /**
     * Update or create attendance for a user at an event.
     */
    public function updateAttendance(User $user, string $eventId, string $status): \Illuminate\Database\Eloquent\Relations\Pivot
    {
        $event = Event::findOrFail($eventId);

        $user->attendedEvents()->syncWithoutDetaching([
            $eventId => ['status' => $status]
        ]);

        event(new AttendanceUpdated($user, $event, $status));

        return $user->attendedEvents()->where('event_id', $eventId)->first()->pivot;
    }

    /**
     * Remove attendance for a user at an event.
     */
    public function removeAttendance(User $user, string $eventId): void
    {
        $user->attendedEvents()->detach($eventId);
    }

    /**
     * Get attendees for an event.
     */
    public function getAttendees(string $eventId, int $perPage = 15): LengthAwarePaginator
    {
        $event = Event::findOrFail($eventId);
        return $event->attendees()->with('roles')->paginate($perPage);
    }

    /**
     * Get events attended by a user.
     */
    public function getUserEvents(string $userId, int $perPage = 15): LengthAwarePaginator
    {
        $user = User::findOrFail($userId);
        return $user->attendedEvents()->paginate($perPage);
    }
}
