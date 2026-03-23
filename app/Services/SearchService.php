<?php

namespace App\Services;

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    /**
     * Perform a multi-entity search.
     */
    public function search(array $filters): array
    {
        $queryText = $filters['query'] ?? null;
        $date = $filters['date'] ?? null;
        $location = $filters['location'] ?? null;
        $entities = $filters['entities'] ?? ['events', 'artists', 'acts', 'users'];
        $perPage = (int) ($filters['per_page'] ?? 15);
        $withMedia = $filters['with_media'] ?? false;

        if (is_string($entities)) {
            $entities = explode(',', $entities);
        }

        $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
        $results = [];

        if (in_array('events', $entities)) {
            $eventQuery = Event::query();
            if ($withMedia) $eventQuery->with('banner');
            $eventQuery->withCount('attendees');

            if ($queryText) {
                $eventQuery->where(function($q) use ($queryText, $like) {
                    $q->where('name', $like, "%{$queryText}%")
                      ->orWhere('location', $like, "%{$queryText}%")
                      ->orWhere('description', $like, "%{$queryText}%");
                });
            }
            if ($date) {
                $eventQuery->whereDate('start_date', '<=', $date)
                           ->whereDate('end_date', '>=', $date);
            }
            if ($location) {
                $eventQuery->where('location', $like, "%{$location}%");
            }

            $results['events'] = $eventQuery->paginate($perPage, ['*'], 'events_page');
        }

        if (in_array('artists', $entities)) {
            $artistQuery = Artist::query();
            if ($withMedia) $artistQuery->with('image');
            if ($queryText) {
                $artistQuery->where('name', $like, "%{$queryText}%");
            }
            $results['artists'] = $artistQuery->paginate($perPage, ['*'], 'artists_page');
        }

        if (in_array('acts', $entities)) {
            $actQuery = Act::query();
            if ($queryText) {
                $actQuery->where('name', $like, "%{$queryText}%");
            }
            $results['acts'] = $actQuery->paginate($perPage, ['*'], 'acts_page');
        }

        if (in_array('users', $entities)) {
            $userQuery = User::where('is_public', true);
            if ($withMedia) $userQuery->with('profileMedia');
            if ($queryText) {
                $userQuery->where(function($q) use ($queryText, $like) {
                    $q->where('first_name', $like, "%{$queryText}%")
                      ->orWhere('last_name', $like, "%{$queryText}%")
                      ->orWhere('username', $like, "%{$queryText}%")
                      ->orWhereRaw("first_name || ' ' || last_name " . $like . " ?", ["%{$queryText}%"]);
                });
            }
            $results['users'] = $userQuery->paginate($perPage, ['*'], 'users_page');
        }

        return $results;
    }

    /**
     * Get suggested events for a user.
     */
    public function getSuggestedEvents(User $user, int $perPage = 10): LengthAwarePaginator
    {
        // For now, simple upcoming events
        return Event::with(['banner'])
            ->withUserStatus()
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get events that friends are attending.
     */
    public function getFriendsEvents(User $user, int $perPage = 10): LengthAwarePaginator
    {
        // For now, same as suggested but could be filtered by friend attendance in the future
        return Event::with(['banner'])
            ->withUserStatus()
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->paginate($perPage);
    }
}
