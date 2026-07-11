<?php

namespace App\Http\Controllers;

use App\Models\DonationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationPaymentController extends Controller
{
    /**
     * Store a QR-based donation along with the transaction screenshot.
     */
    public function store(Request $request)
    {
        // Normalise PAN to uppercase before validating its format.
        if ($request->filled('pan_no')) {
            $request->merge(['pan_no' => strtoupper(trim($request->pan_no))]);
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'mobile'     => 'required|digits_between:10,15',
            'email'      => 'required|email|max:255',
            'pan_no'     => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'address'    => 'required|string|max:1000',
            'amount'     => 'required|numeric|min:1',
            'screenshot' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ], [
            'pan_no.regex' => 'Please enter a valid PAN number (e.g. ABCDE1234F).',
        ]);

        // Store the transaction screenshot on the public disk.
        $path = $request->file('screenshot')->store('donation-screenshots', 'public');

        DonationPayment::create([
            'user_id'    => Auth::id(),
            'name'       => $validated['name'],
            'mobile'     => $validated['mobile'],
            'email'      => $validated['email'],
            'pan_no'     => $validated['pan_no'],
            'address'    => $validated['address'],
            'amount'     => $validated['amount'],
            'screenshot' => $path,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Thank you for your donation! We have received your payment details and will verify them shortly.');
    }
}
