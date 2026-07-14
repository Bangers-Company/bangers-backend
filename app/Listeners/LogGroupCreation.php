<?php

namespace App\Listeners;

use App\Events\GroupCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogGroupCreation
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
    public function handle(GroupCreated $event): void
    {
        \Illuminate\Support\Facades\Log::info("Group created: {$event->group->name} by user {$event->group->owner_id}");
    }
}
