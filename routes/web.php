<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DealHistoryController;
use App\Http\Controllers\Admin\CarAdminController;
use App\Http\Controllers\Admin\DealAdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

Route::middleware('auth')->group(function () {
    Route::get('/cars/{car}/rent', [DealController::class, 'createRental'])->name('deals.create-rental');
    Route::post('/cars/{car}/rent', [DealController::class, 'storeRental'])->name('deals.store-rental');

    Route::get('/cars/{car}/buyout', [DealController::class, 'createBuyout'])->name('deals.create-buyout');
    Route::post('/cars/{car}/buyout', [DealController::class, 'storeBuyout'])->name('deals.store-buyout');

    Route::get('/cars/{car}/leasing', [DealController::class, 'createLeasing'])->name('deals.create-leasing');
    Route::post('/cars/{car}/leasing', [DealController::class, 'storeLeasing'])->name('deals.store-leasing');

    Route::get('/deals/{deal}', [DealController::class, 'show'])->name('deals.show');
    Route::get('/my-deals', [DealHistoryController::class, 'index'])->name('deals.history');

    Route::post('/notifications/read', function () {
        auth()->user()->appNotifications()->whereNull('read_at')->update(['read_at' => now()]);
        return response()->noContent();
    })->name('notifications.read');

    Route::post('/deals/{deal}/cancel', [DealController::class, 'cancel'])->name('deals.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('cars', CarAdminController::class);
    Route::get('/deals', [DealAdminController::class, 'index'])->name('deals.index');
    Route::post('/deals/{deal}/confirm', [DealController::class, 'confirm'])->name('deals.confirm');

    Route::get('/cars/{car}/maintenance', [\App\Http\Controllers\Admin\CarMaintenanceController::class, 'index'])->name('cars.maintenance');
    Route::post('/cars/{car}/maintenance', [\App\Http\Controllers\Admin\CarMaintenanceController::class, 'store'])->name('cars.maintenance.store');

    Route::post('/deals/{deal}/reject', [DealController::class, 'reject'])->name('deals.reject');

    Route::get('/cars/{car}/calendar', [\App\Http\Controllers\Admin\CarCalendarController::class, 'index'])->name('cars.calendar');
});

require __DIR__.'/auth.php';
