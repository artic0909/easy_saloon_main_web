<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index'); // Using same view as requested
    })->name('dashboard');
});
