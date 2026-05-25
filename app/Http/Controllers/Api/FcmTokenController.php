<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    /**
     * Save or update the FCM token for the authenticated user.
     *
     * One token per user (mobile devices typically replace the token
     * on reinstall / clear data). If the same physical token is sent
     * by a different user account, we re-associate it.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fcm_token' => 'required|string|max:4096',
            'platform'  => 'nullable|string|max:20', // android | ios | web (optional)
        ]);

        $userId = Auth::id();

        // 1) If this exact token is on a stale row for another user, reassign it.
        FcmToken::where('fcm_token', $data['fcm_token'])
            ->where('user_id', '!=', $userId)
            ->delete();

        // 2) Upsert by user_id (one current token per user).
        $row = FcmToken::updateOrCreate(
            ['user_id'   => $userId],
            ['fcm_token' => $data['fcm_token']]
        );

        return response()->json([
            'status'  => true,
            'message' => 'FCM token saved successfully.',
            'data'    => $row,
        ], 200);
    }

    /**
     * Remove the FCM token (called on logout or token invalidation).
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'fcm_token' => 'nullable|string|max:4096',
        ]);

        $userId = Auth::id();

        $q = FcmToken::where('user_id', $userId);

        if ($request->filled('fcm_token')) {
            $q->where('fcm_token', $request->fcm_token);
        }

        $deleted = $q->delete();

        return response()->json([
            'status'  => true,
            'message' => 'FCM token removed.',
            'deleted' => $deleted,
        ], 200);
    }
}
