<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|image|max:5120', // Max 5MB
            'type' => 'required|string|in:profile_picture,artist_image,festival_banner',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $file = $request->file('file');
        $id = (string) Str::uuid();
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs('media', $id . '.' . $extension, 'public');

        $media = Media::create([
            'id' => $id,
            'owner_id' => $request->user()?->id ?? Str::uuid(), // Fallback for now since no auth
            'type' => $request->type,
            'storage_key' => $path,
            'url' => Storage::disk('public')->url($path),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'is_public' => $request->input('is_public', true),
        ]);

        return response()->json($media, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return response()->json($media);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->storage_key);
        $media->delete();

        return response()->json(null, 204);
    }
}
