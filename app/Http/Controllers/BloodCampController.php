<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BloodCampController extends Controller
{
    function index()
    {
        $user = auth()->user();
        $data = ['user' => $user];
        return view('blood-camp.index',$data);
    }
}
