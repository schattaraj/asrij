@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<div class="card">
    <div class="card-header">
        <h4>Donation QR Code</h4>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="text-muted">
            This QR code is shown to users in the "Donate" popup on the website. Upload a new image to update it everywhere instantly.
        </p>

        <div class="mb-4">
            <label class="d-block mb-2"><strong>Current QR Code</strong></label>
            @if($qr)
                <img src="{{ asset('/storage/app/public/' . $qr) }}"
                     width="220"
                     class="img-thumbnail">
            @else
                <p class="text-muted">No QR code uploaded yet. A default image is being shown on the site.</p>
            @endif
        </div>

        <form action="{{ route('admin.donation-settings.update') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Upload New QR Code</label>
                <input type="file"
                       name="qr_image"
                       class="form-control"
                       accept="image/*"
                       required>
                <small class="text-muted">Accepted formats: JPG, JPEG, PNG (max 4MB).</small>
            </div>

            <button class="btn btn-primary">
                Update QR Code
            </button>
        </form>

    </div>
</div>

</div>
@endsection
