<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\CustomRegisterController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/registration', [PageController::class, 'registration'])->name('registration');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/conversation', [PageController::class, 'conversation'])->name('conversation');

Route::get('/register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'register']);
