<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\DonorPreference;
use App\Models\EmergencyContact;
use App\Models\ReceiverPreference;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Default values mirrored from the React Native client.
     * Kept here so the API always returns a complete object even
     * before the user has explicitly saved any prefs.
     */
    private function defaults(): array
    {
        return [
            'notifications' => [
                'urgentPush' => true,
                'urgentSms' => true,
                'normalPush' => true,
                'responsePush' => true,
                'responseSms' => true,
                'responseEmail' => true,
                'cooldownPush' => true,
                'cooldownEmail' => true,
                'blooddrivePush' => true,
                'blooddriveEmail' => true,
                'newsPush' => false,
                'newsEmail' => false,
                'quietHours' => false,
            ],
            'privacy' => [
                'visibleInSearch' => true,
                'shareExactLocation' => false,
                'showLastDonation' => true,
                'showPhone' => 'after_respond',
            ],
            'security' => [
                'appLock' => false,
                'autoLock' => '5m',
            ],
            'appearance' => [
                'theme' => 'system',
                'textSize' => 'normal',
                'highContrast' => false,
            ],
            'language' => 'en',
            'medical_info' => '',
            'donor' => [
                'available_to_donate' => true,
                'search_radius_km' => 15,
                'auto_respond' => 'any',
                'last_donation' => null,
            ],
            'receiver' => [
                'default_hospital' => '',
            ],
            'emergency_contacts' => [],
        ];
    }

    /**
     * GET /settings — single call to hydrate the Settings hub.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $defaults = $this->defaults();

        $userSettings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'notifications' => $defaults['notifications'],
                'privacy' => $defaults['privacy'],
                'security' => $defaults['security'],
                'appearance' => $defaults['appearance'],
                'language' => $defaults['language'],
                'medical_info' => $defaults['medical_info'],
            ]
        );

        $donorPref = DonorPreference::firstOrNew(['user_id' => $user->id]);
        $donor = Donor::where('user_id', $user->id)->first();

        $receiverPref = ReceiverPreference::firstOrNew(['user_id' => $user->id]);

        $contacts = EmergencyContact::where('user_id', $user->id)
            ->orderBy('position')
            ->orderBy('id')
            ->get(['id', 'name', 'phone', 'position']);

        return response()->json([
            'status' => true,
            'data' => [
                'notifications' => $userSettings->notifications ?: $defaults['notifications'],
                'privacy' => $userSettings->privacy ?: $defaults['privacy'],
                'security' => $userSettings->security ?: $defaults['security'],
                'appearance' => $userSettings->appearance ?: $defaults['appearance'],
                'language' => $userSettings->language ?: $defaults['language'],
                'medical_info' => $userSettings->medical_info ?? '',
                'donor' => [
                    'available_to_donate' => $donorPref->exists ? (bool) $donorPref->available_to_donate : $defaults['donor']['available_to_donate'],
                    'search_radius_km' => $donorPref->exists ? (int) $donorPref->search_radius_km : $defaults['donor']['search_radius_km'],
                    'auto_respond' => $donorPref->exists ? $donorPref->auto_respond : $defaults['donor']['auto_respond'],
                    'last_donation' => $donor?->last_donation?->format('Y-m-d'),
                ],
                'receiver' => [
                    'default_hospital' => $receiverPref->default_hospital ?? '',
                ],
                'emergency_contacts' => $contacts,
            ],
        ]);
    }

    public function updateNotifications(Request $request)
    {
        $data = $request->validate([
            'urgentPush' => 'sometimes|boolean',
            'urgentSms' => 'sometimes|boolean',
            'normalPush' => 'sometimes|boolean',
            'responsePush' => 'sometimes|boolean',
            'responseSms' => 'sometimes|boolean',
            'responseEmail' => 'sometimes|boolean',
            'cooldownPush' => 'sometimes|boolean',
            'cooldownEmail' => 'sometimes|boolean',
            'blooddrivePush' => 'sometimes|boolean',
            'blooddriveEmail' => 'sometimes|boolean',
            'newsPush' => 'sometimes|boolean',
            'newsEmail' => 'sometimes|boolean',
            'quietHours' => 'sometimes|boolean',
        ]);

        return $this->mergeJson($request->user()->id, 'notifications', $data);
    }

    public function updatePrivacy(Request $request)
    {
        $data = $request->validate([
            'visibleInSearch' => 'sometimes|boolean',
            'shareExactLocation' => 'sometimes|boolean',
            'showLastDonation' => 'sometimes|boolean',
            'showPhone' => 'sometimes|in:always,after_respond,never',
        ]);

        return $this->mergeJson($request->user()->id, 'privacy', $data);
    }

    public function updateSecurity(Request $request)
    {
        $data = $request->validate([
            'appLock' => 'sometimes|boolean',
            'autoLock' => 'sometimes|in:immediately,1m,5m,15m,never',
        ]);

        return $this->mergeJson($request->user()->id, 'security', $data);
    }

    public function updateAppearance(Request $request)
    {
        $data = $request->validate([
            'theme' => 'sometimes|in:system,light,dark',
            'textSize' => 'sometimes|in:small,normal,large',
            'highContrast' => 'sometimes|boolean',
        ]);

        return $this->mergeJson($request->user()->id, 'appearance', $data);
    }

    public function updateLanguage(Request $request)
    {
        $data = $request->validate([
            'language' => 'required|string|max:5',
        ]);

        UserSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['language' => $data['language']]
        );

        return response()->json([
            'status' => true,
            'message' => 'Language updated',
            'data' => ['language' => $data['language']],
        ]);
    }

    public function updateMedicalInfo(Request $request)
    {
        $data = $request->validate([
            'medical_info' => 'nullable|string|max:2000',
        ]);

        UserSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['medical_info' => $data['medical_info'] ?? '']
        );

        return response()->json([
            'status' => true,
            'message' => 'Medical info updated',
            'data' => ['medical_info' => $data['medical_info'] ?? ''],
        ]);
    }

    public function updateDonor(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles ?? [];

        if (!in_array('donor', is_array($roles) ? $roles : [])) {
            return response()->json([
                'status' => false,
                'message' => 'You need to register as a donor first.',
            ], 403);
        }

        $data = $request->validate([
            'available_to_donate' => 'sometimes|boolean',
            'search_radius_km' => 'sometimes|integer|min:1|max:200',
            'auto_respond' => 'sometimes|in:urgent_city,hospitals_only,any',
            'last_donation' => 'sometimes|nullable|date|before_or_equal:today',
        ]);

        $prefPayload = collect($data)
            ->only(['available_to_donate', 'search_radius_km', 'auto_respond'])
            ->all();

        if (!empty($prefPayload)) {
            DonorPreference::updateOrCreate(
                ['user_id' => $user->id],
                $prefPayload
            );
        }

        if (array_key_exists('last_donation', $data)) {
            // last_donation lives on the existing donors table
            Donor::where('user_id', $user->id)->update([
                'last_donation' => $data['last_donation'],
            ]);
        }

        $pref = DonorPreference::where('user_id', $user->id)->first();
        $donor = Donor::where('user_id', $user->id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Donor preferences updated',
            'data' => [
                'available_to_donate' => (bool) $pref?->available_to_donate,
                'search_radius_km' => (int) ($pref?->search_radius_km ?? 15),
                'auto_respond' => $pref?->auto_respond ?? 'any',
                'last_donation' => $donor?->last_donation?->format('Y-m-d'),
            ],
        ]);
    }

    public function updateReceiver(Request $request)
    {
        $data = $request->validate([
            'default_hospital' => 'nullable|string|max:255',
        ]);

        ReceiverPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['default_hospital' => $data['default_hospital'] ?? null]
        );

        return response()->json([
            'status' => true,
            'message' => 'Receiver preferences updated',
            'data' => ['default_hospital' => $data['default_hospital'] ?? ''],
        ]);
    }

    /**
     * Merge a partial JSON payload into the existing column,
     * so the client can PATCH a single toggle without sending the whole group.
     */
    private function mergeJson(int $userId, string $column, array $patch)
    {
        $row = UserSetting::firstOrNew(['user_id' => $userId]);
        $existing = is_array($row->{$column}) ? $row->{$column} : [];
        $row->{$column} = array_merge($existing, $patch);
        $row->save();

        return response()->json([
            'status' => true,
            'message' => ucfirst($column) . ' updated',
            'data' => $row->{$column},
        ]);
    }
}
