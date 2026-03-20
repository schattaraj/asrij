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
                        <h4 class="mb-0">{{ ucfirst($user->role) }} Settings</h4>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Profile -->
                            <div class="tab-pane fade show active" id="profile">
                                <h5 class="border-bottom pb-2">Basic Information</h5>

                                <p><strong>Name:</strong> {{ $user->name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Contact:</strong> {{ $user->mobile ?? '-' }}</p>
                                <p><strong>Pin Code:</strong> {{ $user->pin_code ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $user->address }}</p>

                                {{-- Role Specific --}}
                                @if (in_array('donor', $user->roles ?? []))
                                @include('profile.partials.donor', ['donor' => $donor])
                            @endif
                            
                            @if (in_array('receiver', $user->roles ?? []))
                                @include('profile.partials.receiver', ['receiver' => $receiver])
                            @endif
                            
                            @if (in_array('volunteer', $user->roles ?? []))
                                @include('profile.partials.volunteer', [
                                    'volunteer' => $volunteer,
                                    'extra' => $extra,
                                ])
                            @endif
                            </div>

                            <!-- Notifications -->
                            <div class="tab-pane fade" id="notifications">
                                <h5 class="border-bottom pb-2">Notification Settings</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Email Notifications</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">SMS Notifications</label>
                                </div>

                                <button class="btn btn-primary mt-3">
                                    Save Preferences
                                </button>
                            </div>

                            <!-- Password -->
                            <div class="tab-pane fade" id="password">
                                <h5 class="border-bottom pb-2">Change Password</h5>

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

                            <!-- Chat -->
                            <div class="tab-pane fade" id="chat">
                                <h5 class="border-bottom pb-2">Chat</h5>

                                <div class="alert alert-info">
                                    Chat module coming soon 🚀
                                </div>
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
            let activeTab = "{{ session('active_tab', 'profile') }}";

            let trigger = document.querySelector('#tab-' + activeTab);

            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        });
    </script>
@endsection
@endsection
