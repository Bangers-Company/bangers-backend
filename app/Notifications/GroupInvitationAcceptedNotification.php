<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GroupInvitationAcceptedNotification extends Notification
{

    public Group $group;
    public User $member;

    public function __construct(Group $group, User $member)
    {
        $this->group = $group;
        $this->member = $member;
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'GROUP_INVITATION_ACCEPTED',
            'title' => 'Group Member Joined',
            'message' => "{$this->member->first_name} {$this->member->last_name} joined your group '{$this->group->name}'.",
            'group_id' => $this->group->id,
            'group_name' => $this->group->name,
            'member_id' => $this->member->id,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Group Member Joined 🙌',
            'body' => "{$this->member->first_name} joined '{$this->group->name}'!",
            'data' => [
                'type' => 'GROUP_INVITATION_ACCEPTED',
                'group_id' => $this->group->id,
                'screen' => 'GroupDetails',
            ],
        ];
    }
}
