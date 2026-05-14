<?php
 
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Role-based routes (examples)
    Route::middleware('role:admin')->get('/admin/dashboard', function() {
        return response()->json(['message' => 'Welcome Admin']);
    });
    
    Route::middleware('role:staff')->get('/staff/dashboard', function() {
        return response()->json(['message' => 'Welcome Staff']);
    });
});
