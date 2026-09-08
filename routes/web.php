<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
use App\Http\Controllers\DealController;

Route::middleware('auth')->group(function () {
    Route::get('/cars/{car}/rent', [DealController::class, 'createRental'])->name('deals.create-rental');
    Route::post('/cars/{car}/rent', [DealController::class, 'storeRental'])->name('deals.store-rental');
    Route::get('/deals/{deal}', [DealController::class, 'show'])->name('deals.show');
});

Route::get('/cars/{car}/leasing', [DealController::class, 'createLeasing'])->name('deals.create-leasing');
Route::post('/cars/{car}/leasing', [DealController::class, 'storeLeasing'])->name('deals.store-leasing');

Route::get('/cars/{car}/buyout', [DealController::class, 'createBuyout'])->name('deals.create-buyout');
Route::post('/cars/{car}/buyout', [DealController::class, 'storeBuyout'])->name('deals.store-buyout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
