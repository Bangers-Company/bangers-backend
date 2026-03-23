<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of media.
     */
    public function index()
    {
        return MediaResource::collection(Media::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "file" => "required|file|image|max:5120", // Max 5MB
            "type" =>
                "required|string|in:profile_picture,artist_image,event_banner",
            "is_public" => "boolean",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $file = $request->file("file");
        // $tempId = (string) Str::uuid(); // Keep for filename generation if desired, or use model's generated id after save.
        // Actually, Media model generates ID on creation.
        // Let's create the model first or use a temporary name.
        
        $extension = $file->getClientOriginalExtension();
        $media = new Media([
            "owner_id" => $request->user()?->id,
            "type" => $request->type,
            "mime_type" => $file->getMimeType(),
            "size_bytes" => $file->getSize(),
            "is_public" => $request->input("is_public", true),
        ]);
        
        // Use the model's generated ID for the filename
        $media->id = (string) Str::uuid(); // Manually set if we need it for filename BEFORE save, 
        // OR better: use Str::random() for filename to keep it separate.
        
        $filename = $media->id . "." . $extension;
        $path = $file->storeAs("media", $filename, "public");
        
        $media->storage_key = $path;
        $media->url = Storage::disk("public")->url($path);
        $media->save();

        return (new MediaResource($media))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return new MediaResource($media);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        Storage::disk("public")->delete($media->storage_key);
        $media->delete();

        return response()->json(null, 204);
    }
}
