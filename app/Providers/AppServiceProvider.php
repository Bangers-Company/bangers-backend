<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
    }
}
