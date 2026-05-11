<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('frontend.index');
})->name('home');

Route::get('/services', function () {
    return view('frontend.services.index');
})->name('services.index');

Route::get('/services/{slug}', function ($slug) {
    return view('frontend.services.show', ['slug' => $slug]);
})->name('services.show');

Route::get('/packages', function () {
    return view('frontend.packages.index');
})->name('packages.index');

Route::get('dashboard', function () {
    return view('frontend.dashboard.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::post('profile/update', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
});
