<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('friends')->group(function () {
    Route::get('/', [FriendshipController::class, 'index']);
    Route::get('requests', [FriendshipController::class, 'requests']);
    Route::get('blocked', [FriendshipController::class, 'blocked']); // Need to add blocked method to controller if not there

    Route::post('{user}', [FriendshipController::class, 'store']);
    Route::put('{user}/accept', [FriendshipController::class, 'accept']);
    Route::put('{user}/reject', [FriendshipController::class, 'reject']);
    Route::put('{user}/block', [FriendshipController::class, 'block']);
    Route::delete('{user}', [FriendshipController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('{user}/friends', [FriendshipController::class, 'userFriends']);
});
