<?php

namespace App\Services;

use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    /**
     * Perform a multi-entity search.
     */
    public function search(array $filters): array
    {
        $entities = $filters['entities'] ?? ['events', 'artists', 'acts', 'users'];
        if (is_string($entities)) {
            $entities = explode(',', $entities);
        }

        $results = [];

        foreach ($entities as $entity) {
            $method = 'search' . ucfirst($entity);
            if (method_exists($this, $method)) {
                $results[$entity] = $this->$method($filters);
            }
        }

        return $results;
    }

    protected function searchEvents(array $filters): LengthAwarePaginator
    {
        $queryText = $filters['query'] ?? null;
        $date = $filters['date'] ?? null;
        $location = $filters['location'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 15);
        $withMedia = $filters['with_media'] ?? false;
        $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query = Event::query();
        if ($withMedia) $query->with('banner');
        $query->withCount('attendees');

        if ($queryText) {
            $query->where(function ($q) use ($queryText, $like) {
                $q->where('name', $like, "%{$queryText}%")
                    ->orWhere('location', $like, "%{$queryText}%")
                    ->orWhere('description', $like, "%{$queryText}%");
            });
        }
        if ($date) {
            $query->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date);
        }
        if ($location) {
            $query->where('location', $like, "%{$location}%");
        }

        return $query->paginate($perPage, ['*'], 'events_page');
    }

    protected function searchArtists(array $filters): LengthAwarePaginator
    {
        $queryText = $filters['query'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 15);
        $withMedia = $filters['with_media'] ?? false;
        $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query = Artist::query();
        if ($withMedia) $query->with('image');
        if ($queryText) {
            $query->where('name', $like, "%{$queryText}%");
        }
        return $query->paginate($perPage, ['*'], 'artists_page');
    }

    protected function searchActs(array $filters): LengthAwarePaginator
    {
        $queryText = $filters['query'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 15);
        $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query = Act::query();
        if ($queryText) {
            $query->where('name', $like, "%{$queryText}%");
        }
        return $query->paginate($perPage, ['*'], 'acts_page');
    }

    protected function searchUsers(array $filters): LengthAwarePaginator
    {
        $queryText = $filters['query'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 15);
        $withMedia = $filters['with_media'] ?? false;
        $like = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query = User::where('is_public', true);
        if ($withMedia) $query->with('profileMedia');
        if ($queryText) {
            $query->where(function ($q) use ($queryText, $like) {
                $q->where('first_name', $like, "%{$queryText}%")
                    ->orWhere('last_name', $like, "%{$queryText}%")
                    ->orWhere('username', $like, "%{$queryText}%")
                    ->orWhereRaw("first_name || ' ' || last_name " . $like . " ?", ["%{$queryText}%"]);
            });
        }
        return $query->paginate($perPage, ['*'], 'users_page');
    }

    /**
     * Get suggested events for a user.
     */
    public function getSuggestedEvents(User $user, int $perPage = 10): LengthAwarePaginator
    {
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
        $friendIds = Friendship::where('status', 'accepted')
            ->where(function ($q) use ($user) {
                $q->where('user_id_1', $user->id)
                    ->orWhere('user_id_2', $user->id);
            })
            ->get()
            ->map(fn($f) => $f->user_id_1 === $user->id ? $f->user_id_2 : $f->user_id_1);

        return Event::with(['banner'])
            ->withUserStatus()
            ->whereHas('attendees', function ($q) use ($friendIds) {
                $q->whereIn('user_id', $friendIds);
            })
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->paginate($perPage);
    }
}
