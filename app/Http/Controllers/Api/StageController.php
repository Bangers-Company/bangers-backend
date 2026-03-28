<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Http\Resources\StageResource;
use App\Http\Requests\Admin\StoreStageRequest;
use App\Http\Requests\Admin\UpdateStageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StageController extends Controller
{
    public function index(Request $request)
    {
        $query = Stage::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhereHas('events', function ($eq) use ($search) {
                    $eq->where('name', 'ilike', "%{$search}%");
                }
                );
            });
        }

        $perPage = (int) $request->query('per_page', 15);
        $query->orderBy('name', 'asc');

        if ($perPage == -1) {
            // Cap "unbounded" requests at 500 for safety
            return StageResource::collection($query->with('events')->limit(500)->get());
        }
        
        $perPage = min(max($perPage, 1), 100);

        return StageResource::collection($query->with('events')->paginate($perPage));
    }

    public function store(StoreStageRequest $request)
    {
        Gate::authorize('manage_content');

        $validated = $request->validated();

        if (isset($validated['stage_id'])) {
            $stage = Stage::findOrFail($validated['stage_id']);
        }
        else {
            $stage = Stage::create([
                "name" => $validated['name'],
                "description" => $validated['description'] ?? null,
            ]);
        }

        $stage->events()->syncWithoutDetaching([$validated['event_id']]);

        return (new StageResource($stage->load('events')))->response()->setStatusCode(201);
    }

    public function show(Stage $stage)
    {
        return new StageResource($stage->load("events"));
    }

    public function update(UpdateStageRequest $request, Stage $stage)
    {
        Gate::authorize('manage_content');

        $stage->update($request->validated());

        return new StageResource($stage->load('events'));
    }

    public function destroy(Stage $stage)
    {
        Gate::authorize('manage_content');
        $stage->delete();

        return response()->json(null, 204);
    }
}