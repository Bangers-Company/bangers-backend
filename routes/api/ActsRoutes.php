<?php

use App\Http\Controllers\Api\ActController;
use Illuminate\Support\Facades\Route;

Route::apiResource('acts', ActController::class);

Route::post('acts/{act}/artists', [ActController::class, 'attachArtist'])->name('acts.artists.attach');
Route::delete('acts/{act}/artists', [ActController::class, 'detachArtist'])->name('acts.artists.detach');
Route::post('acts/{act}/festivals', [ActController::class, 'attachFestival'])->name('acts.festivals.attach');
Route::delete('acts/{act}/festivals', [ActController::class, 'detachFestival'])->name('acts.festivals.detach');
