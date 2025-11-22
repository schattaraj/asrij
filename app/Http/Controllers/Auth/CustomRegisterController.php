<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DonorProfile;
use App\Models\ReceiverProfile;
use App\Models\Volunteer;
use App\Models\BloodBank;

class CustomRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // Blade view you’ll create
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:donor,receiver,volunteer,blood_bank',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Role-based profile creation
        switch ($request->role) {
            case 'donor':
                DonorProfile::create([
                    'user_id' => $user->id,
                    'blood_group' => $request->blood_group,
                    'last_donation_date' => $request->last_donation_date,
                    'contact_number' => $request->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                ]);
                break;

            case 'receiver':
                ReceiverProfile::create([
                    'user_id' => $user->id,
                    'receiver_type' => $request->receiver_type,
                    'blood_group' => $request->blood_group,
                    'contact_number' => $request->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                ]);
                break;

            case 'volunteer':
                Volunteer::create([
                    'user_id' => $user->id,
                    'organization_name' => $request->organization_name,
                    'contact_number' => $request->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                    'registration_number' => $request->registration_number,
                ]);
                break;

            case 'blood_bank':
                BloodBank::create([
                    'user_id' => $user->id,
                    'hospital_name' => $request->hospital_name,
                    'contact_number' => $request->mobile,
                    'address' => $request->address,
                    'pin_code' => $request->pin_code,
                    'license_number' => $request->license_number,
                ]);
                break;
        }

        auth()->login($user);

        return redirect('/dashboard')->with('success', 'Registration successful!');
    }
}
