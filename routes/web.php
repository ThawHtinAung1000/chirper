
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;

// Feature 2: Create Chirp
Route::post('/chirps', [ChirpController::class, 'store'])
    ->name('chirps.store');

    // Feature 3: Display Chirps
Route::get('/', [ChirpController::class, 'index'])
    ->name('home');
