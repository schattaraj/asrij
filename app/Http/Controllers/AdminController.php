<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $donors = User::where('role', 'donor')->get();
        $receivers = User::where('role', 'receiver')->get();
        $volunteers = User::where('role', 'volunteer')->get();
        $currentUser = Auth::user(); 
        /*
        |--------------------------------------------------------------------------
        | Registration Growth (This Month vs Last Month)
        |--------------------------------------------------------------------------
        */
        $currentStart = Carbon::now()->startOfMonth();
        $previousStart = Carbon::now()->subMonth()->startOfMonth();
        $previousEnd = Carbon::now()->subMonth()->endOfMonth();
        // Donors
        $currentDonors = User::where('role', 'donor')
            ->where('created_at', '>=', $currentStart)
            ->count();

        $previousDonors = User::where('role', 'donor')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        // Receivers
        $currentReceivers = User::where('role', 'receiver')
            ->where('created_at', '>=', $currentStart)
            ->count();

        $previousReceivers = User::where('role', 'receiver')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        // Volunteers
        $currentVolunteers = User::where('role', 'volunteer')
            ->where('created_at', '>=', $currentStart)
            ->count();

        $previousVolunteers = User::where('role', 'volunteer')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        // Percentage calculation
        $donorGrowth = $this->percentageIncrease($currentDonors, $previousDonors);
        $receiverGrowth = $this->percentageIncrease($currentReceivers, $previousReceivers);
        $volunteerGrowth = $this->percentageIncrease($currentVolunteers, $previousVolunteers);

        return view('admin.index', compact(
            'users',
            'currentUser',
            'donors',
            'receivers',
            'volunteers',
            'donorGrowth',
            'receiverGrowth',
            'volunteerGrowth'
        ));
    }
    /**
     * Calculate percentage increase safely
    */
    private function percentageIncrease($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
 // Update user status
 public function updateStatus(Request $request, $id)
 {
     $user = User::findOrFail($id);
     
     // Validate the new status
     $request->validate([
         'status' => 'required|in:pending,active,inactive',
     ]);

     // Update user status
     $user->status = $request->status;
     $user->save();

     return redirect()->route('admin.users.index')->with('success', 'User status updated successfully');
 }
    public function settings()
    {
        return view('admin.settings');  // Settings view
    }
}
