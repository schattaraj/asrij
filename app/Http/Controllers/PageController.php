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

}
