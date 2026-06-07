<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\RespondOnRequestController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\EmergencyContactController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\LiveLocationController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\VolunteerOrganizationController;

Route::get('/register', function(){
    return "Hello";
});

Route::middleware('auth:sanctum')->delete('/cancel-response/{id}', [RespondOnRequestController::class, 'destroy'])
    ->name('cancelResponse');

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-registration-otp', [AuthController::class, 'verifyRegistrationOtp']);
    Route::post('/send-registration-otp', [AuthController::class, 'sendRegistartionOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('sendOtp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');
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
        Route::post('/donor-verifyRegistrationOtp', [RegistrationController::class, 'verifyRegistrationOtp'])->name('donor.verifyRegistrationOtp');
        Route::post('/respond-on-request', [RespondOnRequestController::class, 'store'])->name('blood-requests.respond');
        Route::get('/respond-on-request', [RespondOnRequestController::class, 'index'])->name('blood-donations.respond');
        Route::get('/respones-on-request', [RespondOnRequestController::class, 'fetchResponses'])->name('fetchResponses');
        Route::put('/update-response',[RespondOnRequestController::class,'update'])->name('updateResponse');
        Route::get('/my-blood-donations', [RequestController::class, 'myBloodDonation'])->name('myBloodDonations');
        Route::post('/volunteer-registration', [RegistrationController::class, 'storeVolunteer'])->name('volunteer.registration');

        // ── Settings ─────────────────────────────────────────────────────────
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
            Route::put('/notifications', [SettingsController::class, 'updateNotifications']);
            Route::put('/privacy', [SettingsController::class, 'updatePrivacy']);
            Route::put('/security', [SettingsController::class, 'updateSecurity']);
            Route::put('/appearance', [SettingsController::class, 'updateAppearance']);
            Route::put('/language', [SettingsController::class, 'updateLanguage']);
            Route::put('/medical-info', [SettingsController::class, 'updateMedicalInfo']);
            Route::put('/donor', [SettingsController::class, 'updateDonor']);
            Route::put('/receiver', [SettingsController::class, 'updateReceiver']);

            Route::get('/emergency-contacts', [EmergencyContactController::class, 'index']);
            Route::post('/emergency-contacts', [EmergencyContactController::class, 'store']);
            Route::delete('/emergency-contacts/{id}', [EmergencyContactController::class, 'destroy']);
        });

        // ── FCM token (push notifications) ───────────────────────────────────
        Route::post('/fcm-token', [FcmTokenController::class, 'store'])->name('fcm-token.store');
        Route::delete('/fcm-token', [FcmTokenController::class, 'destroy'])->name('fcm-token.destroy');

        // ── Profile (used by web profile page + mobile app) ──────────────────
        Route::prefix('profile')->group(function () {
            Route::get('/',                     [ProfileApiController::class, 'show'])->name('api.profile.show');
            Route::patch('/',                   [ProfileApiController::class, 'update'])->name('api.profile.update');
            Route::post('/avatar',              [ProfileApiController::class, 'uploadAvatar'])->name('api.profile.avatar.upload');
            Route::delete('/avatar',            [ProfileApiController::class, 'deleteAvatar'])->name('api.profile.avatar.delete');
            Route::post('/change-password',     [ProfileApiController::class, 'changePassword'])->name('api.profile.password');
            Route::post('/mobile/send-otp',     [ProfileApiController::class, 'sendMobileChangeOtp'])->name('api.profile.mobile.send-otp');
            Route::post('/mobile/verify-otp',   [ProfileApiController::class, 'verifyMobileChangeOtp'])->name('api.profile.mobile.verify-otp');
            Route::get('/devices',              [ProfileApiController::class, 'devices'])->name('api.profile.devices');
            Route::delete('/devices/{id}',      [ProfileApiController::class, 'revokeDevice'])->name('api.profile.devices.revoke');
            Route::get('/activity',             [ProfileApiController::class, 'activity'])->name('api.profile.activity');
            Route::post('/volunteer-members',   [ProfileApiController::class, 'storeVolunteerMember'])->name('api.profile.volunteer-members.store');
        });

        // ── Live location (in-app "track donor") ─────────────────────────────
        Route::post('/me/live-location', [LiveLocationController::class, 'update'])
            ->name('live-location.update');
        Route::get('/blood-requests/{id}/peer-location', [LiveLocationController::class, 'peer'])
            ->name('live-location.peer');

        // ── Account management ───────────────────────────────────────────────
        Route::prefix('account')->group(function () {
            Route::post('/delete-request', [AccountController::class, 'scheduleDeletion']);
            Route::delete('/delete-request', [AccountController::class, 'cancelDeletion']);
            Route::post('/data-export', [AccountController::class, 'requestDataExport']);
        });

        //New Volunteer Routes
        Route::post('/volunteer-organizations', [VolunteerOrganizationController::class, 'store'])->name('volunteer.organization.registration');
    });
    Route::get('/blood-requests', [RequestController::class, 'index'])->name('blood-requests.index');
});
