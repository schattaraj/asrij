<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $role = $request->role; // donor | receiver
    
        $authUser = auth('sanctum')->user(); // Logged in user from Bearer Token
    
        /** ----------------------------
         * Base Validation
         * ---------------------------- */
        $rules = [
            'role'   => 'required|in:donor,receiver',
            'email'  => 'nullable|email|unique:users,email',
            'contact'=> 'required|digits_between:10,15|unique:users,mobile',
            'whatsapp'=>'nullable|digits_between:10,15',
            'address'=> 'required|string',
            'register_for_self' => 'required|boolean'
        ];
    
        /** ----------------------------
         * Donor Validation
         * ---------------------------- */
        if ($role === 'donor') {
            $rules = array_merge($rules, [
                'name'            => 'required|string|max:255',
                'blood_group'     => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'year_of_birth'   => 'required|integer|min:1900|max:' . now()->year,
                'last_donation'   => 'nullable|date',
                'pin_code'        => 'required|digits:6',
            ]);
        }
    
        /** ----------------------------
         * Receiver Validation
         * ---------------------------- */
        if ($role === 'receiver') {
            $rules = array_merge($rules, [
                'name'            => 'required|string|max:255',
                'receiver_type'   => 'required|string',
                'blood_group'     => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'hospital'        => 'required|string|max:255',
                'pin_code'        => 'required|digits:6',
            ]);
        }
    
        /** ----------------------------
         * Run Validation
         * ---------------------------- */
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        DB::beginTransaction();
    
        try {
    
            $userId = null;
            $referedBy = $authUser ? $authUser->id : null;
    
            /** --------------------------------
             * If registering for self
             * -------------------------------- */
            if ($request->register_for_self) {
    
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => bcrypt(str()->random(12)),
                    'roles'    => [$role],
                    'mobile'   => $request->contact,
                    'whatsapp_number' => $request->whatsapp
                ]);
    
                event(new Registered($user));
    
                $token = Password::createToken($user);
                $user->notify(new \App\Notifications\PasswordSetupNotification($token));
    
                $userId = $user->id;
            }
    
            /** ----------------------------
             * Donor Create
             * ---------------------------- */
            if ($role === 'donor') {
    
                $donor = Donor::create([
                    'user_id'        => $userId,
                    'refered_by'     => $referedBy,
                    'blood_group'    => $request->blood_group,
                    'year_of_birth'  => $request->year_of_birth,
                    'last_donation'  => $request->last_donation,
                    'pin_code'       => $request->pin_code,
                    'address'        => $request->address,
                    'name'           => $request->name
                ]);
    
            }
    
            /** ----------------------------
             * Receiver Create
             * ---------------------------- */
            if ($role === 'receiver') {
    
                $receiver = Receiver::create([
                    'user_id'       => $userId,
                    'refered_by'    => $referedBy,
                    'receiver_type' => $request->receiver_type,
                    'blood_group'   => $request->blood_group,
                    'hospital'      => $request->hospital,
                    'pin_code'      => $request->pin_code,
                    'address'       => $request->address,
                    'name'          => $request->name
                ]);
    
            }
    
            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => ucfirst($role) . ' registered successfully'
            ], 201);
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return response()->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
