@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Testimonials</h4>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
            Add Testimonial
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
                        <th>Name</th>
                        <th>Profession</th>
                        <th>Message</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td>{{ $testimonial->id }}</td>

                            <td>
                                @if($testimonial->image)
                                    <img src="{{ asset('/storage/app/public/' . $testimonial->image) }}"
                                         width="80"
                                         class="img-thumbnail">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>

                            <td>{{ $testimonial->name }}</td>

                            <td>{{ $testimonial->profession ?? '-' }}</td>

                            <td>{{ \Illuminate\Support\Str::limit($testimonial->message, 60) }}</td>

                            <td>{{ $testimonial->sort_order }}</td>

                            <td>
                                @if($testimonial->status)
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
                                <a href="{{ route('admin.testimonials.edit',$testimonial->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.testimonials.destroy',$testimonial->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this testimonial?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                No testimonials found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
