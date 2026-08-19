<?php

namespace App\Listeners;

use App\Events\FriendshipAccepted;
use App\Models\User;
use App\Notifications\FriendRequestAcceptedNotification;
class SendFriendRequestAcceptedNotification
{

    public function handle(FriendshipAccepted $event): void
    {
        $friendship = $event->friendship;

        // The requester is the one who originally sent the request and should now be notified that it was accepted
        $recipient = User::find($friendship->requested_by);
        
        // The acceptor is the other party in the friendship
        $acceptorId = ($friendship->user_id_1 === $friendship->requested_by)
            ? $friendship->user_id_2
            : $friendship->user_id_1;
            
        $acceptor = User::find($acceptorId);

        if ($recipient && $acceptor) {
            $recipient->notify(new FriendRequestAcceptedNotification($acceptor));
        }
    }
}
