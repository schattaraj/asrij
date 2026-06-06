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
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Date</label>
                        <input type="date" name="camp_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Start Time</label>
                        <input type="text" name="start_time" class="form-control" placeholder="10:00 AM">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>End Time</label>
                        <input type="text" name="end_time" class="form-control" placeholder="3:00 PM">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
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