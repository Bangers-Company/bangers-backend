<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Mobile Event Detail: Returns full event details with relational data.
     */
    public function show(string $id)
    {
        $event = Event::with([
            'banner',
            'stages',
            'acts.artists'
        ])->findOrFail($id);

        return new EventResource($event);
    }
}
