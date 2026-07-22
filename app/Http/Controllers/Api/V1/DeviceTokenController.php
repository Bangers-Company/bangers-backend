<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    /**
     * Register or update an FCM device token for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'device_type' => 'required|string|in:ios,android,web',
        ]);

        $token = $request->user()->deviceTokens()->updateOrCreate(
            ['token' => $validated['token']],
            [
                'device_type' => $validated['device_type'],
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Device token registered successfully',
            'data' => $token,
        ], 201);
    }

    /**
     * Remove an FCM device token (e.g., on logout).
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => 'required|string',
        ]);

        $deleted = $request->user()->deviceTokens()
            ->where('token', $validated['token'])
            ->delete();

        return response()->json([
            'message' => $deleted ? 'Device token unregistered' : 'Device token not found',
        ]);
    }
}
