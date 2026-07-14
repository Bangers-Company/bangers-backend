<?php

use App\Http\Controllers\Api\ArtistController;
use Illuminate\Support\Facades\Route;

Route::get('artists', [ArtistController::class, 'index'])->name('artists.index');
Route::post('artists', [ArtistController::class, 'store'])->name('artists.store');
Route::get('artists/{artist}', [ArtistController::class, 'show'])->name('artists.show');
Route::put('artists/{artist}', [ArtistController::class, 'update'])->name('artists.update');
Route::delete('artists/{artist}', [ArtistController::class, 'destroy'])->name('artists.destroy');
