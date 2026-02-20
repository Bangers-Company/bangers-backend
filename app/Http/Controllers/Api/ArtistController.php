<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $query = Artist::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
        }

        $perPage = $request->query('per_page', 15);
        $query->orderBy('name', 'asc');

        if ($perPage == -1) {
            return ArtistResource::collection($query->with('acts', 'image')->get());
        }

        return ArtistResource::collection($query->with('acts', 'image')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "bio" => "nullable|string",
            "genre" => "nullable|string|max:255",
            "image_media_id" => "nullable|uuid|exists:media,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artist = Artist::create($validator->validated());

        return new ArtistResource($artist)->response()->setStatusCode(201);
    }

    public function show(Artist $artist)
    {
        return new ArtistResource($artist->load("acts", "image"));
    }

    public function update(Request $request, Artist $artist)
    {
        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string|max:255",
            "bio" => "nullable|string",
            "genre" => "nullable|string|max:255",
            "image_media_id" => "nullable|uuid|exists:media,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artist->update($validator->validated());

        return new ArtistResource($artist);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return response()->json(null, 204);
    }
}
