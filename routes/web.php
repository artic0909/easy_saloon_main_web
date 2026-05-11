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

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
