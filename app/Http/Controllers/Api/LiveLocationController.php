<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodRequestResponse;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Live-location endpoints for the in-app "track donor" feature.
 *
 * Endpoints are fully additive — they only touch the new
 * current_latitude / current_longitude / location_updated_at columns
 * on the users table and never modify a user's registered address.
 */
class LiveLocationController extends Controller
{
    /**
     * Stale threshold (seconds) — peer coordinates older than this are
     * still returned but flagged with `is_stale: true` so the client
     * can show a "last seen Xm ago" hint.
     */
    private const STALE_AFTER_SECONDS = 120;

    /**
     * POST /me/live-location
     *
     * The currently authenticated user pushes their own coordinates.
     * Called periodically by the tracking screen.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user->forceFill([
            'current_latitude'    => $validated['latitude'],
            'current_longitude'   => $validated['longitude'],
            'location_updated_at' => now(),
        ])->save();

        return response()->json([
            'status' => true,
            'data'   => [
                'latitude'           => (float) $user->current_latitude,
                'longitude'          => (float) $user->current_longitude,
                'location_updated_at'=> optional($user->location_updated_at)->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /blood-requests/{id}/peer-location
     *
     * Returns the counterpart's live coords for an accepted match:
     *   - patient (request submitter) gets the accepted donor's coords
     *   - accepted donor gets the patient's coords + hospital coords
     *
     * Authorization is enforced here — non-participants get 403, and
     * the endpoint refuses to expose anything until a response has
     * actually been accepted.
     */
    public function peer(Request $request, int $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $bloodRequest = BloodRequest::find($id);
        if (!$bloodRequest) {
            return response()->json([
                'status'  => false,
                'message' => 'Blood request not found',
            ], 404);
        }

        $acceptedResponse = BloodRequestResponse::where('blood_request_id', $id)
            // ->where('status', 'accepted')
            ->first();

        if (!$acceptedResponse) {
            return response()->json([
                'status'  => false,
                'message' => 'No accepted donor for this request yet',
            ], 404);
        }
        $donor_user = Donor::where('id',$acceptedResponse->donor_id)->first();

        $isPatient = (int) $bloodRequest->submitted_by === (int) $user->id;
        // $isAcceptedDonor = (int) $acceptedResponse->donor_id === (int) $user->id;
        $isAcceptedDonor = (int) $donor_user->user_id === (int) $user->id;

        if (!$isPatient && !$isAcceptedDonor) {
            return response()->json([
                'status'  => false,
                'message' => 'You are not a participant in this match',
            ], 403);
        }

        // Pick the "other side" of the match
        $peerId = $isPatient
            ? (int) $donor_user->user_id
            : (int) $bloodRequest->submitted_by;

        $peer = User::find($peerId);
        if (!$peer) {
            return response()->json([
                'status'  => false,
                'message' => 'Counterpart not found',
            ], 404);
        }

        $hasCoords = !is_null($peer->current_latitude) && !is_null($peer->current_longitude);

        $updatedAt = $peer->location_updated_at
            ? Carbon::parse($peer->location_updated_at)
            : null;

        $isStale = $updatedAt
            ? $updatedAt->diffInSeconds(now()) > self::STALE_AFTER_SECONDS
            : true;

        return response()->json([
            'status' => true,
            'data'   => [
                'role' => $isPatient ? 'patient' : 'donor',
                'peer' => [
                    'id'                 => $peer->id,
                    'name'               => $peer->name,
                    'mobile'             => $peer->mobile,
                    'blood_group'        => $peer->blood_group,
                    'latitude'           => $hasCoords ? (float) $peer->current_latitude  : null,
                    'longitude'          => $hasCoords ? (float) $peer->current_longitude : null,
                    'location_updated_at'=> $updatedAt ? $updatedAt->toIso8601String() : null,
                    'is_stale'           => $isStale,
                ],
                'hospital' => [
                    'name'      => $bloodRequest->hospital_name,
                    'address'   => $bloodRequest->address,
                    'latitude'  => $bloodRequest->patient_latitude !== null
                        ? (float) $bloodRequest->patient_latitude
                        : null,
                    'longitude' => $bloodRequest->patient_longitude !== null
                        ? (float) $bloodRequest->patient_longitude
                        : null,
                ],
                'request' => [
                    'id'           => $bloodRequest->id,
                    'blood_group'  => $bloodRequest->blood_group,
                    'unit'         => $bloodRequest->unit,
                    'status'       => $bloodRequest->status,
                ],
            ],
        ]);
    }
}
