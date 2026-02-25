<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    /**
     * POST /auth/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required|date',
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
        ]);

        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        return $this->generateResponse($user);
    }

    /**
     * POST /auth/login
     * General login for mobile and users.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials.'],
            ]);
        }

        return $this->generateResponse($user);
    }

    /**
     * POST /auth/login (Admin Portal)
     * Restricted login for admins only.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials.'],
            ]);
        }

        if (!$user->hasRole('admin')) {
            return response()->json([
                'message' => 'Access denied. Admin role required.'
            ], 403);
        }

        return $this->generateResponse($user);
    }

    /**
     * POST /auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * POST /auth/refresh
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->generateResponse($user);
    }

    /**
     * Helper to generate standardized response
     */
    private function generateResponse($user)
    {
        $token = $user->createToken('auth_token');

        // Simulating a refresh token with a second token or just naming conventions
        // In a real production apps, we might use a dedicated Refresh Token system.
        // For now, we follow the requested JSON format.

        return response()->json([
            'accessToken' => $token->plainTextToken,
            'refreshToken' => $user->createToken('refresh_token', ['refresh'])->plainTextToken,
            'expiresAt' => Carbon::now()->addMinutes(config('sanctum.expiration') ?? 1440)->toIso8601String(),
            'user' => new UserResource($user->load('roles.permissions'))
        ]);
    }
}
