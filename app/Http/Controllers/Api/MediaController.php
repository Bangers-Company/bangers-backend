<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Http\Resources\MediaResource;
use App\Http\Requests\Admin\StoreMediaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

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
    public function store(StoreMediaRequest $request)
    {
        $type = $request->type;
        $isProfilePicture = $type === 'profile_picture';

        // Allow profile picture upload for common users, others require manage_content
        if (!$isProfilePicture) {
            Gate::authorize('manage_content');
        }

        $file = $request->file("file");
        $extension = $file->getClientOriginalExtension();
        $media = new Media([
            "owner_id" => $request->user()?->id,
            "type" => $type,
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
        Gate::authorize('manage_content');
        Storage::disk("public")->delete($media->storage_key);
        $media->delete();

        return response()->json(null, 204);
    }

    /**
     * Bulk remove media resources.
     */
    public function bulkDestroy(Request $request)
    {
        Gate::authorize('manage_content');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media,id'
        ]);

        $ids = $request->input('ids');
        $mediaItems = Media::whereIn('id', $ids)->get();

        DB::transaction(function () use ($mediaItems) {
            foreach ($mediaItems as $media) {
                Storage::disk("public")->delete($media->storage_key);
                $media->delete();
            }
        });

        return response()->json(null, 204);
    }
}
