<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use App\Events\UserRegistered;

class AuthService
{
    /**
     * Register a new user and assign the default 'user' role.
     */
    public function register(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'dob' => $data['dob'],
        ]);

        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        event(new UserRegistered($user));

        return $user;
    }

    /**
     * Authenticate a user by email and password.
     */
    public function login(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $lastLoginAt = $user->last_login_at;
        $user->update(['last_login_at' => now()]);
        $user->last_login_at = $lastLoginAt;

        return $user;
    }

    /**
     * Authenticate an admin user.
     */
    public function adminLogin(string $email, string $password): User
    {
        $user = $this->login($email, $password);

        if (!$user->hasRole('admin')) {
            throw ValidationException::withMessages([
                'email' => ['Access denied. Admin role required.'],
            ]);
        }

        return $user;
    }

    /**
     * Refresh the user's tokens (Rotate).
     */
    public function refresh(User $user): void
    {
        // Delete the token that was used to perform the refresh
        $user->currentAccessToken()->delete();
    }

    /**
     * Generate a standardized token response.
     */
    public function generateTokenResponse(User $user, array $loadRelations = []): array
    {
        $expiration = config('sanctum.expiration') ?? 1440; // Default to 24h if null

        // Short-lived access token (15 mins)
        $token = $user->createToken('auth_token', ['*'], now()->addMinutes(15));

        // Long-lived refresh token (30 days)
        $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addDays(30));

        return [
            'accessToken' => $token->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
            'expiresAt' => now()->addMinutes(15)->toIso8601String(),
            'user' => $user->load($loadRelations)
        ];
    }
}