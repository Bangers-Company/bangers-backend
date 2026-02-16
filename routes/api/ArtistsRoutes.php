<?php

use App\Http\Controllers\Api\ArtistController;
use Illuminate\Support\Facades\Route;

Route::apiResource('artists', ArtistController::class);
