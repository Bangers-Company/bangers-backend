<?php

use App\Http\Controllers\Mobile\ArtistController;
use Illuminate\Support\Facades\Route;

Route::get('artists/{id}', [ArtistController::class, 'show'])->name('artists.mobile.show');
