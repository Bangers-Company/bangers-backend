<?php

use App\Http\Controllers\Mobile\FriendshipController;
use Illuminate\Support\Facades\Route;

Route::prefix('friends')->group(function () {
    Route::get('/', [FriendshipController::class, 'index']);
    Route::get('{user}/friends', [FriendshipController::class, 'userFriends']);
    Route::get('requests', [FriendshipController::class, 'requests']);
    Route::post('{user}', [FriendshipController::class, 'store']);
    Route::put('{user}/accept', [FriendshipController::class, 'accept']);
    Route::put('{user}/reject', [FriendshipController::class, 'reject']);
    Route::get('{user}/status', [FriendshipController::class, 'status']);
    Route::delete('{user}', [FriendshipController::class, 'destroy']);
});
