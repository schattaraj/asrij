<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\BloodRequestResponse;
use App\Models\Donor;
use App\Models\FcmToken;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class RespondOnRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer',
            'contact_number' => 'required|string|max:20',
        ]);
        try {
            $user = Auth::user();
            $donorId = null;
            $roles = is_array($user->roles) ? $user->roles : json_decode($user->roles, true);
            if (!in_array('donor', $roles ?? [])) {
                return response()->json([
                    'status' => false,
                    'message' => 'You need to register as a donor first.'
                ], 403);
            }
            $donor = Donor::where('user_id',Auth::id())->first();
            $donorId = $donor->id;
            if($donorId == null){
                return response()->json([
                    'status' => false,
                    'message' => 'You need to register as a donor first.'
                ], 403);
            }
            $bloodRequest = BloodRequest::find($validated['request_id']);

            if (!$bloodRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Blood request not found.'
                ], 404);
            }
            if ($bloodRequest->submitted_by == Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You cannot respond to your own blood request.'
                ], 409);
            }
            $existingResponse = BloodRequestResponse::where('donor_id', $donorId)
                ->where('blood_request_id', $validated['request_id'])
                ->first();

            if ($existingResponse) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already responded to this blood request.'
                ], 409);
            }

            // ── Validation 2: block if the donor has a still-active (not expired)
            //    pending response on another blood request ─────────────────────
            $pendingResponses = BloodRequestResponse::where('donor_id', $donorId)
                ->where('blood_request_id', '!=', $validated['request_id'])
                ->where('status', 'pending')
                ->with('request')
                ->get();

            foreach ($pendingResponses as $pending) {
                $pendingRequest = $pending->request;
                if (!$pendingRequest) {
                    continue;
                }

                // Expiry = created_at + required_before window (days/hours).
                $expiresAt = null;
                if ($pendingRequest->required_before && $pendingRequest->required_before_unit) {
                    $expiresAt = $pendingRequest->required_before_unit === 'days'
                        ? $pendingRequest->created_at->copy()->addDays((int) $pendingRequest->required_before)
                        : $pendingRequest->created_at->copy()->addHours((int) $pendingRequest->required_before);
                }

                // No expiry defined or still in the future => request is active.
                if ($expiresAt === null || $expiresAt->isFuture()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You already have a pending blood donation request. Please complete or wait for it to expire before responding to another request.'
                    ], 409);
                }
            }

            // ── Validation 3: 90-day donation cooldown ───────────────────────
            if ($donor->last_donation) {
                $daysSince = Carbon::parse($donor->last_donation)->startOfDay()
                    ->diffInDays(Carbon::now()->startOfDay());

                if ($daysSince < 90) {
                    $remainingDays = 90 - $daysSince;

                    return response()->json([
                        'status' => false,
                        'message' => "You are not eligible to donate yet. You can donate again after {$remainingDays} days."
                    ], 409);
                }
            }

            $createRespond = BloodRequestResponse::create([
                'donor_id' => $donorId,
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
        $donor = Donor::where('user_id',Auth::id())->first();
        $reponded = BloodRequestResponse::where('donor_id', $donor->id)
            ->get();
        return response()->json([
            'status' => true,
            'data' => $reponded
        ]);
    }
    public function fetchResponses()
    {
        $data = BloodRequest::where('submitted_by', Auth::id())
            ->with([
                'responses',
                'donors.user',
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
            'donor_id' => 'required|exists:donors,id',
            'action' => 'required|in:accepted,rejected,reached_hospital,donated,patient_confirmed'
        ]);
        $user = Auth::user();

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
            // if ($validated['action'] === 'accepted') {

            //     // Accept selected donor
            //     $response->update([
            //         'status' => 'accepted'
            //     ]);

            //     // Capture which other donors are about to be auto-rejected
            //     $alsoRejectedDonorIds = BloodRequestResponse::where('blood_request_id', $validated['request_id'])
            //         ->where('donor_id', '!=', $validated['donor_id'])
            //         ->where('status', '!=', 'rejected')
            //         ->pluck('donor_id')
            //         ->all();

            //     // Reject all other donors
            //     BloodRequestResponse::where('blood_request_id', $validated['request_id'])
            //         ->where('donor_id', '!=', $validated['donor_id'])
            //         ->update([
            //             'status' => 'rejected'
            //         ]);

            //     // Update main request
            //     BloodRequest::where('id', $validated['request_id'])
            //         ->update([
            //             'status' => 'matched' // or 'fulfilled'
            //         ]);
            // }

            // // ❌ REJECT DONOR
            // if ($validated['action'] === 'rejected') {
            //     $response->update([
            //         'status' => 'rejected'
            //     ]);
            // }
            switch ($validated['action']) {

                /*
            |--------------------------------------------------------------------------
            | Patient accepts donor
            |--------------------------------------------------------------------------
            */
                case 'accepted':

                    $response->update([
                        'status' => 'accepted'
                    ]);

                    $alsoRejectedDonorIds = BloodRequestResponse::where(
                        'blood_request_id',
                        $validated['request_id']
                    )
                        ->where('donor_id', '!=', $validated['donor_id'])
                        ->where('status', '!=', 'rejected')
                        ->pluck('donor_id')
                        ->all();

                    BloodRequestResponse::where(
                        'blood_request_id',
                        $validated['request_id']
                    )
                        ->where('donor_id', '!=', $validated['donor_id'])
                        ->update([
                            'status' => 'rejected'
                        ]);

                    BloodRequest::where('id', $validated['request_id'])
                        ->update([
                            'status' => 'matched'
                        ]);

                    break;

                /*
            |--------------------------------------------------------------------------
            | Patient rejects donor
            |--------------------------------------------------------------------------
            */
                case 'rejected':

                    $response->update([
                        'status' => 'rejected'
                    ]);

                    break;

                /*
            |--------------------------------------------------------------------------
            | Donor reached hospital
            |--------------------------------------------------------------------------
            */
                case 'reached_hospital':

                    if ($response->status !== 'accepted') {
                        throw new \Exception(
                            'Donor can mark reached hospital only after acceptance.'
                        );
                    }

                    $response->update([
                        'status' => 'reached_hospital'
                    ]);
                $this->notifyPatientOfResponse($response, $user,'reached_hospital');
                    break;

                /*
            |--------------------------------------------------------------------------
            | Donor donated blood
            |--------------------------------------------------------------------------
            */
                case 'donated':

                    if (!in_array($response->status, [
                        'reached_hospital',
                        'donated'
                    ])) {
                        throw new \Exception(
                            'Donor must reach hospital before donation.'
                        );
                    }

                    $response->update([
                        'status' => 'donated'
                    ]);

                    BloodRequest::where('id', $validated['request_id'])
                        ->update([
                            'status' => 'awaiting_patient_confirmation'
                        ]);
                    // Notify the patient that the donor donated so they can confirm.
                    // $user is the donor performing this action.
                    $this->notifyPatientOfResponse($response, $user, 'donated');
                    break;

                case 'patient_confirmed':
                    $response->update([
                        'status' => 'patient_confirmed'
                    ]);
                    BloodRequest::where('id', $validated['request_id'])
                        ->update([
                            'status' => 'completed'
                        ]);

                    // Record the donation date on the donor's profile now that the
                    // patient has confirmed receiving the blood. donor_id here is a
                    // donors.id (the value stored when the response was created in
                    // store()), so the donor row is resolved by its primary key.
                    Donor::where('id', $validated['donor_id'])
                        ->update([
                            'last_donation' => now()->toDateString()
                        ]);

                    // Donor is notified after commit via notifyDonorOfDecision('patient_confirmed').
                    break;
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
        $validator = Validator::make(['response_id' => $id], [
            'response_id' => 'required|integer|exists:blood_request_responses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $response = BloodRequestResponse::where('id', $id)
                ->where('donor_id', Auth::id())
                ->lockForUpdate()
                ->first();

            if (!$response) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Response not found'
                ], 404);
            }

            if ($response->status !== 'pending') {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Only pending responses can be cancelled'
                ], 409);
            }

            $response->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Response cancelled'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to cancel blood request response', [
                'response_id' => $id,
                'donor_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel response'
            ], 500);
        }
    }

    /**
     * Push a notification to the patient (the user who submitted the
     * blood request) telling them that a donor has responded.
     * Fully additive — never throws, never changes the API response.
     */
    private function notifyPatientOfResponse(BloodRequestResponse $response, $donor, string $action = 'respond'): void
    {
        if (!in_array($action, ['respond', 'reached_hospital', 'donated', 'completed'], true)) {
            return;
        }

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
        if ($action == "reached_hospital") {
            $title = 'Donor Reached the Hospital';
            $body  = trim(sprintf(
                '%s has arrived to donate %s%s. Tap to view details.',
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
        }
        if ($action == "donated") {
            $title = 'Donor Donated';
            $body  = trim(sprintf(
                '%s has donated %s%s. Please confirm once you receive the blood.',
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
        }
        if($action == "completed"){
           $title = 'Your donation completed';
            $body  = trim(sprintf(
                'Patient Confirm your donation.',
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
        }
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
        if (!in_array($action, ['accepted', 'rejected', 'patient_confirmed'], true)) {
            return;
        }

        $bloodRequest = BloodRequest::find($requestId);
        if (!$bloodRequest) {
            return;
        }

        // $donorId is a donors.id; FCM tokens are keyed by users.id, so
        // resolve the owning user before looking up tokens.
        $userId = Donor::where('id', $donorId)->value('user_id');
        if (!$userId) {
            return;
        }

        $tokens = FcmToken::where('user_id', $userId)
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
        } elseif ($action === 'patient_confirmed') {
            $title = 'Donation Confirmed';
            $body  = trim(sprintf(
                'The patient has confirmed receiving your %s donation%s. Thank you for saving a life!',
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
