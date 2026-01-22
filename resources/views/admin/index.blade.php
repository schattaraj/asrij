@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard
        </h3>
        {{-- <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li>
                </ul>
              </nav> --}}
    </div>
    <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="dist/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        Donors <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $donors->count() }}</h2>
                    <h6 class="card-text">
                        {{ $donorGrowth >= 0 ? 'Increased' : 'Decreased' }} by {{ abs($donorGrowth) }}%
                    </h6>
                </div>
            </div>
        </div>

        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="dist/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        Receiver <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $receivers->count() }}</h2>
                    <h6 class="card-text">
                        {{ $receiverGrowth >= 0 ? 'Increased' : 'Decreased' }} by {{ abs($receiverGrowth) }}%
                    </h6>
                </div>
            </div>
        </div>

        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="dist/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">
                        Volunteers <i class="mdi mdi-diamond mdi-24px float-end"></i>
                    </h4>
                    <h2 class="mb-5">{{ $volunteers->count() }}</h2>
                    <h6 class="card-text">
                        {{ $volunteerGrowth >= 0 ? 'Increased' : 'Decreased' }} by {{ abs($volunteerGrowth) }}%
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Registration</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->sortByDesc('created_at')->take(10) as $user)
                                    <tr>
                                        <td>
                                            <img src="dist/assets/images/faces/face1.jpg" class="me-2" alt="image">
                                            {{ $user->name }}
                                        </td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            <label class="badge badge-gradient-success">ACTIVE</label>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
