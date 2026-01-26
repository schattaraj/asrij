<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donor;
use App\Models\Receiver;
use App\Models\Volunteer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    //
    public function store(Request $request)
    {
        $role = $request->role; // donor | receiver | volunteer | admin
    /** ----------------------------
     * Base Validation (All Roles)
     * ---------------------------- */
    $rules = [
        'role'   => 'required|in:donor,receiver,volunteer,admin',
        'email'  => 'email|unique:users,email',
        'contact'=> 'required|unique:users,mobile|digits_between:10,15',
        'whatsapp'=>'required|unique:users,whatsapp_number',
        'address'=> 'required|string',
    ];

    /** ----------------------------
     * Role-Specific Validation
     * ---------------------------- */
    if ($role === 'donor') {
        $rules = array_merge($rules, [
            'name'            => 'required|string|max:255',
            // 'type'            => 'required|in:Individual,NGO,Charity',
            'blood_group'     => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'year_of_birth'   => 'required|integer|min:1900|max:' . now()->year,
            'last_donation'   => 'nullable|date',
            'pin_code'        => 'required|digits:6',
        ]);
    }

    if ($role === 'receiver') {
        $rules = array_merge($rules, [
            'name'            => 'required|string|max:255',
            'receiver_type'   => 'required|string',
            'blood_group'     => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'hospital'        => 'required|string|max:255',
            'pin_code'        => 'required|digits:6',
        ]);
    }

    if ($role === 'volunteer') {
        $rules = array_merge($rules, [
            'volunteer_type'  => 'required|in:individual,ngo,charity,club',
            'email'           => 'required|email|unique:users,email',
        ]);

        if ($request->volunteer_type === 'individual') {
            $rules = array_merge($rules, [
                'name'          => 'required|string|max:255',
                'blood_group'   => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'year_of_birth' => 'required|integer|min:1900|max:' . now()->year,
                'pin_code'      => 'required|digits:6',
            ]);
        }

        if (in_array($request->volunteer_type, ['ngo','charity','club'])) {
            $rules = array_merge($rules, [
                'registration_number' => 'required|string',
                'organization'        => 'required|string|max:255',
                'group_quantity'      => 'required|integer|min:1',
                'contact'             => 'required|digits_between:10,15',
            ]);
        }
    }

    if ($role === 'admin') {
        $rules = array_merge($rules, [
            'name' => 'required|string|max:255',
        ]);
    }

    /** ----------------------------
     * Run Validation
     * ---------------------------- */
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }
    DB::transaction(function () use ($request, $role) {
        $user = User::create([
            'name'     => $request->name ?? $request->organization ?? null,
            'email'    => $request->email,
            'password' => bcrypt(str()->random(12)), // Temporary password
            'role'     => $role,
            'mobile'  => $request->contact,
            'whatsapp_number'=>$request->whatsapp
        ]);
        // 🔹 Trigger email verification
        event(new Registered($user));
         // 🔹 Send password setup link
        $token = Password::createToken($user);
        $user->notify(new \App\Notifications\PasswordSetupNotification($token));
        switch ($role) {

            case 'donor':
                Donor::create([
                    'user_id'        => $user->id,
                    'type'           => $request->type,
                    'blood_group'    => $request->blood_group,
                    'year_of_birth'  => $request->year_of_birth,
                    'last_donation'  => $request->last_donation,
                    'pin_code' => $request->pin_code ?? $request->pincode,
                    'address'  => $request->address ?? '',
                ]);
                break;

            case 'receiver':
                Receiver::create([
                    'user_id'      => $user->id,
                    'receiver_type'=> $request->receiver_type,
                    'blood_group'  => $request->blood_group,
                    'hospital'     => $request->hospital,
                ]);
                break;

            case 'volunteer':
                Volunteer::create([
                    'user_id'        => $user->id,
                    'volunteer_type' => $request->volunteer_type,
                    'registration_number' => $request->registration_number,
                    'organization' => $request->organization,
                    'group_quantity'=> $request->group_quantity,
                    'president_name'   => $request->president_name,
                    'president_number' => $request->president_number,
                    'secretary_name' => $request->secretary_name,
                    'secretary_number' => $request->secretary_number,
                    'account_name'  => $request->account_name,
                    'contact' => $request->contact,
                    'extra_data'     => json_encode($request->except([
                        'name','email','role','contact','pin_code','address'
                    ])),
                ]);
                break;

            case 'admin':
                Admin::create([
                    'user_id' => $user->id
                ]);
                break;
        }
    });
        return redirect()
        ->back()
        ->with('success', ucfirst($role) . ' registered successfully');
    }
}
