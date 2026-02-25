<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::name('api.')->group(function () {
    // Public Auth Routes
    require __DIR__.'/api/AuthRoutes.php';

    // Restricted Admin Routes
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        require __DIR__.'/api/EventsRoutes.php';
        require __DIR__.'/api/ArtistsRoutes.php';
        require __DIR__.'/api/StagesRoutes.php';
        require __DIR__.'/api/ActsRoutes.php';
        require __DIR__.'/api/MediaRoutes.php';
        require __DIR__.'/api/SearchRoutes.php';
        require __DIR__.'/api/DashboardRoutes.php';
        require __DIR__.'/api/UserRoutes.php';
        require __DIR__.'/api/FriendshipRoutes.php';
        require __DIR__.'/api/AttendanceRoutes.php';
        require __DIR__.'/api/RbacRoutes.php';
    });
});

Route::prefix('mobile')->name('api.mobile.')->group(function () {
    require __DIR__.'/mobile/DashboardRoutes.php';
    require __DIR__.'/mobile/SearchRoutes.php';
    require __DIR__.'/mobile/EventsRoutes.php';
    require __DIR__.'/mobile/ArtistsRoutes.php';
    require __DIR__.'/mobile/ActsRoutes.php';
    require __DIR__.'/mobile/SyncRoutes.php';
    require __DIR__.'/mobile/AuthRoutes.php';
    require __DIR__.'/mobile/UserRoutes.php';
    require __DIR__.'/mobile/FriendshipRoutes.php';
    require __DIR__.'/mobile/AttendanceRoutes.php';
});
