<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use App\Events\UserRegistered;
use App\Events\AttendanceUpdated;
use App\Events\GroupCreated;
use App\Events\FriendshipAccepted;
use App\Events\EventCreated;
use App\Events\EventUpdated;
use App\Events\EventDeleted;
use App\Listeners\ClearDashboardCache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "admin" role all permissions
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Define gates based on permissions
        // This is a simple way without a dedicated Gate definition for each permission
        // because we use $user->hasPermission($ability) if we want to be explicit.
        // But for Gate::authorize('manage_users') to work:
        Gate::define('manage_users', function (User $user) {
            return $user->hasPermission('manage_users');
        });

        Gate::define('manage_events', function (User $user) {
            return $user->hasPermission('manage_events');
        });

        Gate::define('manage_content', function (User $user) {
            return $user->hasPermission('manage_content');
        });

        Gate::policy(\App\Models\Group::class, \App\Policies\GroupPolicy::class);
        Gate::policy(\App\Models\GroupTimetable::class, \App\Policies\GroupTimetablePolicy::class);

        // Register multi-event listener
        Event::listen(EventCreated::class, ClearDashboardCache::class);
        Event::listen(EventUpdated::class, ClearDashboardCache::class);
        Event::listen(EventDeleted::class, ClearDashboardCache::class);
    }
}
