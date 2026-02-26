<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Http\Resources\ActResource;
use Illuminate\Http\Request;

class ActController extends Controller
{
    /**
     * Mobile Act Detail: Returns act info, artists, and associated events.
     */
    public function show(string $id)
    {
        $act = Act::with([
            'artists.image',
            'stages',
            'events.banner'
        ])->findOrFail($id);

        return new ActResource($act);
    }
}
