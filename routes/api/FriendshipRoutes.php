<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('friends')->group(function () {
    Route::get('/', [FriendshipController::class, 'index']);
    Route::get('requests', [FriendshipController::class, 'requests']);
    Route::get('blocked', [FriendshipController::class, 'blocked']); // Need to add blocked method to controller if not there

    Route::post('{userId}', [FriendshipController::class, 'store']);
    Route::put('{userId}/accept', [FriendshipController::class, 'accept']);
    Route::put('{userId}/reject', [FriendshipController::class, 'reject']);
    Route::put('{userId}/block', [FriendshipController::class, 'block']);
    Route::delete('{userId}', [FriendshipController::class, 'destroy']);
});
