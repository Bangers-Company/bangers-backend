<?php

namespace App\Listeners;

use App\Events\FriendshipAccepted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFriendshipAction
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
    public function handle(FriendshipAccepted $event): void
    {
        \Illuminate\Support\Facades\Log::info("Friendship accepted between {$event->friendship->user_id} and {$event->friendship->friend_id}");
    }
}
