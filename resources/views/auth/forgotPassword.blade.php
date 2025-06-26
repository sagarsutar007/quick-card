@extends('layouts.auth')

@section('title', 'Forgot Password | Social Connoisseurs Management Portal')

@section('content')
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 col-xxl-3">
                    <div class="card mb-0">
                        <div class="card-body pt-5">
                            <a href="{{ route('home') }}" class="text-nowrap logo-img text-center d-block mb-4">
                                <img src="{{ asset('images/logos/black-logo.png') }}" width="180" alt="">
                            </a>
                            <div class="mb-5 text-center">
                            <p class="mb-0 ">   
                                Please enter the email address associated with your account and get a link to reset your password.                
                            </p>
                            </div>
                            <form method="POST" action="{{ route('management.forgot-password.submit') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="example@email.com" autofocus>
                                </div>
                                <button class="btn btn-primary w-100 py-8 mb-3 btn-loading">Continue</button>
                                <a href="{{ route('login.management') }}" class="btn btn-light-primary text-primary w-100 py-8"><i class="ti ti-arrow-left fs-5"></i> Back to Login</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
