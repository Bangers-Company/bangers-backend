<?php

namespace App\Listeners;

use App\Events\AttendanceUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAttendanceChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AttendanceUpdated $event): void
    {
        \Illuminate\Support\Facades\Log::info("User {$event->user->id} updated attendance for event {$event->event->id} to '{$event->status}'");
    }
}
