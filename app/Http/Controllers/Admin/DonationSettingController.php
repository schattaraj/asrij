<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationSettingController extends Controller
{
    /**
     * Show the form to manage the donation QR code.
     */
    public function edit()
    {
        $qr = Setting::get('donation_qr');
        return view('admin.donation-settings.edit', compact('qr'));
    }

    /**
     * Update the donation QR code image.
     */
    public function update(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Delete the previous QR image if one exists.
        $old = Setting::get('donation_qr');
        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        $file = $request->file('qr_image');
        $name = 'donation-qr_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/settings', $name, 'public');

        Setting::set('donation_qr', $path);

        return back()->with('success', 'Donation QR code updated successfully.');
    }
}
