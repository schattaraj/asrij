<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'location' => 'required|string',
        'latitude' => 'required',
        'longitude' => 'required',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048',
    ]);

    // Save to DB or process emergency request
    // $request->location
    // $request->latitude
    // $request->longitude

    return back()->with('success', 'Emergency request submitted successfully.');
}

}
