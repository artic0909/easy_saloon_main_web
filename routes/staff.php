<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\BookingController;
use App\Http\Controllers\Staff\ProfileController;

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Booking Management
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/pending-bookings', [BookingController::class, 'pending'])->name('bookings.pending');
    Route::get('/completed-bookings', [BookingController::class, 'completed'])->name('bookings.completed');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/verify-otp', [BookingController::class, 'verifyOtp'])->name('bookings.verify_otp');

    // Custom Bookings
    Route::get('/custom-bookings/{id}', [BookingController::class, 'customShow'])->name('custom_bookings.show');
    Route::post('/custom-bookings/{id}/status', [BookingController::class, 'customUpdateStatus'])->name('custom_bookings.status');
    Route::post('/custom-bookings/{id}/verify-otp', [BookingController::class, 'customVerifyOtp'])->name('custom_bookings.verify_otp');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/availability', [ProfileController::class, 'updateAvailability'])->name('profile.availability');
    
    // Live Location (Placeholder)
    Route::get('/location', function() {
        return view('staff.location.index');
    })->name('location.index');
});
