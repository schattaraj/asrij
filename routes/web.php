<?php

use App\Http\Controllers\Admin\BannerController;
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
use App\Http\Controllers\Admin\BloodCampController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');
Route::get('/registration', [PageController::class, 'registration'])->name('registration');
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
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/blood-donations', [PageController::class, 'bloodDonations'])->name('bloodDonations');
Route::get('/blood-request', [PageController::class, 'bloodRequests'])->name('bloodRequests');
Route::middleware(['auth','role:donor|receiver|volunteer'])->group(function () {

  Route::post('/change-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
  Route::get('/blood-camps', [BloodCampController::class, 'index'])->name('bloodCamps');      
});
// Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('banners', BannerController::class);
    Route::resource('camps', BloodCampController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::get('our-organization', [OrganizationController::class,'ourOrganization'])->name('our-organization');
    Route::put('our-organization/{organization}', [OrganizationController::class,'ourOrganizationUpdate'])->name('organization.update');
    Route::delete('our-organization/member/{member}', [OrganizationController::class,'destroyMember'])->name('organization.member.destroy');
    //     Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    //For Storage
    Route::get('/storage-link', function () {

        if (File::exists(public_path('storage'))) {
            return back()->with('info', 'Storage link already exists.');
        }

        Artisan::call('storage:link');

        return back()->with('success', 'Storage link created successfully.');
    })->name('admin.storage-link');
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

Route::get('/users/update/{id}', [RegistrationController::class, 'index'])
    ->name('users.update');
Route::post('/users/{id}/update-role', [RegistrationController::class, 'updateUserRole'])
    ->name('users.update.role');


Route::get('/all-blood-requests', function () {
    return view('all-blood-requests.index');
})->name("all-blood-requests");

Route::get('/hash-password',function(){
$password = Hash::make('admin@123');
return $password;
});