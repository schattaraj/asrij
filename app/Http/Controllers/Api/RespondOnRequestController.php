<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\BloodRequestResponse;
use App\Models\FcmToken;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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

            // ── Push notify the patient (fail-safe, additive) ─────────────────
            try {
                $this->notifyPatientOfResponse($createRespond, $user);
            } catch (\Throwable $e) {
                Log::warning('notifyPatientOfResponse failed: ' . $e->getMessage());
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
            ->with(['responses',
            'donors',
            'submitter:id,name,mobile',
            'patient:id,name,mobile'
            ])
            ->latest()
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

            // IDs of donors whose status flips to "rejected" during this call,
            // so we can notify them after the transaction commits.
            $alsoRejectedDonorIds = [];

            // ✅ ACCEPT DONOR
            if ($validated['action'] === 'accepted') {

                // Accept selected donor
                $response->update([
                    'status' => 'accepted'
                ]);

                // Capture which other donors are about to be auto-rejected
                $alsoRejectedDonorIds = BloodRequestResponse::where('blood_request_id', $validated['request_id'])
                    ->where('donor_id', '!=', $validated['donor_id'])
                    ->where('status', '!=', 'rejected')
                    ->pluck('donor_id')
                    ->all();

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

            // ── Push notify the selected donor + any auto-rejected donors.
            //    Fully additive — never throws, never changes the API response.
            try {
                $this->notifyDonorOfDecision(
                    (int) $validated['request_id'],
                    (int) $validated['donor_id'],
                    $validated['action']
                );

                foreach ($alsoRejectedDonorIds as $rejectedDonorId) {
                    $this->notifyDonorOfDecision(
                        (int) $validated['request_id'],
                        (int) $rejectedDonorId,
                        'rejected'
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('notifyDonorOfDecision failed: ' . $e->getMessage());
            }

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

    /**
     * Push a notification to the patient (the user who submitted the
     * blood request) telling them that a donor has responded.
     * Fully additive — never throws, never changes the API response.
     */
    private function notifyPatientOfResponse(BloodRequestResponse $response, $donor): void
    {
        $bloodRequest = BloodRequest::find($response->blood_request_id);
        if (!$bloodRequest) {
            return;
        }

        $patientId = $bloodRequest->submitted_by;
        if (!$patientId) {
            return;
        }

        // Avoid self-notification (e.g. patient responding to their own request).
        if ((int) $patientId === (int) ($donor->id ?? 0)) {
            return;
        }

        $tokens = FcmToken::where('user_id', $patientId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($tokens)) {
            return;
        }

        $donorName = $donor->name ?? 'A donor';
        $bgroup    = $bloodRequest->blood_group ?: '';
        $hospital  = $bloodRequest->hospital_name ?: '';

        $title = 'New Donor Response';
        $body  = trim(sprintf(
            '%s has agreed to donate %s%s. Tap to view details.',
            $donorName,
            $bgroup ? $bgroup . ' blood' : 'blood',
            $hospital ? ' at ' . $hospital : ''
        ));

        $data = [
            'type'             => 'blood_request_response',
            'request_id'       => (string) $bloodRequest->id,
            'response_id'      => (string) $response->id,
            'donor_id'         => (string) ($donor->id ?? ''),
            'donor_name'       => (string) $donorName,
            'donor_contact'    => (string) ($response->contact_number ?? ''),
            'blood_group'      => (string) $bgroup,
            'hospital_name'    => (string) $hospital,
            'status'           => (string) ($response->status ?? 'pending'),
        ];

        $fcm    = app(FcmService::class);
        $result = $fcm->sendToMany($tokens, $title, $body, $data);

        if (!empty($result['invalid_tokens'])) {
            FcmToken::whereIn('fcm_token', $result['invalid_tokens'])->delete();
        }

        Log::info('FCM notify patient of donor response', [
            'request_id'  => $bloodRequest->id,
            'response_id' => $response->id,
            'patient_id'  => $patientId,
            'tokens'      => count($tokens),
            'success'     => $result['success'],
            'failure'     => $result['failure'],
        ]);
    }

    /**
     * Notify a donor that the patient has accepted or rejected their
     * response. Fully additive — never throws, never changes the API
     * response of the calling endpoint.
     */
    private function notifyDonorOfDecision(int $requestId, int $donorId, string $action): void
    {
        if (!in_array($action, ['accepted', 'rejected'], true)) {
            return;
        }

        $bloodRequest = BloodRequest::find($requestId);
        if (!$bloodRequest) {
            return;
        }

        $tokens = FcmToken::where('user_id', $donorId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($tokens)) {
            return;
        }

        $bgroup   = $bloodRequest->blood_group ?: '';
        $hospital = $bloodRequest->hospital_name ?: '';

        if ($action === 'accepted') {
            $title = 'Donation Accepted';
            $body  = trim(sprintf(
                'You have been selected to donate %s%s. Please contact the requester to coordinate.',
                $bgroup ? $bgroup . ' blood' : 'blood',
                $hospital ? ' at ' . $hospital : ''
            ));
        } else {
            $title = 'Donation Not Needed';
            $body  = trim(sprintf(
                'Thank you for offering to help. %s has already been fulfilled by another donor.',
                $hospital ? 'The request at ' . $hospital : 'This blood request'
            ));
        }

        $data = [
            'type'          => 'blood_request_decision',
            'request_id'    => (string) $bloodRequest->id,
            'donor_id'      => (string) $donorId,
            'status'        => $action,
            'blood_group'   => (string) $bgroup,
            'hospital_name' => (string) $hospital,
        ];

        $fcm    = app(FcmService::class);
        $result = $fcm->sendToMany($tokens, $title, $body, $data);

        if (!empty($result['invalid_tokens'])) {
            FcmToken::whereIn('fcm_token', $result['invalid_tokens'])->delete();
        }

        Log::info('FCM notify donor of decision', [
            'request_id' => $bloodRequest->id,
            'donor_id'   => $donorId,
            'action'     => $action,
            'tokens'     => count($tokens),
            'success'    => $result['success'],
            'failure'    => $result['failure'],
        ]);
    }
}
