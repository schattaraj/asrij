<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerMember;
use App\Models\VolunteerOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function ourOrganization(){
        $organization = VolunteerOrganization::with([
        'members.user',
        'creator'
    ])->findOrFail(3);
    $users = User::orderBy('name')->get();
    return view('admin.our-organization.index',compact('organization','users'));
    }
    public function ourOrganizationUpdate(Request $request, VolunteerOrganization $organization){
    $validated = $request->validate([
        'organization_name' => 'required|string|max:255',
        'type' => 'nullable|string|max:100',
        'registration_number' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:20',
        'email' => 'nullable|email',
        'address' => 'nullable|string',
        'president_name' => 'nullable|string|max:255',
        'president_number' => 'nullable|string|max:20',
        'secretary_name' => 'nullable|string|max:255',
        'secretary_number' => 'nullable|string|max:20',
        'account_name' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:255',
    ]);
      DB::transaction(function () use ($request, $organization, $validated) {

        /*
        |--------------------------------------------------------------------------
        | Update Organization
        |--------------------------------------------------------------------------
        */
        $organization->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Update Existing Members
        |--------------------------------------------------------------------------
        */

        if ($request->has('members')) {

            foreach ($request->members as $memberId => $memberData) {

                $member = VolunteerMember::where(
                    'volunteer_organization_id',
                    $organization->id
                )->find($memberId);

                if (!$member) {
                    continue;
                }

                $member->update([
                    'position' => $memberData['position'] ?? null,
                    'last_donation' => $memberData['last_donation'] ?? null,
                    'is_available' => $memberData['is_available'] ?? 0,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Add New Member
        |--------------------------------------------------------------------------
        */

        if ($request->filled('new_member.user_id') ||
            $request->filled('new_member.name')) {

            VolunteerMember::create([
                'volunteer_organization_id' => $organization->id,

                // Existing user
                'user_id' => $request->input('new_member.user_id'),

                // Manual member
                // 'name' => $request->input('new_member.name'),
                // 'mobile' => $request->input('new_member.mobile'),
                // 'email' => $request->input('new_member.email'),
                // 'blood_group' => $request->input('new_member.blood_group'),

                // Member details
                'position' => $request->input('new_member.position'),
                'last_donation' => $request->input('new_member.last_donation'),
                'is_available' => $request->input('new_member.is_available', 1),
            ]);
        }
    });

    return redirect()
        ->route('admin.our-organization')
        ->with('success', 'Organization updated successfully.'.$validated['organization_name']);
    }
    public function destroyMember(VolunteerMember $member)
{
    $organization = $member->organization;

    $memberCount = $organization->members()->count();

    if (
        $memberCount === 1 &&
        $member->user_id === Auth::id()
    ) {
        return back()->with(
            'error',
            'You cannot remove yourself because you are the last member of this organization.'
        );
    }
if ($organization->members()->count() <= 1) {
    return back()->with(
        'error',
        'An organization must have at least one member.'
    );
}
    $member->delete();

    return back()->with(
        'success',
        'Member removed successfully.'
    );
}
}
