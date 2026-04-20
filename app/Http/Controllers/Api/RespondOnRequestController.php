<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\BloodRequestResponse;
use Illuminate\Support\Facades\DB;


class RespondOnRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer',
            'contact_number' => 'required|string|max:20',
        ]);
        try {
            $user = auth()->user();
            $roles = is_array($user->roles) ? $user->roles : json_decode($user->roles, true);
            if (!in_array('donor', $roles ?? [])) {
                return response()->json([
                    'status' => false,
                    'message' => 'You need to register as a donor first.'
                ], 403);
            }    
            $createRespond = BloodRequestResponse::create([
                'donor_id' => auth()->id(),
                'blood_request_id' => $validated['request_id'],
                'contact_number' => $validated['contact_number'],
                'status' => 'pending'
            ]);
            if (!$createRespond) {
                throw new \Exception('Failed to send the response');
            }
            return response()->json([
                'status' => true,
                'message' => "Thank you! Your response has been sent to the requester."
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $reponded = BloodRequestResponse::where('donor_id', auth()->id())
            ->get();
        return response()->json([
            'status' => true,
            'data' => $reponded
        ]);
    }
    public function fetchResponses()
    {
        $data = BloodRequest::where('submitted_by', auth()->id())
            ->with(['responses', 'donors'])
            ->get();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:blood_requests,id',
            'donor_id' => 'required|exists:users,id',
            'action' => 'required|in:accepted,rejected'
        ]);

        DB::beginTransaction();

        try {
            $response = BloodRequestResponse::where('blood_request_id', $validated['request_id'])
                ->where('donor_id', $validated['donor_id'])
                ->first();

            if (!$response) {
                throw new \Exception('Response not found');
            }

            // ✅ ACCEPT DONOR
            if ($validated['action'] === 'accepted') {

                // Accept selected donor
                $response->update([
                    'status' => 'accepted'
                ]);

                // Reject all other donors
                BloodRequestResponse::where('blood_request_id', $validated['request_id'])
                    ->where('donor_id', '!=', $validated['donor_id'])
                    ->update([
                        'status' => 'rejected'
                    ]);

                // Update main request
                BloodRequest::where('id', $validated['request_id'])
                    ->update([
                        'status' => 'fulfilled' // or 'fulfilled'
                    ]);
            }

            // ❌ REJECT DONOR
            if ($validated['action'] === 'rejected') {
                $response->update([
                    'status' => 'rejected'
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Response updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $response = BloodRequestResponse::where('id', $id)
            ->where('donor_id', auth()->id())
            ->first();

        if (!$response) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }

        $response->delete();

        return response()->json([
            'status' => true,
            'message' => 'Response cancelled'
        ]);
    }
}
