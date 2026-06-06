@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">Edit Blood Camp</h4>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.camps.update', $camp->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Title</label>
                        <input type="text" name="title"
                               value="{{ $camp->title }}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Date</label>
                        <input type="date" name="camp_date"
                               value="{{ $camp->camp_date }}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Start Time</label>
                        <input type="text" name="start_time"
                               value="{{ $camp->start_time }}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>End Time</label>
                        <input type="text" name="end_time"
                               value="{{ $camp->end_time }}"
                               class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Location</label>
                        <input type="text" name="location"
                               value="{{ $camp->location }}"
                               class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4">{{ $camp->description }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Image</label>

                        @if($camp->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$camp->image) }}"
                                     width="100">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $camp->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$camp->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>

                <button class="btn btn-success">
                    Update Camp
                </button>

            </form>

        </div>
    </div>

</div>

@endsection