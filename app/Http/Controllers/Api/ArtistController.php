<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    public function index()
    {
        return response()->json(Artist::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'image_media_id' => 'nullable|uuid|exists:media,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artist = Artist::create($validator->validated());

        return response()->json($artist, 201);
    }

    public function show(Artist $artist)
    {
        return response()->json($artist->load('acts', 'image'));
    }

    public function update(Request $request, Artist $artist)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'bio' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'image_media_id' => 'nullable|uuid|exists:media,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artist->update($validator->validated());

        return response()->json($artist);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return response()->json(null, 204);
    }
}
