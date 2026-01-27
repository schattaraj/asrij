<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email',
            'amount'          => 'required|numeric|min:1',
            'donation_type'   => 'required|string',
            'message'         => 'nullable|string',
        ]);

        // Save to database OR send to payment gateway here
        // Donation::create($validated);

        return back()->with('success', 'Thank you for your donation!');
    }
}
