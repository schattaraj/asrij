@extends('layouts.app')
@section('title', 'Home')

@section('content')
    {{-- <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/img/banner1.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h3>Donate Blood, Save Lives</h3>
                    <p>Your one drop of blood can give someone a new life.</p>
                    <a href="#registration-section" class="btn btn-primary">Register Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/banner2.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h3>Help People</h3>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/banner3.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption">
                    <h3>The World Needs Your Help</h3>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div> --}}
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">
            @foreach ($banners as $key => $banner)
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $key }}"
                    class="{{ $key == 0 ? 'active' : '' }}">
                </button>
            @endforeach
        </div>

        <div class="carousel-inner">

            @foreach ($banners as $key => $banner)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ asset('/storage/app/public/' . $banner->image) }}" class="d-block w-100"
                        alt="{{ $banner->title }}">

                    <div class="carousel-caption">
                        <h3>{{ $banner->title }}</h3>
                        <p>{{ $banner->description }}</p>

                        @if ($banner->button_text)
                            <a href="{{ $banner->button_link }}" class="btn btn-primary">
                                {{ $banner->button_text }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
    <!-- ===== STATS SECTION ===== -->
    <section class="stats">
        <div class="stat-box">
            <h2 class="count" data-target="{{$units_donated}}">0</h2>
            <p>Total Units Donated</p>
        </div>
        <div class="stat-box">
            <h2 class="count" data-target="{{$donors}}">0</h2>
            <p>Total Donors</p>
        </div>
        <div class="stat-box">
            <h2 class="count" data-target="{{$receivers}}">0</h2>
            <p>Total Receivers</p>
        </div>
        <div class="stat-box">
            <h2 class="count" data-target="{{$volunteers}}">0</h2>
            <p>Total Volunteers</p>
        </div>
    </section>
    @auth
    @else
        <section class="registration-section" id="registration-section">
            <div class="container">
                {{-- <h1 style="font-size: 24px;font-weight:600">Register as a Donor / Receiver / Volunteer</h1> --}}
                <h1 style="font-size: 24px;font-weight:600">Choose Your Role</h1>

                <div class="registration-tabs">
                    <button class="tab-btn" data-tab="donor" data-bs-target="#donor"> <i class="fa-solid fa-droplet"></i>
                        <span>Donor</span></button>
                    <button class="tab-btn" data-tab="request_blood" data-bs-target="#request_blood"> <i
                            class="fa-solid fa-hand-holding-heart"></i>
                        <span>Request Blood</span></button>
                    <button class="tab-btn" data-tab="volunteer" data-bs-target="#volunteer"> <i
                            class="fa-solid fa-hands-helping"></i>
                        <span>Volunteer</span></button>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Donor Registration -->
                <div id="donor" class="tab-content">
                    {{-- <div class="role-message alert alert-info d-none">
                        You are already a donor.
                    </div> --}}
                    <form action="{{ route('registration') }}" class="registration-form" method="post">
                        @csrf
                        <input type="hidden" name="role" value="donor">
                        {{-- <div class="mb-3">
                            <label class="form-label fw-semibold">Who is this request for?</label>

                            <div class="d-flex gap-3">
                                <!-- Other -->
                                <input type="radio" class="btn-check" name="request_for" id="other" value="other"
                                    autocomplete="off" checked>
                                <label class="btn btn-outline-primary d-flex align-items-center gap-2 px-4 py-2 rounded-pill"
                                    for="other">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Someone Else
                                </label>

                                <!-- Self -->
                                <input type="radio" class="btn-check" name="request_for" id="self" value="self"
                                    autocomplete="off">
                                <label class="btn btn-outline-primary d-flex align-items-center gap-2 px-4 py-2 rounded-pill"
                                    for="self">
                                    <i class="bi bi-person-fill"></i>
                                    Myself
                                </label>

                            </div>
                        </div> --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">This registration is for -</label>

                            <div class="d-flex gap-2">

                                <!-- Someone Else (FIRST + DEFAULT) -->
                                <input type="radio" class="btn-check" name="request_for" id="other" value="other"
                                    checked>
                                <label class="option-card" for="other">
                                    <i class="bi bi-people-fill option-icon"></i>
                                    Someone Else
                                </label>

                                <!-- Myself -->
                                <input type="radio" class="btn-check" name="request_for" id="self" value="self">
                                <label class="option-card" for="self">
                                    <i class="bi bi-person-circle option-icon"></i>
                                    <span class="role-message">Myself</span>
                                </label>

                            </div>
                        </div>
                        {{-- <div class="form-check mb-3">
                            <input class="form-check-input autofill-user" type="checkbox" name="request_for" id="autofill-user">
                            <label class="form-check-label" for="autofill-user">
                                Use my profile details
                            </label>
                        </div> --}}
                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid mb-0 @enderror" name="name"
                                id="donor_full_name" placeholder="Full Name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label for="donor_full_name">Full Name</label>
                        </div>

                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                id="donor_email" placeholder="Email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label for="donor_email">Email</label>
                        </div>

                        <div class="form-floating">
                            <select class="form-select @error('blood_group') is-invalid @enderror" name="blood_group"
                                id="donor_floating_blood_select" required>
                                <option value="">Select Blood Group</option>
                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}" {{ old('blood_group') == $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="donor_floating_blood_select">Blood Group</label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                name="dob" id="donor_date_of_birth" value="{{ old('date_of_birth') }}" required
                                max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}">

                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="donor_date_of_birth">Date of Birth</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" name="gender" id="donor_floating_gender" required>
                                <option value="">Select Gender</option>
                                @foreach (['Male', 'Female', 'Other'] as $group)
                                    <option value="{{ $group }}" {{ old('gender') == $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="donor_floating_gender">Gender</label>
                        </div>
                        <div class="form-floating">
                            <input type="date" id="date"
                                class="form-control @error('last_donation') is-invalid @enderror" name="last_donation"
                                value="{{ old('last_donation') }}">
                            @error('last_donation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label for="date">Last date of blood donation</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="contact"
                                name="mobile" placeholder="Contact Number" value="{{ old('mobile') }}" maxlength="10"
                                required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label>Contact Number</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" style="width: 16px" type="checkbox" name="whatsapp_checkbox"
                                id="sameAsContact">
                            <label class="form-check-label" for="sameAsContact">
                                WhatsApp number same as contact number
                            </label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                id="whatsapp" name="whatsapp_number" placeholder="WhatsApp Number" maxlength="10"
                                value="{{ old('whatsapp_number') }}" required>
                            @error('whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label>WhatsApp Number</label>
                        </div>

                        {{-- <div class="form-floating">
                            <input type="text" id="donor_pin_code"
                                class="form-control @error('pin_code') is-invalid @enderror" name="pin_code"
                                placeholder="Pin Code" value="{{ old('pin_code') }}" required>
                            @error('pin_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label for="donor_pin_code">Pin Code</label>
                        </div> --}}
                        <div class="input-group mb-3">
                            <div class="form-floating flex-grow-1">
                                <input id="donor_address"
                                    class="form-control mb-0 readonly @error('address') is-invalid @enderror" name="address"
                                    placeholder="Address" style="border-top-right-radius: 0;border-bottom-right:0;" required
                                    value="{{ old('address') }}" readonly>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label>Address</label>
                            </div>
                            <button type="button" class="btn btn-outline-danger rounded-end w-auto open-location-modal"
                                data-bs-toggle="modal" data-bs-target="#locationModal" data-location-input="donor_address"
                                data-lat="donor_latitude" data-lng="donor_longitude">
                                Change
                            </button>
                            <input type="hidden" name="donor_latitude" id="donor_latitude">
                            <input type="hidden" name="donor_longitude" id="donor_longitude">
                        </div>
                        <button type="submit" class="btn-primary">Submit Registration</button>
                    </form>
                </div>

                <!-- Request Blood -->
                <div id="request_blood" class="tab-content">
                    <div class="role-message alert alert-info d-none">
                        You already have a blood request.
                    </div>
                    <form action="{{ route('blood-requests.store') }}" class="registration-form" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="receiver">
                        <!-- Receiver Type -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">This request is for -</label>

                            <div class="d-flex gap-2">

                                <!-- Someone Else (FIRST + DEFAULT) -->
                                <input type="radio" class="btn-check" name="request_for" id="requestFor_other"
                                    value="other" checked>
                                <label class="option-card" for="requestFor_other">
                                    <i class="bi bi-people-fill option-icon"></i>
                                    Someone Else
                                </label>

                                <!-- Myself -->
                                <input type="radio" class="btn-check" name="request_for" id="requestFor_self"
                                    value="self">
                                <label class="option-card" for="requestFor_self">
                                    <i class="bi bi-person-circle option-icon"></i>
                                    Myself
                                </label>

                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" name="patient_type" id="patient_type" required>
                                <option value="">Select Medicine Condition</option>
                                <option>General</option>
                                <option>Thalassemia</option>
                                <option>Emergency</option>
                                <option>Surgery</option>
                                <option>Cancer</option>
                                <option>Other</option>
                            </select>
                            <label for="patient_type">Medical Condition</label>
                        </div>
                        {{-- <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="requestForSelf" name="request_for">
                            <label class="form-check-label" for="requestForSelf">
                                Request for myself
                            </label>
                        </div> --}}
                        <!-- Patient Name -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Patient Name" required>
                            <label for="name">Patient Name</label>
                        </div>
                        <div class="form-floating">
                            <input type="email" class="form-control" name="email" id="receiver_full_name"
                                placeholder="Email">
                            <label for="receiver_full_name">Email</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" name="blood_group" id="receiver_floating_blood_select" required>
                                <option value="">Select Blood Group</option>
                                <option>A+</option>
                                <option>A-</option>
                                <option>B+</option>
                                <option>B-</option>
                                <option>AB+</option>
                                <option>AB-</option>
                                <option>O+</option>
                                <option>O-</option>
                            </select>
                            <label for="receiver_floating_blood_select">Blood Group</label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                name="dob" id="donor_date_of_birth" value="{{ old('date_of_birth') }}" required
                                max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}">

                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label for="donor_date_of_birth">Date of Birth</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" name="gender" id="donor_floating_gender" required>
                                <option value="">Select Gender</option>
                                @foreach (['Male', 'Female', 'Other'] as $group)
                                    <option value="{{ $group }}" {{ old('gender') == $group ? 'selected' : '' }}>
                                        {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="donor_floating_gender">Gender</label>
                        </div>
                        <!-- Hospital Name -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="hospital_name" id="hospital"
                                placeholder="Hospital Name" required>
                            <label for="hospital">Hospital Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" value="1" name="unit" id="unit"
                                placeholder="Unit Needed" required readonly onclick="alert('Only 1 unit can be requested per request. Please submit another request if more units are needed.')">
                            <label for="unit">Unit</label>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-floating flex-grow-1">
                                <input type="number" class="form-control mb-0" name="required_before"
                                    placeholder="Required Within" min="1"
                                    style="border-top-right-radius: 0;border-bottom-right-radius:0;" required>
                                <label>Required Within</label>
                            </div>
                            <select class="form-select mb-0" name="required_before_unit" style="max-width: 120px;">
                                <option value="days">Days</option>
                                <option value="hours">Hours</option>
                            </select>
                        </div>
                        <!-- Contact Number -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="mobile" placeholder="Contact Number" required>
                            <label for="contact">Contact Number</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" style="width: 16px" type="checkbox" name="whatsapp_checkbox"
                                id="recieverWhatsapp">
                            <label class="form-check-label" for="recieverWhatsapp">
                                WhatsApp number same as contact number
                            </label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                name="whatsapp_number" placeholder="WhatsApp Number" value="{{ old('whatsapp_number') }}"
                                required>
                            @error('whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label>WhatsApp Number</label>
                        </div>

                        <!-- Address -->
                        {{-- <div class="form-floating mb-3">
                            <textarea class="form-control" name="address" id="address" placeholder="Address" style="height: 120px;" required></textarea>
                            <label for="address">Address</label>
                        </div> --}}
                        <div class="input-group mb-3">
                            <div class="form-floating flex-grow-1">
                                <input id="patient_address" class="form-control mb-0 @error('address') is-invalid @enderror"
                                    name="address" placeholder="Address"
                                    style="border-top-right-radius: 0;border-bottom-right:0;" required
                                    value="{{ old('address') }}" readonly>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label>Address</label>
                            </div>
                            <button type="button" class="btn btn-outline-danger rounded-end w-auto open-location-modal"
                                data-bs-toggle="modal" data-bs-target="#locationModal" data-location-input="patient_address"
                                data-lat="patient_latitude" data-lng="patient_longitude">
                                Change
                            </button>
                            <input type="hidden" name="patient_latitude" id="patient_latitude">
                            <input type="hidden" name="patient_longitude" id="patient_longitude">
                        </div>
                        {{-- <div class="form-floating">
                            <input type="file" id="prescription" class="form-control" name="prescription"
                                placeholder="Prescription" required>
                            <label for="prescription">Upload Prescriotion</label>
                        </div> --}}
                        <div class="form-group">
                            <label for="" class="form-label">Upload Prescriotion <span
                                    style="color:red;">*</span></label>
                            <input type="file" id="prescription" class="form-control" name="prescription"
                                placeholder="Prescription" required>
                        </div>
                        {{-- <div class="form-floating">
                            <input type="text" id="receiver_pin_code" class="form-control" name="pin_code"
                                placeholder="Pin Code" required>
                            <label for="receiver_pin_code">Pin Code</label>
                        </div> --}}
                        <button type="submit" class="btn-primary">Submit Registration</button>
                    </form>
                </div>

                <!-- Volunteer Registration -->
                <div id="volunteer" class="tab-content">
                    <div class="role-message alert alert-info d-none">
                        You are already a volunteer.
                    </div>
                    <form action="{{ route('volunteer.registration') }}" class="registration-form" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="volunteer">
                        <div class="form-floating">
                            <select class="form-select" onchange="volunteerFields(this)" name="type" id="floatingSelect"
                                required>
                                <option value="">Select Option</option>
                                <option value="individual">Individual</option>
                                <option value="ngo">NGO</option>
                                <option value="charity">Charity</option>
                                <option value="club">Club</option>
                            </select>
                            <label for="floatingSelect">Type</label>
                        </div>
                        <div class="fields" id="individual">
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">This request is for -</label>

                                <div class="d-flex gap-2">

                                    <!-- Someone Else (FIRST + DEFAULT) -->
                                    <input type="radio" class="btn-check" name="request_for"
                                        id="volunteer_request_for_other" value="other" checked>
                                    <label class="option-card" for="volunteer_request_for_other">
                                        <i class="bi bi-people-fill option-icon"></i>
                                        Someone Else
                                    </label>

                                    <!-- Myself -->
                                    <input type="radio" class="btn-check" name="request_for"
                                        id="volunteer_request_for_self" value="self">
                                    <label class="option-card" for="volunteer_request_for_self">
                                        <i class="bi bi-person-circle option-icon"></i>
                                        Myself
                                    </label>

                                </div>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" id="full_name"
                                    placeholder="Full Name" required>
                                <label for="full_name">Full Name</label>
                            </div>
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="volunteer_email"
                                    placeholder="Email">
                                <label for="volunteer_email">Email</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select" name="blood_group" id="volunteer_floating_blood_select" required>
                                    <option value="">Select Blood Group</option>
                                    <option>A+</option>
                                    <option>A-</option>
                                    <option>B+</option>
                                    <option>B-</option>
                                    <option>AB+</option>
                                    <option>AB-</option>
                                    <option>O+</option>
                                    <option>O-</option>
                                </select>
                                <label for="volunteer_floating_blood_select">Blood Group</label>
                            </div>
                            <div class="form-floating">
                                <input type="date" class="form-control" name="dob" id="volunteer_date_of_birth"
                                    value="" required max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}">
                                <label for="volunteer_date_of_birth">Date of Birth</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select" name="gender" id="volunteer_floating_gender" required>
                                    <option value="">Select Gender</option>
                                    @foreach (['Male', 'Female', 'Other'] as $group)
                                        <option value="{{ $group }}" {{ old('gender') == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="volunteer_floating_gender">Gender</label>
                            </div>
                            <div class="form-floating">
                                <input type="date" id="date" class="form-control" name="last_donation"
                                    placeholder="Last Date of Donation">
                                <label for="date">Last date of blood donation</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" name="mobile" placeholder="Contact Number"
                                    required>
                                <label for="">Contact Number</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="whatsapp_checkbox" style="width: 16px" type="checkbox"
                                    id="volunteerWhatsapp">
                                <label class="form-check-label" for="volunteerWhatsapp">
                                    WhatsApp number same as contact number
                                </label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" name="whatsapp_number"
                                    placeholder="WhatsApp Number" value="" required>
                                <label>WhatsApp Number</label>
                            </div>
                            <div class="input-group mb-3">
                                <div class="form-floating flex-grow-1">
                                    <input id="individual_volunteer_address" class="form-control mb-0" name="address"
                                        placeholder="Address" style="border-top-right-radius: 0;border-bottom-right:0;"
                                        required value="" readonly>
                                    <label>Address</label>
                                </div>
                                <button type="button" class="btn btn-outline-danger rounded-end w-auto open-location-modal"
                                    data-bs-toggle="modal" data-bs-target="#locationModal"
                                    data-location-input="individual_volunteer_address"
                                    data-lat="individual_volunteer_latitude" data-lng="individual_volunteer_longitude">
                                    Change
                                </button>
                                <input type="hidden" name="volunteer_latitude" id="individual_volunteer_latitude">
                                <input type="hidden" name="volunteer_longitude" id="individual_volunteer_longitude">
                            </div>
                        </div>
                        <div class="fields" id="ngo">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="registration_number"
                                            placeholder="Registration Number" required>
                                        <label>Registration Number</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="organization_name"
                                            placeholder="Organization / Trust Name" required>
                                        <label>Organization / Trust Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="president_name"
                                            placeholder="President Name">
                                        <label>President Name</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="president_number"
                                            placeholder="President Number">
                                        <label>President Number</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 6 - Secretary -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="secretary_name"
                                            placeholder="Secretary Name">
                                        <label>Secretary Name</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="secretary_number"
                                            placeholder="Secretary Number">
                                        <label>Secretary Number</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 7 - Account -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="account_name"
                                            placeholder="Account Name">
                                        <label>Account Name</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="account_number"
                                            placeholder="Account Number">
                                        <label>Account Number</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="contact_number"
                                            placeholder="Contact Number" required>
                                        <label>Contact Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" name="email" placeholder="Email ID"
                                            required>
                                        <label>Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-group mb-3">
                                        <div class="form-floating flex-grow-1">
                                            <input id="ngo_volunteer_address" class="form-control mb-0" name="address"
                                                placeholder="Address"
                                                style="border-top-right-radius: 0;border-bottom-right:0;" required
                                                value="" readonly>
                                            <label>Address</label>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-danger rounded-end w-auto open-location-modal"
                                            data-bs-toggle="modal" data-bs-target="#locationModal"
                                            data-location-input="ngo_volunteer_address" data-lat="ngo_volunteer_latitude"
                                            data-lng="ngo_volunteer_longitude">
                                            Change
                                        </button>
                                        <input type="hidden" name="volunteer_latitude" id="ngo_volunteer_latitude">
                                        <input type="hidden" name="volunteer_longitude" id="ngo_volunteer_longitude">
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="pincode" placeholder="Pin Code"
                                            required>
                                        <label>Pin Code</label>
                                    </div>
                                </div> --}}
                            </div>


                            <!-- Members Section -->
                            {{-- <div class="row">
                                <div class="col-12">
                                    <h5 class="mt-3">Members</h5>
                                </div>
                            </div>

                            <div id="members-area">

                                <!-- Member Row Template -->
                                <div class="row member-row">
                                    <div class="col-md-5">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="member_name[]"
                                                placeholder="Member Name">
                                            <label>Member Name</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="member_contact_number[]"
                                                placeholder="Contact Number">
                                            <label>Contact Number</label>
                                        </div>
                                    </div>

                                    <!-- Position dropdown -->
                                    <div class="col-md-3">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="member_position[]">
                                                <option value="Member">Member</option>
                                                <option value="President">President</option>
                                                <option value="Secretary">Secretary</option>
                                                <option value="Treasurer">Treasurer</option>
                                            </select>
                                            <label>Position</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Add Member Button -->
                            <div class="row mb-2">
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary" onclick="addMember()">Add Member</button>
                                </div>
                            </div> --}}
                        </div>
                        <button type="submit" class="btn-primary">Submit Registration</button>
                    </form>
                </div>
            </div>
        </section>
    @endauth
    <!-- Donation Process Section -->
    <section class="donation-process py-5" data-aos="fade-down">
        <div class="container text-center">
            <h2 class="mb-5 section-title heading">Donation Process</h2>
            <div class="row justify-content-center">
                <!-- Step 1: Registration -->
                <div class="col-md-4 mb-4">
                    <div class="process-step">
                        <div class="step-icon mb-3">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h5 class="fw-bold">Registration</h5>
                        <p class="text-muted">
                            Fill out a quick registration form with your basic information to get started.
                        </p>
                        <span class="step">1</span>
                    </div>
                </div>

                <!-- Step 2: Screening -->
                <div class="col-md-4 mb-4">
                    <div class="process-step">
                        <div class="step-icon mb-3">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h5 class="fw-bold">Screening</h5>
                        <p class="text-muted">
                            Undergo a simple health screening to ensure you’re eligible to donate safely.
                        </p>
                        <span class="step">2</span>
                    </div>
                </div>

                <!-- Step 3: Donation -->
                <div class="col-md-4 mb-4">
                    <div class="process-step">
                        <div class="step-icon mb-3">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <h5 class="fw-bold">Donation</h5>
                        <p class="text-muted">
                            Complete your donation in a comfortable and caring environment.
                        </p>
                        <span class="step">3</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="blood_requests_section">
        <div class="container my-5">

            <!-- Section Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0 section-title"><i class="fa-solid fa-droplet"></i> Blood Requests</h3>
                    <small class="text-muted">Help save lives by responding to requests</small>
                </div>
                <span class="badge bg-danger px-3 py-2">Live</span>
            </div>

            <!-- Filters -->
            <div class="filter-bar mb-4 p-3 rounded shadow-sm bg-white">
                <div class="row g-2">

                    <div class="col-md-3">
                        <select class="form-select" id="bloodFilter">
                            <option value="">All Blood Groups</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                <option value="{{ $group }}">
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select" id="urgencyFilter">
                            <option value="">All</option>
                            <option value="urgent">Urgent</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Search location or hospital...">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-danger w-100" onclick="resetFilters()">Reset</button>
                    </div>

                </div>
            </div>

            <!-- Requests Grid -->
            <div class="row g-3" id="requestContainer"></div>
            {{-- <div class="text-center mt-4">
                <button class="btn btn-outline-danger px-4">
                  View All Requests
                </button>
              </div> --}}
            <div id="viewAllWrapper" class="text-center mt-4" style="display:none;">
                <a href="{{ route('all-blood-requests') }}" class="btn btn-outline-danger px-4">
                    View All Requests
                </a>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="confirmDonationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Confirm Your Response</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="donationForm">
                            <input type="hidden" id="request_id" name="request_id">

                            <div class="mb-3">
                                <label class="form-label text-muted small">Your Name</label>
                                <input type="text" id="donor_name" class="form-control bg-light" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Contact Number</label>
                                <input type="text" id="donor_phone" class="form-control" required>
                                <div class="form-text text-info">
                                    <i class="fas fa-info-circle me-1"></i> Is this number currently reachable? Update it
                                    if needed.
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary w-100"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger w-100">Confirm & Respond</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== OUR VISION / ABOUT ===== -->
    <section class="d-none vision py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <!-- Text Section -->
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-down" data-aos-duration="800" data-aos-delay="200">
                    <h2 class="fw-bold mb-3 text-uppercase" style="color: var(--primary-color);">Our Vision</h2>
                    <p class="lead text-secondary">
                        We aim to build a digital bridge connecting donors and receivers across communities.
                        for saving lives efficiently.
                        Through this portal, we promote timely blood donations and emergency response systems
                    </p>
                    <a href="#donate" class="btn btn-primary mt-3 px-4 py-2 shadow-sm">
                        Learn More <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

                <!-- Image Section -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="vision-img position-relative overflow-hidden rounded-4 shadow">
                        <img src="{{ asset('assets/img/banner1.jpg') }}" alt="Our Vision"
                            class="img-fluid w-100 h-100 object-fit-cover" style="max-height: 400px;" />
                        {{-- <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25" style="opacity: 25%"></div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== BLOOD CAMP GALLERY ===== -->
    <section class="gallery">
        <div class="container">
            <h2 data-aos="fade-down">Our Blood Camps</h2>
            {{-- <div class="gallery-container">
            <img src="{{asset('assets/img/camp1.jpg')}}" alt="Blood Camp 1">
            <img src="{{asset('assets/img/camp2.jpg')}}" alt="Blood Camp 2">
            <img src="{{asset('assets/img/camp1.jpg')}}" alt="Blood Camp 3">
        </div> --}}
            <div class="w-100" style="position:relative;">
                <div class="swiper" data-aos="fade-down">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        {{-- <div class="swiper-slide"><a href="#"><img src="{{ asset('assets/img/camp1.jpg') }}"
                                    alt="Blood Camp 1"></a>
                            <div class="text-area">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="date mb-0">
                                        17 Feb, 2026
                                    </div>
                                    <span><i class="fa-regular fa-clock"></i> 10.00am - 3.00pm</span>
                                </div>
                                <a href="#">
                                    <h4>O- Blood Donors Needed</h4>
                                </a>
                                <p>O Negative blood cells are called “universal” meaning they can be transfused to almost
                                    any
                                    patient in need and blood cells are safest.</p>
                                <div class="event-latest-details">
                                    <a class="comments" href="#"> <i class="fa-solid fa-location-dot"></i>
                                        Selimpore Road, Kolkata</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/camp2.jpg') }}" alt="Blood Camp 1">
                            <div class="text-area">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="date mb-0">
                                        17 Feb, 2026
                                    </div>
                                    <span><i class="fa-regular fa-clock"></i> 10.00am - 3.00pm</span>
                                </div>
                                <a href="#"></a>
                                <h4>Donation - Feel Real Peace</h4>
                                <p>You're the real hero because you can gift a new life for patient.So donate your blood and
                                    enjoy a precious life. Don't fear, it's really easy.</p>
                                <div class="event-latest-details">
                                    <a class="comments" href="#"> <i class="fa-solid fa-location-dot"></i> Pure
                                        Life Hospital</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/camp1.jpg') }}" alt="Blood Camp 1">
                            <div class="text-area">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="date mb-0">
                                        17 Feb, 2026
                                    </div>
                                    <span><i class="fa-regular fa-clock"></i> 10.00am - 3.00pm</span>
                                </div>
                                <a href="#">
                                    <h4>A Campus Blood Mission</h4>
                                </a>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec
                                    ullamcorper mattis, pulvinar dapibus leo.</p>
                                <div class="event-latest-details">
                                    <a class="comments" href="#"> <i class="fa-solid fa-location-dot"></i> Pure
                                        Life Hospital</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide"><img src="{{ asset('assets/img/camp1.jpg') }}" alt="Blood Camp 1">
                            <div class="text-area">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="date mb-0">
                                        17 Feb, 2026
                                    </div>
                                    <span><i class="fa-regular fa-clock"></i> 10.00am - 3.00pm</span>
                                </div>
                                <h4>A Campus Blood Mission</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec
                                    ullamcorper mattis, pulvinar dapibus leo.</p>
                                <div class="event-latest-details">
                                    <a class="comments" href="#"> <i class="fa-solid fa-location-dot"></i> Pure
                                        Life Hospital</a>
                                </div>
                            </div>
                        </div> --}}
                        @forelse($blood_camps as $camp)
                            <div class="swiper-slide">
                                {{-- <a href="{{ route('blood-camp.details', $camp->slug) }}"> --}}
                                <img src="{{ asset('/storage/app/public/' . $camp->image) }}" alt="{{ $camp->title }}">
                                {{-- </a> --}}

                                <div class="text-area">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="date mb-0">
                                            {{ \Carbon\Carbon::parse($camp->camp_date)->format('d M, Y') }}
                                        </div>

                                        <span>
                                            <i class="fa-regular fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($camp->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($camp->end_time)->format('h:i A') }}
                                        </span>
                                    </div>

                                    {{-- <a href="{{ route('blood-camp.details', $camp->slug) }}"> --}}
                                    <h4>{{ $camp->title }}</h4>
                                    {{-- </a> --}}

                                    <p>
                                        {{ \Illuminate\Support\Str::limit($camp->description, 120) }}
                                    </p>

                                    <div class="event-latest-details">
                                        <a class="comments"
                                            href="https://www.google.com/maps/search/?api=1&query={{ $camp->camp_latitude }},{{ $camp->camp_longitude }}"
                                            target="_blank" rel="noopener noreferrer">
                                            <i class="fa-solid fa-location-dot"></i>
                                            {{ $camp->location }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="text-area text-center">
                                    <h4>No blood camps available</h4>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>
                    <!-- If we need scrollbar -->
                    {{-- <div class="swiper-scrollbar"></div> --}}
                </div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>
    <!-- ===== EMERGENCY AMBULANCE CONTACT ===== -->
    <section class="d-none emergency py-5 text-center text-white" data-aos="fade-up">
        <div class="container">
            <h2 class="fw-bold mb-4">Find Emergency Ambulance Contact</h2>
            <form method="post" class="d-flex justify-content-center flex-wrap">
                <input type="text" class="form-control w-auto px-3" placeholder="Enter Pin Code"
                    style="min-width: 250px;">
                <button type="button" class="btn btn-light fw-semibold px-4">
                    Search
                </button>
            </form>
            <p class="note mt-3 mb-0 text-light opacity-75">
                Emergency support is available <strong>24/7</strong>
            </p>
        </div>
    </section>
    <!-- ===== ACCIDENTAL SUPPORT ===== -->
    {{-- <section class="accident-support py-5 bg-light"  data-aos="fade-up">
        <div class="container">
            <h2 class="fw-bold text-center mb-4" style="color: var(--primary-color);">
                On-Road Accidental Support
            </h2>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form class="p-4 rounded-4 shadow-sm bg-white">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Enter your location">
                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Describe the situation"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-semibold py-2">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- On-Road Accidental Support Section -->
    <section class="d-none accident-support d-flex align-items-center py-5" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-8">
                    <h2 class="fw-bold text-center mb-5 section-title">
                        <i class="bi bi-truck-front me-2"></i> On-Road Accidental Support
                    </h2>

                    <form method="POST" action="{{ route('support.store') }}" enctype="multipart/form-data"
                        class="support-form p-4 p-md-5 rounded-4 shadow-lg bg-white" id="accidental_support">
                        @csrf

                        <div class="text-center mb-4">
                            <div class="support-icon mb-3">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <p class="text-muted mb-0">
                                Report an on-road emergency and get immediate support.
                            </p>
                        </div>

                        <!-- Location Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Location</label>
                            {{-- <input type="text" id="locationInput" name="location" class="form-control"
                                placeholder="Search or select your location" required> --}}
                            <div class="input-group">
                                <input type="text" id="locationInput" name="location" class="form-control"
                                    placeholder="Select your location" readonly required>

                                <button type="button" class="btn btn-outline-danger open-location-modal"
                                    data-bs-toggle="modal" data-bs-target="#locationModal"
                                    data-location-input="locationInput" data-lat="support_latitude"
                                    data-lng="support_longitude">
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Hidden Lat/Lng -->
                        <input type="hidden" id="support_latitude" name="latitude">
                        <input type="hidden" id="support_longitude" name="longitude">

                        <!-- Map -->
                        {{-- <div id="map" class="mb-4 rounded" style="height: 350px;"></div> --}}
                        @auth
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Your Contact Number</label>
                                <input type="text" class="form-control" name="contact_number"
                                    placeholder="Your Contact Number" />
                            </div>
                        @endauth
                        <!-- Image -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Image (optional)</label>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Describe the Situation</label>
                            <textarea class="form-control" rows="4" name="description" placeholder="Provide details about the accident..."
                                required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger fw-semibold py-2 btn-glow">
                                <i class="bi bi-send-fill me-1"></i> Submit Request
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- ===== OUR VISION / ABOUT ===== -->
    {{-- <section class="vision">
        <div class="vision-text">
            <h2>Our Vision</h2>
            <p>We aim to build a digital bridge connecting donors and receivers across communities. 
            Through this portal, we promote timely blood donations and emergency response systems 
            for saving lives efficiently.</p>
        </div>
        <div class="vision-img">
            <img src="images/banner.jpg" alt="Our Vision">
        </div>
    </section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact">
        <h2>Contact Us</h2>
        <p><i class="fas fa-envelope"></i> info@bloodconnect.org</p>
        <p><i class="fas fa-phone"></i> +91 98765 43210</p>
        <p><i class="fas fa-map-marker-alt"></i> 123, Red Cross Road, New Delhi</p>
    </section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5 text-center text-white" style="background-color: var(--primary-color);">
  <div class="container">
    <h2 class="fw-bold mb-4 text-uppercase">Contact Us</h2>
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="contact-info fs-5">
          <p class="mb-3">
            <i class="fas fa-envelope me-2"></i>
            <a href="mailto:info@bloodconnect.org" class="text-white text-decoration-none hover-underline">
              info@bloodconnect.org
            </a>
          </p>
          <p class="mb-3">
            <i class="fas fa-phone me-2"></i>
            <a href="tel:+919876543210" class="text-white text-decoration-none hover-underline">
              +91 98765 43210
            </a>
          </p>
          <p class="mb-0">
            <i class="fas fa-map-marker-alt me-2"></i>
            123, Red Cross Road, New Delhi
          </p>
        </div>
      </div>
    </div>
  </div>
</section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-4 text-uppercase" style="color: var(--primary-color);">Contact Us</h2>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="contact-card p-4 rounded-4 shadow-sm bg-white">
          <p class="mb-3 fs-5">
            <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>
            <a href="mailto:info@bloodconnect.org" class="text-dark text-decoration-none hover-highlight">
              info@bloodconnect.org
            </a>
          </p>
          <p class="mb-3 fs-5">
            <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>
            <a href="tel:+919876543210" class="text-dark text-decoration-none hover-highlight">
              +91 98765 43210
            </a>
          </p>
          <p class="mb-0 fs-5">
            <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
            123, Red Cross Road, New Delhi
          </p>
        </div>
      </div>
    </div>
  </div>
</section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-4 text-uppercase" style="color: var(--primary-color);">
      Contact Us
    </h2>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="contact-card p-4 rounded-4 shadow-sm bg-white">
          <!-- Contact Details -->
          <div class="mb-4">
            <p class="mb-3 fs-5">
              <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>
              <a href="mailto:info@bloodconnect.org" class="text-dark text-decoration-none hover-highlight">
                info@bloodconnect.org
              </a>
            </p>
            <p class="mb-3 fs-5">
              <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>
              <a href="tel:+919876543210" class="text-dark text-decoration-none hover-highlight">
                +91 98765 43210
              </a>
            </p>
            <p class="mb-0 fs-5">
              <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
              123, Red Cross Road, New Delhi
            </p>
          </div>

          <hr class="my-4">

          <!-- Contact Form -->
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
              <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary fw-semibold py-2">
                Send Message
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</section> --}}
    <!-- ===== CONTACT SECTION ===== -->
    {{-- <section class="contact py-5">
  <div class="container">
    <div class="row align-items-center justify-content-center g-4">

      <!-- Left Side: Image -->
      <div class="col-lg-5">
        <div class="contact-img overflow-hidden rounded-4 shadow-sm">
          <img 
            src="{{asset('assets/img/banner1.jpg')}}" 
            alt="Contact BloodConnect" 
            class="img-fluid w-100 h-100 object-fit-cover"
            style="max-height: 500px;"
          >
        </div>
      </div>

      <!-- Right Side: Content + Form -->
      <div class="col-lg-6">
        <div class="contact-card p-4 p-md-5 rounded-4 bg-white shadow-sm">
          <h2 class="fw-bold mb-4 text-uppercase" style="color: var(--primary-color);">
            Contact Us
          </h2>

          <!-- Contact Details -->
          <div class="mb-4">
            <p class="mb-3 fs-5">
              <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>
              <a href="mailto:info@bloodconnect.org" class="text-dark text-decoration-none hover-highlight">
                info@bloodconnect.org
              </a>
            </p>
            <p class="mb-3 fs-5">
              <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>
              <a href="tel:+919876543210" class="text-dark text-decoration-none hover-highlight">
                +91 98765 43210
              </a>
            </p>
            <p class="mb-0 fs-5">
              <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i>
              123, Red Cross Road, New Delhi
            </p>
          </div>

          <hr class="my-4">

          <!-- Contact Form -->
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
              <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary fw-semibold py-2">
                Send Message
              </button>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
</section> --}}
    <section class="testimonial-section">
        <div class="container">
            <h2 class="testimonial-title section-title heading" data-aos="fade-down">Testimonial</h2>

            <!-- Swiper -->
            <div class="swiper mySwiper" data-aos="fade-down">
                <div class="swiper-wrapper">

                    @forelse ($testimonials as $testimonial)
                        <div class="swiper-slide">
                            <img src="{{ $testimonial->image ? asset('/storage/app/public/' . $testimonial->image) : 'https://i.pravatar.cc/100' }}"
                                alt="{{ $testimonial->name }}" class="testimonial-img" />
                            <h3 class="testimonial-name">{{ $testimonial->name }}</h3>
                            @if ($testimonial->profession)
                                <span class="testimonial-profession d-block text-muted mb-2"
                                    style="font-size:13px;">{{ $testimonial->profession }}</span>
                            @endif
                            <p class="testimonial-text">
                                “{{ $testimonial->message }}”
                            </p>
                        </div>
                    @empty
                        <!-- Fallback testimonial when none are configured -->
                        <div class="swiper-slide">
                            <img src="https://i.pravatar.cc/100?img=3" alt="User" class="testimonial-img" />
                            <h3 class="testimonial-name">Sarah Johnson</h3>
                            <p class="testimonial-text">
                                “Absolutely amazing experience! The team was professional, friendly, and went above and
                                beyond my expectations.”
                            </p>
                        </div>
                    @endforelse

                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>
    <div class="contact-us my-sm-5">
        <div class="container">
            <div class="row contact-form">
                <div class="col-md-8 left" data-aos="fade-right">
                    <h3>Send Us a Message</h3>
                    <form action="/">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Morger Dior" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email">Your Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="example@gmail.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Messages</label>
                            <textarea name="message" id="message" class="form-control" required></textarea>
                        </div>
                        <button class="btn btn-primary">
                            <span>Send Message</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-4 right" data-aos="fade-left">
                    <div class="contact-info">
                        <h3>Contact Information</h3>
                        <a href="#" target="">
                            <div class="item"><i class="fa-solid fa-location-dot"></i><span>Sai Plaza Ground Floor,
                                    Police Chowki, Bishnupur, Bankura, West Bengal, India 722122</span></div>
                        </a>
                        <a href="mailto:support@asrij.org">
                            <div class="item"><i class="fa-solid fa-envelope"></i><span>support@asrij.org</span></div>
                        </a>
                        {{-- <a href="tel:+917048115559" target="_blank">
                            <div class="item"><i class="fa-solid fa-phone"></i><span>+91 7048115559</span></div>
                        </a> --}}
                    </div>
                    <div class="social-links">
                        <h3>Follow Us</h3>
                        <a href="https://www.instagram.com/asrij_foundation/" target="_blank">
                        <i class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.facebook.com/profile.php?id=61586204187656&mibextid=rS40aB7S9Ucbxw6v"
                            target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://x.com/AsrijFoundation" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://www.linkedin.com/company/asrijfoundation" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
          <div class="map mt-5 w-100">
              <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3669.5411924386317!2d72.541278!3d23.11388555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e82dd003ff749%3A0x359e803f537cea25!2sGANESH%20GLORY%2C%20Gota%2C%20Ahmedabad%2C%20Gujarat%20382481!5e0!3m2!1sen!2sin!4v1740900895991!5m2!1sen!2sin"
                  style="border:0; width: 100%;" allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
      </div> --}}
        </div>
    </div>
    {{-- @include('partials.home-content') --}}
    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="otpMessage">Please enter the OTP you received.</p>
                    <div class="mb-3">
                        <label for="otpInput" class="form-label">OTP</label>
                        <input type="text" class="form-control" id="otpInput" maxlength="6"
                            placeholder="Enter 6-digit OTP">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="resendOtpBtn">Resend OTP</button>
                        <button type="button" class="btn btn-primary" id="verifyOtpBtn">Verify</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const counters = document.querySelectorAll(".count");
                const speed = 200; // lower = faster

                const animateCounter = (counter) => {
                    const updateCount = () => {
                        const target = +counter.getAttribute("data-target");
                        const count = +counter.innerText;
                        const increment = target / speed;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + increment);
                            requestAnimationFrame(updateCount);
                        } else {
                            counter.innerText = target.toLocaleString(); // formatted with commas
                        }
                    };
                    updateCount();
                };

                // Use IntersectionObserver to trigger only when visible
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            obs.unobserve(entry.target); // stop observing once animated
                        }
                    });
                }, {
                    threshold: 0.5
                });

                counters.forEach(counter => observer.observe(counter));
            });

            const container = document.getElementById("requestContainer");

            function render(data) {
                container.innerHTML = "";

                data.forEach(req => {
                    const createdDate = req.created_at ?
                        new Date(req.created_at).toLocaleString('en-IN', {
                            day: '2-digit',
                            month: 'short',
                            year: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        }) :
                        "N/A";
                        const required_within = `${req.required_before} ${req.required_before_unit}`;
                        const isExpired = !!req.is_expired;
                        const completed = req.status == 'completed';

                        // Expired takes priority over the urgent styling/badge.
                        const cardClass = isExpired ? 'expired-card' : (req.urgency === 'urgent' ? 'urgent-card' : '');
                        const statusBadge = completed ? '<span class="badge bg-secondary urgent-tag"><i class="fa-regular fa-clock"></i> Donated</span>'
                         : isExpired
                            ? '<span class="badge bg-secondary urgent-tag"><i class="fa-regular fa-clock"></i> EXPIRED</span>'
                            : (req.urgency === 'urgent'
                                ? '<span class="badge bg-danger urgent-tag"><i class="fa-solid fa-exclamation"></i> URGENT</span>'
                                : '');
                    container.innerHTML += `
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="request-card ${cardClass}" ${completed || isExpired ? 'style="opacity:.7"' : ''}>
          <div class="d-flex justify-content-between">
            <div class="blood-group">${req.blood_group}</div>
          ${statusBadge}
        </div>
          <div class="meta"><strong>Hospital Name</strong> : ${req.hospital_name}</div>
          <div class="meta mb-1"><strong>Address</strong> : ${req.address}</div>
          <div class="meta mb-2"><strong>Units</strong> : ${req.unit}</div>
          <div class="meta mb-1">
            <strong>Token</strong> : ${req.token || 'N/A'}
            </div>
        <div class="meta mb-1">
            <strong>Requested On</strong> : ${createdDate}
        </div>
        <div class="meta mb-2">
            <strong>Required within ${required_within}</strong>
            ${completed ? '<span class="text-danger fw-bold ms-1">(Expired)</span>' : isExpired ? '<span class="text-danger fw-bold ms-1">(Expired)</span>' : ''}
        </div>
          ${req?.distance   ? `<div class="meta mb-3"><strong>Distance</strong> : ${req.distance_text} from your registered address</div>` : ''}
            <button
              class="btn btn-sm ${isExpired ? 'btn-secondary' : (req.has_responded ? 'btn-success' : 'btn-danger')} w-100 donate-btn"
              data-request_id="${req.id}"
              data-blood_group="${req?.blood_group}"
              ${isExpired || req.has_responded ? 'disabled' : ''}
            >
              ${completed ? 'Donated' : isExpired ? 'Expired' : (req.has_responded ? 'Already Responded' : 'Donate')}
            </button>
        </div>
      </div>
    `;
                });

                attachEvents();
            }

            function attachEvents() {
                document.querySelectorAll(".donate-btn").forEach(btn => {
                    btn.addEventListener("click", () => {
                        if (btn.disabled) return;
                        let token = localStorage.getItem("token");

                        // 1. Auth & Validation
                        if (!token) {
                            bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal')).show();
                            return;
                        }

                        if (userData && userData.blood_group && userData.blood_group !== btn.dataset
                            .blood_group) {
                            showAlert("warning",
                                "Blood group mismatch. Please check the request requirements.");
                            return;
                        }

                        // 2. Populate Modal Data
                        const modalEl = document.getElementById('confirmDonationModal');

                        // Pass the request ID from the clicked button
                        modalEl.querySelector('#request_id').value = btn.dataset.request_id;

                        // Pre-fill from your global userData object
                        modalEl.querySelector('#donor_name').value = userData.name || "User";
                        modalEl.querySelector('#donor_phone').value = userData.mobile || "";

                        // 3. Show the Modal
                        bootstrap.Modal.getOrCreateInstance(modalEl).show();
                    });
                });

                // 3. Submit handling
                document.getElementById('donationForm').addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const submitBtn = e.target.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Processing...`;

                    const payload = {
                        request_id: document.getElementById('request_id').value,
                        contact_number: document.getElementById('donor_phone')
                            .value // This takes the (possibly edited) number
                    };

                    try {
                        const response = await fetch('{{ route('blood-requests.respond') }}', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${localStorage.getItem("token")}`,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (result.status) {
                            showAlert("success", "Thank you! Your response has been sent to the requester.");
                            fetchRequests();
                            bootstrap.Modal.getInstance(document.getElementById('confirmDonationModal')).hide();
                        } else {
                            showAlert("error", result.message || "Something went wrong.");
                        }
                    } catch (error) {
                        showAlert("danger", "Connection error. Please try again.");
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Confirm & Respond";
                    }
                });
            }

            function filterData() {
                const blood = document.getElementById("bloodFilter").value;
                const urgency = document.getElementById("urgencyFilter").value;
                const search = document.getElementById("searchInput").value.toLowerCase();

                const filtered = allRequests.filter(r => {
                    return (
                        (!blood || r.blood_group === blood) &&
                        (!urgency || r.urgency === urgency) &&
                        (
                            r.address.toLowerCase().includes(search) ||
                            r.hospital_name.toLowerCase().includes(search)
                        )
                    );
                });

                render(filtered);
            }

            function resetFilters() {
                document.getElementById("bloodFilter").value = "";
                document.getElementById("urgencyFilter").value = "";
                document.getElementById("searchInput").value = "";
                render(allRequests);
            }

            /* Event Listeners */
            document.getElementById("bloodFilter").addEventListener("change", filterData);
            document.getElementById("urgencyFilter").addEventListener("change", filterData);
            document.getElementById("searchInput").addEventListener("input", filterData);

            /* Init */
            // render(requests);
            const API_URL = "{{ route('blood-requests.index') }}";
            let allRequests = [];

            async function fetchRequests() {
                showLoader();
                try {
                    const token = localStorage.getItem("token");
                    let option = {
                        headers: {
                            'Accept': 'application/json'
                        }
                    };
                    if (token) {
                        option = {
                            headers: {
                                'Accept': 'application/json',
                                Authorization: 'Bearer ' + token
                            }
                        }
                    }
                    const res = await fetch(API_URL, option);
                    const data = await res.json();

                    allRequests = data?.data;
                    if (!allRequests || allRequests.length === 0) {
                        document.getElementById("blood_requests_section").style.display = 'none';
                        return;
                    }
                    // Show only first 4
                    render(allRequests.slice(0, 4));

                    toggleViewAll(allRequests.length);

                } catch (err) {
                    console.error("Error loading requests", err);
                }
                hideLoader();
            }

            function toggleViewAll(count) {
                const btn = document.getElementById("viewAllWrapper");

                if (count > 4) {
                    btn.style.display = "block";
                } else {
                    btn.style.display = "none";
                }
            }
            document.addEventListener('DOMContentLoaded', fetchRequests);

            //Registration tabs - Donor / Request Blood / Volunteer
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    let token = localStorage.getItem("token");
                    if (!token) {
                        console.log("No Token found");
                        let loginModalEl = document.getElementById('loginModal');
                        let loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                        loginModal.show();
                        return;
                    }
                    let checkClass = document.getElementById(btn.dataset.tab).classList;
                    if (checkClass.contains("active")) {
                        tabBtns.forEach(b => b.classList.remove('active'));
                        tabContents.forEach(content => content.classList.remove('active'));
                        return;
                    }
                    tabBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    tabContents.forEach(content => content.classList.remove('active'));
                    checkClass.add('active');
                    console.log("btn", btn.dataset.tab);
                    switch (btn.dataset.tab) {
                        case "donor":
                            console.log("test", userData.roles.includes('donor'));
                            autoDetectLocation({
                                locationInputId: "donor_address",
                                latInputId: "donor_latitude",
                                lngInputId: "donor_longitude"
                            });
                            break;
                        case "request_blood":
                            autoDetectLocation({
                                locationInputId: "patient_address",
                                latInputId: "patient_latitude",
                                lngInputId: "patient_longitude"
                            });
                            break;
                        case "volunteer":
                            autoDetectLocation({
                                locationInputId: "volunteer_address",
                                latInputId: "volunteer_latitude",
                                lngInputId: "volunteer_longitude"
                            });
                            break;
                        default:
                            break;
                    }
                });
            });

            document.querySelectorAll(".registration-section .form-check-input").forEach(function(item) {
                item.addEventListener("change", function(elm) {
                    const checkbox = elm.target;
                    // closest parent container
                    const parent = checkbox.closest("form");
                    // find input inside that parent
                    const input = parent.querySelector("input[name='whatsapp_checkbox']");
                    const contact = parent.querySelector("input[name='mobile']");
                    const whatsapp = parent.querySelector("input[name='whatsapp_number']");
                    console.log(input.checked, contact.value, whatsapp.value);
                    if (input.checked) {
                        whatsapp.value = contact.value;
                        whatsapp.setAttribute('readonly', true);
                    } else {
                        whatsapp.value = '';
                        whatsapp.removeAttribute('readonly');
                    }
                });
            });

            // Request Blood Starts here................................
            document.getElementById("requestFor_self").addEventListener('change', function() {

                const form = this.closest('form'); // ✅ get current form

                if (this.checked) {
                    showLoader();
                    fetch(`{{ url('/') }}/api/v1/user`, {
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            fillForm(form, data);
                            disableFields(form, true);
                        })
                        .catch(err => {
                            console.error("Error fetching user data", err);
                            alert("Failed to auto-fill data. Please try again.");
                            this.checked = false; // uncheck on error
                        })
                        .finally(() => {
                            hideLoader();
                        });
                } else {
                    clearForm(form);
                    disableFields(form, false);
                }
            });
            document.getElementById("requestFor_other").addEventListener('change', function() {
                const form = this.closest('form');
                clearForm(form);
                disableFields(form, false);
            });

            // function fillForm(form, data) {
            //     form.querySelector('[name="name"]').value = data.name || '';
            //     form.querySelector('[name="email"]').value = data.email || '';
            //     form.querySelector('[name="mobile"]').value = data.mobile || '';
            //     form.querySelector('[name="whatsapp_number"]').value = data.whatsapp_number || '';
            //     form.querySelector('[name="blood_group"]').value = data.blood_group || '';
            //     form.querySelector('[name="dob"]').value = data.dob || '';
            //     form.querySelector('[name="gender"]').value = data.gender || '';
            //     form.querySelector('[name="address"]').value = data.address || '';
            //     // form.querySelector('[name="pin_code"]').value = data.pin_code || '';
            //     form.querySelector('[name="patient_latitude"]').value = data.latitude || '';
            //     form.querySelector('[name="patient_longitude"]').value = data.longitude || '';
            // }

            function fillForm(form, data, fields = {
                name: 'name',
                email: 'email',
                mobile: 'mobile',
                whatsapp_number: 'whatsapp_number',
                blood_group: 'blood_group',
                dob: 'dob',
                gender: 'gender',
                address: 'address',
                patient_latitude: 'latitude',
                patient_longitude: 'longitude'
            }) {
                Object.entries(fields).forEach(([formName, dataKey]) => {
                    const input = form.querySelector(`[name="${formName}"]`);
                    if (input && data[dataKey] !== null) {
                        input.value = data[dataKey];
                    }
                });
            }

            function disableFields(form, state) {
                const fields = ['name', 'email', 'mobile', 'whatsapp_number', 'address'];
                fields.forEach(field => {
                    const el = form.querySelector(`[name="${field}"]`);
                    el.readOnly = state ? el.value ? true : false : state;
                });
                // Disable selects too (like blood group, year)
                ['blood_group', 'dob', 'gender'].forEach(field => {
                    const el = form.querySelector(`[name="${field}"]`);
                    if (el && el.value) {
                        el.style.pointerEvents = state ? 'none' : 'auto';
                        el.style.backgroundColor = state ? '#e9ecef' : '';
                        el.setAttribute('data-readonly', state);
                    } else {
                        el.style.pointerEvents = 'auto';
                        el.style.backgroundColor = '';
                        el.setAttribute('data-readonly', false);
                    }
                });
            }

            function clearForm(form) {
                const fields = ['name', 'email', 'mobile', 'whatsapp_number', 'address', 'blood_group', 'dob',
                    'gender'
                ];
                fields.forEach(field => {
                    const el = form.querySelector(`[name="${field}"]`);
                    if (el.type !== 'hidden' && el.type !== 'checkbox' && el.type !== 'radio') {
                        el.value = '';
                    }
                });
            }

            document.querySelector('#request_blood .registration-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                try {
                    // const validPin = await validatePincode(formData.get('address'), formData.get("pin_code"));
                    // if (!validPin) {
                    //     return;
                    // }
                    if (!formData.get('request_for')) {

                    }
                    const token = localStorage.getItem('token');
                    showLoader();
                    const response = await fetch("{{ route('blood-requests.store') }}", {
                        method: "POST",
                        headers: {
                            Authorization: 'Bearer ' + token,
                            "Accept": "application/json"
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (response.status == 409) {
                        showAlert("error", data.message || "Failed to submit request. Please check your input.");
                        hideLoader();
                        return;
                    }
                    if (!response.ok) {
                        showAlert("error", data.message || "Failed to submit request. Please check your input.");
                        hideLoader();
                        return;
                    }
                    if (response.status === 201) {
                        if (checkMessageForOTP(data?.message)) {
                            // Store the mobile number for later use
                            window.currentMobileForOTP = formData.get('mobile');
                            const masked = mobile.substring(0, 2) + "******" + formData.get('mobile').substring(8);
                            document.getElementById("otpMessage").textContent = "OTP sent to +91 " + masked;
                            // Show the modal
                            const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                            otpModal.show();
                        } else {
                            showAlert("success", data?.message || "Blood request submitted successfully!");
                            fetchRequests();
                            form.reset();
                        }
                        // showAlert("success", data?.message);
                        // Optionally, you can add the new request to the list without reloading
                        // allRequests.unshift(data);
                        // render(allRequests.slice(0, 4));
                        // showAlert("success", "Blood request submitted successfully!");
                    }

                } catch (error) {
                    console.error(error);
                } finally {
                    hideLoader();
                }
            });
            // Function to verify OTP
            async function verifyOTP() {
                const otp = document.getElementById('otpInput').value;
                const mobile = window.currentMobileForOTP;

                if (!otp || otp.length !== 6) {
                    alert('Please enter a valid 6-digit OTP');
                    return;
                }

                try {
                    const response = await fetch(`{{ route('verifyOtp') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            mobile: mobile,
                            otp: otp
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showAlert('success', 'OTP verified successfully!');
                        // Close the modal
                        const otpModal = bootstrap.Modal.getInstance(document.getElementById('otpModal'));
                        otpModal.hide();
                        fetchRequests();
                        // Handle successful verification (e.g., redirect or update UI)
                        console.log('Login successful:', data);
                        // You might want to store the token: localStorage.setItem('token', data.token);
                    } else {
                        alert(data.message || 'OTP verification failed');
                    }
                } catch (error) {
                    console.error('Error verifying OTP:', error);
                    alert('An error occurred while verifying OTP');
                }
            }

            // Function to resend OTP
            async function resendOTP() {
                const mobile = window.currentMobileForOTP;

                if (!mobile) {
                    alert('Mobile number not available');
                    return;
                }

                try {
                    showLoader();
                    const response = await fetch(`{{ route('sendOtp') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            mobile: mobile
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const masked = mobile.substring(0, 2) + "******" + mobile.substring(8);
                        document.getElementById("otpMessage").textContent = "A new OTP has been sent to +91 " + masked;
                        startResendTimer();
                        document.querySelector(".otp-box").focus();
                    } else {
                        alert(data.message || 'Failed to send OTP');
                    }
                } catch (error) {
                    console.error('Error sending OTP:', error);
                    alert('An error occurred while sending OTP');
                } finally {
                    hideLoader();
                }
            }

            // Event listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Verify OTP button
                document.getElementById('verifyOtpBtn').addEventListener('click', verifyOTP);

                // Resend OTP button
                document.getElementById('resendOtpBtn').addEventListener('click', resendOTP);

                // Allow Enter key to verify OTP
                document.getElementById('otpInput').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        verifyOTP();
                    }
                });
            });
            // Request Blood Ends here................................

            // Donor Registration Form Starts here................................
            document.querySelector('#donor .registration-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                try {
                    // const validPin = await validatePincode(formData.get('address'), formData.get("pin_code"));
                    // if (!validPin) {
                    //     return;
                    // }
                    if (!formData.get('request_for')) {

                    }
                    const token = localStorage.getItem('token');
                    const response = await fetch("{{ route('donor.registration') }}", {
                        method: "POST",
                        headers: {
                            Authorization: 'Bearer ' + token,
                            "Accept": "application/json"
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        showAlert("error", data.message || "Failed to submit request. Please check your input.");
                        return;
                    }
                    if (response.status === 200) {
                        showAlert("success", "You are successfully registered as a donor!");
                    }
                    if (response.status === 201) {
                        showAlert("success", data.message || "");
                    }
                    toggleFields(form, false);
                    form.reset();

                } catch (error) {
                    console.error(error);
                    alert("Something went wrong!");
                }
            });
            document.getElementById("self").addEventListener("change", function() {
                const form = this.closest('form');
                if (this.checked) {
                    showLoader();
                    fetch(`{{ url('/') }}/api/v1/user`, {
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.roles.includes('donor')) {
                                showAlert('warning', 'You are already registered as donor');
                                checkbox.checked = false;
                                return;
                            }
                            autoFill(form, data);
                            toggleFields(form, true);
                        })
                        .catch(err => {
                            console.error("Error fetching user data", err);
                            showAlert('error', "Failed to auto-fill data. Please try again.");
                            this.checked = false;
                        })
                        .finally(() => {
                            hideLoader();
                        });
                } else {
                    clearForm(form);
                    toggleFields(form, false);
                }
            });
            document.getElementById("other").addEventListener("change", function() {
                const form = this.closest('form');
                clearForm(form);
                toggleFields(form, false);
            });
            document.querySelectorAll('.autofill-user').forEach(checkbox => {
                checkbox.addEventListener('change', function() {

                    const form = this.closest('form');

                    if (this.checked) {
                        showLoader();
                        fetch(`{{ url('/') }}/api/v1/user`, {
                                headers: {
                                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.roles.includes('donor')) {
                                    showAlert('warning', 'You are already registered as donor');
                                    checkbox.checked = false;
                                    return;
                                }
                                autoFill(form, data);
                                toggleFields(form, true);
                            })
                            .catch(err => {
                                console.error("Error fetching user data", err);
                                showAlert('error', "Failed to auto-fill data. Please try again.");
                                this.checked = false;
                            })
                            .finally(() => {
                                hideLoader();
                            });
                    } else {
                        clearForm(form);
                        toggleFields(form, false);
                    }
                });
            });

            function autoFill(form, data) {

                const fieldMap = {
                    name: 'name',
                    email: 'email',
                    mobile: 'mobile',
                    whatsapp_number: 'whatsapp_number',
                    address: 'address',
                    blood_group: 'blood_group',
                    dob: 'dob',
                    gender: 'gender'
                };

                Object.keys(fieldMap).forEach(key => {
                    const input = form.querySelector(`[name="${fieldMap[key]}"]`);
                    if (input && data[key]) {
                        input.value = data[key];
                    }
                });

                // Special case: WhatsApp same as contact checkbox (if exists)
                const sameCheckbox = form.querySelector('#sameAsContact');
                if (sameCheckbox && data.mobile === data.whatsapp_number) {
                    sameCheckbox.checked = true;
                }
            }

            function toggleFields(form, state) {
                const fields = ['name', 'email', 'mobile', 'whatsapp_number', 'address'];

                fields.forEach(field => {
                    const el = form.querySelector(`[name="${field}"]`);
                    if (el && el.value) {
                        el.readOnly = state;
                    } else {
                        el.readOnly = false;
                    };
                });

                // Disable selects too (like blood group, year)
                ['blood_group', 'dob', 'gender'].forEach(field => {
                    const el = form.querySelector(`[name="${field}"]`);
                    if (el && el.value) {
                        el.style.pointerEvents = state ? 'none' : 'auto';
                        el.style.backgroundColor = state ? '#e9ecef' : '';
                        el.setAttribute('data-readonly', state);
                    } else {
                        el.style.pointerEvents = 'auto';
                        el.style.backgroundColor = '';
                        el.setAttribute('data-readonly', false);
                    }
                });
            }

            // Donor Registration Form Ends here................................

            // function volunteerFields(elm) {
            //     let volunteer = document.getElementById('volunteer');
            //     const types = ['individual', 'ngo', 'charity', 'club'];
            //     volunteer.removeAttribute("style");
            //     document.querySelectorAll('.fields').forEach(field => {
            //         field.style.display = 'none';
            //     });
            //     switch (elm.value) {
            //         case "individual":
            //             document.getElementById('individual').style.display = 'block';
            //             break;
            //         case "ngo":
            //             document.getElementById('ngo').style.display = 'block';
            //             volunteer.style.maxWidth = '800px';
            //             break;
            //         case "charity":
            //             document.getElementById('charity').style.display = 'block';
            //             volunteer.style.maxWidth = '800px';
            //             break;
            //         case "club":
            //             document.getElementById('club').style.display = 'block';
            //             volunteer.style.maxWidth = '800px';
            //             break;
            //         default:
            //             break;
            //     }
            //     types.forEach(type => {
            //         const section = document.getElementById(type);
            //         const inputs = section.querySelectorAll('input, select, textarea');

            //         if (type === elm.value) {
            //             section.style.display = 'block';
            //             inputs.forEach(el => el.disabled = false);
            //         } else {
            //             section.style.display = 'none';
            //             inputs.forEach(el => {
            //                 el.disabled = true;
            //                 el.removeAttribute('required');
            //             });
            //         }
            //     });
            // }
            function volunteerFields(elm) {
                let volunteer = document.getElementById('volunteer');
                const types = ['individual', 'ngo', 'charity', 'club'];
                volunteer.removeAttribute("style");
                document.querySelectorAll('.fields').forEach(field => {
                    field.style.display = 'none';
                });
                switch (elm.value) {
                    case "individual":
                        document.getElementById('individual').style.display = 'block';
                        break;
                    case "ngo":
                        document.getElementById('ngo').style.display = 'block';
                        volunteer.style.maxWidth = '800px';
                        break;
                    case "charity":
                        document.getElementById('ngo').style.display = 'block';
                        volunteer.style.maxWidth = '800px';
                        break;
                    case "club":
                        document.getElementById('ngo').style.display = 'block';
                        volunteer.style.maxWidth = '800px';
                        break;
                    default:
                        break;
                }
                const sectionMap = {
                    individual: 'individual',
                    ngo: 'ngo',
                    charity: 'ngo',
                    club: 'ngo'
                };

                const activeSection = sectionMap[elm.value];

                ['individual', 'ngo'].forEach(sectionId => {
                    const section = document.getElementById(sectionId);
                    const inputs = section.querySelectorAll('input, select, textarea');

                    if (sectionId === activeSection) {
                        section.style.display = 'block';
                        inputs.forEach(input => input.disabled = false);
                    } else {
                        section.style.display = 'none';
                        inputs.forEach(input => {
                            input.disabled = true;
                            input.removeAttribute('required');
                        });
                    }
                });
            }

            function addMember() {
                let memberRow = document.querySelector('.member-row').cloneNode(true);
                memberRow.querySelectorAll('input').forEach(input => input.value = "");
                document.getElementById('members-area').appendChild(memberRow);
            }

            document.getElementById("volunteer_request_for_self").addEventListener('change', function() {

                const form = this.closest('form');
                console.log("form", form);
                if (this.checked) {
                    showLoader();
                    fetch(`{{ url('/') }}/api/v1/user`, {
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            fillForm(form, data, {
                                name: 'name',
                                email: 'email',
                                mobile: 'mobile',
                                whatsapp_number: 'whatsapp_number',
                                blood_group: 'blood_group',
                                dob: 'dob',
                                gender: 'gender',
                                address: 'address',
                                volunteer_latitude: 'latitude',
                                volunteer_longitude: 'longitude'
                            });
                            disableFields(form, true);
                        })
                        .catch(err => {
                            console.error("Error fetching user data", err);
                            alert("Failed to auto-fill data. Please try again.");
                            this.checked = false; // uncheck on error
                        })
                        .finally(() => {
                            hideLoader();
                        });
                } else {
                    clearForm(form);
                    disableFields(form, false);
                }
            });
            document.getElementById("volunteer_request_for_other").addEventListener('change', function() {
                const form = this.closest('form');
                clearForm(form);
                disableFields(form, false);
            });

            document.querySelector('#volunteer .registration-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                const form = this;
                let formData = new FormData(form);
                let action = "{{ route('volunteer.organization.registration') }}";
                if (formData.get("type") == "individual") {
                    action = this.action;
                }
                const token = localStorage.getItem('token');
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        Authorization: 'Bearer ' + token,
                        "Accept": "application/json"
                    },
                    body: formData
                });

                const data = await response.json();
                if (response.status == 409) {
                    showAlert("error", data.message || "Failed to submit request. Please check your input.");
                    hideLoader();
                    return;
                }
                if (!response.ok) {
                    showAlert("error", data.message || "Failed to submit request. Please check your input.");
                    hideLoader();
                    return;
                }
                if (response.status === 201) {
                    if (data?.data?.otp_sent) {
                        // Store the mobile number for later use
                        window.currentMobileForOTP = formData.get('mobile');
                        const masked = mobile.substring(0, 2) + "******" + formData.get('mobile').substring(8);
                        document.getElementById("otpMessage").textContent = "OTP sent to +91 " + masked;
                        // Show the modal
                        const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                        otpModal.show();
                    } else {
                        showAlert("success", data?.message || "Volunteer Registered Successfully.");
                        fetchRequests();
                        form.reset();
                    }
                }
            });
        </script>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful 🎉',
                    html: `
                <p style="font-size: 16px;">
                    You have successfully registered.<br><br>
                    📧 <strong>Please check your email</strong> to verify your account<br>
                    🔐 and <strong>set your password</strong>.
                </p>
            `,
                    confirmButtonText: 'Got it!',
                    confirmButtonColor: '#e63946',
                    backdrop: true
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `
                <p style="font-size: 16px;">
                    {{ session('error') }}<br><br>
                </p>
            `,
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#e63946',
                    backdrop: true
                });
            </script>
        @endif

        <!-- Showing validation errors (if any) -->
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'There were some problems',
                    html: `
                <ul style="font-size: 16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                    confirmButtonText: 'Got it!',
                    confirmButtonColor: '#e63946',
                    backdrop: true
                });
            </script>
        @endif
    @endsection
@endsection
