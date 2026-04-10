<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            // 'email'     => 'nullable|email|unique:users,email',
            'mobile'    => 'required|digits:10|unique:users,mobile',
            'dob'       => 'required|date',
            'address'   => 'nullable|string',
            'pin_code'  => 'required|string|max:10',
            'blood_group' => 'required|string|max:3',
            'latitude'  => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            // 'password'  => 'nullable|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

    // ✅ Check OTP verified
    if (!Cache::get('otp_verified_' . $request->mobile)) {
        return response()->json([
            'status' => false,
            'message' => 'Please verify OTP first'
        ], 403);
    }
        $user = User::create([
            'name'      => $request->name,
            // 'email'     => $request->email,
            'mobile'    => $request->mobile,
            'dob'       => $request->dob,
            'address'   => $request->address,
            'pin_code'  => $request->pin_code,
            'blood_group' => $request->blood_group,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,

            'roles'     => ['user'],
            // 'password'  => $request->password ? Hash::make($request->password) : null,
        ]);

    // Optional: clear OTP verification
    Cache::forget('otp_verified_' . $request->mobile);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user
        ], 201);
    }
    public function sendRegistartionOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|unique:users,mobile'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $otp = rand(100000, 999999);
    
        // Store OTP
        DB::table('otps')->updateOrInsert(
            ['mobile' => $request->mobile],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    
        // TODO: Integrate SMS API here
        // For testing:
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // remove in production
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | SEND OTP
    |--------------------------------------------------------------------------
    */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10'
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        
        DB::table('otps')->updateOrInsert(
            ['mobile' => $request->mobile],
            [
                'otp'        => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // TODO: integrate SMS gateway here

        return response()->json([
            'message' => 'OTP sent successfully',
            'otp'     => $otp // remove in production
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY OTP
    |--------------------------------------------------------------------------
    */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:6'
        ]);

        $record = DB::table('otps')
            ->where('mobile', $request->mobile)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (Carbon::now()->gt($record->expires_at)) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();

        DB::table('otps')->where('mobile', $request->mobile)->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ]);
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
        return response()->json(['message' => 'OTP not found'], 404);
    }

    if ($record->otp != $request->otp) {
        return response()->json(['message' => 'Invalid OTP'], 400);
    }

    if (now()->gt($record->expires_at)) {
        return response()->json(['message' => 'OTP expired'], 400);
    }

    // Mark verified (optional: store in session/cache)
    Cache::put('otp_verified_' . $request->mobile, true, 300);
    return response()->json([
        'message' => 'OTP verified successfully',
        'verification_token' => Str::random(40)
    ]);
}
    /*
    |--------------------------------------------------------------------------
    | LOGIN WITH PASSWORD
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $request->validate([
            'mobile'   => 'required|digits:10',
            'password' => 'required'
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
