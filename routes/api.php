<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiRegisterController;
use App\Http\Controllers\Api\ApiLoginController;

Route::get('/register', function(){
    return "Hello";
});
Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('/login', [ApiLoginController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ApiRegisterController::class, 'userProfile']);
    Route::post('/logout', [ApiLoginController::class, 'logout']);
});
