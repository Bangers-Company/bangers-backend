<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\GroupTimetable;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GroupTimetablePolicy
{
    public function view(User $user, GroupTimetable $timetable): bool
    {
        \Log::info('GroupTimetablePolicy@view check', [
            'user_id' => $user->id,
            'timetable_id' => $timetable->id,
            'group_id' => $timetable->group_id,
            'has_group_relation' => (bool)$timetable->group
        ]);
        return $timetable->group && $timetable->group->members->contains('id', $user->id);
    }

    public function manage(User $user, GroupTimetable $timetable): bool
    {
        return $this->view($user, $timetable); // Any member can currently manage group timetable
    }
}
