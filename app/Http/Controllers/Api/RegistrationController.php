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
use Illuminate\Support\Facades\Http;
use App\Services\SmsService;
use App\Http\Requests\Api\StoreVolunteerRegistrationRequest;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    private function handleOtherRegistration($authUser, $validated, SmsService $smsService)
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
            'latitude' => $validated['donor_latitude'] ?? null,
            'longitude' => $validated['donor_longitude'] ?? null,
            'referred_by' => $authUser ? $authUser->id : null,
            'roles' => ['user', 'donor'],
            'is_verified' => false,
        ]);

        if (!$user) {
            throw new \Exception('User creation failed');
        }

        // OTP
       $otp = $this->generateOtp($validated['mobile']);
       $smsResponse = $smsService->sendOtpSms($validated['mobile'], $otp);

        // Donor
        $this->createDonor($user->id, $validated);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent. Please verify.',
            'otp_response' => $smsResponse
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
            ], 409);
        }

        $authUser->update([
            'name' => $authUser->name ?: $validated['name'],
            'email' => $authUser->email ?: ($validated['email'] ?? null),
            'blood_group' => $authUser->blood_group ?: $validated['blood_group'],
            'dob' => $authUser->dob ?: $validated['dob'],
            'whatsapp_number' => $authUser->whatsapp_number ?: ($validated['whatsapp_number'] ?? null),
            'address' => $authUser->address ?: $validated['address'],
            'latitude' => $authUser->latitude ?: ($validated['donor_latitude'] ?? null),
            'longitude' => $authUser->longitude ?: ($validated['donor_longitude'] ?? null),
            // 'pin_code' => $authUser->pin_code ?: $validated['pin_code'],
        ]);

        $this->createDonor($authUser->id, $validated);
        
        $roles = $authUser->roles ?? [];

        if (!in_array('donor', $roles)) {
            $roles[] = 'donor';
        
            $authUser->update([
                'roles' => $roles
            ]);
        }
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
            'last_donation'  => $validated['last_donation'] ?? null,
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
    public function store(Request $request, SmsService $smsService)
    {
        $authUser = auth('sanctum')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email'  => 'nullable|email',
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
                $request->validate([
                    'email'  => 'nullable|email|unique:users,email',
                ]);
                $response = $this->handleOtherRegistration($authUser, $validated, $smsService);
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
    public function storeVolunteer(StoreVolunteerRegistrationRequest $request, SmsService $smsService)
    {
        $authUser = $request->user();
        $validated = $request->validated();
        $isOrganization = in_array($validated['volunteer_type'], ['ngo', 'charity', 'club'], true);
        $otpSent = false;
        $smsResponse = null;

        try {
            DB::beginTransaction();

            if ($validated['request_for'] === 'self') {
                $user = $authUser;

                if (Volunteer::where('user_id', $user->id)->exists()) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'You are already registered as a volunteer.',
                        'errors' => [
                            'request_for' => ['You are already registered as a volunteer.'],
                        ],
                    ], 409);
                }

                if (User::where('mobile', $validated['mobile'])->where('id', '!=', $user->id)->exists()) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'User already exists with this mobile number.',
                        'errors' => [
                            'mobile' => ['User already exists with this mobile number.'],
                        ],
                    ], 409);
                }

                if (User::where('email', $validated['email'])->where('id', '!=', $user->id)->exists()) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'User already exists with this email address.',
                        'errors' => [
                            'email' => ['User already exists with this email address.'],
                        ],
                    ], 409);
                }

                $roles = $user->roles ?? [];
                if (!in_array('volunteer', $roles, true)) {
                    $roles[] = 'volunteer';
                }

                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? $user->email,
                    'mobile' => $validated['mobile'],
                    'blood_group' => $validated['blood_group'] ?? $user->blood_group,
                    'dob' => $validated['dob'] ?? $user->dob,
                    'address' => $validated['address'],
                    'latitude' => $validated['volunteer_latitude'] ?? $user->latitude,
                    'longitude' => $validated['volunteer_longitude'] ?? $user->longitude,
                    'roles' => $roles,
                ]);
            } else {
                if (User::where('mobile', $validated['mobile'])->exists()) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'User already exists with this mobile number.',
                        'errors' => [
                            'mobile' => ['User already exists with this mobile number.'],
                        ],
                    ], 409);
                }

                if (!empty($validated['email']) && User::where('email', $validated['email'])->exists()) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'User already exists with this email address.',
                        'errors' => [
                            'email' => ['User already exists with this email address.'],
                        ],
                    ], 409);
                }

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'mobile' => $validated['mobile'],
                    'password' => Hash::make(Str::random(32)),
                    'status' => 'pending',
                    'blood_group' => $validated['blood_group'] ?? null,
                    'dob' => $validated['dob'] ?? null,
                    'address' => $validated['address'],
                    'latitude' => $validated['volunteer_latitude'] ?? null,
                    'longitude' => $validated['volunteer_longitude'] ?? null,
                    'referred_by' => $authUser->id,
                    'roles' => ['user', 'volunteer'],
                    'is_verified' => false,
                ]);

                $otp = $this->generateOtp($validated['mobile']);
                $smsResponse = $smsService->sendOtpSms($validated['mobile'], $otp);
                $otpSent = true;
            }

            $members = $this->normalizeVolunteerMembers($request);

            $extraData = [
                'request_for' => $validated['request_for'],
                'members' => $members,
            ];

            if ($validated['volunteer_type'] === 'student') {
                $extraData['student'] = [
                    'institution' => $validated['institution'] ?? null,
                    'student_id' => $validated['student_id'] ?? null,
                    'course' => $validated['course'] ?? null,
                    'year_of_study' => $validated['year_of_study'] ?? null,
                ];
            }

            $volunteer = Volunteer::create([
                'user_id' => $user->id,
                'volunteer_type' => $validated['volunteer_type'],
                'organization' => $isOrganization ? ($validated['organization'] ?? null) : null,
                'registration_number' => $isOrganization ? ($validated['registration_number'] ?? null) : null,
                'group_quantity' => $isOrganization ? ($validated['group_quantity'] ?? null) : null,
                'president_name' => $validated['president_name'] ?? null,
                'president_number' => $validated['president_number'] ?? null,
                'secretary_name' => $validated['secretary_name'] ?? null,
                'secretary_number' => $validated['secretary_number'] ?? null,
                'account_name' => $validated['account_name'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'contact_number' => $validated['mobile'],
                'address' => $validated['address'],
                'latitude' => $validated['volunteer_latitude'] ?? null,
                'longitude' => $validated['volunteer_longitude'] ?? null,
                'extra_data' => $extraData,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $otpSent
                    ? 'Volunteer registration created. OTP sent for verification.'
                    : 'Volunteer registered successfully.',
                'data' => [
                    'user' => $user->fresh(),
                    'volunteer' => $volunteer->fresh(),
                    'otp_sent' => $otpSent,
                    'sms_response' => $smsResponse,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Volunteer registration failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function normalizeVolunteerMembers(Request $request): array
    {
        $members = [];

        if ($request->filled('members')) {
            $rawMembers = is_string($request->input('members'))
                ? json_decode($request->input('members'), true)
                : $request->input('members');

            if (is_array($rawMembers)) {
                foreach ($rawMembers as $member) {
                    if (!is_array($member) || empty(trim((string) ($member['name'] ?? '')))) {
                        continue;
                    }

                    $members[] = [
                        'name' => trim((string) $member['name']),
                        'contact_number' => trim((string) ($member['contact_number'] ?? $member['contact'] ?? '')),
                        'position' => trim((string) ($member['position'] ?? '')),
                    ];
                }
            }
        }

        foreach ((array) $request->input('member_name', []) as $index => $name) {
            if (empty(trim((string) $name))) {
                continue;
            }

            $members[] = [
                'name' => trim((string) $name),
                'contact_number' => trim((string) $request->input("member_contact_number.$index", '')),
                'position' => trim((string) $request->input("member_position.$index", '')),
            ];
        }

        return array_values($members);
    }

    private function storeVolunteerLegacy(Request $request,SmsService $smsService)
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
            $alreadyVolunteer = Volunteer::where('user_id',$user->id)->first();
            if($alreadyVolunteer){
                return response()->json([
                    'status' => false,
                    'message' => 'You are already a volunteer'
                ], 409);
            }
        $roles = $user->roles ?? [];

        if (!in_array('volunteer', $roles)) {
            $roles[] = 'volunteer';
        
            $user->update([
                'roles' => $roles
            ]);
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
            $otp = $this->generateOtp($validated['mobile']);
            $smsService->sendOtpSms($request->mobile, $otp);
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
