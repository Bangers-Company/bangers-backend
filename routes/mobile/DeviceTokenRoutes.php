<?php

use App\Http\Controllers\Api\V1\DeviceTokenController;
use Illuminate\Support\Facades\Route;

Route::post('/user/device-tokens', [DeviceTokenController::class, 'store']);
Route::delete('/user/device-tokens', [DeviceTokenController::class, 'destroy']);
