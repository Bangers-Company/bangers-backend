<?php

namespace App\Listeners;

use App\Events\GroupInvitationSent;
use App\Notifications\GroupInvitationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendGroupInvitationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(GroupInvitationSent $event): void
    {
        $event->invitedUser->notify(new GroupInvitationNotification($event->group, $event->inviter));
    }
}
