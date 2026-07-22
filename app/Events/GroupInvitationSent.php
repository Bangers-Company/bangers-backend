<?php

namespace App\Events;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupInvitationSent
{
    use Dispatchable, SerializesModels;

    public Group $group;
    public User $invitedUser;
    public User $inviter;

    public function __construct(Group $group, User $invitedUser, User $inviter)
    {
        $this->group = $group;
        $this->invitedUser = $invitedUser;
        $this->inviter = $inviter;
    }
}
