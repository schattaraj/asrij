<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BloodCamp;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function home() {
            $banners = Banner::where('status', 1)
        ->orderBy('sort_order')
        ->get();
        $blood_camps = BloodCamp::where('status', 1)
        ->latest('camp_date')
        ->get();
        $testimonials = Testimonial::where('status', 1)
        ->orderBy('sort_order')
        ->get();
        $units_donated = BloodRequest::where('status','completed')->count();
        $donors = Donor::get()->count();
        $receivers =  BloodRequest::where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');
        $volunteers = User::whereJsonContains('roles', 'volunteer')->count();
        return view('home',compact('banners','blood_camps','testimonials',
        'units_donated','donors','receivers','volunteers'));
    }

    public function about() {
        $units_donated = BloodRequest::where('status','completed')->count();
        $donors = Donor::get()->count();
        $lives_helped =  BloodRequest::where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');
        $volunteers = User::whereJsonContains('roles', 'volunteer')->count();
        return view('about',compact('units_donated','donors','lives_helped','volunteers'));
    }

    public function contact() {
        return view('contact');
    }

    /**
     * Display the static Privacy Policy page.
     */
    public function privacyPolicy() {
        return view('privacy-policy');
    }

    /**
     * Display the public Account Deletion page (required by Google Play).
     * Must be publicly accessible without login.
     */
    public function deleteAccount() {
        return view('delete-account');
    }

    /**
     * Display the public Data Deletion page (required by Google Play Data Safety).
     * Must be publicly accessible without login.
     */
    public function deleteData() {
        return view('delete-data');
    }

    public function contactStore(Request $request) {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:160',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:160',
            'message' => 'required|string|max:2000',
        ]);

        \App\Models\ContactMessage::create($data);

        return back()->with('contact_success', 'Thank you! Your message has been sent. We will get back to you soon.');
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
        $user = Auth::user();
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
