<?php

use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications', [NotificationController::class, 'index']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
