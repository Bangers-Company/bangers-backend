<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FriendRequestNotification extends Notification
{

    public User $requester;

    public function __construct(User $requester)
    {
        $this->requester = $requester;
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'FRIEND_REQUEST',
            'title' => 'New Friend Request',
            'message' => "{$this->requester->first_name} {$this->requester->last_name} sent you a friend request.",
            'requester_id' => $this->requester->id,
            'requester_username' => $this->requester->username,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'New Friend Request 🤝',
            'body' => "{$this->requester->first_name} sent you a friend request. Tap to respond!",
            'data' => [
                'type' => 'FRIEND_REQUEST',
                'requester_id' => $this->requester->id,
                'screen' => 'FriendRequests',
            ],
        ];
    }
}
