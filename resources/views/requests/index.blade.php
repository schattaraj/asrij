@extends('layouts.app')

@section('content')

<div class="container my-5">
  <h3 class="fw-bold mb-4">All Blood Requests</h3>

  <div class="row g-3">
    @foreach($requests as $req)
      <div class="col-md-6 col-lg-4 col-xl-3 d-flex">
        <div class="request-card w-100 {{ $req->urgency === 'urgent' ? 'urgent-card' : '' }}">
          
          @if($req->urgency === 'urgent')
            <span class="badge bg-danger urgent-tag">URGENT</span>
          @endif

          <div class="blood-group">{{ $req->blood }}</div>

          <div class="meta">{{ $req->patient }}</div>
          <div class="meta">{{ $req->hospital }}</div>
          <div class="meta">{{ $req->address }}</div>
          <div class="meta mb-2">Units: {{ $req->units }}</div>

          <button class="btn btn-sm btn-danger w-100 mt-auto">
            Donate
          </button>

        </div>
      </div>
    @endforeach
  </div>
</div>

@endsection