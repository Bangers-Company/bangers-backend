<?php

namespace App\Services;

use App\Models\EventTimetable;
use App\Models\Group;
use App\Models\GroupTimetable;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Models\UserTimetableFavorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TimetableService
{
    /**
     * Get the official timetable for an event.
     */
    public function getOfficialTimetable(string $eventId, ?User $user): ?EventTimetable
    {
        $timetable = EventTimetable::with(['entries' => function ($query) use ($user) {
                $query->with(['stage', 'act.artists'])
                      ->whereNull('deleted_at')
                      ->orderBy('start_time');
                
                if ($user) {
                    $query->select('*')
                          ->selectSub(function ($q) use ($user) {
                              $q->from('user_timetable_favorites')
                                ->where('user_id', $user->id)
                                ->whereColumn('timetable_entry_id', 'timetable_entries.id')
                                ->selectRaw('1');
                          }, 'is_attending');
                }
            }])
            ->where('event_id', $eventId)
            ->where('is_official', true)
            ->where('is_public', true)
            ->first();

        if ($timetable && $user) {
            foreach ($timetable->entries as $entry) {
                $entry->is_attending = (bool) $entry->is_attending;
            }
        }

        return $timetable;
    }

    /**
     * Toggle a user's favorite status for a timetable entry.
     */
    public function toggleFavorite(User $user, string $entryId): bool
    {
        $favorite = UserTimetableFavorite::where('user_id', $user->id)
            ->where('timetable_entry_id', $entryId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return false;
        }

        UserTimetableFavorite::create([
            'user_id' => $user->id,
            'timetable_entry_id' => $entryId,
        ]);

        return true;
    }

    /**
     * Create a group timetable and populate it with official entries.
     */
    public function createGroupTimetable(string $groupId, string $eventId, string $name, User $creator): GroupTimetable
    {
        return DB::transaction(function () use ($groupId, $eventId, $name, $creator) {
            $timetable = GroupTimetable::create([
                'group_id' => $groupId,
                'event_id' => $eventId,
                'name' => $name,
            ]);

            $officialEntries = TimetableEntry::whereHas('timetable', function ($q) use ($eventId) {
                $q->where('event_id', $eventId)->where('is_official', true);
            })->get();

            foreach ($officialEntries as $entry) {
                $timetable->entries()->attach($entry->id, [
                    'added_by' => $creator->id,
                ]);
            }

            return $timetable;
        });
    }

    /**
     * Get a group timetable with detailed attendance.
     */
    public function getGroupTimetableWithAttendance(string $groupId, string $timetableId, User $user): GroupTimetable
    {
        $timetable = GroupTimetable::with([
            'entries.stage', 
            'entries.act.artists',
            'attendingUsers.profileMedia'
        ])
            ->where('group_id', $groupId)
            ->findOrFail($timetableId);

        $userId = $user->id;
        $attendingUsers = $timetable->attendingUsers;

        $timetable->entries->map(function ($entry) use ($attendingUsers, $userId) {
            $entryAttendees = $attendingUsers->where('pivot.timetable_entry_id', $entry->id);
            
            $entry->pivot->is_attending = $entryAttendees->contains('id', $userId);
            $entry->pivot->attending_count = $entryAttendees->count();
            
            $entry->attendees = $entryAttendees->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => trim($u->first_name . ' ' . $u->last_name),
                    'profile_photo_path' => $u->profileMedia ? $u->profileMedia->url : null,
                ];
            })->values();

            return $entry;
        });

        $timetable->makeHidden('attendingUsers');

        return $timetable;
    }

    /**
     * Update entries for a group timetable.
     */
    public function updateGroupTimetableEntries(GroupTimetable $timetable, array $entryIds, User $actor): void
    {
        $entries = TimetableEntry::with('timetable')->whereIn('id', $entryIds)->get();

        foreach ($entries as $entry) {
            if (!$entry->timetable || $entry->timetable->event_id !== $timetable->event_id) {
                abort(400, "Entry {$entry->id} belongs to a different event or has no timetable.");
            }
        }
        
        // dd('Passed checks', $entries->count());

        DB::transaction(function () use ($timetable, $entries, $actor) {
            $timetable->entries()->detach();
            foreach ($entries as $entry) {
                $timetable->entries()->attach($entry->id, [
                    'added_by' => $actor->id,
                ]);
            }
        });
    }

    /**
     * Toggle user attendance for a specific entry in a group timetable.
     */
    public function toggleGroupEntryAttendance(GroupTimetable $timetable, string $entryId, User $user): bool
    {
        $userId = $user->id;
        $isAttending = $timetable->attendingUsers()
            ->where('user_id', $userId)
            ->where('timetable_entry_id', $entryId)
            ->exists();

        if ($isAttending) {
            $timetable->attendingUsers()
                ->wherePivot('timetable_entry_id', $entryId)
                ->detach($userId);
            return false;
        }

        $timetable->attendingUsers()->attach($userId, [
            'timetable_entry_id' => $entryId,
        ]);

        return true;
    }

    /**
     * Get attendees for a specific entry in a group timetable.
     */
    public function getGroupEntryAttendees(GroupTimetable $timetable, string $entryId): Collection
    {
        $users = $timetable->attendingUsers()
            ->where('timetable_entry_id', $entryId)
            ->get(['users.id', 'users.first_name', 'users.last_name', 'users.profile_media_id']);

        return $users->map(function ($u) {
            $u->name = trim($u->first_name . ' ' . $u->last_name);
            $u->profile_photo_path = $u->profileMedia ? $u->profileMedia->url : null;
            return $u;
        });
    }
}
