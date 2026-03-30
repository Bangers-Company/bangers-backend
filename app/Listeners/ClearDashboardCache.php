<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearDashboardCache
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
    public function handle(object $event): void
    {
        if ($event instanceof \App\Events\UserRegistered) {
            \Illuminate\Support\Facades\Cache::forget("user_{$event->user->id}_dashboard");
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        }

        if ($event instanceof \App\Events\AttendanceUpdated) {
            \Illuminate\Support\Facades\Cache::forget("user_{$event->user->id}_dashboard");
        }

        if ($event instanceof \App\Events\GroupCreated) {
            \Illuminate\Support\Facades\Cache::forget("user_{$event->group->owner_id}_dashboard");
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        }

        if ($event instanceof \App\Events\FriendshipAccepted) {
            \Illuminate\Support\Facades\Cache::forget("user_{$event->friendship->user_id_1}_dashboard");
            \Illuminate\Support\Facades\Cache::forget("user_{$event->friendship->user_id_2}_dashboard");
        }

        if ($event instanceof \App\Events\EventCreated || $event instanceof \App\Events\EventUpdated || $event instanceof \App\Events\EventDeleted) {
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
            // For mobile, we might need a more granular invalidation if we cache per-event details,
            // but for now, the main dashboard keys will expire or be cleared by attendance updates.
        }
    }
}
