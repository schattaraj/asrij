@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Organization</h4>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.organization.update', $organization) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            {{-- Organization Details --}}
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Organization Name</label>
                                    <input type="text" name="organization_name" class="form-control"
                                        value="{{ old('organization_name', $organization->organization_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>
                                    <input type="text" name="type" class="form-control"
                                        value="{{ old('type', $organization->type) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Registration Number</label>
                                    <input type="text" name="registration_number" class="form-control"
                                        value="{{ old('registration_number', $organization->registration_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control"
                                        value="{{ old('contact_number', $organization->contact_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $organization->email) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ old('address', $organization->address) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">President Name</label>
                                    <input type="text" name="president_name" class="form-control"
                                        value="{{ old('president_name', $organization->president_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">President Number</label>
                                    <input type="text" name="president_number" class="form-control"
                                        value="{{ old('president_number', $organization->president_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Secretary Name</label>
                                    <input type="text" name="secretary_name" class="form-control"
                                        value="{{ old('secretary_name', $organization->secretary_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Secretary Number</label>
                                    <input type="text" name="secretary_number" class="form-control"
                                        value="{{ old('secretary_number', $organization->secretary_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Name</label>
                                    <input type="text" name="account_name" class="form-control"
                                        value="{{ old('account_name', $organization->account_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" name="account_number" class="form-control"
                                        value="{{ $organization->getRawOriginal('account_number') }}">
                                </div>

                            </div>

                            <hr>

                            {{-- Members --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Organization Members</h5>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Last Donation</th>
                                            <th>Available</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($organization->members as $member)
                                            <tr>

                                                <td>
                                                    {{ $member->user?->name }}

                                                    <input type="hidden" name="members[{{ $member->id }}][id]"
                                                        value="{{ $member->id }}">
                                                </td>

                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="members[{{ $member->id }}][position]"
                                                        value="{{ $member->position }}">
                                                </td>

                                                <td>
                                                    <input type="date" class="form-control"
                                                        name="members[{{ $member->id }}][last_donation]"
                                                        value="{{ optional($member->last_donation)->format('Y-m-d') }}">
                                                </td>

                                                <td>
                                                    <select class="form-select"
                                                        name="members[{{ $member->id }}][is_available]">

                                                        <option value="1"
                                                            {{ $member->is_available ? 'selected' : '' }}>
                                                            Yes
                                                        </option>

                                                        <option value="0"
                                                            {{ !$member->is_available ? 'selected' : '' }}>
                                                            No
                                                        </option>

                                                    </select>
                                                </td>
                                                <td>
                                                    @php
                                                        $isLastMember =
                                                            $organization->members->count() === 1 &&
                                                            $member->user_id === auth()->id();
                                                    @endphp
                                                    @if (!$isLastMember)
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#deleteMemberModal"
                                                            onclick="setDeleteMember(this,{{ $member->id }})">
                                                            Remove
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>

                            <hr>

                            {{-- Add New Member --}}
                            <h5>Add Member</h5>

                            <div class="row">

                                <div class="col-md-4">
                                    <label class="form-label">User</label>

                                    <select name="new_member[user_id]" class="form-select">

                                        <option value="">
                                            Select User
                                        </option>

                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Position</label>

                                    <input type="text" class="form-control" name="new_member[position]">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Last Donation</label>

                                    <input type="date" class="form-control" name="new_member[last_donation]">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Available</label>

                                    <select class="form-select" name="new_member[is_available]">

                                        <option value="1">Yes</option>
                                        <option value="0">No</option>

                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                Update Organization
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteMemberModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to remove this member?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <form id="deleteMemberForm" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            Yes, Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
    function setDeleteMember(e,memberId) {
        e.preventDefault;
        let url = "{{ route('admin.organization.member.destroy', ':id') }}";
        url = url.replace(':id', memberId);

        document.getElementById('deleteMemberForm').action = url;
    }
</script>
@endsection
