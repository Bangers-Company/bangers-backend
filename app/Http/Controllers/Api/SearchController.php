<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use App\Http\Resources\SearchResource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $results = $this->performSearch($request, false);

        return new SearchResource($results);
    }

    /**
     * Mobile search (with images)
     */
    public function mobileSearch(Request $request)
    {
        $results = $this->performSearch($request, true);

        return new SearchResource($results);
    }

    private function performSearch(Request $request, bool $withMedia = false)
    {
        $queryText = $request->input("query");
        $date = $request->input("date");
        $location = $request->input("location");
        $entities = $request->input("entities", [
            "events",
            "artists",
            "acts",
            "users",
        ]);
        $perPage = min(max((int) $request->input("per_page", 15), 1), 100);

        if ($queryText) {
            $queryText = str_replace(["%", "_"], ["\\%", "\\_"], $queryText);
        }

        if ($location) {
            $location = str_replace(["%", "_"], ["\\%", "\\_"], $location);
        }

        if (is_string($entities)) {
            $entities = explode(",", $entities);
        }

        $results = [];

        if (in_array("events", $entities)) {

            $eventQuery = Event::query();

            if ($withMedia) {
                $eventQuery->with('banner');
            }

            if ($queryText) {
                $eventQuery->where("name", "ilike", "%{$queryText}%");
            }

            if ($date) {
                $eventQuery
                    ->whereDate("start_date", "<=", $date)
                    ->whereDate("end_date", ">=", $date);
            }

            if ($location) {
                $eventQuery->where("location", "ilike", "%{$location}%");
            }

            $results["events"] = $eventQuery->paginate(
                $perPage,
                ["*"],
                "events_page",
            );
        }

        if (in_array("artists", $entities)) {

            $artistQuery = Artist::query();

            if ($withMedia) {
                $artistQuery->with('image');
            }

            if ($queryText) {
                $artistQuery->where("name", "ilike", "%{$queryText}%");
            }

            $results["artists"] = $artistQuery->paginate(
                $perPage,
                ["*"],
                "artists_page",
            );
        }

        if (in_array("acts", $entities)) {

            $actQuery = Act::query();

            if ($queryText) {
                $actQuery->where("name", "ilike", "%{$queryText}%");
            }

            $results["acts"] = $actQuery->paginate(
                $perPage,
                ["*"],
                "acts_page",
            );
        }

        if (in_array("users", $entities)) {

            $userQuery = User::where('is_public', true);

            if ($withMedia) {
                $userQuery->with('profileMedia');
            }

            if ($queryText) {
                $userQuery->where(function($q) use ($queryText) {
                    $q->where("first_name", "ilike", "%{$queryText}%")
                      ->orWhere("last_name", "ilike", "%{$queryText}%")
                      ->orWhere("username", "ilike", "%{$queryText}%")
                      // Support full name search roughly
                      ->orWhereRaw("first_name || ' ' || last_name ILIKE ?", ["%{$queryText}%"]);
                });
            }

            $results["users"] = $userQuery->paginate(
                $perPage,
                ["*"],
                "users_page",
            );
        }

        return $results;
    }
}
