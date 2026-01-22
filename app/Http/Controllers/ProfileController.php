<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);
        // If validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password'); // ✅ preserve tab
        }
        
        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->with('error', 'Current password is incorrect.')
                ->with('active_tab', 'password');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()
            ->with('success', 'Password updated successfully!')
            ->with('active_tab', 'password');
    }
}
