<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiRegisterController;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;

Route::get('/register', function(){
    return "Hello";
});
// Route::post('/register', [ApiRegisterController::class, 'register']);
// Route::post('/login', [ApiLoginController::class, 'login']);

// Authenticated routes
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', [ApiRegisterController::class, 'userProfile']);
//     Route::post('/logout', [ApiLoginController::class, 'logout']);
// });
Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-registration-otp', [AuthController::class, 'verifyRegistrationOtp']);
    Route::post('/send-registration-otp', [AuthController::class, 'sendRegistartionOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json($request->user());
        });
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
