@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                        {{-- <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            <span class="badge {{ $user->status == 'pending' ? 'bg-warning' : ($user->status == 'active' ? 'bg-success' : 'bg-danger') }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <!-- Update Status Form -->
                                            <form action="" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-select" onchange="this.form.submit()">
                                                    <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> --}}
@stop
