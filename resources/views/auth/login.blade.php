@extends('layouts.auth')

@section('title', 'Quick Card | Management Portal Login')

@section('content')
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
          <div class="card mb-0">
            <div class="card-body">
              <a href="{{ route('home') }}" class="text-nowrap logo-img text-center d-block mb-3 w-100">
                <img src="{{ asset('images/logos/black-logo.png') }}" width="180" alt="">
              </a>

              <!-- Divider -->
              <div class="position-relative text-center mb-4 mt-2">
                <p class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">Log in to your account</p>
                <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
              </div>

              <!-- Feedback Messages -->
                @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    @if ($errors->has('status'))
                        <div class="alert alert-danger">
                            {{ $errors->first('status') }}
                        </div>
                    @endif
                @endif

              <!-- Login Form -->
              <form method="POST" action="{{ route('management.login') }}">
                @csrf
                <div class="mb-3">
                    <label for="emailOrPhone" class="form-label">Email or Phone</label>
                    <input type="text" class="form-control @error('emailOrPhone') is-invalid @enderror" id="emailOrPhone" name="emailOrPhone" placeholder="example@email.com" value="{{ old('emailOrPhone') }}" autofocus >
                    @error('emailOrPhone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 position-relative input-wrapper">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="*********">
                    <span class="append-icon toggle-password"><i class="bi bi-eye-slash-fill"></i></span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label text-dark" for="remember">Keep me logged in</label>
                  </div>
                  <a class="text-primary fw-medium" href="{{ route('management.forgot-password') }}">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2 btn-loading"><i class="bi bi-box-arrow-in-right me-1"></i> Login</button>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
