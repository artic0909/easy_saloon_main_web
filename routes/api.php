<?php
 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Staff\DashboardController as StaffDashboardController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/filters', [ServiceController::class, 'filters']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/services/category/{id}', [ServiceController::class, 'byCategory']);

Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/custom-data', [PackageController::class, 'customPackageData']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::get('/coupons', [CouponController::class, 'index']);
Route::post('/coupons/verify', [CouponController::class, 'verify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/payments/create-order', [App\Http\Controllers\Api\PaymentController::class, 'createOrder']);
    Route::post('/payments/verify', [App\Http\Controllers\Api\PaymentController::class, 'verifyPayment']);
    
    Route::get('/addresses', [App\Http\Controllers\Api\AddressController::class, 'index']);
    Route::post('/addresses', [App\Http\Controllers\Api\AddressController::class, 'store']);

    Route::post('/bookings/service', [App\Http\Controllers\Api\BookingController::class, 'storeServiceBooking']);
    Route::post('/bookings/package', [App\Http\Controllers\Api\BookingController::class, 'storePackageBooking']);
    Route::post('/bookings/custom-package', [App\Http\Controllers\Api\BookingController::class, 'storeCustomPackageBooking']);
    
    // Role-based routes
    Route::middleware('role:admin')->get('/admin/dashboard', function() {
        return response()->json(['message' => 'Welcome Admin']);
    });
    
    Route::middleware('role:staff')->group(function() {
        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index']);
    });
});
