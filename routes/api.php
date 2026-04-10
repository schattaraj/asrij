<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiRegisterController;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\RegistrationController;

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
        Route::prefix('blood-requests')->group(function () {

            Route::post('/', [RequestController::class, 'store'])->name('blood-requests.store');
            Route::get('/{id}', [RequestController::class, 'show']);
            Route::put('/{id}', [RequestController::class, 'update']);
            Route::delete('/{id}', [RequestController::class, 'destroy']);
        
        });
        Route::post('/donor-registration', [RegistrationController::class, 'store'])->name('donor.registration');
    });
    Route::get('/blood-requests', [RequestController::class, 'index'])->name('blood-requests.index');
});