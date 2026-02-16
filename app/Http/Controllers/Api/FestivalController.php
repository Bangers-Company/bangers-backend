<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Festival;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FestivalController extends Controller
{
    public function index()
    {
        return response()->json(Festival::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'banner_media_id' => 'nullable|uuid|exists:media,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $festival = Festival::create($validator->validated());

        return response()->json($festival, 201);
    }

    public function show(Festival $festival)
    {
        return response()->json($festival->load('stages', 'banner'));
    }

    public function update(Request $request, Festival $festival)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'banner_media_id' => 'nullable|uuid|exists:media,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $festival->update($validator->validated());

        return response()->json($festival);
    }

    public function destroy(Festival $festival)
    {
        $festival->delete();

        return response()->json(null, 204);
    }
}
