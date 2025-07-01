@extends('layouts.main')

@section('title', 'View User')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">View User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.users') }}">User</a></li>
                            <li class="breadcrumb-item" aria-current="page">View User</li>
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
                $coverPath = public_path('uploads/images/cover/' . $user->cover_image);
            @endphp    
            <img src="{{ $user->cover_image && file_exists($coverPath) ? asset('uploads/images/cover/' . $user->cover_image) : asset('images/backgrounds/profilebg.jpg') }}" alt="" class="w-100 h-100">
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
                                    $profilePath = public_path('uploads/images/profile/' . $user->profile_image);
                                @endphp    
                                <img src="{{ $user->profile_image && file_exists($profilePath) ? asset('uploads/images/profile/' . $user->profile_image) : asset('images/profile/user-1.jpg') }}" alt="" class="w-100 h-100">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="fs-5 mb-0 fw-semibold">{{ $user->name }}</h5>
                        <p class="mb-0 fs-4">{{ ucfirst($user->getRoleNames()->first()) }}</p>
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
                    <li class="position-relative">
                        <a class="btn btn-secondary" href="{{ route('users.edit', $user->id) }}">
                            <i class="ti ti-pencil"></i> Edit User
                        </a>
                    </li>
                </ul>
            </div>
            </div>
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
        </div>
    </div> 
@endsection

@section('js')
<script>
    $(document).ready(function () {

        $('#logsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('logs.mydata', ['user_id' => $user->id]) }}',
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
