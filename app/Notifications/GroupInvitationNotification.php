<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GroupInvitationNotification extends Notification
{

    public Group $group;
    public User $inviter;

    public function __construct(Group $group, User $inviter)
    {
        $this->group = $group;
        $this->inviter = $inviter;
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'GROUP_INVITATION',
            'title' => 'Group Invitation',
            'message' => "{$this->inviter->first_name} invited you to join the group '{$this->group->name}'.",
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'inviter_id' => $this->inviter->id,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'New Group Invite 🎪',
            'body' => "{$this->inviter->first_name} invited you to join '{$this->group->name}'!",
            'data' => [
                'type' => 'GROUP_INVITATION',
                'group_id' => $this->group->id,
                'screen' => 'GroupDetails',
            ],
        ];
    }
}
