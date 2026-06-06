@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<div class="card">
    <div class="card-header">
        <h4>Add Banner</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.banners.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label>Title</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Button Text</label>
                <input type="text"
                       name="button_text"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Button Link</label>
                <input type="text"
                       name="button_link"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Sort Order</label>
                <input type="number"
                       name="sort_order"
                       class="form-control"
                       value="0">
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary">
                Save Banner
            </button>

            <a href="{{ route('admin.banners.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>
</div>

</div>
@endsection
