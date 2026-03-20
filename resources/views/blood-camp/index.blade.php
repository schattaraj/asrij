@extends('layouts.profile')
@section('title', 'Blood Camps')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
       <h3 class="mb-0">Blood Camps</h3>
       <a href="#" class="btn btn-primary">Add Blood Camp</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A Campus Blood Mission</td>
                        <td>17Feb, 2026</td>
                        <td>Pure Life Hospital</td>
                        <td>
                            <div class="d-flex align-items-center">
                            <a href="#" class="btn btn-success me-2"><i class="ti ti-pencil"></i></a>
                            <a href="#" class="btn btn-danger"><i class="ti ti-trash"></i></a>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection