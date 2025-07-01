@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">My Profile</h4>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">My Profile</li>
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
        <div class="card-body p-0">
            @php
                $coverPath = public_path('uploads/images/cover/' . Auth::user()->cover_image);
            @endphp    
            <img src="{{ Auth::user()->cover_image && file_exists($coverPath) ? asset('uploads/images/cover/' . Auth::user()->cover_image) : asset('images/backgrounds/profilebg.jpg') }}" alt="" class="w-100 h-100">
            <div class="row align-items-center">
            <div class="col-lg-4 order-lg-1 order-2">
                <div class="d-flex align-items-center justify-content-around m-4">
                <div class="text-center">
                    <i class="ti ti-file-description fs-6 d-block mb-2"></i>
                    <h4 class="mb-0 fw-semibold lh-1">0</h4>
                    <p class="mb-0 fs-4">Students</p>
                </div>
                <div class="text-center">
                    <i class="ti ti-user-circle fs-6 d-block mb-2"></i>
                    <h4 class="mb-0 fw-semibold lh-1">0</h4>
                    <p class="mb-0 fs-4">Uploaded</p>
                </div>
                <div class="text-center">
                    <i class="ti ti-user-check fs-6 d-block mb-2"></i>
                    <h4 class="mb-0 fw-semibold lh-1">0</h4>
                    <p class="mb-0 fs-4">Locked</p>
                </div>
                </div>
            </div>
            <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                <div class="mt-n5">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;";>
                            <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;";>
                                @php
                                    $profilePath = public_path('uploads/images/profile/' . Auth::user()->profile_image);
                                @endphp    
                                <img src="{{ Auth::user()->profile_image && file_exists($profilePath) ? asset('uploads/images/profile/' . Auth::user()->profile_image) : asset('images/profile/user-1.jpg') }}" alt="" class="w-100 h-100">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="fs-5 mb-0 fw-semibold">{{ Auth::user()->name }}</h5>
                        <p class="mb-0 fs-4">{{ ucfirst(Auth::user()->getRoleNames()->first()) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-last">
                <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-center my-3 gap-3">
                    @if(!empty($user->facebook))
                    <li class="position-relative">
                        <a class="text-white d-flex align-items-center justify-content-center bg-primary p-2 fs-4 rounded-circle" href="{{ $user->facebook }}" width="30" height="30">
                            <i class="ti ti-brand-facebook"></i>
                        </a>
                    </li>
                    @endif
                    @if(!empty($user->whatsapp))
                    <li class="position-relative">
                        <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle" href="https://wa.me/+91{{ $user->whatsapp }}">
                            <i class="ti ti-brand-whatsapp"></i>
                        </a>
                    </li>
                    @endif
                    @if(!empty($user->twitter))
                    <li class="position-relative">
                        <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle" href="{{ $user->twitter }}">
                            <i class="ti ti-brand-twitter"></i>
                        </a>
                    </li>
                    @endif
                    @if(!empty($user->instagram))
                    <li class="position-relative">
                        <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle" href="{{ $user->instagram }}">
                            <i class="ti ti-brand-instagram"></i>
                        </a>
                    </li>
                    @endif
                    @if(!empty($user->instagram))
                    <li class="position-relative">
                        <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle" href="{{ $user->youtube }}">
                            <i class="ti ti-brand-youtube"></i>
                        </a>
                    </li>
                    @endif
                    @if(!empty($user->email))
                    <li class="position-relative">
                        <a class="text-white bg-danger d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle" href="{{ $user->email }}">
                            <i class="ti ti-mail"></i>
                        </a>
                    </li>
                    @endif
                    @if(!empty($user->phone))
                    <li class="position-relative">
                        <a class="text-white bg-danger d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle" href="{{ $user->phone }}">
                            <i class="ti ti-phone"></i>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            </div>
            <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">
                        <i class="ti ti-user-circle me-2 fs-6"></i>
                        <span class="d-none d-md-block">Profile</span> 
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password" aria-selected="true">
                        <i class="ti ti-key me-2 fs-6"></i>
                        <span class="d-none d-md-block">Password</span> 
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-social-tab" data-bs-toggle="pill" data-bs-target="#pills-social" type="button" role="tab" aria-controls="pills-social" aria-selected="false">
                        <i class="ti ti-heart me-2 fs-6"></i>
                        <span class="d-none d-md-block">Social</span> 
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#pills-notifications" type="button" role="tab" aria-controls="pills-notifications" aria-selected="false">
                        <i class="ti ti-bell me-2 fs-6"></i>
                        <span class="d-none d-md-block">Notifications</span> 
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-logs-tab" data-bs-toggle="pill" data-bs-target="#pills-logs" type="button" role="tab" aria-controls="pills-logs" aria-selected="false">
                        <i class="ti ti-file-text me-2 fs-6"></i>
                        <span class="d-none d-md-block">Logs</span> 
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <x-profile-card 
                        intro="{{ $user->about }}"
                        institute="{{ $user->designation }}"
                        email="{{ $user->email }}"
                        phone="+91 {{ $user->phone }}"
                        address="{{ $user->address }}"
                    />
                </div>
                <div class="col-lg-8">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card shadow-none border">
                            <div class="card-body">
                            <h5 class="mb-3">Profile Details</h5>
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="text"
                                            name="name"
                                            class="form-control"
                                            id="tb-fname"
                                            placeholder="Enter Name here"
                                            value="{{ $user->name }}"
                                        />
                                        <label for="tb-fname">Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="text"
                                            name="designation"
                                            class="form-control"
                                            id="tb-designation"
                                            placeholder="Enter Designation"
                                            value="{{ $user->designation }}"
                                        />
                                        <label for="tb-designation">Designation</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="number"
                                            name="phone"
                                            class="form-control"
                                            id="tb-phone"
                                            placeholder="Enter Phone"
                                            value="{{ $user->phone }}"
                                        />
                                        <label for="tb-phone">Phone</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            id="tb-email"
                                            placeholder="Enter Email"
                                            value="{{ $user->email }}"
                                        />
                                        <label for="tb-email">Email</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="text"
                                            name="address"
                                            class="form-control"
                                            id="tb-address"
                                            placeholder="Enter Address"
                                            value="{{ $user->address }}"
                                        />
                                        <label for="tb-address">Address (Area, City, Dist-Pincode)</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-floating mb-3 border rounded-2" style="border-color: #dfe5ef!important;">
                                        <div class="fs-2 text-muted mb-0 pt-2 ps-3" for="active-radio">Gender</div>
                                        <div class="form-check form-check-inline ms-3 mb-0 mt-1">
                                            <input class="form-check-input danger check-outline outline-danger" type="radio" id="active-radio" name="gender" value="male" {{ old('gender', $user->gender) === 'male' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active-radio">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input danger check-outline outline-danger" type="radio" id="in-active-radio" name="gender" value="female" {{ old('gender', $user->gender) === 'female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="in-active-radio">Female</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-3">About</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="about" placeholder="Enter text here" id="tb-about" style="height: 120px">{{ $user->about }}</textarea>
                                        <label for="tb-about" class="p-3">Enter about here</label>
                                    </div>
                                </div>
                            </div>
                            <!-- <h5 class="mb-3">Cover / Profile Images</h5> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coverFile" class="form-label">Cover Image</label>
                                        <input class="form-control" name="cover_image" type="file" id="coverFile" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="profileImg" class="form-label">Profile Picture</label>
                                        <input class="form-control" name="profile_image" type="file" id="profileImg" />
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary font-medium rounded-pill px-4 float-end mt-3 btn-loading">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-check me-2 fs-4"></i> Save Details
                                </div>
                            </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <x-profile-card 
                        intro="{{ $user->about }}"
                        institute="{{ $user->designation }}"
                        email="{{ $user->email }}"
                        phone="{{ $user->phone }}"
                        address="{{ $user->address }}"
                    />
                </div>
                <div class="col-lg-8">
                    <form action="{{ route('profile.update.password') }}" method="POST">
                        @csrf
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <h5 class="mb-3">Change Password</h5>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="toggle-password-field position-relative input-wrapper mb-3">
                                            <div class="form-floating">
                                                <input type="password" name="password" class="form-control" placeholder="Password">
                                                <label><i class="ti ti-lock me-2 fs-4"></i>Password</label>
                                            </div>
                                            <span class="toggle-password-icon"><i class="ti ti-eye fs-5"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="toggle-password-field position-relative input-wrapper mb-3">
                                            <div class="form-floating">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                                                <label><i class="ti ti-lock me-2 fs-4"></i>Confirm Password</label>
                                            </div>
                                            <span class="toggle-password-icon"><i class="ti ti-eye fs-5"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary font-medium rounded-pill px-4 float-end mt-3 btn-loading">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-check me-2 fs-4"></i> Update Password
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-social" role="tabpanel" aria-labelledby="pills-social-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <x-profile-card 
                        intro="{{ $user->about }}"
                        institute="{{ $user->designation }}"
                        email="{{ $user->email }}"
                        phone="{{ $user->phone }}"
                        address="{{ $user->address }}"
                    />
                </div>
                <div class="col-lg-8">
                    <form action="{{ route('profile.update.social') }}" method="POST">
                    @csrf
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <h5 class="mb-3">Social Media Links</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="url" name="facebook" class="form-control" placeholder="Facebook" value="{{ $user->facebook }}">
                                        <label><i class="ti ti-brand-facebook me-2 fs-4"></i>Facebook Account URL</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" name="whatsapp" class="form-control" placeholder="Whatsapp" value="{{ $user->whatsapp }}">
                                        <label><i class="ti ti-brand-whatsapp me-2 fs-4"></i>WhatsApp Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="url" name="twitter" class="form-control" placeholder="Twitter" value="{{ $user->twitter }}">
                                        <label><i class="ti ti-brand-twitter me-2 fs-4"></i>Twitter Account URL</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="url" name="instagram" class="form-control" placeholder="Instagram" value="{{ $user->instagram }}">
                                        <label><i class="ti ti-brand-instagram me-2 fs-4"></i>Instagram Account URL</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="url" name="youtube" class="form-control" placeholder="Youtube" value="{{ $user->youtube }}">
                                        <label><i class="ti ti-brand-youtube me-2 fs-4"></i>YouTube Account URL</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="url" name="threads" class="form-control" placeholder="Threads" value="{{ $user->threads }}">
                                        <label><i class="bi bi-threads me-2 fs-4"></i>Threads Account URL</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary font-medium rounded-pill px-4 float-end mt-3 btn-loading">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-check me-2 fs-4"></i> Update Details
                                </div>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-notifications" role="tabpanel" aria-labelledby="pills-notifications-tab" tabindex="0">
        </div>
        <div class="tab-pane fade" id="pills-logs" role="tabpanel" aria-labelledby="pills-logs-tab" tabindex="0">
            <div class="card shadow-none border">
                <div class="card-body">
                    <h5 class="mb-3">User Activity Logs</h5>
                    <table id="logsTable" class="table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');

        if (tab) {
            const targetTab = `#pills-${tab}`;
            const targetTabButton = $(`[data-bs-target="${targetTab}"]`);

            if (targetTabButton.length) {
                $('.user-profile-tab .nav-link').removeClass('active');
                $('.tab-content .tab-pane').removeClass('active show');
                
                targetTabButton.addClass('active');
                $(targetTab).addClass('active show');
            }
        }

        $('#logsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('logs.mydata') }}',
            stateSave: true,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'My Logs Report',
                    filename: 'my_logs',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'My Logs Report',
                    filename: 'my_logs',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    text: '<i class="ti ti-file-text me-1"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: 'My Logs Report',
                    text: '<i class="ti ti-printer me-1"></i> Print',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="ti ti-layout me-1"></i> Columns',
                    className: 'btn btn-secondary btn-sm'
                }
            ],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'action', name: 'action' },
                { data: 'description', name: 'description' },
                { data: 'ip_address', name: 'ip_address' },
                { data: 'created_at', name: 'created_at' }
            ]
        });

    });
</script>
@endsection
