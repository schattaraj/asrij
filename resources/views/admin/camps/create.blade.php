@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">Add Blood Camp</h4>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.camps.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="camp_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="text" name="start_time" class="form-control" placeholder="10:00 AM">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="text" name="end_time" class="form-control" placeholder="3:00 PM">
                    </div>

                    <div class="col-md-12 mb-3">
                        {{-- <input type="text" name="location" class="form-control"> --}}
                            <input type="hidden" name="camp_latitude" id="camp_latitude">
                            <input type="hidden" name="camp_longitude" id="camp_longitude">
                            <div class="input-group">
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" name="location" autocomplete="off"
                                        id="camp_address" placeholder="Address" readonly required>
                                    <label for="address">Location</label>
                                </div>
                                <button type="button" class="btn btn-outline-danger open-location-modal"
                                    data-bs-toggle="modal" data-bs-target="#locationModal"
                                    data-location-input="camp_address" data-lat="camp_latitude"
                                    data-lng="camp_longitude">
                                    Change
                                </button>
                            </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <button class="btn btn-primary">
                    Save Camp
                </button>

            </form>

        </div>
    </div>

</div>

@endsection