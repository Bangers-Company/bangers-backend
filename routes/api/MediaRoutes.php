<?php

use App\Http\Controllers\Api\MediaController;
use Illuminate\Support\Facades\Route;

Route::post('media', [MediaController::class, 'store'])->name('media.store');
Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
