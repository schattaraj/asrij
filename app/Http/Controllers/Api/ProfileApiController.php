<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodRequestResponse;
use App\Models\Donor;
use App\Models\FcmToken;
use App\Models\Receiver;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerMember;
use App\Models\VolunteerOrganization;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Profile-related endpoints. Used by both the web profile page (which
 * hydrates entirely via these APIs) and the React Native mobile app.
 *
 * Mounted under: /api/v1/profile/*
 */
class ProfileApiController extends Controller
{
    /**
     * GET /api/v1/profile
     *
     * Single-call hydration: user core fields + role payloads + activity
     * snapshot + registered devices + suggested next roles.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $roles = is_array($user->roles) ? $user->roles : (json_decode($user->roles, true) ?: []);

        $donor     = in_array('donor', $roles, true)     ? Donor::where('user_id', $user->id)->first()     : null;
        $receiver  = in_array('receiver', $roles, true)  ? Receiver::where('user_id', $user->id)->first()  : null;
        $volunteer = in_array('volunteer', $roles, true) ? VolunteerMember::where('user_id', $user->id)->with(['organization'])->first() : null;
        $members = in_array('volunteer', $roles, true) ? 
        VolunteerMember::where('volunteer_organization_id',$volunteer->volunteer_organization_id)
        ->where('user_id','!=',$user->id)->get() : collect();


        // Activity snapshot
        $donationsCount  = BloodRequestResponse::where('donor_id', $user->id)->where('status', 'accepted')->count();
        $respondedCount  = BloodRequestResponse::where('donor_id', $user->id)->count();
        $requestsCount   = BloodRequest::where('submitted_by', $user->id)->count();
        $openRequests    = BloodRequest::where('submitted_by', $user->id)->where('status', 'open')->count();

        // Suggested roles the user hasn't yet adopted
        $allRoles      = ['donor', 'volunteer'];
        $missingRoles  = array_values(array_diff($allRoles, $roles));
        $shareText = <<<TEXT
🩸 Blood Donor Profile

Name: {$user->name}
Blood Group: {$user->blood_group}
Email: {$user->email}

❤️ Total Donations: {$donationsCount}

Join me in saving lives through blood donation!

Visit ASRIJ:
https://asrij.org/
TEXT;

        return response()->json([
            'status' => true,
            'data'   => [
                'user' => [
                    'id'              => $user->id,
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'mobile'          => $user->mobile,
                    'whatsapp_number' => $user->whatsapp_number,
                    'dob'             => $user->dob,
                    'gender'          => $user->gender,
                    'address'         => $user->address,
                    'pin_code'        => $user->pin_code,
                    'blood_group'     => $user->blood_group,
                    'latitude'        => $user->latitude,
                    'longitude'       => $user->longitude,
                    'avatar'          => $user->avatar,
                    'avatar_url'      => $user->avatar_url,
                    'is_verified'     => (bool) $user->is_verified,
                    'roles'           => $roles,
                ],
                'role_data' => [
                    'donor'     => $donor,
                    'receiver'  => $receiver,
                    'volunteer' => $volunteer ? array_merge(
                        $volunteer->toArray(),
                        ['extra' => $volunteer->extra_data ?? []]
                    ) : null,
                    'members' => $members
                ],
                'activity' => [
                    'donations_count' => $donationsCount,
                    'responses_count' => $respondedCount,
                    'requests_count'  => $requestsCount,
                    'open_requests'   => $openRequests,
                    'last_donation'   => optional($donor?->last_donation)->toDateString(),
                ],
                'suggested_roles' => $missingRoles,
                'share_text' => trim($shareText),
            ],
        ]);
    }

    /**
     * PATCH /api/v1/profile
     *
     * Update the user's editable fields. Mobile is intentionally NOT
     * accepted here (it has its own OTP-protected flow below).
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'         => 'sometimes|string|max:120',
            'email'        => 'sometimes|nullable|email|max:160',
            'blood_group'  => 'sometimes|nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'dob'          => 'sometimes|nullable|date|before_or_equal:today',
            'gender'       => 'sometimes|nullable|in:Male,Female,Other',
            'address'      => 'sometimes|nullable|string|max:500',
            'pin_code'     => 'sometimes|nullable|string|max:10',
            'latitude'     => 'sometimes|nullable|numeric|between:-90,90',
            'longitude'    => 'sometimes|nullable|numeric|between:-180,180',
            'whatsapp_number' => 'sometimes|nullable|string|max:20',
        ]);

        $user = $request->user();
        $user->fill($data);
        $user->save();

        // Cascade blood-group change to the donor row if present so list
        // matching stays consistent.
        if (array_key_exists('blood_group', $data) && $data['blood_group']) {
            Donor::where('user_id', $user->id)->update(['blood_group' => $data['blood_group']]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully.',
            'data'    => $this->show($request)->getData(true)['data'],
        ]);
    }

    /**
     * POST /api/v1/profile/avatar  (multipart: file)
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user = $request->user();

        // Remove the old file if it was a local upload (skip absolute URLs).
        if ($user->avatar && !preg_match('#^https?://#i', $user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store("avatars/{$user->id}", 'public');

        $user->avatar = $path;
        $user->save();

        return response()->json([
            'status'     => true,
            'message'    => 'Avatar updated.',
            'avatar'     => $user->avatar,
            'avatar_url' => $user->avatar_url,
        ]);
    }

    /**
     * DELETE /api/v1/profile/avatar
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar && !preg_match('#^https?://#i', $user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Avatar removed.',
        ]);
    }

    /**
     * POST /api/v1/profile/change-password
     */
    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password'          => 'required|string',
            'new_password'              => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!$user->password || !Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * POST /api/v1/profile/mobile/send-otp
     * Body: { mobile }
     */
    public function sendMobileChangeOtp(Request $request, SmsService $sms)
    {
        $data = $request->validate([
            'mobile' => 'required|string|size:10|regex:/^[0-9]{10}$/',
        ]);

        $user = $request->user();

        if ($data['mobile'] === $user->mobile) {
            return response()->json([
                'status'  => false,
                'message' => 'This is already your registered mobile number.',
            ], 422);
        }

        $taken = User::where('mobile', $data['mobile'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($taken) {
            return response()->json([
                'status'  => false,
                'message' => 'This mobile number is already linked to another account.',
            ], 409);
        }

        $otp = rand(100000, 999999);

        DB::table('otps')->updateOrInsert(
            ['mobile' => $data['mobile']],
            [
                'otp'        => $otp,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        try {
            $sms->sendOtpSms($data['mobile'], $otp);
        } catch (\Throwable $e) {
            // SMS gateway failure shouldn't kill the flow in dev/staging.
            \Log::warning('mobile-change OTP SMS failed: ' . $e->getMessage());
        }

        return response()->json([
            'status'  => true,
            'message' => 'OTP sent to the new mobile number.',
            // never leak OTP in production
        ]);
    }

    /**
     * POST /api/v1/profile/mobile/verify-otp
     * Body: { mobile, otp }
     */
    public function verifyMobileChangeOtp(Request $request)
    {
        $data = $request->validate([
            'mobile' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'otp'    => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($data['mobile'] === $user->mobile) {
            return response()->json([
                'status'  => false,
                'message' => 'This is already your registered mobile number.',
            ], 422);
        }

        $row = DB::table('otps')
            ->where('mobile', $data['mobile'])
            ->where('otp', $data['otp'])
            ->where('expires_at', '>=', now())
            ->first();

        if (!$row) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        // Re-check uniqueness right before the swap.
        $taken = User::where('mobile', $data['mobile'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($taken) {
            return response()->json([
                'status'  => false,
                'message' => 'This mobile number is already linked to another account.',
            ], 409);
        }

        $user->mobile = $data['mobile'];
        $user->save();

        DB::table('otps')->where('mobile', $data['mobile'])->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Mobile number updated successfully.',
            'mobile'  => $user->mobile,
        ]);
    }

    /**
     * GET /api/v1/profile/devices
     */
    public function devices(Request $request)
    {
        $userId = $request->user()->id;

        $rows = FcmToken::where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get(['id', 'fcm_token', 'created_at', 'updated_at']);

        // Mask token so the UI can show "···abcd1234" without leaking it.
        $rows->transform(function ($row) {
            $tok = (string) $row->fcm_token;
            $row->token_preview = strlen($tok) > 12
                ? '...' . substr($tok, -10)
                : $tok;
            unset($row->fcm_token);
            return $row;
        });

        return response()->json([
            'status' => true,
            'data'   => $rows,
        ]);
    }

    /**
     * DELETE /api/v1/profile/devices/{id}
     */
    public function revokeDevice(Request $request, $id)
    {
        $userId = $request->user()->id;

        $deleted = FcmToken::where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'status'  => false,
                'message' => 'Device not found.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Device revoked.',
        ]);
    }

    /**
     * GET /api/v1/profile/activity
     * (Same numbers `show()` returns, exposed standalone for the mobile app's
     * "My Activity" tab.)
     */
    public function activity(Request $request)
    {
        $user = $request->user();
        $donor = Donor::where('user_id', $user->id)->first();

        return response()->json([
            'status' => true,
            'data'   => [
                'donations_count' => BloodRequestResponse::where('donor_id', $user->id)->where('status', 'accepted')->count(),
                'responses_count' => BloodRequestResponse::where('donor_id', $user->id)->count(),
                'requests_count'  => BloodRequest::where('submitted_by', $user->id)->count(),
                'open_requests'   => BloodRequest::where('submitted_by', $user->id)->where('status', 'open')->count(),
                'last_donation'   => optional($donor?->last_donation)->toDateString(),
            ],
        ]);
    }
}
