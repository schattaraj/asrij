<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $user = null;
        $token = $request->bearerToken();
        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            $user = $accessToken?->tokenable;
        }
        $requests = BloodRequest::latest()->get();

        if ($user) {
            $userLat = $user->latitude;
            $userLng = $user->longitude;
    
            $requests = $requests->map(function ($req) use ($userLat, $userLng) {
                $distance = $this->calculateDistance(
                    $userLat,
                    $userLng,
                    $req->patient_latitude,
                    $req->patient_longitude
                );
                $req->setAttribute('distance', $distance);
                $req->setAttribute('distance_text', $this->formatDistance($distance));
    
                return $req;
            });
        }
        $requests->makeHidden([
            'name', 'mobile', 'request_for',
            'submitted_by', 'created_at', 'updated_at'
        ]);
    
        return response()->json([
            'status' => true,
            'data' => $requests
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'blood_group' => 'required|string|max:3',
            'hospital_name' => 'required|string|max:255',
            'unit' => 'required|integer',
            'patient_type' => 'required|string|max:50',
            'mobile' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'pin_code' => 'required|string|max:10',
            'email' => 'nullable|email|max:255',
            'required_before' => 'required|integer|min:1',
            'required_before_unit' => 'required|in:hours,days',
            'patient_latitude' => 'nullable|string|max:20',
            'patient_longitude' => 'nullable|string|max:20',
            'prescription' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'request_for' => 'required|string|max:255',
        ]);
        $validated['request_for'] = $request->has('request_for') ? 'self' : 'other';
        $validated['submitted_by'] = auth()->id();

        if ($request->hasFile('prescription')) {
            $file = $request->file('prescription');
            $path = $file->store('prescriptions', 'public'); // storage/app/public/prescriptions
            $validated['prescription'] = $path;
        }
    
        // $bloodRequest = new BloodRequest;
        // $bloodRequest->name = $validated['name'];
        // $bloodRequest->blood_group = $validated['blood_group'];
        // $bloodRequest->hospital_name = $validated['hospital_name'];
        // $bloodRequest->unit = $validated['unit'];
        // $bloodRequest->patient_type = $validated['patient_type'];
        // $bloodRequest->mobile = $validated['mobile'];
        // $bloodRequest->whatsapp_number = $validated['whatsapp_number'] ?? null;
        // $bloodRequest->address = $validated['address'];
        // $bloodRequest->pin_code = $validated['pin_code'];
        // $bloodRequest->email = $validated['email'] ?? null;
        // $bloodRequest->request_for = $validated['request_for'];
        // $bloodRequest->submitted_by = $validated['submitted_by'];
        // $bloodRequest->required_before = $validated['required_before'];
        // $bloodRequest->required_before_unit = $validated['required_before_unit'];
        // $bloodRequest->patient_latitude = $validated['patient_latitude'] ?? null;
        // $bloodRequest->patient_longitude = $validated['patient_longitude'] ?? null;
        DB::beginTransaction();
        try {
            $userCreate = null;
    
            if ($validated['request_for'] === 'other') {
    
                $userExist = User::where('mobile', $validated['mobile'])->first();
    
                if ($userExist) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User with this mobile number already exist.'
                    ], 404);
                }
    
                $userCreate = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'mobile' => $validated['mobile'],
                    'pin_code' => $validated['pin_code'],
                    'status' => 'pending',
                    'blood_group' => $validated['blood_group'],
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
            }
    
            // Create Blood Request
            $bloodRequest = BloodRequest::create($validated);
    
            if (!$bloodRequest) {
                throw new \Exception('Blood request creation failed');
            }
    
            DB::commit();
    
            return response()->json($bloodRequest, 201);
    
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
}