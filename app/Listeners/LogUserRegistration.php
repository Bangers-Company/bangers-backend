<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserRegistration
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
    public function handle(UserRegistered $event): void
    {
        \Illuminate\Support\Facades\Log::info("User registered: {$event->user->email}", [
            'id' => $event->user->id,
            'username' => $event->user->username,
        ]);
    }
}
