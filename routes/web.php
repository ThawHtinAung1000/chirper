<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;

// Feature 3: Display all Chirps
Route::get('/', [ChirpController::class, 'index'])
    ->name('home');

// Feature 2: Create Chirp
Route::post('/chirps', [ChirpController::class, 'store'])
    ->name('chirps.store');

// Feature 4: Display edit form
Route::get('/chirps/{chirp}/edit', [
    ChirpController::class,
    'edit'
])->name('chirps.edit');

// Feature 4: Update Chirp
Route::put('/chirps/{chirp}', [
    ChirpController::class,
    'update'
])->name('chirps.update');

// Feature 5: Delete Chirp
Route::delete('/chirps/{chirp}', [
    ChirpController::class,
    'destroy'
])->name('chirps.destroy');
