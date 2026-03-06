<?php

use App\Http\Controllers\Mobile\FriendshipController;
use Illuminate\Support\Facades\Route;

Route::prefix('friends')->group(function () {
    Route::get('/', [FriendshipController::class, 'index']);
    Route::get('requests', [FriendshipController::class, 'requests']);
    Route::post('{userId}', [FriendshipController::class, 'store']);
    Route::put('{userId}/accept', [FriendshipController::class, 'accept']);
    Route::put('{userId}/reject', [FriendshipController::class, 'reject']);
    Route::delete('{userId}', [FriendshipController::class, 'destroy']);
});
