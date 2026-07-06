<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\FestivalController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/festivals', [FestivalController::class, 'index'])->name('festivals.index');
    Route::get('/festivals/{festival}', [FestivalController::class, 'show'])->name('festivals.show');
    Route::post('/festivals/{festival}/book', [BookingController::class, 'store'])->name('festivals.book');

    Route::post('/points/redeem-discount', [PointsController::class, 'redeemDiscount'])->name('points.redeem-discount');
    Route::post('/points/redeem-vip', [PointsController::class, 'redeemVip'])->name('points.redeem-vip');
});

require __DIR__.'/auth.php';
