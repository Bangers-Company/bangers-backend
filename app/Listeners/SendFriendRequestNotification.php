<?php

namespace App\Listeners;

use App\Events\FriendRequestSent;
use App\Models\User;
use App\Notifications\FriendRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFriendRequestNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(FriendRequestSent $event): void
    {
        $friendship = $event->friendship;

        // Determine recipient user ID (the user who did NOT trigger the request)
        $recipientId = ($friendship->user_id_1 === $friendship->requested_by)
            ? $friendship->user_id_2
            : $friendship->user_id_1;

        $recipient = User::find($recipientId);
        $requester = User::find($friendship->requested_by);

        if ($recipient && $requester) {
            $recipient->notify(new FriendRequestNotification($requester));
        }
    }
}
