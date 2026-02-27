<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'ilike', "%{$search}%")
                  ->orWhere('location', 'ilike', "%{$search}%");
        }

        $perPage = $request->query('per_page', 15);

        $sortBy = $request->query('sort_by', 'start_date');
        $sortOrder = $request->query('sort_order', 'desc');

        // Whitelist sortable columns to prevent SQL injection
        $allowedSorts = ['name', 'start_date', 'end_date', 'location'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'start_date';
        }

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        if ($perPage == -1) {
            return EventResource::collection($query->with('stages', 'banner', 'acts')->get());
        }

        return EventResource::collection($query->with('stages', 'banner', 'acts')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "location" => "nullable|string|max:255",
            "start_date" => "required|date",
            "end_date" => "required|date|after_or_equal:start_date",
            "banner_media_id" => "nullable|uuid|exists:media,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event = Event::create($validator->validated());

        return new EventResource($event)->response()->setStatusCode(201);
    }

    public function show(Event $event)
    {
        return new EventResource($event->load("stages", "acts.artists", "banner"));
    }

    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string|max:255",
            "description" => "nullable|string",
            "location" => "nullable|string|max:255",
            "start_date" => "sometimes|required|date",
            "end_date" => "sometimes|required|date|after_or_equal:start_date",
            "banner_media_id" => "nullable|uuid|exists:media,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event->update($validator->validated());

        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(null, 204);
    }
}
