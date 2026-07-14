<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Mobile Artist Detail: Returns artist info and events they are associated with.
     */
    public function show(string $id)
    {
        $artist = Artist::with([
            'image',
            'acts.events.banner'
        ])->findOrFail($id);

        return new ArtistResource($artist);
    }
}
