<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Http\Resources\StageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StageController extends Controller
{
    public function index()
    {
        return StageResource::collection(Stage::paginate(15));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "festival_id" => "required|uuid|exists:festivals,id",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stage = Stage::create($validator->validated());

        return new StageResource($stage)->response()->setStatusCode(201);
    }

    public function show(Stage $stage)
    {
        return new StageResource($stage->load("festival"));
    }

    public function update(Request $request, Stage $stage)
    {
        $validator = Validator::make($request->all(), [
            "festival_id" => "sometimes|required|uuid|exists:festivals,id",
            "name" => "sometimes|required|string|max:255",
            "description" => "nullable|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stage->update($validator->validated());

        return new StageResource($stage);
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return response()->json(null, 204);
    }
}
