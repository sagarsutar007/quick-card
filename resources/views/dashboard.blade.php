@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <div>
                    <h2 class="fs-7">{{ $schoolCount }}</h2>
                    <h6 class="fw-medium text-info mb-0">Total Schools</h6>
                </div>
                <div class="ms-auto">
                    <span class="text-info display-6"><i class="ti ti-building-community"></i></span>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-primary">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                <div>
                    <h2 class="fs-7">{{ $studentCount }}</h2>
                    <h6 class="fw-medium text-primary mb-0">Total Students</h6>
                </div>
                <div class="ms-auto">
                    <span class="text-primary display-6"><i class="ti ti-users"></i></span>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-success">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                <div>
                    <h2 class="fs-7">{{ $studentsWithPhoto }}</h2>
                    <h6 class="fw-medium text-success mb-0">With Photos</h6>
                </div>
                <div class="ms-auto">
                    <span class="text-success display-6"><i class="ti ti-camera-check"></i></span>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-bottom border-danger">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                <div>
                    <h2 class="fs-7">{{ $studentsWithoutPhoto }}</h2>
                    <h6 class="fw-medium text-danger mb-0">Without Photos</h6>
                </div>
                <div class="ms-auto">
                    <span class="text-danger display-6"><i class="ti ti-camera-off"></i></span>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                <div class="card-body py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-sm-6">
                      <h5 class="fw-semibold mb-9 fs-5">Welcome back {{ Auth::user()->name }}!</h5>
                        @if ($studentsWithoutPhoto)
                            <p class="mb-3">
                                There are <strong>{{ $studentsWithoutPhoto }}</strong> students without profile photos. Keeping student records complete helps maintain data accuracy and visual identification.
                            </p>
                            <p class="mb-3 text-muted">
                                Let's ensure every student has a proper profile image uploaded.
                            </p>
                            <a href="{{ route('management.schools') }}" class="btn btn-warning">Upload Missing Photos</a>
                        @else
                            <p class="mb-3">
                                🎉 Fantastic! All student profile photos are up-to-date.
                            </p>
                            <p class="mb-3 text-muted">
                                This means your records are well maintained. Keep up the great work!
                            </p>
                            <a href="{{ route('management.my-profile') }}" class="btn btn-success">Visit Your Profile</a>
                        @endif

                    </div>
                    <div class="col-sm-5">
                        <div class="position-relative mb-n7 text-end">
                            @if (auth()->user()->gender === 'female')
                                <img src="{{ asset('images/backgrounds/welcome-bg2.png') }}" alt="" class="img-fluid">
                            @else
                                <img src="{{ asset('images/backgrounds/track-bg.png') }}" alt="" class="img-fluid" style="width: 180px; height: 230px;">
                            @endif
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-0">Recent Activities</h5>
                    <div class="card shadow-none mt-0 mb-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-0">User</th>
                                    <th>Event</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark ">
                                @forelse ($latestActivities as $activity)
                                <tr>
                                    <td class="ps-0 text-truncate">
                                        @php
                                            $profilePath = public_path('uploads/images/profile/' . $activity->user->profile_image);
                                        @endphp 
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 pe-1">
                                                <img src="{{ $activity->user->profile_image && file_exists($profilePath) ? asset('uploads/images/profile/' . $activity->user->profile_image) : asset('images/profile/user-1.jpg') }}" class="rounded-circle" width="40" height="40" alt="">
                                            </div>
                                            <div>
                                                <h6 class="fw-semibold mb-1">{{ $activity->user->name ?? 'Unknown' }}</h6>
                                                <p class="fs-2 mb-0 text-muted">{{ ucfirst($activity->user->designation ?? $activity->user?->getRoleNames()->first()) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $activity->action }}
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->created_at->format('d F, Y h:i A') }}</td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No recent activity</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection