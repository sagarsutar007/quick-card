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
                            <div class="mb-4 text-center">
                                <p class="mb-0 ">   
                                    Please enter your new password.                
                                </p>
                            </div>
                            <!-- Feedback Messages -->
                            @if (session('status'))
                                <div class="alert alert-success text-center">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <!-- Required Hidden Fields -->
                                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                                <input type="hidden" name="email" value="{{ request('email') }}">

                                <div class="mb-4 position-relative input-wrapper">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                                    <span class="append-icon toggle-password"><i class="bi bi-eye-slash-fill"></i></span>
                                </div>

                                <div class="mb-4 position-relative input-wrapper">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter Password">
                                    <span class="append-icon toggle-password"><i class="bi bi-eye-slash-fill"></i></span>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-8 mb-3 btn-loading">Reset Password</button>
                                <a href="{{ route('login.management') }}" class="btn btn-light-primary text-primary w-100 py-8">Back to Login</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
