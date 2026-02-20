<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Http\Resources\ActResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActController extends Controller
{
    public function index(Request $request)
    {
        $query = Act::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $perPage = $request->query('per_page', 15);

        if ($perPage == -1) {
            return ActResource::collection($query->with('artists', 'stages.events', 'events')->get());
        }

        return ActResource::collection($query->with('artists', 'stages.events', 'events')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act = Act::create($validator->validated());

        return new ActResource($act)->response()->setStatusCode(201);
    }

    public function show(Act $act)
    {
        return new ActResource($act->load("artists", "stages.events", "events"));
    }

    public function update(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->update($validator->validated());

        return new ActResource($act);
    }

    public function destroy(Act $act)
    {
        $act->delete();

        return response()->json(null, 204);
    }

    public function attachArtist(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "artist_id" => "required|uuid|exists:artists,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->artists()->syncWithoutDetaching([$request->artist_id]);

        return response()->json([
            "message" => "Artist attached to act successfully.",
        ]);
    }

    public function detachArtist(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "artist_id" => "required|uuid|exists:artists,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->artists()->detach($request->artist_id);

        return response()->json([
            "message" => "Artist detached from act successfully.",
        ]);
    }

    public function attachStage(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "stage_id" => "required|uuid|exists:stages,id",
            "event_id" => "required|uuid|exists:events,id",
            "date" => "nullable|date",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->stages()->syncWithoutDetaching([
            $request->stage_id => [
                'event_id' => $request->event_id,
                'date' => $request->date,
            ]
        ]);

        return response()->json([
            "message" => "Act attached to stage successfully.",
        ]);
    }

    public function detachStage(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "stage_id" => "required|uuid|exists:stages,id",
            "event_id" => "required|uuid|exists:events,id",
            "date" => "nullable|date",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // We use DB to delete specific pivot record to be safe with many-to-many-to-many
        \DB::table('event_stage_acts')
            ->where('act_id', $act->id)
            ->where('stage_id', $request->stage_id)
            ->where('event_id', $request->event_id)
            ->when($request->has('date'), function($q) use ($request) {
                return $q->where('date', $request->date);
            })
            ->delete();

        return response()->json([
            "message" => "Act detached from stage successfully.",
        ]);
    }

    public function attachEvent(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "event_id" => "required|uuid|exists:events,id",
            "date" => "nullable|date",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->events()->syncWithoutDetaching([
            $request->event_id => [
                'stage_id' => null,
                'date' => $request->date,
            ]
        ]);

        return response()->json([
            "message" => "Act attached to event successfully.",
        ]);
    }

    public function detachEvent(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "event_id" => "required|uuid|exists:events,id",
            "date" => "nullable|date",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        \DB::table('event_stage_acts')
            ->where('act_id', $act->id)
            ->where('event_id', $request->event_id)
            ->whereNull('stage_id')
            ->when($request->has('date'), function($q) use ($request) {
                return $q->where('date', $request->date);
            })
            ->delete();

        return response()->json([
            "message" => "Act detached from event successfully.",
        ]);
    }
}
