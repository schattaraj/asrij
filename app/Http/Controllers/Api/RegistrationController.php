<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Volunteer;

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
            // 'pin_code' => $authUser->pin_code ?: $validated['pin_code'],
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
            // 'pin_code' => 'required|string|max:10',
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
    public function storeVolunteer(Request $request)
{
    $commonRules = [
        'volunteer_type' => 'required|in:individual,ngo,charity,club',
        'email'          => 'nullable|email',
        'mobile'        => 'required|digits_between:10,15',
        'address'        => 'required|string',
        'volunteer_latitude'  => 'nullable',
        'volunteer_longitude' => 'nullable',
    ];

    // ✅ Individual rules
    $individualRules = [
        'name'          => 'required|string|max:255',
        'blood_group'   => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        'dob' => 'required|date',
        'mobile'        => 'required|digits_between:10,15',
    ];

    // ✅ Organization rules (ngo/charity/club)
    $orgRules = [
        'registration_number' => 'required|string',
        'organization'        => 'required|string|max:255',
        'group_quantity'      => 'required|integer|min:1',
    ];

    $rules = $commonRules;

    if ($request->volunteer_type === 'individual') {
        $rules = array_merge($rules, $individualRules);
    }

    if (in_array($request->volunteer_type, ['ngo', 'charity', 'club'])) {
        $rules = array_merge($rules, $orgRules);
    }

    // ✅ Run Validation
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed',
            'errors'  => $validator->errors()
        ], 422);
    }
    try {
        DB::beginTransaction();

        $user = null;
        $otpSent = false;

        if ($request->request_for === 'self') {
        
            // ✅ Use logged-in user
            $user = auth()->user();
        
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
        
        } else {
            $existingUser = User::where('mobile', $request->mobile)->first();
            if ($existingUser) {
                // ❌ STOP here (as per your requirement)
                return response()->json([
                    'status'  => false,
                    'message' => 'User already exists with this mobile number'
                ], 409); // 409 Conflict
            }
            $user = User::create([
                'name'            => $request->name ?? $request->organization,
                'email'           => $request->email ?? null,
                'mobile'          => $request->mobile,
                'roles'           => ['user','volunteer'],
        
                'refered_by'      => auth()->id(),   // who created it
                'is_verified'     => false,
            ]);
            $this->generateOtp($validated['mobile']);
            $otpSent = true;
        }

        // Members array
        $members = [];
        if ($request->has('member_name')) {
            foreach ($request->member_name as $index => $name) {
                if (empty($name)) continue;

                $members[] = [
                    'name'     => $name,
                    'contact'  => $request->member_contact_number[$index] ?? null,
                    'position' => $request->member_position[$index] ?? null,
                ];
            }
        }

        $volunteer = Volunteer::create([
            'user_id'            => $user->id,
            'volunteer_type'     => $request->volunteer_type,
        
            'registration_number'=> $request->registration_number,
            'organization'       => $request->organization,
            'group_quantity'     => $request->group_quantity,
        
            'president_name'     => $request->president_name,
            'president_number'   => $request->president_number,
            'secretary_name'     => $request->secretary_name,
            'secretary_number'   => $request->secretary_number,
        
            'account_name'       => $request->account_name,
            'account_number'     => $request->account_number,
        
            // ✅ request_for aware logic
            'contact'            => $request->mobile,
        
            'address'            => $request->address,
            'latitude'           => $request->volunteer_latitude,
            'longitude'          => $request->volunteer_longitude,
        
            'extra_data' => [
                'members'     => $members,
            ],
        ]);

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => $otpSent
                ? 'User created & OTP sent successfully.'
                : 'Volunteer created successfully.',
            'data'    => [
                'user'      => $user,
                'volunteer' => $volunteer,
                'otp_sent'  => $otpSent
            ]
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong',
            'error'   => $e->getMessage() // remove in production
        ], 500);
    }
}
    // public function storeVolunteer(Request $request)
    // {
    //     $rules = array_merge($rules, [
    //         'volunteer_type'  => 'required|in:individual,ngo,charity,club',
    //         'email'           => 'required|email|unique:users,email',
    //     ]);

    //     if ($request->volunteer_type === 'individual') {
    //         $rules = array_merge($rules, [
    //             'name'          => 'required|string|max:255',
    //             'blood_group'   => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
    //             'year_of_birth' => 'required|integer|min:1900|max:' . now()->year,
    //             'pin_code'      => 'required|digits:6',
    //         ]);
    //     }

    //     if (in_array($request->volunteer_type, ['ngo','charity','club'])) {
    //         unset($rules['whatsapp']);
    //         unset($rules['name']);
    //         unset($rules['blood_group']);
    //         unset($rules['year_of_birth']);
    //         unset($rules['last_donation']);
    //         $rules = array_merge($rules, [
    //             'registration_number' => 'required|string',
    //             'organization'        => 'required|string|max:255',
    //             'group_quantity'      => 'required|integer|min:1',
    //             'contact'             => 'required|digits_between:10,15',
    //         ]);
    //     }

    // /** ----------------------------
    //  * Run Validation
    //  * ---------------------------- */
    // $validator = Validator::make($request->all(), $rules);

    // if ($validator->fails()) {
    //     return redirect()
    //         ->back()
    //         ->withErrors($validator)
    //         ->withInput();
    // }
    // DB::transaction(function () use ($request, $role) {
    //     $user = User::create([
    //         'name'     => $request->name ?? $request->organization ?? null,
    //         'email'    => $request->email,
    //         'roles'     => [$role],
    //         'mobile'  => $request->contact,
    //         'whatsapp_number'=>$request->whatsapp
    //     ]);
    //     // 🔹 Trigger email verification
    //     event(new Registered($user));
    //      // 🔹 Send password setup link
    //     $token = Password::createToken($user);
    //     $user->notify(new \App\Notifications\PasswordSetupNotification($token));

    //             $members = [];
    //             if ($request->has('member_name')) {
    //                 foreach ($request->member_name as $index => $name) {
                
    //                     // Skip empty rows
    //                     if (empty($name)) {
    //                         continue;
    //                     }
                
    //                     $members[] = [
    //                         'name'     => $name,
    //                         'contact'  => $request->member_contact_number[$index] ?? null,
    //                         'position' => $request->member_position[$index] ?? null,
    //                     ];
    //                 }
    //             }
    //             Volunteer::create([
    //                 'user_id'        => $user->id,
    //                 'volunteer_type' => $request->volunteer_type,
    //                 'registration_number' => $request->registration_number,
    //                 'organization' => $request->organization,
    //                 'group_quantity'=> $request->group_quantity,
    //                 'president_name'   => $request->president_name,
    //                 'president_number' => $request->president_number,
    //                 'secretary_name' => $request->secretary_name,
    //                 'secretary_number' => $request->secretary_number,
    //                 'account_name'  => $request->account_name,
    //                 'account_number'  => $request->account_number,
    //                 'contact' => $request->contact,
    //                 'extra_data'     => [
    //                     'members' => $members
    //                 ],
    //             ]);
    // });
    // return response()->json([
    //     'status' => true,
    //     'message' => 'Volunteer Registration successful.'
    // ], 200);
    // }
}
