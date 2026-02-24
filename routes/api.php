<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::name('api.')->group(function () {
    require __DIR__.'/api/EventsRoutes.php';
    require __DIR__.'/api/ArtistsRoutes.php';
    require __DIR__.'/api/StagesRoutes.php';
    require __DIR__.'/api/ActsRoutes.php';
    require __DIR__.'/api/MediaRoutes.php';
    require __DIR__.'/api/SearchRoutes.php';
    require __DIR__.'/api/DashboardRoutes.php';
});

Route::prefix('mobile')->name('api.mobile.')->group(function () {
    require __DIR__.'/mobile/DashboardRoutes.php';
    require __DIR__.'/mobile/SearchRoutes.php';
    require __DIR__.'/mobile/EventsRoutes.php';
    require __DIR__.'/mobile/ArtistsRoutes.php';
    require __DIR__.'/mobile/ActsRoutes.php';
    require __DIR__.'/mobile/SyncRoutes.php';
});
