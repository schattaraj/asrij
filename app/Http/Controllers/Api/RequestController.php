<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use App\Models\BloodRequestResponse;
use App\Models\FcmToken;
use App\Services\SmsService;
use App\Services\FcmService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
        return null;
    }

    $earthRadius = 6371; // KM

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return round($earthRadius * $c, 2); // distance in KM
}
private function formatDistance($distance)
{
    if (is_null($distance)) {
        return null;
    }

    if ($distance < 1) {
        return round($distance * 1000) . ' m'; // meters
    }

    return round($distance, 1) . ' km'; // kilometers
}
    // public function index(Request $request)
    // {
    //     $user = null;
    //     $token = $request->bearerToken();
    //     if ($token) {
    //         $accessToken = PersonalAccessToken::findToken($token);
    //         $user = $accessToken?->tokenable;
    //     }
    //     $query = BloodRequest::where('status', 'open')
    //         ->whereHas('userByMobile', function ($q) {
    //             $q->where('is_verified', true);
    //         });
    //     if ($request->filled('urgency')) {
    //         $query->where('urgency', $request->urgency);
    //     }
    //     $query->latest();
    //     if ($request->filled('limit')) {
    //         $query->limit((int) $request->limit);
    //     }
    //     $requests = $query->get();

    //     if ($user) {
    //         $userLat = $user->latitude;
    //         $userLng = $user->longitude;

    //         $requests = $requests->map(function ($req) use ($userLat, $userLng, $user) {
    //             $distance = $this->calculateDistance(
    //                 $userLat,
    //                 $userLng,
    //                 $req->patient_latitude,
    //                 $req->patient_longitude
    //             );
    //             $req->setAttribute('distance', $distance);
    //             $req->setAttribute('distance_text', $this->formatDistance($distance));
    //             $hasResponded = BloodRequestResponse::where('blood_request_id', $req->id)
    //                 ->where('donor_id', $user->id)
    //                 ->exists();
    //             $response = BloodRequestResponse::where('blood_request_id', $req->id)
    //                 ->where('donor_id', $user->id)
    //                 ->first();
    //             $req->setAttribute('has_responded', $hasResponded);
    //             $req->setAttribute('response_status', $response->status ?? null);
    //             return $req;
    //         });
    //     }
    //     $requests->makeHidden([
    //         'name',
    //         'mobile',
    //         'request_for',
    //         'submitted_by',
    //         'created_at',
    //         'updated_at'
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'data' => $requests
    //     ]);
    // }
