@extends('layouts.profile')
@section('content')
    <style>
        .card p {
            margin-bottom: 6px;
        }
    </style>
    <div class="container mb-5">
        <div class="row">

            <!-- RIGHT CONTENT -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Settings</h4>
                    </div>

                    <div class="card-body">
                        <nav class="mb-3">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                              <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</button>
                              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Change Password</button>
                            </div>
                          </nav>
                        <div class="tab-content">
                            <!-- Profile -->
                            <div class="tab-pane fade show active" id="profile">                            
                                <form id="profileForm">
                                    @csrf
                            
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" id="userName" name="name" class="form-control">
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label">Mobile</label>
                                        <input type="text" id="userMobile" name="mobile" class="form-control" readonly>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label">Blood Group</label>
                                        <select id="userBlood" name="blood_group" class="form-control">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label">DOB</label>
                                        <input type="date" id="userDOB" name="dob" class="form-control">
                                    </div>
                            
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                    
                                        <div class="input-group">
                                            <input type="text"
                                                   id="userAddress"
                                                   name="address"
                                                   class="form-control"
                                                   readonly>
                                    
                                            <button type="button"
                                                    class="btn btn-outline-danger open-location-modal"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#locationModal"
                                                    data-location-input="userAddress"
                                                    data-lat="userLat"
                                                    data-lng="userLng">
                                                Change
                                            </button>
                                        </div>
                                    
                                        <input type="hidden" id="userLat" name="latitude">
                                        <input type="hidden" id="userLng" name="longitude">
                                    </div>
                            
                                    <button type="button" class="btn btn-primary" onclick="updateProfile()">
                                        Update Profile
                                    </button>
                                </form>
                            </div>
                            <!-- Password -->
                            <div class="tab-pane fade" id="password">
                                <form method="POST" action="{{ route('profile.password.update') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Current Password</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                        @error('current_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>New Password</label>
                                        <input type="password" name="new_password" class="form-control" required>
                                        @error('new_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                            required>
                                    </div>

                                    <button class="btn btn-warning">
                                        Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0d6efd'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc3545'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                confirmButtonColor: '#dc3545'
            });
        @endif
        document.addEventListener('DOMContentLoaded', function() {
            // let activeTab = "{{ session('active_tab', 'profile') }}";

            // let trigger = document.querySelector('#tab-' + activeTab);

            // if (trigger) {
            //     new bootstrap.Tab(trigger).show();
            // }
            loadUserProfile();
        });

        function loadUserProfile(retryCount = 0) {
           showLoader();
            if (userData && userData !== null) {
                displayUserProfile(userData);
                hideLoader();
            } else {
                if (retryCount < 5) { // retry max 5 times
                    console.log("userData not available, retrying...");
                    setTimeout(() => {
                        loadUserProfile(retryCount + 1);
                    }, 2000); // retry after 2 seconds
                } else {
                    console.error("Failed to load userData");
                }
            }
        }

        function displayUserProfile(user) {
            document.getElementById("userName").value = user.name || "";
    document.getElementById("userMobile").value = user.mobile || "";

    document.getElementById("userBlood").value = user.blood_group || "";
    document.getElementById("userDOB").value = user.dob || "";

    document.getElementById("userAddress").value = user.address || "";
    document.getElementById("userLat").value = user.latitude || "";
    document.getElementById("userLng").value = user.longitude || "";
        }
    </script>
@endsection
@endsection
