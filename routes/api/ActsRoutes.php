<?php

use App\Http\Controllers\Api\ActController;
use Illuminate\Support\Facades\Route;

Route::apiResource('acts', ActController::class);

Route::post('acts/{act}/artists', [ActController::class, 'attachArtist'])->name('acts.artists.attach');
Route::delete('acts/{act}/artists', [ActController::class, 'detachArtist'])->name('acts.artists.detach');
Route::post('acts/{act}/stages', [ActController::class, 'attachStage'])->name('acts.stages.attach');
Route::delete('acts/{act}/stages', [ActController::class, 'detachStage'])->name('acts.stages.detach');
