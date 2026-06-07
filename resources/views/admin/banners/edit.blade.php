@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<div class="card">
    <div class="card-header">
        <h4>Edit Banner</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.banners.update',$banner->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Title</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title',$banner->title) }}">
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="4">{{ old('description',$banner->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Current Image</label>
                <br>

                <img src="{{ asset('/storage/app/public/' . $banner->image) }}"
                     width="200"
                     class="img-thumbnail">
            </div>

            <div class="mb-3">
                <label>Change Image</label>
                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Button Text</label>
                <input type="text"
                       name="button_text"
                       class="form-control"
                       value="{{ old('button_text',$banner->button_text) }}">
            </div>

            <div class="mb-3">
                <label>Button Link</label>
                <input type="text"
                       name="button_link"
                       class="form-control"
                       value="{{ old('button_link',$banner->button_link) }}">
            </div>

            <div class="mb-3">
                <label>Sort Order</label>
                <input type="number"
                       name="sort_order"
                       class="form-control"
                       value="{{ old('sort_order',$banner->sort_order) }}">
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" class="form-select">
                    <option value="1"
                        {{ $banner->status == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ $banner->status == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <button class="btn btn-primary">
                Update Banner
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
