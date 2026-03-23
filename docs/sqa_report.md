# 🔍 Software Quality Assurance Report — `bangers-backend`

**Project:** bangers-backend (Laravel 12, PHP 8.2, PostgreSQL)  
**Date:** 2026-03-23  
**Scope:** Full codebase review — 30 controllers, 16 models, 2 middleware, 11 API resources, 34 migrations, 10 test files

---

## 📊 Executive Summary

| Category | Score | Verdict |
|---|---|---|
| **🔒 Security** | **4 / 10** | ⚠️ Needs significant work |
| **⚡ Performance** | **5 / 10** | ⚠️ Several N+1 and eager-loading issues |
| **🔧 Maintainability** | **5 / 10** | ⚠️ Heavy code duplication, no service layer |
| **📐 Extensibility** | **4 / 10** | ⚠️ Tightly coupled, hard to extend |
| **🏗️ Laravel Best Practices** | **4 / 10** | ⚠️ Many conventions skipped |

**Overall: 4.4 / 10** — The app works but has structural debt that will compound. Addressing security first, then refactoring for maintainability, will pay off quickly.

---

## 🔒 Security — 4 / 10

### ✅ The Good
- **Password hashing** — Uses `Hash::make()` and the `hashed` cast on the User model
- **Sanctum authentication** — Token-based auth is correctly applied to protected routes
- **SQL injection protection on sort** — `EventController` whitelists sortable columns
- **Search input escaping** — `SearchController` escapes `%` and `_` in LIKE queries
- **Admin routes gated** — API admin routes require `auth:sanctum` + `role:admin` middleware

### ❌ The Bad

#### 🚨 `.env` committed to Git with secrets
The `.env` file is tracked and contains the `APP_KEY`, database credentials (`root`/`secret`), and is set to `APP_DEBUG=true`. This is a **critical security vulnerability**.

> [!CAUTION]
> **Immediate action required:** Add `.env` to `.gitignore`, rotate the `APP_KEY`, and change all credentials. `APP_DEBUG=true` in production leaks stack traces and environment variables to attackers.

#### 🚨 No rate limiting on auth endpoints
[AuthController.php](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/AuthController.php) and [Mobile/AuthController.php](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/AuthController.php) have no rate limiting, enabling brute-force attacks on login and registration.

```php
// Fix: Add throttle middleware in route files
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
});
```

#### 🚨 Fake refresh token system
The `generateResponse()` method creates a second Sanctum token named `refresh_token` — this is **not a real refresh token**. Both tokens have the same expiry and privileges. An attacker with one token effectively has two.

#### ⚠️ No authorization on CRUD endpoints
[ActController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/ActController.php), [EventController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/EventController.php), [StageController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/StageController.php), and [MediaController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/MediaController.php) perform no authorization checks (no Policies, no Gates). They rely solely on the admin middleware at the route level, but this is brittle — a misconfigured route would expose full CRUD.

#### ⚠️ Weak password validation
Registration only requires `min:8` — no uppercase, numbers, or special characters. Use Laravel's `Password` rule:

```php
use Illuminate\Validation\Rules\Password;

'password' => ['required', Password::min(8)->mixedCase()->numbers()],
```

