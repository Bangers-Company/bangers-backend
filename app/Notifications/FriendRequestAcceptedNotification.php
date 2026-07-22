<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FriendRequestAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $acceptor;

    public function __construct(User $acceptor)
    {
        $this->acceptor = $acceptor;
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'FRIEND_REQUEST_ACCEPTED',
            'title' => 'Friend Request Accepted',
            'message' => "{$this->acceptor->first_name} {$this->acceptor->last_name} accepted your friend request.",
            'acceptor_id' => $this->acceptor->id,
            'acceptor_username' => $this->acceptor->username,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Friend Request Accepted 🎉',
            'body' => "{$this->acceptor->first_name} accepted your friend request! Say hi!",
            'data' => [
                'type' => 'FRIEND_REQUEST_ACCEPTED',
                'acceptor_id' => $this->acceptor->id,
                'screen' => 'UserProfile',
            ],
        ];
    }
}
