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

        $roles = $user->roles ?? [];

    if (in_array('donor', $roles)) {
        $data['donor'] = $user->donor;
    }

    if (in_array('receiver', $roles)) {
        $data['receiver'] = $user->receiver;
    }

    if (in_array('volunteer', $roles)) {
        $data['volunteer'] = $user->volunteer;
        $data['extra'] = $user->volunteer->extra_data ?? [];
    }

        return view('profile.index', $data);
    }
    public function bloodDonations(){
        return view('blood-donations.index');
    }
    public function bloodRequests(){
        return view('blood-requests.index');
    }
}
