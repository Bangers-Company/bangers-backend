<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Http\Resources\StageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StageController extends Controller
{
    public function index(Request $request)
    {
        $query = Stage::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%")
                  ->orWhereHas('events', function($eq) use ($search) {
                      $eq->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $perPage = $request->query('per_page', 15);
        $query->orderBy('name', 'asc');

        if ($perPage == -1) {
            return StageResource::collection($query->with('events')->get());
        }

        return StageResource::collection($query->with('events')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "event_id" => "required|uuid|exists:events,id",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "stage_id" => "nullable|uuid|exists:stages,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();

        if (isset($validated['stage_id'])) {
            $stage = Stage::findOrFail($validated['stage_id']);
        } else {
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

    public function update(Request $request, Stage $stage)
    {
        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stage->update($validator->validated());

        return new StageResource($stage->load('events'));
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return response()->json(null, 204);
    }
}
