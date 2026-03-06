<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\UserTimetableFavorite;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * POST /favorites/{timetable_entry_id}
     */
    public function store(Request $request, $timetableEntryId)
    {
        $user = $request->user();

        $exists = UserTimetableFavorite::where('user_id', $user->id)
            ->where('timetable_entry_id', $timetableEntryId)
            ->exists();

        if (!$exists) {
            UserTimetableFavorite::create([
                'user_id' => $user->id,
                'timetable_entry_id' => $timetableEntryId,
                'created_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Added to favorites'], 201);
    }

    /**
     * DELETE /favorites/{timetable_entry_id}
     */
    public function destroy(Request $request, $timetableEntryId)
    {
        UserTimetableFavorite::where('user_id', $request->user()->id)
            ->where('timetable_entry_id', $timetableEntryId)
            ->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }

    /**
     * GET /favorites
     */
    public function index(Request $request)
    {
        $favorites = UserTimetableFavorite::with(['entry.stage', 'entry.act.artists'])
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($favorites);
    }
}
