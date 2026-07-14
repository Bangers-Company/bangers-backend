<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Events\EventCreated;
use App\Events\EventUpdated;
use App\Events\EventDeleted;

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

        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $sortBy = $request->query('sort_by', 'start_date');
        $sortOrder = $request->query('sort_order', 'asc');

        // Whitelist sortable columns to prevent SQL injection
        $allowedSorts = ['name', 'start_date', 'end_date', 'location'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'start_date';
        }

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        return EventResource::collection($query->with('stages', 'banner', 'acts')->withUserStatus()->paginate($perPage));
    }

    public function store(StoreEventRequest $request)
    {
        $this->authorize('create', Event::class);
        $event = Event::create($request->validated());

        event(new EventCreated($event));

        return (new EventResource($event))->response()->setStatusCode(201);
    }

    public function show(Event $event)
    {
        return new EventResource($event->load("stages", "acts.artists", "banner")->append('user_status'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);
        $event->update($request->validated());

        event(new EventUpdated($event));

        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();

        event(new EventDeleted($event));

        return response()->json(null, 204);
    }
}
