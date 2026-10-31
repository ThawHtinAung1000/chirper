
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/chirps', [ChirpController::class, 'store'])
    ->name('chirps.store');