#### ⚠️ UserResource exposes email to everyone
[UserResource](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Resources/UserResource.php#L19) always returns the user's `email` and `dob` — even when viewing another user's profile. These should be conditionally hidden for non-self/non-admin requests.

#### ⚠️ Media upload has no user-ownership check
Any authenticated user could potentially delete another user's media via `DELETE /media/{id}` since [MediaController::destroy](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/MediaController.php#L69) doesn't verify ownership.

### 💡 Improvement Tips
1. **Rotate secrets** and ensure `.env` is never committed
2. **Add `throttle` middleware** to login/register routes (both Api and Mobile)
3. **Implement proper Policies** for Act, Event, Stage, Media, Group
4. **Use `Password::defaults()`** with strong rules
5. **Conditionally expose PII** in UserResource based on context
6. **Implement proper refresh tokens** using a dedicated package or custom logic with token rotation

---

## ⚡ Performance — 5 / 10

### ✅ The Good
- **Pagination** — Most list endpoints support configurable `per_page` pagination
- **Eager loading** — Controllers generally use `with()` to avoid basic N+1 queries
- **Search indexes** — Migration `add_search_indexes` exists for full-text search optimization
- **`withCount`** — Used correctly for attendee counts

### ❌ The Bad

#### 🚨 N+1 query in EventResource
[EventResource](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Resources/EventResource.php#L30) executes a **database query for every event** in a collection:

```php
'user_status' => $request->user() ? $this->attendees()
    ->where('user_id', $request->user()->id)
    ->first()?->pivot?->status : null,
```

When listing 15 events, this fires 15 additional queries. **Fix:** Eager-load the current user's attendance status or use a sub-select.

#### ⚠️ Dashboard over-fetching
[Mobile DashboardController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/DashboardController.php) loads 5+ deeply nested relationship chains in a single request:

```php
$user->load([
    'roles.permissions', 'profileMedia',
    'upcomingEvents' => fn($q) => $q->with($eventRelations)->withCount('attendees'),
    'pastEvents' => fn($q) => $q->with($eventRelations)->withCount('attendees'),
    'pendingFriendRequests.requester'
]);
```

Plus 2 additional queries for `suggestedEvents` and `friendsEvents`. This is a **very heavy** endpoint.

#### ⚠️ Unbounded `per_page = -1` loads entire tables
[ActController::index](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/ActController.php#L25-L27) and `EventController::index` support `per_page=-1` which loads **all records with relationships**. This can cause OOM on a large dataset.

#### ⚠️ Sync endpoints load all records without limit
[SyncController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/SyncController.php) uses `->get()` without any limit. If a client sends `since=0`, it returns the entire table.

#### ⚠️ No caching anywhere
No cache layer for read-heavy endpoints like dashboard stats, event listings, or timetables. `CACHE_STORE=database` is configured but never used.

#### ⚠️ GroupTimetableController loads members collection for authorization
Every method in [GroupTimetableController](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/GroupTimetableController.php) calls `$group->members->contains($request->user()->id)` which loads **all group members** into memory just to check membership.

### 💡 Improvement Tips
1. **Fix the EventResource N+1** — Use a sub-select or eager-load user attendance
2. **Cap `per_page`** — Never allow unlimited: `min(max($perPage, 1), 100)`
3. **Add pagination/limit to Sync endpoints** — Chunk or paginate delta queries
4. **Cache dashboard stats** — Use `Cache::remember()` on `DashboardController::stats()`
5. **Use `->wherePivot()` or `exists()` for membership checks** instead of loading all members
6. **Consider splitting the mobile dashboard** into multiple lighter endpoints

---

## 🔧 Maintainability — 5 / 10

### ✅ The Good
- **Consistent folder structure** — Clear separation of Api/Mobile/Admin namespaces
- **API Resources** — Correct use of `JsonResource` for response transformation
- **Route file splitting** — Each domain has its own route file, well organized
- **UUIDs** — Consistent use of UUIDs for primary keys across all models
- **SoftDeletes** — Used on Event and Act models

### ❌ The Bad

#### 🚨 Massive code duplication between Api and Mobile controllers
These controller pairs are nearly identical:

| Api Controller | Mobile Controller | Duplication |
|---|---|---|
| `AuthController` (137 lines) | `AuthController` (86 lines) | ~80% identical |
| `FriendshipController` (170 lines) | `FriendshipController` (129 lines) | ~90% identical |
| `AttendanceController` (64 lines) | `AttendanceController` (45 lines) | ~85% identical |
| `SearchController` (152 lines) | `SearchController` (79 lines) | ~70% identical |
| `DashboardController` (60 lines) | `DashboardController` (101 lines) | Partially duplicated |

This means **every bug fix must be applied in two places**.

#### ⚠️ No Form Request classes
All validation is inline in controllers. With 30 controllers, this leads to:
- Duplicated validation rules across Api/Mobile for the same resource
- Fat controllers mixing validation, authorization, and business logic
- Harder to test validation rules in isolation

#### ⚠️ No Service layer
Business logic (friendship ordering, group membership checks, timetable population) lives directly in controllers. This makes it impossible to reuse logic across Api/Mobile/Admin without duplication.

#### ⚠️ Inconsistent validation approach
Some controllers use `Validator::make()` + manual error response, others use `$request->validate()`. Both approaches are used in the same project, sometimes even in adjacent controllers:

```php
// ActController uses Validator::make()
$validator = Validator::make($request->all(), [...]);
if ($validator->fails()) return response()->json($validator->errors(), 422);

// AuthController uses $request->validate()
$request->validate([...]);
```

#### ⚠️ Composite primary key on Friendship model
[Friendship](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Models/Friendship.php#L15) uses `protected $primaryKey = ['user_id_1', 'user_id_2']`. **Eloquent does not support composite primary keys.** This breaks `find()`, `findOrFail()`, `save()`, and route model binding. The mobile `FriendshipController::reject` already works around this by using raw query builder `->delete()`.

#### ⚠️ Manual UUID generation despite `HasUuids` trait
[GroupTimetableController::store](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Mobile/GroupTimetableController.php#L30) and [Admin TimetableController::store](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Admin/TimetableController.php#L44) manually call `Str::uuid()` despite models using the `HasUuids` trait which does this automatically.

#### ⚠️ Dead code
[Api DashboardController::MobileDashboard](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Http/Controllers/Api/DashboardController.php#L35) has a comment "NOT USING THIS FUNCTION" but the method still exists. The method name also violates PHP naming conventions (PascalCase instead of camelCase).

### 💡 Improvement Tips
1. **Extract shared logic into Services** (e.g., `AuthService`, `FriendshipService`, `GroupService`)
2. **Create Form Request classes** for every store/update action
3. **Fix the Friendship composite PK** — Add a UUID `id` column or use a single auto-incrementing `id`
4. **Standardize on `$request->validate()`** — Remove all `Validator::make()` usage
5. **Remove dead code** and TODO comments
6. **Remove manual `Str::uuid()` calls** where `HasUuids` already handles it

---

## 📐 Extensibility — 4 / 10

### ✅ The Good
- **RBAC system** — Roles and permissions tables are flexible and well-structured
- **Trait-based model composition** — Good use of `HasUuids`, `SoftDeletes`, `HasApiTokens`
- **Separate Api/Mobile/Admin namespaces** — Intent to support multiple clients

### ❌ The Bad

#### 🚨 No interface/contract abstractions
There are no interfaces, contracts, or service providers for dependency injection. Everything is tightly coupled to concrete implementations.

#### 🚨 No event/listener architecture
No Laravel Events, Listeners, or Observers are used. Actions like "user registered", "friendship accepted", "group created" should fire events for notifications, analytics, etc. Currently, adding side-effects requires modifying controller code directly.

#### ⚠️ No Policies
Without Policies, adding new roles or fine-grained permissions requires modifying controller code. Laravel's Policy system would make authorization declarative and testable.

#### ⚠️ Gate definitions don't scale
[AppServiceProvider](file:///c:/Users/bas.voss/Projects/Bangers/bangers-backend/app/Providers/AppServiceProvider.php#L33-L43) hardcodes three gates. Adding a new permission requires a code change. Consider auto-registering gates from the permissions table:

```php
// Auto-register ALL permissions as gates
Permission::all()->each(function ($permission) {
    Gate::define($permission->name, fn(User $user) => $user->hasPermission($permission->name));
});
```

#### ⚠️ No middleware for CORS
No CORS middleware is visible in the codebase, which may cause issues for the admin frontend.

#### ⚠️ No API versioning
Routes are not versioned (`/api/v1/...`). Breaking changes will affect all clients simultaneously.

### 💡 Improvement Tips
1. **Implement a Service layer** with interfaces for DI
2. **Add Laravel Events** for key actions (registration, friendship, attendance)
3. **Create Policies** for all major resources
4. **Add API versioning** (`/api/v1/`, `/api/v2/`)
5. **Auto-register Gates** from permissions table
6. **Consider Action classes** for complex operations (e.g., `CreateGroupAction`, `SyncTimetableAction`)

---

## 🏗️ Laravel Best Practices — 4 / 10

### ✅ The Good
- **Sanctum for API auth** — Correct choice for SPA/mobile auth
- **API Resources** — Proper use of `whenLoaded()` and `whenCounted()`
- **Database migrations** — Well-structured with foreign keys
- **Pest testing framework** — Modern test framework in place
- **Model casts** — Used correctly for dates, booleans
- **`booted()` lifecycle hooks** — Good use on Act and Event models

### ❌ The Bad

#### 🚨 Minimal test coverage
Only **7 feature tests** exist, all for admin CRUD (Acts, Artists, Events, Media, Search, Stages). **Zero tests** for:
- Authentication (login, register, refresh)
- Authorization (role checks, permission gates)
- Friendship flow
- Group management
- Timetable operations
- Mobile endpoints
- Edge cases (duplicate friendships, self-friending, etc.)

Additionally, `RefreshDatabase` is **commented out** in `Pest.php`:
```php
pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');
```

#### 🚨 Tests don't include auth
The existing event tests call routes like `api.events.index` without authentication, even though these routes require `auth:sanctum` + `role:admin`. These tests likely **fail or test the wrong thing**.

#### ⚠️ No Form Requests
Laravel strongly recommends extracting validation into Form Request classes. The project has zero.

#### ⚠️ No Policies
The project uses ad-hoc `Gate::authorize()` and inline `if` checks. Laravel Policies should be used for resource authorization.

#### ⚠️ Route Model Binding underused
Many controllers manually call `findOrFail()`:
```php
// Current (anti-pattern)
public function show($id) {
    $user = User::findOrFail($id);
}

// Laravel way
public function show(User $user) {
    // Already resolved
}
```

#### ⚠️ Missing factories
Only 5 factories exist (Act, Artist, Event, Media, Stage). Missing: User, Group, Friendship, Timetable, etc. — making it impossible to write comprehensive tests.

#### ⚠️ No custom exception handling
No custom exception classes. All errors use generic `abort()` calls with string messages. Laravel recommends custom exception classes for machine-readable error codes.

#### ⚠️ Implicit model binding broken for some models
The `Friendship` model with its composite primary key cannot use route model binding — a fundamental Laravel feature.

### 💡 Improvement Tips
1. **Write tests for auth flows, friendships, groups, and mobile endpoints** — These are the most critical untested paths
2. **Uncomment `RefreshDatabase`** and fix the test database setup
3. **Create Form Request classes** — `StoreEventRequest`, `UpdateUserRequest`, etc.
4. **Create Policies** — `EventPolicy`, `GroupPolicy`, `MediaPolicy`, etc.
5. **Use Route Model Binding** everywhere — Replace manual `findOrFail()` calls
6. **Create missing factories** — User, Group, Friendship, Timetable
7. **Create custom exceptions** — `GroupMembershipException`, `FriendshipAlreadyExistsException`, etc.

---

## 🎯 Priority Roadmap

### 🔴 Critical (Do ASAP)
1. **Remove `.env` from Git** and rotate all secrets
2. **Set `APP_DEBUG=false`** for any non-local environment
3. **Add rate limiting** on auth endpoints
4. **Fix the EventResource N+1** query

### 🟡 High Priority (Next Sprint)
5. **Fix Friendship composite PK** — Add a proper `id` column
6. **Create a Service layer** — Extract shared logic from duplicated controllers
7. **Add Form Requests** for all store/update operations
8. **Write auth + friendship + group tests** with proper factories
9. **Cap `per_page`** and add limits to sync endpoints

### 🟢 Medium Priority (Ongoing)
10. **Add Policies** for all resources
11. **Implement Laravel Events** for key actions
12. **Add API versioning**
13. **Use Route Model Binding** consistently
14. **Replace fake refresh tokens** with proper implementation
15. **Add caching** for dashboard and read-heavy endpoints

---

## 📁 Files Reviewed

| Category | Files |
|---|---|
| **Controllers (Api)** | AuthController, ActController, ArtistController, AttendanceController, DashboardController, EventController, FriendshipController, LineupSyncController, MediaController, PermissionController, RolesController, SearchController, StageController, UserController |
| **Controllers (Mobile)** | ActController, ArtistController, AttendanceController, AuthController, DashboardController, EventController, EventDiscoveryController, FavoriteController, FriendshipController, GroupController, GroupTimetableController, SearchController, SyncController, TimetableController, UserController |
| **Controllers (Admin)** | TimetableController |
| **Models** | Act, Artist, Event, EventTimetable, Friendship, Group, GroupMember, GroupTimetable, GroupTimetableEntry, Media, Permission, Role, Stage, TimetableEntry, User, UserTimetableFavorite |
| **Middleware** | ForceJsonResponse, RoleMiddleware |
| **Resources** | ActResource, ArtistResource, AttendanceResource, EventResource, FriendshipResource, MediaResource, PermissionResource, RoleResource, SearchResource, StageResource, UserResource |
| **Tests** | Pest.php, ExampleTest, ActsTest, ArtistsTest, EventsTest, MediaTest, SearchTest, StagesTest |
| **Config** | .env, .env.example, composer.json, AppServiceProvider |
