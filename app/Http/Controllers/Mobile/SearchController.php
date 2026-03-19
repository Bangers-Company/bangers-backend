<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Act;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Universal Mobile Search across Events, Artists, and Acts.
     */
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:100',
            'entities' => 'nullable',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $queryText = $request->input('query');
        $entities = $request->input('entities', ['events', 'artists', 'acts', 'users']);
        $perPage = $request->input('per_page', 10);

        if (is_string($entities)) {
            $entities = explode(',', $entities);
        }

        $results = [];

        $like = \Illuminate\Support\Facades\DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

        if (in_array('events', $entities)) {
            $eventQuery = Event::with('banner');
            if ($queryText) {
                $eventQuery->where('name', $like, "%{$queryText}%")
                          ->orWhere('location', $like, "%{$queryText}%");
            }
            $results['events'] = $eventQuery->paginate($perPage, ['*'], 'events_page');
        }

        if (in_array('artists', $entities)) {
            $artistQuery = Artist::with('image');
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
            $userQuery = User::where('is_public', true)->with('profileMedia');
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

        return new SearchResource($results);
    }
}
