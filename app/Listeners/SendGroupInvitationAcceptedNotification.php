<?php

namespace App\Listeners;

use App\Events\GroupInvitationAccepted;
use App\Models\User;
use App\Notifications\GroupInvitationAcceptedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendGroupInvitationAcceptedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(GroupInvitationAccepted $event): void
    {
        // Notify the group owner that a member accepted the invitation
        $owner = User::find($event->group->owner_id);

        if ($owner && $owner->id !== $event->user->id) {
            $owner->notify(new GroupInvitationAcceptedNotification($event->group, $event->user));
        }
    }
}
