<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or password'
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();
        // Role-based redirect
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('profile');
        // return match (Auth::user()->role) {
        //     'admin'     => redirect()->route('admin.dashboard'),
        //     'donor'     => redirect()->route('donor.dashboard'),
        //     'receiver'  => redirect()->route('receiver.dashboard'),
        //     'volunteer' => redirect()->route('volunteer.dashboard'),
        //     default     => redirect()->route('home'),
        // };
        // $user = Auth::user();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Login successful',
        //     'role' => $user->role,
        //     'user' => $user
        // ]);
    }
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home');
    }
}
