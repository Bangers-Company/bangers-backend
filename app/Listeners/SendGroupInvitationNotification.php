<?php

namespace App\Listeners;

use App\Events\GroupInvitationSent;
use App\Notifications\GroupInvitationNotification;
class SendGroupInvitationNotification
{

    public function handle(GroupInvitationSent $event): void
    {
        $event->invitedUser->notify(new GroupInvitationNotification($event->group, $event->inviter));
    }
}
