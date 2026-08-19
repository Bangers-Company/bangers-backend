<?php

namespace App\Events;

use App\Models\Friendship;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestSent
{
    use Dispatchable, SerializesModels;

    public Friendship $friendship;

    public function __construct(Friendship $friendship)
    {
        $this->friendship = $friendship;
    }
}
