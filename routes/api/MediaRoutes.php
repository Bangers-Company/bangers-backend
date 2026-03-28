<?php

use App\Http\Controllers\Api\MediaController;
use Illuminate\Support\Facades\Route;

Route::get('media', [MediaController::class, 'index'])->name('media.index');
Route::post('media', [MediaController::class, 'store'])->name('media.store');
Route::post('media/bulk-delete', [MediaController::class, 'bulkDestroy'])->name('media.bulk_destroy');
Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
