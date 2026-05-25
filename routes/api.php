<?php
 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Api\Staff\BookingsManagementController;
use App\Http\Controllers\Api\Admin\BookingsManagementController as AdminBookingsManagementController;
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
    Route::post('/profile/update', [App\Http\Controllers\Api\ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
    Route::get('/profile/addresses', [App\Http\Controllers\Api\ProfileController::class, 'addresses']);
    Route::post('/profile/addresses/save', [App\Http\Controllers\Api\ProfileController::class, 'saveAddress']);
    Route::delete('/profile/addresses/{id}', [App\Http\Controllers\Api\ProfileController::class, 'deleteAddress']);
    Route::post('/profile/addresses/{id}/delete', [App\Http\Controllers\Api\ProfileController::class, 'deleteAddress']);
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
    
    Route::get('/my-bookings', [App\Http\Controllers\Api\MybookingsController::class, 'index']);
    Route::post('/my-bookings/{id}/cancel', [App\Http\Controllers\Api\MybookingsController::class, 'cancel']);
    Route::post('/my-bookings/{id}/rate', [App\Http\Controllers\Api\MybookingsController::class, 'rate']);
    
    // Role-based routes
    Route::middleware('role:admin')->group(function() {
        Route::get('/admin/dashboard', function() {
            return response()->json(['message' => 'Welcome Admin']);
        });

        // Admin Booking Management
        Route::get('/admin/bookings', [AdminBookingsManagementController::class, 'index']);
        Route::get('/admin/pending-bookings', [AdminBookingsManagementController::class, 'pending']);
        Route::get('/admin/cancelled-bookings', [AdminBookingsManagementController::class, 'cancelled']);
        Route::get('/admin/bookings/{id}', [AdminBookingsManagementController::class, 'show']);
        Route::post('/admin/bookings/{id}/assign-staff', [AdminBookingsManagementController::class, 'assignStaff']);
        Route::post('/admin/bookings/{id}/status', [AdminBookingsManagementController::class, 'updateStatus']);
        Route::delete('/admin/bookings/{id}', [AdminBookingsManagementController::class, 'destroy']);

        // Admin Custom Bookings
        Route::get('/admin/custom-bookings/{id}', [AdminBookingsManagementController::class, 'customShow']);
        Route::post('/admin/custom-bookings/{id}/assign-staff', [AdminBookingsManagementController::class, 'customAssignStaff']);
        Route::post('/admin/custom-bookings/{id}/status', [AdminBookingsManagementController::class, 'customUpdateStatus']);
        Route::delete('/admin/custom-bookings/{id}', [AdminBookingsManagementController::class, 'customDestroy']);
    });
    
    Route::middleware('role:staff')->group(function() {
        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index']);

        // Booking Management
        Route::get('/staff/bookings', [BookingsManagementController::class, 'index']);
        Route::get('/staff/pending-bookings', [BookingsManagementController::class, 'pending']);
        Route::get('/staff/completed-bookings', [BookingsManagementController::class, 'completed']);
        Route::get('/staff/cancelled-bookings', [BookingsManagementController::class, 'cancelled']);
        Route::get('/staff/bookings/{booking}', [BookingsManagementController::class, 'show']);
        Route::post('/staff/bookings/{booking}/status', [BookingsManagementController::class, 'updateStatus']);
        Route::post('/staff/bookings/{booking}/verify-otp', [BookingsManagementController::class, 'verifyOtp']);

        // Custom Bookings
        Route::get('/staff/custom-bookings/{id}', [BookingsManagementController::class, 'customShow']);
        Route::post('/staff/custom-bookings/{id}/status', [BookingsManagementController::class, 'customUpdateStatus']);
        Route::post('/staff/custom-bookings/{id}/verify-otp', [BookingsManagementController::class, 'customVerifyOtp']);
    });
});
