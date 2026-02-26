<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('me', [UserController::class, 'me']);
    Route::get('{id}', [UserController::class, 'show']);
});
