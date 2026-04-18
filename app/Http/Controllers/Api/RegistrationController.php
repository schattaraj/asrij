<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    private function handleOtherRegistration($authUser, $validated)
    {
        if (User::where('mobile', $validated['mobile'])->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'User with this mobile number already exist.'
            ], 409);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'mobile' => $validated['mobile'],
            'pin_code' => $validated['pin_code'],
            'status' => 'pending',
            'blood_group' => $validated['blood_group'],
            'dob' => $validated['dob'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'address' => $validated['address'],
            'referred_by' => $authUser ? $authUser->id : null,
            'roles' => ['user', 'donor'],
            'is_verified' => false,
        ]);

        if (!$user) {
            throw new \Exception('User creation failed');
        }

        // OTP
        $this->generateOtp($validated['mobile']);

        // Donor
        $this->createDonor($user->id, $validated);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent. Please verify.'
        ], 201);
    }
    private function handleSelfRegistration($authUser, $validated)
    {
        if (!$authUser) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (Donor::where('user_id', $authUser->id)->exists()) {
            return response()->json([
                'status' => true,
                'message' => 'You are already registered as a donor.'
            ], 200);
        }

        $authUser->update([
            'name' => $authUser->name ?: $validated['name'],
            'email' => $authUser->email ?: ($validated['email'] ?? null),
            'blood_group' => $authUser->blood_group ?: $validated['blood_group'],
            'dob' => $authUser->dob ?: $validated['dob'],
            'whatsapp_number' => $authUser->whatsapp_number ?: ($validated['whatsapp_number'] ?? null),
            'address' => $authUser->address ?: $validated['address'],
            'pin_code' => $authUser->pin_code ?: $validated['pin_code'],
        ]);

        $this->createDonor($authUser->id, $validated);

        return response()->json([
            'status' => true,
            'message' => 'Registered as donor successfully.'
        ], 200);
    }
    private function createDonor($userId, $validated)
    {
        $donor = Donor::create([
            'user_id'        => $userId,
            'blood_group'    => $validated['blood_group'],
            'year_of_birth'  => \Carbon\Carbon::parse($validated['dob'])->year,
            'last_donation'  => $validated['last_donation'],
            'pin_code'       => $validated['pin_code'],
            'address'        => $validated['address']
        ]);

        if (!$donor) {
            throw new \Exception('Donor Registration Failed');
        }

        return $donor;
    }
    private function generateOtp($mobile)
    {
        $otp = rand(100000, 999999);

        DB::table('otps')->updateOrInsert(
            ['mobile' => $mobile],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        return $otp;
    }
    public function store(Request $request)
    {
        $authUser = auth('sanctum')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email'  => 'nullable|email|unique:users,email',
            'blood_group' => 'required|string|max:3',
            'dob'       => 'required|date',
            'last_donation' => 'nullable|date',
            'mobile' => 'required|digits_between:10,15',
            'whatsapp_number' => 'nullable|digits_between:10,15',
            'address' => 'required|string',
            'pin_code' => 'required|string|max:10',
            'donor_latitude'  => 'nullable|string|max:20',
            'donor_longitude' => 'nullable|string|max:20',
            'request_for' => 'required|string|in:self,other',
        ]);

        // KEEPING YOUR CHECKBOX LOGIC
        // $validated['request_for'] = $request->has('request_for') ? 'self' : 'other';

        try {
            DB::beginTransaction();

            if ($validated['request_for'] === 'self') {
                $response = $this->handleSelfRegistration($authUser, $validated);
            } else {
                $response = $this->handleOtherRegistration($authUser, $validated);
            }

            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:6'
        ]);

        $record = DB::table('otps')
            ->where('mobile', $request->mobile)
            ->first();

        if (!$record) {
            return response()->json(['status' => false, 'message' => 'OTP not found'], 404);
        }

        if ($record->otp != $request->otp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP'], 400);
        }

        if (now()->gt($record->expires_at)) {
            return response()->json(['status' => false, 'message' => 'OTP expired'], 400);
        }
        $user = User::where('mobile', $request->mobile)->first();
        $user->update([
            'is_verified' => true
        ]);
        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
        ]);
    }
}