public function index(Request $request)
{
    $user = null;
    $token = $request->bearerToken();

    if ($token) {
        $accessToken = PersonalAccessToken::findToken($token);
        $user = $accessToken?->tokenable;
    }

    $query = BloodRequest::where('status', 'open')
        ->whereHas('userByMobile', function ($q) {
            $q->where('is_verified', true);
        });

    /*
    |--------------------------------------------------------------------------
    | Filter by urgency
    |--------------------------------------------------------------------------
    | urgent => required within less than 3 days
    | normal => all others
    */
    if ($request->filled('urgency')) {

        if ($request->urgency === 'urgent') {
            $query->where('required_before_unit', 'days')
                  ->where('required_before', '<', 3);
        }

        if ($request->urgency === 'normal') {
            $query->where(function ($q) {
                $q->where('required_before_unit', '!=', 'days')
                  ->orWhere('required_before', '>=', 3);
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Filter requests within 20 KM
    |--------------------------------------------------------------------------
    */
    if ($user && $user->latitude && $user->longitude) {

        $userLat = $user->latitude;
        $userLng = $user->longitude;
        $radius = 20; // KM

        $query->selectRaw("
            blood_requests.*,
            (
                6371 * acos(
                    cos(radians(?))
                    * cos(radians(patient_latitude))
                    * cos(radians(patient_longitude) - radians(?))
                    + sin(radians(?))
                    * sin(radians(patient_latitude))
                )
            ) AS distance
        ", [$userLat, $userLng, $userLat])
        ->having('distance', '<=', $radius)
        ->orderBy('distance');

    } else {
        $query->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Limit
    |--------------------------------------------------------------------------
    */
    if ($request->filled('limit')) {
        $query->limit((int) $request->limit);
    }

    $requests = $query->get();

    /*
    |--------------------------------------------------------------------------
    | Fetch all donor responses in one query
    |--------------------------------------------------------------------------
    */
    $responses = collect();

    if ($user) {
        $responses = BloodRequestResponse::where('donor_id', $user->id)
            ->get()
            ->keyBy('blood_request_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Transform response
    |--------------------------------------------------------------------------
    */
    $requests = $requests->map(function ($req) use ($responses) {

        // Use SQL-calculated distance if available
        $distance = $req->distance ?? null;

        if ($distance !== null) {
            $req->setAttribute('distance', round($distance, 2));

            $req->setAttribute(
                'distance_text',
                $this->formatDistance($distance)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Dynamic urgency
        |--------------------------------------------------------------------------
        */
        $urgency = 'normal';

        if (
            $req->required_before_unit === 'days' &&
            $req->required_before < 3
        ) {
            $urgency = 'urgent';
        }

        $req->setAttribute('urgency', $urgency);

        /*
        |--------------------------------------------------------------------------
        | Optimized response lookup
        |--------------------------------------------------------------------------
        */
        $response = $responses[$req->id] ?? null;

        $req->setAttribute('has_responded', !!$response);

        $req->setAttribute(
            'response_status',
            $response->status ?? null
        );

        return $req;
    });

    /*
    |--------------------------------------------------------------------------
    | Hide unnecessary fields
    |--------------------------------------------------------------------------
    */
    $requests->makeHidden([
        'name',
        'mobile',
        'request_for',
        'submitted_by',
        'created_at',
        'updated_at'
    ]);

    return response()->json([
        'status' => true,
        'data' => $requests
    ]);
}
    public function store(Request $request,SmsService $smsService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'blood_group' => 'required|string|max:3',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'hospital_name' => 'required|string|max:255',
            'unit' => 'required|integer',
            'patient_type' => 'required|string|max:50',
            'mobile' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            // 'pin_code' => 'required|string|max:10',
            'email' => 'nullable|email|max:255',
            'required_before' => 'required|integer|min:1',
            'required_before_unit' => 'required|in:hours,days',
            'patient_latitude' => 'nullable|string|max:20',
            'patient_longitude' => 'nullable|string|max:20',
            'prescription' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'request_for' => 'required|string|in:self,other',
        ]);
        // $validated['request_for'] = $request->has('request_for') ? 'self' : 'other';
        $validated['submitted_by'] = Auth::id();

        if ($request->hasFile('prescription')) {
            $file = $request->file('prescription');
            $path = $file->store('prescriptions', 'public'); // storage/app/public/prescriptions
            $validated['prescription'] = $path;
        }
        DB::beginTransaction();
        try {
            $userCreate = null;
            if ($validated['request_for'] === 'other') {
    
                $userExist = User::where('mobile', $validated['mobile'])->first();
    
                if ($userExist) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User with this mobile number already exist. Please login with that number to complete the verification.'
                    ], 409);
                }
    
                $userCreate = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'mobile' => $validated['mobile'],
                    // 'pin_code' => $validated['pin_code'],
                    'status' => 'pending',
                    'blood_group' => $validated['blood_group'],
                    'dob' => $validated['dob'],
                    'gender'=>$validated['gender'],
                    'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                    'address' => $validated['address'],
                    'latitude' => $validated['patient_latitude'] ?? null,
                    'longitude' => $validated['patient_longitude'] ?? null,
                    'referred_by' => $validated['submitted_by'],
                    'roles' => ['user'],
                    'is_verified' => false,
                ]);
    
                if (!$userCreate) {
                    throw new \Exception('User creation failed');
                }
    
                // Generate OTP
                $otp = rand(100000, 999999);
    
                $otpSaved = DB::table('otps')->updateOrInsert(
                    ['mobile' => $validated['mobile']],
                    [
                        'otp' => $otp,
                        'expires_at' => now()->addMinutes(5),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
    
                if (!$otpSaved) {
                    // OTP failed → delete user
                    $userCreate->delete();
    
                    throw new \Exception('Failed to send OTP. Please try again.');
                }
                $smsService->sendOtpSms($validated['mobile'], $otp);
            }
            $validated['submitted_by'] = Auth::id();
            $validated['user_id'] = Auth::id();
            if ($validated['request_for'] === 'other'){
            $validated['user_id'] = $userCreate->id;
            }
            // Create Blood Request
            $bloodRequest = BloodRequest::create($validated);
    
            if (!$bloodRequest) {
                throw new \Exception('Blood request creation failed');
            }
    
            DB::commit();

            // ── Push notify nearby donors (additive, fail-safe) ────────────────
            try {
                $this->notifyNearbyDonors($bloodRequest);
            } catch (\Throwable $e) {
                Log::warning('notifyNearbyDonors failed: ' . $e->getMessage());
            }

            return response()->json([
                'status' => true,
                'data' => $bloodRequest,
                'otp' => $userCreate ? $otp : null, // For testing purposes, return OTP in response. Remove in production.
                'message' => $userCreate ? 'Blood request created successfully. OTP sent to patient\'s mobile for verification.' : 'Blood request created successfully.'
            ], 201);

        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $request = BloodRequest::find($id);

        if (!$request) {
            return response()->json([
                'status' => false,
                'message' => 'Request not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $request
        ]);
    }

    public function update(Request $request, $id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'status' => false,
                'message' => 'Request not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:45',
            'blood_group' => 'sometimes|string|max:45',
            'hospital_name' => 'sometimes|string|max:255',
            'unit' => 'sometimes|string|max:45',
            'patient_type' => 'sometimes|string|max:255',
            'mobile' => 'sometimes|string|max:15',
            'pin_code' => 'sometimes|string|max:45',
            'request_for' => 'sometimes|string|max:255',
            'submitted_by' => 'sometimes|integer',
        ]);

        $bloodRequest->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Blood request updated successfully',
            'data' => $bloodRequest
        ]);
    }

    public function destroy($id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'status' => false,
                'message' => 'Request not found'
            ], 404);
        }

        $bloodRequest->delete();

        return response()->json([
            'status' => true,
            'message' => 'Blood request deleted successfully'
        ]);
    }
    /**
     * Push a notification to every user whose roles include "donor" and who
     * is within the configured radius (default 20 KM) of the patient's
     * coordinates. Fully additive — does not change any request response.
     */
    private function notifyNearbyDonors(BloodRequest $bloodRequest): void
    {
        $lat = $bloodRequest->patient_latitude;
        $lng = $bloodRequest->patient_longitude;

        if (!$lat || !$lng) {
            return; // no patient coordinates → nothing to do
        }

        $radius = (float) config('services.fcm.donor_radius_km', 20);

        // Find donor users within radius, excluding the requester themselves,
        // joined to their FCM token. Uses Haversine in SQL for performance.
        $rows = DB::table('users')
            ->join('fcm_token', 'fcm_token.user_id', '=', 'users.id')
            ->whereNotNull('users.latitude')
            ->whereNotNull('users.longitude')
            ->whereNotNull('fcm_token.fcm_token')
            ->where(function ($q) {
                // Notify users whose roles include "donor" OR "volunteer"
                $q->whereRaw("JSON_CONTAINS(users.roles, '\"donor\"')")
                  ->orWhereRaw("JSON_CONTAINS(users.roles, '\"volunteer\"')");
            })
            ->where(function ($q) use ($bloodRequest) {
                $q->whereRaw('LOWER(users.blood_group) = ?', [
                    strtolower($bloodRequest->blood_group)
                ])
                    ->orWhereRaw('LOWER(users.blood_group) = ?', ['any']);
            })
            ->when($bloodRequest->submitted_by, fn ($q) =>
                $q->where('users.id', '!=', $bloodRequest->submitted_by))

            ->selectRaw(
                "fcm_token.fcm_token AS token,
                 users.id AS user_id,
                 users.blood_group,
                 (6371 * acos(
                     cos(radians(?)) * cos(radians(users.latitude))
                     * cos(radians(users.longitude) - radians(?))
                     + sin(radians(?)) * sin(radians(users.latitude))
                 )) AS distance_km",
                [$lat, $lng, $lat]
            )
            ->havingRaw('distance_km <= ?', [$radius])
            ->orderBy('distance_km')
            ->get();

        if ($rows->isEmpty()) {
            return;
        }

        $tokens = $rows->pluck('token')->filter()->unique()->values()->all();

        if (empty($tokens)) {
            return;
        }

        $title = 'Urgent Blood Request Nearby';
        $body  = sprintf(
            '[%s] Need %s blood (%d unit%s) at %s. Tap to respond.',
            $bloodRequest->token,
            $bloodRequest->blood_group,
            (int) $bloodRequest->unit,
            ((int) $bloodRequest->unit) === 1 ? '' : 's',
            $bloodRequest->hospital_name
        );

        $data = [
            'type'             => 'blood_request',
            'request_id'       => (string) $bloodRequest->id,
            'request_token'    => (string) $bloodRequest->token,
            'blood_group'      => (string) $bloodRequest->blood_group,
            'unit'             => (string) $bloodRequest->unit,
            'hospital_name'    => (string) $bloodRequest->hospital_name,
            'address'          => (string) $bloodRequest->address,
            'patient_latitude' => (string) $lat,
            'patient_longitude'=> (string) $lng,
            'urgency'          => (string) ($bloodRequest->urgency ?? 'normal'),
        ];

        $fcm    = app(FcmService::class);
        $result = $fcm->sendToMany($tokens, $title, $body, $data);

        // Garbage-collect invalid/unregistered tokens
        if (!empty($result['invalid_tokens'])) {
            FcmToken::whereIn('fcm_token', $result['invalid_tokens'])->delete();
        }

        Log::info('FCM notify nearby donors', [
            'request_id' => $bloodRequest->id,
            'tokens'     => count($tokens),
            'success'    => $result['success'],
            'failure'    => $result['failure'],
            'invalid'    => count($result['invalid_tokens']),
        ]);
    }

    public function myBloodDonation(){
        $data = BloodRequestResponse::where('donor_id', Auth::id())
        ->with('request')
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'data' => $data
    ]);
    }
}