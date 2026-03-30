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
    /**
     * POST /auth/register
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions']), 201);
    }

    /**
     * POST /auth/login
     * General login for mobile and users.
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->email, $request->password);

        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions']));
    }

    /**
     * POST /auth/login (Admin Portal)
     * Restricted login for admins only.
     */
    public function adminLogin(LoginRequest $request)
    {
        $user = $this->authService->adminLogin($request->email, $request->password);

        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions']));
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

        if (!$request->user()->tokenCan('refresh')) {
            abort(403, 'Invalid token ability. Refresh token required.');
        }

        $this->authService->refresh($user);

        return response()->json($this->authService->generateTokenResponse($user, ['roles.permissions']));
    }
}
