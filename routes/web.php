
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;

//Create Chirp
Route::post('/chirps', [ChirpController::class, 'store'])
    ->name('chirps.store');

//Display Chirps
Route::get('/', [ChirpController::class, 'index'])
    ->name('home');

// Show the edit form
Route::get('/chirps/{chirp}/edit', [
    ChirpController::class,
    'edit'
])->name('chirps.edit');

// Update a Chirp
Route::put('/chirps/{chirp}', [
    ChirpController::class,
    'update'
])->name('chirps.update');
