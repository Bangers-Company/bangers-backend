<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Http\Resources\ActResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActController extends Controller
{
    public function index()
    {
        return ActResource::collection(Act::paginate(15));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act = Act::create($validator->validated());

        return new ActResource($act)->response()->setStatusCode(201);
    }

    public function show(Act $act)
    {
        return new ActResource($act->load("artists", "stages"));
    }

    public function update(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->update($validator->validated());

        return new ActResource($act);
    }

    public function destroy(Act $act)
    {
        $act->delete();

        return response()->json(null, 204);
    }

    public function attachArtist(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "artist_id" => "required|uuid|exists:artists,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->artists()->syncWithoutDetaching([$request->artist_id]);

        return response()->json([
            "message" => "Artist attached to act successfully.",
        ]);
    }

    public function detachArtist(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "artist_id" => "required|uuid|exists:artists,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->artists()->detach($request->artist_id);

        return response()->json([
            "message" => "Artist detached from act successfully.",
        ]);
    }

    public function attachStage(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "stage_id" => "required|uuid|exists:stages,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->stages()->syncWithoutDetaching([$request->stage_id]);

        return response()->json([
            "message" => "Act attached to stage successfully.",
        ]);
    }

    public function detachStage(Request $request, Act $act)
    {
        $validator = Validator::make($request->all(), [
            "stage_id" => "required|uuid|exists:stages,id",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $act->stages()->detach($request->stage_id);

        return response()->json([
            "message" => "Act detached from stage successfully.",
        ]);
    }
}
