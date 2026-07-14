<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions', 'profileMedia']), 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->email, $request->password);

        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions', 'profileMedia']));
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        
        if (!$user->tokenCan('refresh')) {
            return response()->json(['message' => 'Invalid token for refresh'], 403);
        }

        $this->authService->refresh($user);
        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions', 'profileMedia']));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
