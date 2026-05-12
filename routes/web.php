<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('home');

Route::get('/services', [App\Http\Controllers\Frontend\ServiceListingController::class, 'index'])->name('services.index');

Route::get('/services/{slug}', function ($slug) {
    return view('frontend.services.show', ['slug' => $slug]);
})->name('services.show');

Route::get('/packages', function () {
    return view('frontend.packages.index');
})->name('packages.index');

Route::get('/packages/{slug}', function ($slug) {
    $package = \App\Models\Package::where('slug', $slug)->with('items.service')->firstOrFail();
    return view('frontend.packages.show', compact('package'));
})->name('packages.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [App\Http\Controllers\BookingController::class, 'checkout'])->name('checkout');
    Route::post('/booking/confirm', [App\Http\Controllers\BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile/update', [App\Http\Controllers\DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
        
        Route::get('/bookings', [App\Http\Controllers\DashboardController::class, 'bookings'])->name('dashboard.bookings');
        Route::post('/bookings/{id}/cancel', [App\Http\Controllers\DashboardController::class, 'cancelBooking'])->name('dashboard.bookings.cancel');
        
        Route::get('/addresses', [App\Http\Controllers\DashboardController::class, 'addresses'])->name('dashboard.addresses');
        Route::post('/addresses/save', [App\Http\Controllers\DashboardController::class, 'saveAddress'])->name('dashboard.addresses.save');
        Route::delete('/addresses/{id}', [App\Http\Controllers\DashboardController::class, 'deleteAddress'])->name('dashboard.addresses.delete');
        
        Route::get('/notifications', [App\Http\Controllers\Dashboard\NotificationController::class, 'index'])->name('dashboard.notifications');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Dashboard\NotificationController::class, 'markAsRead'])->name('dashboard.notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\Dashboard\NotificationController::class, 'markAllAsRead'])->name('dashboard.notifications.read-all');
    });
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
});
