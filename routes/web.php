<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;
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

    Route::get('/security/dashboard', [VisitorController::class, 'index'])->name('security.dashboard');
    Route::post('/security/visitor/check-in', [VisitorController::class, 'store'])->name('security.visitor.store');
});

require __DIR__.'/auth.php';
