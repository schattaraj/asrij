<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    const MAX_CONTACTS = 3;

    public function index(Request $request)
    {
        $contacts = EmergencyContact::where('user_id', $request->user()->id)
            ->orderBy('position')
            ->orderBy('id')
            ->get(['id', 'name', 'phone', 'position']);

        return response()->json([
            'status' => true,
            'data' => $contacts,
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $count = EmergencyContact::where('user_id', $userId)->count();
        if ($count >= self::MAX_CONTACTS) {
            return response()->json([
                'status' => false,
                'message' => 'You can save up to ' . self::MAX_CONTACTS . ' emergency contacts.',
            ], 422);
        }

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
        ]);

        $contact = EmergencyContact::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'position' => $count,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Emergency contact added',
            'data' => $contact->only(['id', 'name', 'phone', 'position']),
        ], 201);
    }

    public function destroy(Request $request, int $id)
    {
        $contact = EmergencyContact::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$contact) {
            return response()->json([
                'status' => false,
                'message' => 'Contact not found',
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'status' => true,
            'message' => 'Emergency contact removed',
        ]);
    }
}
