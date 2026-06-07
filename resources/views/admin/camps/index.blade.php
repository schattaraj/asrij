@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Blood Camps</h4>

        <a href="{{ route('admin.camps.create') }}" class="btn btn-primary">
            + Add Camp
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($camps as $camp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                @if($camp->image)
                                    <img src="{{ asset('/storage/app/public/'.$camp->image) }}"
                                         width="60"
                                         height="60"
                                         style="object-fit:cover;">
                                @endif
                            </td>

                            <td>{{ $camp->title }}</td>

                            <td>{{ \Carbon\Carbon::parse($camp->camp_date)->format('d M Y') }}</td>

                            <td>{{ $camp->start_time }} - {{ $camp->end_time }}</td>

                            <td>{{ $camp->location }}</td>

                            <td>
                                @if($camp->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.camps.edit', $camp->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('admin.camps.destroy', $camp->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this camp?')">
                                        Delete
                                    </button>

                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection