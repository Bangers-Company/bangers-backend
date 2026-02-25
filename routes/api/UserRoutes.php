<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('me', [UserController::class, 'me']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index']); // Admin only check in controller

    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);

    Route::post('{id}/roles/{roleId}', [UserController::class, 'assignRole']);
    Route::delete('{id}/roles/{roleId}', [UserController::class, 'removeRole']);
});
