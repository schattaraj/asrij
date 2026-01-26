<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {
        return view('home');
    }

    public function registration() {
        return view('registration');
    }

    public function login() {
        return view('login');
    }

    public function dashboard() {
        return view('dashboard');
    }

    public function conversation() {
        return view('conversation');
    }
    public function profile()
    {
        $user = auth()->user();
        $data = ['user' => $user];
        if ($user->role === 'donor') {
            $data['donor'] = $user->donor;
        }
    
        if ($user->role === 'receiver') {
            $data['receiver'] = $user->receiver;
        }
    
        if ($user->role === 'volunteer') {
            $data['volunteer'] = $user->volunteer;
            $data['extra'] = json_decode($user->volunteer->extra_data ?? '{}', true);
        }
    
        return view('profile.index', $data);
    }
    
}
