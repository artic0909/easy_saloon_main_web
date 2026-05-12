<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\TrackingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\EquipmentUseController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubCategoryController::class);
    Route::resource('equipment_uses', EquipmentUseController::class)->parameters([
        'equipment_uses' => 'equipment_use'
    ]);
    Route::resource('services', ServiceController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('coupons', CouponController::class);
    Route::post('/coupons/{coupon}/notify', [CouponController::class, 'notifyUsers'])->name('coupons.notify');
    
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/assign', [BookingManagementController::class, 'assignStaff'])->name('bookings.assign');
    Route::patch('/bookings/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{booking}', [BookingManagementController::class, 'destroy'])->name('bookings.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Tracking
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');

    // CMS Routes
    Route::get('/cms/banners', [CmsController::class, 'banners'])->name('cms.banners.index');
    Route::post('/cms/banners', [CmsController::class, 'storeBanner'])->name('cms.banners.store');
    Route::delete('/cms/banners/{banner}', [CmsController::class, 'deleteBanner'])->name('cms.banners.destroy');
    // AJAX Routes for dependent dropdowns
    Route::get('/get-subcategories/{category_id}', [ServiceController::class, 'getSubCategories']);
    Route::get('/get-equipment/{subcategory_id}', [ServiceController::class, 'getEquipment']);
});
