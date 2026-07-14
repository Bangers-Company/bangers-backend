<?php

use App\Http\Controllers\Mobile\GenreController;
use Illuminate\Support\Facades\Route;

Route::get('genres', [GenreController::class, 'index']);
