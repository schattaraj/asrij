@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<div class="card">
    <div class="card-header">
        <h4>Add Testimonial</h4>
    </div>

    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.testimonials.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label>Profession</label>
                <input type="text"
                       name="profession"
                       class="form-control"
                       placeholder="e.g. Doctor, Teacher, Donor"
                       value="{{ old('profession') }}">
            </div>

            <div class="mb-3">
                <label>Message</label>
                <textarea name="message"
                          class="form-control"
                          rows="4">{{ old('message') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Sort Order</label>
                <input type="number"
                       name="sort_order"
                       class="form-control"
                       value="{{ old('sort_order', 0) }}">
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary">
                Save Testimonial
            </button>

            <a href="{{ route('admin.testimonials.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

</div>
@endsection
