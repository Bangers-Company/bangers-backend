<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Http\Resources\ActResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\Admin\StoreActRequest;
use App\Http\Requests\Admin\UpdateActRequest;
use App\Http\Requests\Admin\AttachArtistRequest;
use App\Http\Requests\Admin\AttachStageRequest;
use App\Http\Requests\Admin\AttachEventRequest;

class ActController extends Controller
{
    public function index(Request $request)
    {
        $query = Act::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
        }

        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return ActResource::collection($query->with('artists', 'stages.events', 'events')->paginate($perPage));
    }

    public function store(StoreActRequest $request)
    {
        $this->authorize('create', Act::class);
        $act = Act::create($request->validated());

        return (new ActResource($act))->response()->setStatusCode(201);
    }

    public function show(Act $act)
    {
        return new ActResource($act->load("artists", "stages.events", "events"));
    }

    public function update(UpdateActRequest $request, Act $act)
    {
        $this->authorize('update', $act);
        $act->update($request->validated());

        return new ActResource($act);
    }

    public function destroy(Act $act)
    {
        $this->authorize('delete', $act);
        $act->delete();

        return response()->json(null, 204);
    }

    public function attachArtist(AttachArtistRequest $request, Act $act)
    {
        $act->artists()->syncWithoutDetaching([$request->validated()['artist_id']]);

        return response()->json([
            "message" => "Artist attached to act successfully.",
        ]);
    }

    public function detachArtist(AttachArtistRequest $request, Act $act)
    {
        $act->artists()->detach($request->validated()['artist_id']);

        return response()->json([
            "message" => "Artist detached from act successfully.",
        ]);
    }

    public function attachStage(AttachStageRequest $request, Act $act)
    {
        $validated = $request->validated();
        $act->stages()->syncWithoutDetaching([
            $validated['stage_id'] => [
                'event_id' => $validated['event_id'],
                'date' => $validated['date'] ?? null,
            ]
        ]);

        return response()->json([
            "message" => "Act attached to stage successfully.",
        ]);
    }

    public function detachStage(AttachStageRequest $request, Act $act)
    {
        $validated = $request->validated();
        // We use DB to delete specific pivot record to be safe with many-to-many-to-many
        \DB::table('event_stage_acts')
            ->where('act_id', $act->id)
            ->where('stage_id', $validated['stage_id'])
            ->where('event_id', $validated['event_id'])
            ->when(isset($validated['date']), function($q) use ($validated) {
                return $q->where('date', $validated['date']);
            })
            ->delete();

        return response()->json([
            "message" => "Act detached from stage successfully.",
        ]);
    }

    public function attachEvent(AttachEventRequest $request, Act $act)
    {
        $validated = $request->validated();
        $act->events()->syncWithoutDetaching([
            $validated['event_id'] => [
                'stage_id' => null,
                'date' => $validated['date'] ?? null,
            ]
        ]);

        return response()->json([
            "message" => "Act attached to event successfully.",
        ]);
    }

    public function detachEvent(AttachEventRequest $request, Act $act)
    {
        $validated = $request->validated();
        \DB::table('event_stage_acts')
            ->where('act_id', $act->id)
            ->where('event_id', $validated['event_id'])
            ->whereNull('stage_id')
            ->when(isset($validated['date']), function($q) use ($validated) {
                return $q->where('date', $validated['date']);
            })
            ->delete();

        return response()->json([
            "message" => "Act detached from event successfully.",
        ]);
    }
}
