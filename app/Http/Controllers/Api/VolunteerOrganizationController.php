<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerMember;
use Illuminate\Http\Request;
use App\Models\VolunteerOrganization;
use Illuminate\Support\Facades\DB;

class VolunteerOrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            VolunteerOrganization::with('members')->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:volunteer_organizations',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email:rfc,dns',
            'address' => 'nullable|string',
            'president_name' => 'nullable|string|max:255',
            'president_number' => 'nullable|string|max:20',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_number' => 'nullable|string|max:20',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

     DB::beginTransaction();

        try {

            $organization = VolunteerOrganization::create([
                ...$validated,
                'created_by' => $request->user()->id,
            ]);

            $user = $request->user();
            $roles = $user->roles ?? [];
            
            VolunteerMember::create([
                'user_id' => $user->id,
                'volunteer_organization_id' => $organization->id,
                'position' => 'admin',
                'is_available' => true,
            ]);
            if (!in_array('volunteer', $roles)) {
                $roles[] = 'volunteer';
            }
            $user->update([
                'roles' => $roles,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Organization created successfully.',
                'organization' => $organization,
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = VolunteerOrganization::with('members')->findOrFail($id);

        return response()->json($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $organization = VolunteerOrganization::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|string|max:255',
            'organization_name' => 'sometimes|string|max:255',
            'registration_number' => 'sometimes|string|unique:volunteer_organizations,registration_number,' . $id,
            'contact_number' => 'sometimes|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'president_name' => 'nullable|string|max:255',
            'president_number' => 'nullable|string|max:20',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_number' => 'nullable|string|max:20',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        $organization->update($validated);

        return response()->json([
            'message' => 'Organization updated successfully',
            'data' => $organization
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = VolunteerOrganization::findOrFail($id);

        $organization->delete();

        return response()->json([
            'message' => 'Organization deleted successfully'
        ]);
    }
}
