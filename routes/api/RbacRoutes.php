<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RolesController::class);
    Route::get('permissions', [PermissionController::class, 'index']);
});
