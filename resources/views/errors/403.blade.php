@extends('layouts.main')

@section('title', 'Access Denied!')

@section('content')
<div class="d-flex align-items-center justify-content-center w-100">
    <div class="row justify-content-center w-100">
        <div class="col-lg-4">
            <div class="text-center">
                <img src="{{ asset('images/backgrounds/login-security.svg') }}" alt="" class="img-fluid" width="500">
                <h1 class="fw-semibold my-7 fs-9">Access Denied!</h1>
                <h5 class="fw-semibold mb-7">You don't have permission to view this page!</h5>
                <a class="btn btn-primary" href="{{ route('management.dashboard') }}" role="button">Go Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection