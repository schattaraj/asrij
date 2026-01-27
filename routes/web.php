<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;

Route::get('/', [PageController::class, 'home'])->name('home');
// Route::get('/registration', [PageController::class, 'registration'])->name('registration');
Route::get('/login', [PageController::class, 'login'])->name('login');
// Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/conversation', [PageController::class, 'conversation'])->name('conversation');

// Route::get('/register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [CustomRegisterController::class, 'register']);
// Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

Route::post('/registration', [RegistrationController::class, 'store'])->name('registration');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

Route::middleware(['auth','role:admin'])->group(function () {
  
});

Route::middleware(['auth','role:donor|receiver|volunteer'])->group(function () {
  Route::get('/profile', [PageController::class, 'profile'])->name('profile');
  Route::post('/change-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
});
// Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
//     Route::get('settings', [AdminController::class, 'settings'])->name('settings');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');
});

Route::post('/support', [SupportController::class, 'store'])
    ->name('support.store');

Route::get('/test-otp', function (App\Services\TwilioService $twilio) {
    $twilio->sendOtp('+18777804236', '123456');
    return 'OTP Sent';
});

Route::get('/test-whatsapp', function (App\Services\WhatsAppService $whatsapp) {
    $response = $whatsapp->sendTemplateMessage(
        '918637378344',
        'jaspers_market_order_confirmation_v1',
        ['John Doe', '123456','2 days']
    );
    return $response;
});


Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');