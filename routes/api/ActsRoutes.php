<?php

use App\Http\Controllers\Api\ActController;
use Illuminate\Support\Facades\Route;

Route::get('acts', [ActController::class, 'index'])->name('acts.index');
Route::post('acts', [ActController::class, 'store'])->name('acts.store');
Route::get('acts/{act}', [ActController::class, 'show'])->name('acts.show');
Route::put('acts/{act}', [ActController::class, 'update'])->name('acts.update');
Route::delete('acts/{act}', [ActController::class, 'destroy'])->name('acts.destroy');

Route::post('acts/{act}/artists', [ActController::class, 'attachArtist'])->name('acts.artists.attach');
Route::delete('acts/{act}/artists', [ActController::class, 'detachArtist'])->name('acts.artists.detach');
Route::post('acts/{act}/stages', [ActController::class, 'attachStage'])->name('acts.stages.attach');
Route::delete('acts/{act}/stages', [ActController::class, 'detachStage'])->name('acts.stages.detach');

Route::post('acts/{act}/events', [ActController::class, 'attachEvent'])->name('acts.events.attach');
Route::delete('acts/{act}/events', [ActController::class, 'detachEvent'])->name('acts.events.detach');
