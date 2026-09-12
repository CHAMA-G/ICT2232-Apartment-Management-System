<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/security/dashboard', function () {
        return view('security.dashboard');
    })->name('security.dashboard');

    Route::get('/resident/dashboard', [ResidentController::class, 'dashboard'])->name('resident.dashboard');
    Route::post('/resident/pre-register-visitor', [ResidentController::class, 'storePreRegisteredVisitor'])->name('resident.pre_register_visitor');
    Route::post('/resident/book-facility', [ResidentController::class, 'storeBooking'])->name('resident.book_facility');

    Route::post('/visitor/store', [VisitorController::class, 'store'])->name('visitor.store');
});

require __DIR__.'/auth.php';


