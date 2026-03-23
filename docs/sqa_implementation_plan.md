# 🛠️ SQA Implementation Plan — `bangers-backend`

**Based on:** [SQA Report](./sqa_report.md) (2026-03-23)  
**Structure:** 4 phases, ordered by risk and dependencies

---

## Phase 1 — 🔴 Critical Security & Stability Fixes

**Goal:** Eliminate critical vulnerabilities and data integrity issues.  
**Estimated effort:** 1–2 days  
**Risk if skipped:** High — secrets exposed, brute-force possible, data corruption via composite PK

---

### 1.1 Remove `.env` from Git & Rotate Secrets

**Files:**
- `.gitignore`
- `.env` / `.env.example`

**Steps:**
1. Add `.env` to `.gitignore` (verify it's not already there correctly)
2. Run `git rm --cached .env` to untrack the file
3. Rotate the `APP_KEY` using `php artisan key:generate`
4. Change all database credentials (`DB_USERNAME`, `DB_PASSWORD`)
5. Ensure `APP_DEBUG=false` in any non-local `.env` configuration
6. Commit the `.gitignore` change with a clear commit message
7. Consider using `git filter-branch` or BFG Repo Cleaner to purge `.env` from Git history

---

### 1.2 Add Rate Limiting to Auth Endpoints

**Files:**
- `routes/api/AuthRoutes.php`
- `routes/mobile/AuthRoutes.php`

**Steps:**
1. Wrap login and register routes in `throttle` middleware:
   ```php
   Route::middleware('throttle:5,1')->group(function () {
       Route::post('/auth/login', ...);
       Route::post('/auth/register', ...);
   });
   ```
2. Consider a separate, stricter limiter for password-related endpoints
3. Test that the 429 response is returned after exceeding the limit

---

### 1.3 Strengthen Password Validation

**Files:**
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Mobile/AuthController.php`

**Steps:**
1. Replace `'password' => 'required|min:8'` with:
   ```php
   use Illuminate\Validation\Rules\Password;
   
   'password' => ['required', Password::min(8)->mixedCase()->numbers()],
   ```
2. Apply to both Api and Mobile AuthControllers (will later be deduplicated in Phase 2)

---

### 1.4 Fix EventResource N+1 Query

**Files:**
- `app/Http/Resources/EventResource.php`
- Controllers that return `EventResource` collections

**Steps:**
1. Remove the inline `->attendees()->where(...)` query from `EventResource`
2. Add a method or scope on the `Event` model to eager-load user attendance status:
   ```php
   // Option A: Eager-load as a sub-select in controllers that return collections
   $events->each(function ($event) use ($userId) {
       $event->user_status = $event->attendees
           ->firstWhere('id', $userId)?->pivot?->status;
   });
   
   // Option B: Use selectSub in query
   Event::addSelect([
       'user_status' => UserEventAttendance::select('status')
           ->whereColumn('event_id', 'events.id')
           ->where('user_id', $userId)
           ->limit(1)
   ]);
   ```
3. Verify with `DB::enableQueryLog()` that the N+1 is eliminated

---

### 1.5 Fix Friendship Composite Primary Key

**Files:**
- `app/Models/Friendship.php`
- New migration: `add_id_to_friendships_table.php`

**Steps:**
1. Create a migration to add a UUID `id` column as the actual primary key:
   ```php
   Schema::table('friendships', function (Blueprint $table) {
       $table->uuid('id')->primary()->first();
       $table->unique(['user_id_1', 'user_id_2']); // Keep uniqueness constraint
   });
   ```
2. Update the `Friendship` model:
   - Add `HasUuids` trait
   - Remove `public $incrementing = false;`
   - Change `$primaryKey` to `'id'`
3. Test that all friendship operations still work correctly

---

### 1.6 Cap Pagination & Limit Sync Endpoints

**Files:**
- `app/Http/Controllers/Api/ActController.php`
- `app/Http/Controllers/Api/EventController.php`
- `app/Http/Controllers/Mobile/SyncController.php`

**Steps:**
1. Remove `per_page == -1` branches — enforce a max of 100:
   ```php
   $perPage = min(max((int) $request->query('per_page', 15), 1), 100);
   ```
2. Add a hard limit to Sync endpoints:
   ```php
   $query->limit(500); // or paginate
   ```
3. Consider adding a required `since` parameter to Sync endpoints

---

## Phase 2 — 🟡 Architecture & Maintainability

**Goal:** Eliminate duplication, introduce proper Laravel patterns, improve code organization.  
**Estimated effort:** 3–5 days  
**Prerequisite:** Phase 1 complete

---

### 2.1 Create Service Layer

**New files:**
- `app/Services/AuthService.php`
- `app/Services/FriendshipService.php`
- `app/Services/GroupService.php`
- `app/Services/AttendanceService.php`
- `app/Services/SearchService.php`
- `app/Services/TimetableService.php`

**Steps:**
1. Create `app/Services/` directory
2. Extract shared business logic from duplicated controller pairs:
   - `AuthService` — login, register, token generation, refresh logic
   - `FriendshipService` — friend ordering (min/max), request/accept/reject/block logic
   - `GroupService` — membership checks, invitation flow
   - `AttendanceService` — attendance update/remove
   - `SearchService` — unified search logic with media flag
   - `TimetableService` — timetable CRUD, entry management
3. Inject services into both Api and Mobile controllers via constructor DI
4. Controllers become thin wrappers that call services and return responses
5. Example structure:
   ```php
   class AuthService
   {
       public function register(array $data): User { ... }
       public function login(string $email, string $password): User { ... }
       public function generateTokenResponse(User $user): array { ... }
       public function refresh(User $user): array { ... }
   }
   ```

---

### 2.2 Create Form Request Classes

**New files (examples):**
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Http/Requests/Auth/RegisterRequest.php`
- `app/Http/Requests/Event/StoreEventRequest.php`
- `app/Http/Requests/Event/UpdateEventRequest.php`
- `app/Http/Requests/Act/StoreActRequest.php`
- `app/Http/Requests/Act/UpdateActRequest.php`
- `app/Http/Requests/User/UpdateUserRequest.php`
- `app/Http/Requests/Group/StoreGroupRequest.php`
- `app/Http/Requests/Attendance/UpdateAttendanceRequest.php`
- `app/Http/Requests/Timetable/StoreTimetableRequest.php`
- `app/Http/Requests/Timetable/UpdateTimetableRequest.php`
- `app/Http/Requests/Media/StoreMediaRequest.php`

**Steps:**
1. Create a Form Request for every store/update action using `php artisan make:request`
2. Move validation rules from controllers into the `rules()` method
3. Add `authorize()` methods where applicable (replaces inline auth checks)
4. Standardize: remove all `Validator::make()` usage
5. Both Api and Mobile controllers share the same Form Request classes

---

### 2.3 Create Policies

**New files:**
- `app/Policies/EventPolicy.php`
- `app/Policies/ActPolicy.php`
- `app/Policies/StagePolicy.php`
- `app/Policies/MediaPolicy.php`
- `app/Policies/GroupPolicy.php`
- `app/Policies/UserPolicy.php`

**Steps:**
1. Create policies using `php artisan make:policy EventPolicy --model=Event`
2. Define `viewAny`, `view`, `create`, `update`, `delete` gates
3. Register policies in `AppServiceProvider` or use auto-discovery
4. Replace inline `if` checks and `Gate::authorize()` calls with `$this->authorize()` in controllers
5. Auto-register all permission-based gates from the database:
   ```php
   // AppServiceProvider::boot()
   Gate::before(fn(User $user, $ability) => $user->hasRole('admin') ? true : null);
   // Remove hardcoded Gate::define() calls
   ```

---

### 2.4 Adopt Route Model Binding

**Files:** All controllers using manual `findOrFail()`:
- `UserController` (Api + Mobile)
- `GroupController`
- `GroupTimetableController`
- `AttendanceController`
- `FriendshipController`
- `Admin/TimetableController`

**Steps:**
1. Replace `$id` parameters with typed model parameters:
   ```php
   // Before
   public function show($id) {
       $event = Event::findOrFail($id);
   }
   
   // After
   public function show(Event $event) {
       // $event is already resolved
   }
   ```
2. Update corresponding route definitions if needed
3. Note: Cannot apply to `Friendship` until the composite PK is fixed (Phase 1.5)

---

### 2.5 Clean Up Dead Code & Inconsistencies

**Files:**
- `app/Http/Controllers/Api/DashboardController.php` — Remove `MobileDashboard()` method
- All controllers using `Str::uuid()` manually — Remove where `HasUuids` handles it
- Standardize method naming to camelCase

**Steps:**
1. Delete the `MobileDashboard()` method and any routes pointing to it
2. Remove manual `Str::uuid()` from `GroupTimetableController::store`, `Admin/TimetableController::store`, `MediaController::store`
3. Verify that `HasUuids` trait is present on the corresponding models
4. Search for any other orphaned or commented-out code

---

### 2.6 Conditionally Expose PII in UserResource

**Files:**
- `app/Http/Resources/UserResource.php`

**Steps:**
1. Conditionally include sensitive fields based on context:
   ```php
   'email' => $this->when(
       $request->user()?->id === $this->id || $request->user()?->hasRole('admin'),
       $this->email
   ),
   'dob' => $this->when(
       $request->user()?->id === $this->id || $request->user()?->hasRole('admin'),
       $this->dob
   ),
   ```

---

## Phase 3 — 🟢 Testing & Quality Gates

**Goal:** Build comprehensive test coverage and ensure reliability.  
**Estimated effort:** 3–5 days  
**Prerequisite:** Phase 2 complete (services and form requests make tests easier to write)

---

### 3.1 Fix Test Infrastructure

**Files:**
- `tests/Pest.php`
- `phpunit.xml`

**Steps:**
1. Uncomment `RefreshDatabase` in `Pest.php`:
   ```php
   pest()->extend(Tests\TestCase::class)
       ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
       ->in('Feature');
   ```
2. Configure test database in `phpunit.xml` (SQLite in-memory or separate PostgreSQL DB)
3. Verify `php artisan test` runs with a clean state

---

### 3.2 Create Missing Factories

**New files:**
- `database/factories/UserFactory.php`
- `database/factories/RoleFactory.php`
- `database/factories/PermissionFactory.php`
- `database/factories/FriendshipFactory.php`
- `database/factories/GroupFactory.php`
- `database/factories/GroupMemberFactory.php` (if needed)
- `database/factories/EventTimetableFactory.php`
- `database/factories/TimetableEntryFactory.php`
- `database/factories/GroupTimetableFactory.php`

**Steps:**
1. Create factories with realistic fake data
2. Add helper states for common scenarios (e.g., `User::factory()->admin()`)
3. Create seeders using the factories for local development

---

### 3.3 Write Feature Tests

**New files:**
- `tests/Feature/Auth/LoginTest.php`
- `tests/Feature/Auth/RegisterTest.php`
- `tests/Feature/Auth/RefreshTest.php`
- `tests/Feature/FriendshipTest.php`
- `tests/Feature/GroupTest.php`
- `tests/Feature/GroupTimetableTest.php`
- `tests/Feature/TimetableTest.php`
- `tests/Feature/AttendanceTest.php`
- `tests/Feature/Mobile/DashboardTest.php`
- `tests/Feature/Mobile/SearchTest.php`
- `tests/Feature/Mobile/SyncTest.php`
- `tests/Feature/UserTest.php`

**Coverage targets:**
- Auth: login success/failure, register validation, refresh, logout, rate limiting
- Friendship: send/accept/reject/block, self-friending guard, duplicate guard
- Groups: create, invite, accept/reject invite, leave, delete, ownership checks
- Timetables: CRUD, entry management, attendance toggle, overlap detection
- Attendance: go/interested, remove
- Authorization: admin-only routes reject regular users
- Edge cases: invalid UUIDs, missing data, concurrent operations

---

### 3.4 Fix Existing Tests

**Files:**
- `tests/Feature/EventsTest.php`
- `tests/Feature/ActsTest.php`
- `tests/Feature/ArtistsTest.php`
- `tests/Feature/MediaTest.php`
- `tests/Feature/StagesTest.php`
- `tests/Feature/SearchTest.php`

**Steps:**
1. Add authentication to all existing tests (create admin user, `actingAs()`)
2. Verify tests pass against the admin routes with proper auth
3. Add negative test cases (unauthenticated, wrong role)

---

## Phase 4 — 🔵 Extensibility & Performance Optimization

**Goal:** Future-proof the architecture and optimize for scale.  
**Estimated effort:** 3–5 days  
**Prerequisite:** Phase 3 complete (tests provide safety net for refactoring)

---

### 4.1 Implement Laravel Events & Listeners

**New files:**
- `app/Events/UserRegistered.php`
- `app/Events/FriendshipAccepted.php`
- `app/Events/GroupCreated.php`
- `app/Events/AttendanceUpdated.php`
- `app/Events/TimetablePublished.php`
- `app/Listeners/` — Corresponding listener classes

**Steps:**
1. Create event classes for key domain actions
2. Fire events from services (created in Phase 2)
3. Create listeners for side-effects (logging, notifications, analytics)
4. Register in `EventServiceProvider` or use auto-discovery

---

### 4.2 Add Caching Layer

**Files:**
- `app/Http/Controllers/Api/DashboardController.php`
- `app/Http/Controllers/Mobile/DashboardController.php`
- `app/Services/` (if caching is done at service level)

**Steps:**
1. Cache admin dashboard stats:
   ```php
   return Cache::remember('admin.dashboard.stats', 300, function () {
       return [...]; // Existing query logic
   });
   ```
2. Cache mobile dashboard suggested/friends events
3. Invalidate caches when underlying data changes (via Events from 4.1)
4. Consider Redis for production instead of database cache

---

### 4.3 Implement Proper Refresh Tokens

**Files:**
- `app/Services/AuthService.php` (created in Phase 2)
- Both AuthControllers (Api + Mobile)
- New migration for refresh tokens table (or repurpose existing)

**Steps:**
1. Options:
   - **Option A:** Use separate long-lived Sanctum tokens with a `refresh` ability and validate the ability on the refresh endpoint
   - **Option B:** Implement a custom `refresh_tokens` table with hashed tokens, family tracking, and rotation
2. Update `generateResponse()` to issue a proper refresh token
3. Update the `refresh()` method to validate and rotate the refresh token
4. Add refresh token expiry that is longer than access token expiry

---

### 4.4 Add API Versioning

**Files:**
- `routes/api.php`
- Route files in `routes/api/` and `routes/mobile/`

**Steps:**
1. Move current routes under a `/v1/` prefix:
   ```php
   Route::prefix('v1')->name('api.v1.')->group(function () {
       // Current admin routes
   });
   
   Route::prefix('mobile/v1')->name('api.mobile.v1.')->group(function () {
       // Current mobile routes
   });
   ```
2. Update mobile client to use versioned endpoints
3. Document the versioning strategy

---

### 4.5 Create Custom Exception Classes

**New files:**
- `app/Exceptions/GroupMembershipException.php`
- `app/Exceptions/FriendshipAlreadyExistsException.php`
- `app/Exceptions/TimetableOverlapException.php`
- `app/Exceptions/UnauthorizedActionException.php`

**Steps:**
1. Create exception classes extending Laravel's `HttpException` or base `Exception`
2. Add machine-readable error codes and structured error responses
3. Register custom rendering in `bootstrap/app.php` or exception handler
4. Replace `abort()` calls with specific exception throws

---

### 4.6 Optimize Group Membership Checks

**Files:**
- `app/Http/Controllers/Mobile/GroupTimetableController.php`
- `app/Http/Controllers/Mobile/GroupController.php`
- `app/Services/GroupService.php` (created in Phase 2)

**Steps:**
1. Replace `$group->members->contains($userId)` (loads all members) with:
   ```php
   $group->members()->where('user_id', $userId)->exists()
   ```
2. Extract to `GroupService::ensureMember(Group $group, User $user)` to DRY up the check
3. Consider middleware for group membership verification

---

### 4.7 Add CORS Middleware

**Files:**
- `bootstrap/app.php` or `config/cors.php`

**Steps:**
1. Verify if Laravel's built-in CORS handling is configured
2. If not, publish the CORS config: `php artisan config:publish cors`
3. Configure allowed origins, methods, and headers for the admin frontend
4. Test cross-origin requests from the admin frontend

---

## Summary

| Phase | Focus | Effort | Items |
|---|---|---|---|
| **Phase 1** | 🔴 Critical Security & Stability | 1–2 days | 6 items |
| **Phase 2** | 🟡 Architecture & Maintainability | 3–5 days | 6 items |
| **Phase 3** | 🟢 Testing & Quality Gates | 3–5 days | 4 items |
| **Phase 4** | 🔵 Extensibility & Performance | 3–5 days | 7 items |
| **Total** | | **~10–17 days** | **23 items** |

Each phase builds on the previous one. Phase 1 is standalone and should be done immediately. Phases 2–4 can be parallelized somewhat, but the recommended order ensures that services and form requests (Phase 2) exist before writing tests (Phase 3), and tests exist before doing major refactors (Phase 4).
