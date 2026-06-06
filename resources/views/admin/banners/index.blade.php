@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Banners</h4>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        Add Banner
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>{{ $banner->id }}</td>

                        <td>
                            <img src="{{ asset('/public/storage/' . $banner->image) }}"
                                 width="120"
                                 class="img-thumbnail">
                        </td>

                        <td>{{ $banner->title }}</td>

                        <td>{{ $banner->sort_order }}</td>

                        <td>
                            @if($banner->status)
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.banners.edit',$banner->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.banners.destroy',$banner->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this banner?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No banners found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

</div>
@endsection
