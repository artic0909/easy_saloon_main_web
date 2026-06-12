<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('home');

Route::get('/services', [App\Http\Controllers\Frontend\ServiceListingController::class, 'index'])->name('services.index');

Route::get('/services/{slug}', [App\Http\Controllers\Frontend\ServiceListingController::class, 'show'])->name('services.show');

Route::get('/packages', [App\Http\Controllers\Frontend\PackageListingController::class, 'index'])->name('packages.index');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/packages/custom-package', [App\Http\Controllers\Frontend\PackageListingController::class, 'customPackage'])->name('packages.custom');
    Route::get('/packages/custom-checkout', [App\Http\Controllers\CustomBookingController::class, 'checkout'])->name('packages.custom.checkout');
    Route::post('/packages/custom-confirm', [App\Http\Controllers\CustomBookingController::class, 'confirm'])->name('packages.custom.confirm');
    Route::get('/checkout', [App\Http\Controllers\BookingController::class, 'checkout'])->name('checkout');
    Route::post('/booking/confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('/coupon/verify', [App\Http\Controllers\Api\CouponController::class, 'verify'])->name('coupon.verify');
    Route::post('/payment/initiate', [App\Http\Controllers\PaymentController::class, 'initiatePayment'])->name('payment.initiate');
    Route::post('/payment/verify', [App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile/update', [App\Http\Controllers\DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
        Route::post('/claim-scratch-card', [App\Http\Controllers\DashboardController::class, 'claimScratchCard'])->name('dashboard.scratch-card.claim');
        
        Route::get('/bookings', [App\Http\Controllers\DashboardController::class, 'bookings'])->name('dashboard.bookings');
        Route::post('/bookings/{id}/cancel', [App\Http\Controllers\DashboardController::class, 'cancelBooking'])->name('dashboard.bookings.cancel');
        Route::post('/bookings/{id}/rate', [App\Http\Controllers\DashboardController::class, 'rateBooking'])->name('dashboard.bookings.rate');
        
        Route::get('/addresses', [App\Http\Controllers\DashboardController::class, 'addresses'])->name('dashboard.addresses');
        Route::post('/addresses/save', [App\Http\Controllers\DashboardController::class, 'saveAddress'])->name('dashboard.addresses.save');
        Route::delete('/addresses/{id}', [App\Http\Controllers\DashboardController::class, 'deleteAddress'])->name('dashboard.addresses.delete');
        
        Route::get('/notifications', [App\Http\Controllers\Dashboard\NotificationController::class, 'index'])->name('dashboard.notifications');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Dashboard\NotificationController::class, 'markAsRead'])->name('dashboard.notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\Dashboard\NotificationController::class, 'markAllAsRead'])->name('dashboard.notifications.read-all');
    });
});

Route::get('/packages/{slug}', [App\Http\Controllers\Frontend\PackageListingController::class, 'show'])->name('packages.show');

// Blog Routes
Route::get('/media-coverage', [App\Http\Controllers\Frontend\BlogsController::class, 'index'])->name('blogs.index');
Route::get('/media-coverage/{id}', [App\Http\Controllers\Frontend\BlogsController::class, 'show'])->name('blogs.show');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
});
