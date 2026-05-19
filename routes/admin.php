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
use App\Http\Controllers\Admin\ProfileController;

use App\Http\Controllers\Admin\EquipmentUseController;
use App\Http\Controllers\Admin\PaymentController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('staff', StaffController::class);
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::resource('categories', CategoryController::class);

    Route::resource('equipment_uses', EquipmentUseController::class)->parameters([
        'equipment_uses' => 'equipment_use'
    ]);
    Route::resource('services', ServiceController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('coupons', CouponController::class);
    Route::post('/coupons/{coupon}/notify', [CouponController::class, 'notifyUsers'])->name('coupons.notify');
    
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/pending', [BookingManagementController::class, 'pending'])->name('bookings.pending');
    Route::get('/bookings/{booking}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/assign', [BookingManagementController::class, 'assignStaff'])->name('bookings.assign');
    Route::patch('/bookings/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{booking}', [BookingManagementController::class, 'destroy'])->name('bookings.destroy');

    // Custom Bookings Management
    Route::get('/custom-bookings/{booking}', [BookingManagementController::class, 'customShow'])->name('custom_bookings.show');
    Route::post('/custom-bookings/{booking}/assign', [BookingManagementController::class, 'customAssignStaff'])->name('custom_bookings.assign');
    Route::patch('/custom-bookings/{booking}/status', [BookingManagementController::class, 'customUpdateStatus'])->name('custom_bookings.status');
    Route::delete('/custom-bookings/{booking}', [BookingManagementController::class, 'customDestroy'])->name('custom_bookings.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Tracking
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');

    // CMS Routes
    Route::get('/cms/banners', [CmsController::class, 'banners'])->name('cms.banners.index');
    Route::post('/cms/banners', [CmsController::class, 'storeBanner'])->name('cms.banners.store');
    Route::delete('/cms/banners/{banner}', [CmsController::class, 'deleteBanner'])->name('cms.banners.destroy');

    Route::resource('cms/promo', App\Http\Controllers\Admin\PromoBannerController::class)->names([
        'index' => 'cms.promo.index',
        'store' => 'cms.promo.store',
        'update' => 'cms.promo.update',
        'destroy' => 'cms.promo.destroy',
    ]);

    // Profile Manage Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/numbers', [ProfileController::class, 'numbers'])->name('numbers');
        Route::post('/numbers', [ProfileController::class, 'storeNumber'])->name('numbers.store');
        Route::put('/numbers/{achievement}', [ProfileController::class, 'updateNumber'])->name('numbers.update');
        Route::delete('/numbers/{achievement}', [ProfileController::class, 'deleteNumber'])->name('numbers.destroy');

        Route::get('/feedbacks', [ProfileController::class, 'feedbacks'])->name('feedbacks');
        Route::get('/feedbacks/create', [ProfileController::class, 'createFeedback'])->name('feedbacks.create');
        Route::post('/feedbacks', [ProfileController::class, 'storeFeedback'])->name('feedbacks.store');
        Route::get('/feedbacks/{feedback}/edit', [ProfileController::class, 'editFeedback'])->name('feedbacks.edit');
        Route::put('/feedbacks/{feedback}', [ProfileController::class, 'updateFeedback'])->name('feedbacks.update');
        Route::delete('/feedbacks/{feedback}', [ProfileController::class, 'deleteFeedback'])->name('feedbacks.destroy');

        Route::get('/media-coverage', [ProfileController::class, 'mediaCoverage'])->name('media_coverage');
        Route::get('/media-coverage/create', [ProfileController::class, 'createBlog'])->name('media_coverage.create');
        Route::post('/media-coverage', [ProfileController::class, 'storeMediaCoverage'])->name('media_coverage.store');
        Route::get('/media-coverage/{blog}', [ProfileController::class, 'showBlog'])->name('media_coverage.show');
        Route::get('/media-coverage/{blog}/edit', [ProfileController::class, 'editBlog'])->name('media_coverage.edit');
        Route::put('/media-coverage/{blog}', [ProfileController::class, 'updateMediaCoverage'])->name('media_coverage.update');
        Route::delete('/media-coverage/{blog}', [ProfileController::class, 'deleteMediaCoverage'])->name('media_coverage.destroy');

        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::post('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');
    });
});
