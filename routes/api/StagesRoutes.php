<?php

use App\Http\Controllers\Api\StageController;
use Illuminate\Support\Facades\Route;

Route::apiResource('stages', StageController::class);
