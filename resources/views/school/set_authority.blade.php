@extends('layouts.main')

@section('title', 'Set Authority')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Set Authority</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.schools') }}">Schools</a></li>
                            <li class="breadcrumb-item" aria-current="page">Set Authority</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">  
                        <img src="{{ asset('images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-3">Set Authority</h5>
            </div>
            <form method="post" action="{{ route('schools.saveAuthority', $school->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name here">
                            <label for="name">Authorised Person Name</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                            <label for="phone">Phone No</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="toggle-password-field position-relative input-wrapper mb-3">
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                <label><i class="ti ti-lock me-2 fs-4"></i>Password</label>
                            </div>
                            <span class="toggle-password-icon"><i class="ti ti-eye fs-5"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-group mb-3">
                            <input class="form-control" name="profile_image" type="file" id="profileImg" style="height: 58px;line-height: 40px;"/>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="Address" name="address" placeholder="Enter address here">
                            <label for="Address">Address</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3 border rounded-2" style="border-color: #dfe5ef!important;">
                            <label class="fs-2 text-muted mb-2" for="active-radio" style="padding: 0px 14px; margin: 3px 0px;">Status</label>
                            <br>
                            <div class="form-check form-check-inline ms-3 mb-2 mt-1">
                                <input class="form-check-input danger check-outline outline-danger" type="radio" id="active-radio" name="status" value="1" checked>
                                <label class="form-check-label" for="active-radio">Active</label>
                            </div>
                            <div class="form-check form-check-inline mb-2 mt-1">
                                <input class="form-check-input danger check-outline outline-danger" type="radio" id="in-active-radio" name="status" value="0">
                                <label class="form-check-label" for="in-active-radio">In-active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('management.schools') }}" class="btn btn-outline-dark">Skip <i class="bi bi-arrow-right-short ms-2"></i></a>
                        <button type="submit" class="btn btn-primary font-medium waves-effect btn-loading">Set Authority <i class="bi bi-arrow-right ms-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>    
@endsection

@section('js')
<script>
    $(document).ready(function () {

    });
</script>
@endsection
