@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<div class="card">
    <div class="card-header">
        <h4>Edit Testimonial</h4>
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

        <form action="{{ route('admin.testimonials.update',$testimonial->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name',$testimonial->name) }}">
            </div>

            <div class="mb-3">
                <label>Profession</label>
                <input type="text"
                       name="profession"
                       class="form-control"
                       placeholder="e.g. Doctor, Teacher, Donor"
                       value="{{ old('profession',$testimonial->profession) }}">
            </div>

            <div class="mb-3">
                <label>Message</label>
                <textarea name="message"
                          class="form-control"
                          rows="4">{{ old('message',$testimonial->message) }}</textarea>
            </div>

            @if($testimonial->image)
                <div class="mb-3">
                    <label>Current Image</label>
                    <br>

                    <img src="{{ asset('/storage/app/public/' . $testimonial->image) }}"
                         width="150"
                         class="img-thumbnail">
                </div>
            @endif

            <div class="mb-3">
                <label>Change Image</label>
                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Sort Order</label>
                <input type="number"
                       name="sort_order"
                       class="form-control"
                       value="{{ old('sort_order',$testimonial->sort_order) }}">
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" class="form-select">
                    <option value="1"
                        {{ $testimonial->status == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ $testimonial->status == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <button class="btn btn-primary">
                Update Testimonial
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
