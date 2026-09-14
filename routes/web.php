<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'handleGoogleCallback']);

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

    Route::get('/security/dashboard', [VisitorController::class, 'dashboard'])->name('security.dashboard');
    Route::get('/security/visitors/search', [VisitorController::class, 'dashboard'])->name('security.visitors.search');
    Route::post('/security/visitors/{id}/check-in', [VisitorController::class, 'checkin'])->name('security.visitors.check_in');
    Route::patch('/security/visitors/{id}/check-out', [VisitorController::class, 'checkout'])->name('security.visitors.check_out');
    Route::post('/visitor/checkin/{id}', [VisitorController::class, 'checkin'])->name('visitor.checkin');
    Route::post('/visitor/checkout/{id}', [VisitorController::class, 'checkout'])->name('visitor.checkout');

    Route::get('/resident/dashboard', [ResidentController::class, 'dashboard'])->name('resident.dashboard');
    Route::get('/resident/check-availability', [ResidentController::class, 'checkAvailability'])->name('bookings.checkAvailability');
    Route::post('/resident/pre-register-visitor', [ResidentController::class, 'storePreRegisteredVisitor'])->name('resident.pre_register_visitor');
    Route::patch('/resident/visitors/{id}', [ResidentController::class, 'updateVisitor'])->name('resident.update_visitor');
    Route::delete('/resident/visitors/{id}', [ResidentController::class, 'destroyVisitor'])->name('resident.destroy_visitor');
    Route::post('/resident/book-facility', [ResidentController::class, 'storeBooking'])->name('resident.book_facility');
    Route::patch('/resident/bookings/{id}', [ResidentController::class, 'updateBooking'])->name('resident.update_booking');
    Route::delete('/resident/bookings/{id}', [ResidentController::class, 'destroyBooking'])->name('resident.destroy_booking');
    Route::post('/resident/service-request', [ResidentController::class, 'storeServiceRequest'])->name('resident.store_service_request');
    Route::patch('/resident/service-requests/{id}', [ResidentController::class, 'updateServiceRequest'])->name('resident.update_service_request');
    Route::delete('/resident/service-requests/{id}', [ResidentController::class, 'destroyServiceRequest'])->name('resident.destroy_service_request');

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/service-request/{id}/status', [AdminController::class, 'storeAdminRequestStatus'])->name('admin.store_service_status');
    Route::patch('/admin/visitors/{id}', [AdminController::class, 'updateVisitor'])->name('admin.update_visitor');
    Route::delete('/admin/visitors/{id}', [AdminController::class, 'destroyVisitor'])->name('admin.destroy_visitor');
    Route::patch('/admin/bookings/{id}', [AdminController::class, 'updateBooking'])->name('admin.update_booking');
    Route::delete('/admin/bookings/{id}', [AdminController::class, 'destroyBooking'])->name('admin.destroy_booking');
    Route::patch('/admin/service-requests/{id}', [AdminController::class, 'updateServiceRequest'])->name('admin.update_service_request');
    Route::delete('/admin/service-requests/{id}', [AdminController::class, 'destroyServiceRequest'])->name('admin.destroy_service_request');

    Route::post('/visitor/store', [VisitorController::class, 'store'])->name('visitor.store');
});

require __DIR__.'/auth.php';


