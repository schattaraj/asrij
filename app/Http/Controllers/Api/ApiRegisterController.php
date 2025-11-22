<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DonorProfile;
use App\Models\ReceiverProfile;
use App\Models\Volunteer;
use App\Models\BloodBank;

class ApiRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'role' => 'required|in:donor,receiver,volunteer,blood_bank',
            'password' => 'required|min:6',
            'mobile' => 'required|unique:users,mobile',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $request->email,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Role-specific profile creation
        switch ($validated['role']) {
            case 'donor':
                DonorProfile::create([
                    'user_id' => $user->id,
                    'blood_group' => $request->blood_group,
                    'last_donation_date' => $request->last_donation_date,
                    'contact_number' => $user->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                ]);
                break;
            case 'receiver':
                ReceiverProfile::create([
                    'user_id' => $user->id,
                    'receiver_type' => $request->receiver_type,
                    'blood_group' => $request->blood_group,
                    'contact_number' => $user->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                ]);
                break;
            case 'volunteer':
                Volunteer::create([
                    'user_id' => $user->id,
                    'organization_name' => $request->organization_name,
                    'contact_number' => $user->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                    'registration_number' => $request->registration_number,
                ]);
                break;
            case 'blood_bank':
                BloodBank::create([
                    'user_id' => $user->id,
                    'hospital_name' => $request->hospital_name,
                    'contact_number' => $user->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                    'license_number' => $request->license_number,
                ]);
                break;
        }

        // Issue API token for mobile app
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function userProfile(Request $request)
    {
        return response()->json($request->user()->load(['donorProfile', 'receiverProfile', 'volunteer', 'bloodBank']));
    }
}
