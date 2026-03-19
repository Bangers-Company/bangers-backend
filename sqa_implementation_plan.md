# Backend SQA Implementation Plan — bangers-backend

This plan addresses the backend-specific requirements generated from the mobile application's [SQA Report](../bangers-mobile/sqa_report.md).

---

## ⚡ Task P1 — Add Dedicated Friendship Status Endpoint
**Severity: 🔴 High | Effort: Medium**

The mobile app currently downloads all friends and all friend requests just to check the friendship status between the logged-in user and a single target profile. This is an extreme N+1 performance bottleneck. We need a dedicated, lightweight endpoint on the backend.

### 1. Update [FriendshipController.php](file:///home/voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/FriendshipController.php)
Add a `status` method that directly queries the `friendships` table and returns just the string status (`none`, `pending_sent`, `pending_received`, `accepted`):

```php
    public function status(Request $request, $userId)
    {
        $user = $request->user();
        if ($user->id === (int) $userId) {
            return response()->json(['status' => 'self']);
        }

        $u1 = min($user->id, $userId);
        $u2 = max($user->id, $userId);

        $friendship = Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->first();

        if (!$friendship) {
            return response()->json(['status' => 'none']);
        }

        if ($friendship->status === 'accepted') {
            return response()->json(['status' => 'accepted']);
        }

        // If pending, determine who sent it
        if ($friendship->requested_by === $user->id) {
            return response()->json(['status' => 'pending_sent']);
        }

        return response()->json(['status' => 'pending_received']);
    }
```

### 2. Update [FriendshipRoutes.php](file:///home/voss/Projects/Bangers/bangers-backend/routes/mobile/FriendshipRoutes.php)
Register the new route:
```php
Route::get('{userId}/status', [FriendshipController::class, 'status']);
```

---

## 🔒 Task S1 — Enforce Server-Side Input Validation
**Severity: 🔴 High | Effort: Low**

While the mobile app will be adding client-side validation, the backend must never trust the client. Several endpoints process user input without strict validation constraints.

### 1. Update [SearchController.php](file:///home/voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/SearchController.php)
Add validation to `index()` to prevent excessively long search strings or malformed parameters:

```php
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:100',
            'entities' => 'nullable', // comma-separated string or array
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);
        // ...
```

---

## ✅ Task S9 — Verify Refresh Token Rotation
**Severity: 🟢 Low**

The mobile SQA report called out verifying whether refresh token rotation is secure.

**Status: Verified & Secure ✅**
- `AuthController::refresh()` securely deletes the incoming token (`$user->currentAccessToken()->delete()`) before generating a *new* pair in `generateResponse()`.
- No further action required. The attack window for a stolen refresh token is effectively neutralized upon use.

---

## 🧩 Task E6 — Add Feature Flags System
**Severity: 🟢 Low | Effort: Medium**

The mobile app requested a feature flag system to handle staged rollouts. This requires a dedicated backend endpoint to serve the current state of feature flags.

### 1. Create a Configuration File
Create `config/features.php` to define the flags:
```php
return [
    'flags' => [
        'enable_biometric_login' => env('FEATURE_BIOMETRIC_LOGIN', false),
        'enable_new_event_cards' => env('FEATURE_NEW_EVENT_CARDS', true),
        // Add more as needed
    ]
];
```

### 2. Create the Endpoint
Create an endpoint `GET /api/mobile/config/features` that returns the evaluated flags. This allows the mobile app to fetch the configuration on startup.

---

## 🛠 Task M1 — Add Pest Coverage
**Severity: 🟡 Medium | Effort: Low**

Ensure that backend tests cover the new endpoints.
1. Add Pest integration tests for `GET /api/mobile/friends/{id}/status` covering all 5 states (`self`, `none`, `accepted`, `pending_sent`, `pending_received`).
2. Add validation tests for the search endpoint exceeding string length limits.
