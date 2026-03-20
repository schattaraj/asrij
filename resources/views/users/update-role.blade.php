@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h4>Update User Role</h4>
                </div>

                <div class="card-body">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- User Info --}}
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $user->name }} <br>
                        <strong>Email:</strong> {{ $user->email }} <br>
                        <strong>Current Roles:</strong> 
                        {{ is_array($user->roles) ? implode(', ', $user->roles) : $user->roles }}
                    </div>

                    <hr>

                    {{-- Update Role Form --}}
                    <form action="{{ route('users.update.role', $user->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="role" class="form-label">Select Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Select Role --</option>
                                <option value="donor">Donor</option>
                                <option value="receiver">Receiver</option>
                                <option value="volunteer">Volunteer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Update Role
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection