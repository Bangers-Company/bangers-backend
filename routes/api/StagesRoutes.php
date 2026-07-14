<?php

use App\Http\Controllers\Api\StageController;
use Illuminate\Support\Facades\Route;

Route::get('stages', [StageController::class, 'index'])->name('stages.index');
Route::post('stages', [StageController::class, 'store'])->name('stages.store');
Route::get('stages/{stage}', [StageController::class, 'show'])->name('stages.show');
Route::put('stages/{stage}', [StageController::class, 'update'])->name('stages.update');
Route::delete('stages/{stage}', [StageController::class, 'destroy'])->name('stages.destroy');
