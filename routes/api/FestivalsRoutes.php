<?php

use App\Http\Controllers\Api\FestivalController;
use Illuminate\Support\Facades\Route;

Route::apiResource('festivals', FestivalController::class);
