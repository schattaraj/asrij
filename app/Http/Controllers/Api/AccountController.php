<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountDeletionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    const DELETION_GRACE_DAYS = 30;

    /**
     * POST /account/delete-request
     * Schedule the account for deletion in 30 days.
     */
    public function scheduleDeletion(Request $request)
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:500',
            'mobile_confirm' => 'required|string',
        ]);

        $user = $request->user();

        if (trim($data['mobile_confirm']) !== trim($user->mobile)) {
            return response()->json([
                'status' => false,
                'message' => 'The mobile number you typed does not match your account.',
            ], 422);
        }

        $existing = AccountDeletionRequest::where('user_id', $user->id)
            ->active()
            ->first();

        if ($existing) {
            return response()->json([
                'status' => true,
                'message' => 'Account is already scheduled for deletion.',
                'data' => [
                    'scheduled_for' => $existing->scheduled_for->format('Y-m-d'),
                ],
            ]);
        }

        $scheduledFor = Carbon::now()->addDays(self::DELETION_GRACE_DAYS)->toDateString();

        $deletion = AccountDeletionRequest::create([
            'user_id' => $user->id,
            'reason' => $data['reason'] ?? null,
            'scheduled_for' => $scheduledFor,
        ]);

        // Revoke all tokens so the user is signed out everywhere.
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Your account is scheduled for deletion. Log in within ' . self::DELETION_GRACE_DAYS . ' days to cancel.',
            'data' => [
                'scheduled_for' => $deletion->scheduled_for->format('Y-m-d'),
            ],
        ], 201);
    }

    /**
     * DELETE /account/delete-request — cancel the pending deletion.
     */
    public function cancelDeletion(Request $request)
    {
        $pending = AccountDeletionRequest::where('user_id', $request->user()->id)
            ->active()
            ->latest()
            ->first();

        if (!$pending) {
            return response()->json([
                'status' => false,
                'message' => 'No pending deletion to cancel.',
            ], 404);
        }

        $pending->cancelled_at = now();
        $pending->save();

        return response()->json([
            'status' => true,
            'message' => 'Account deletion cancelled.',
        ]);
    }

    /**
     * POST /account/data-export — queue a GDPR-style export request.
     * Stubbed: records the request; an async worker can process it later.
     */
    public function requestDataExport(Request $request)
    {
        $user = $request->user();

        // No dedicated table yet — log it for now so the request is auditable.
        \Log::info('Data export requested', [
            'user_id' => $user->id,
            'mobile' => $user->mobile,
            'requested_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data export requested. A copy of your data will be emailed within 30 days.',
        ]);
    }
}
