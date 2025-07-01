@extends('layouts.main')

@section('title', 'Assigned Users')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Assigned Users</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.schools') }}">Schools</a></li>
                            <li class="breadcrumb-item" aria-current="page">Assigned Users</li>
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

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <!-- <h5 class="mb-3">Assigned Users</h5> -->
            </div>
            <table id="schoolTable" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_image ? asset('uploads/images/profile/' . $user->profile_image) : asset('images/avatar.png') }}" alt="avatar" class="rounded-circle" width="35" />
                                    <div class="ms-3">
                                        <div class="user-meta-info">
                                            <h6 class="user-name mb-0">{{ $user->name }}</h6>
                                            <span class="user-work fs-3">
                                                {{ ucfirst($user->designation) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ ucfirst($user->getRoleNames()->first()) }}</td>
                            <td>
                                @php
                                    $role = $user->getRoleNames()->first();
                                    $isAssigned = false;

                                    if ($role === 'authority') {
                                        $isAssigned = $user->school_id == $school->id;
                                    } elseif ($role === 'staff') {
                                        $isAssigned = $user->schools->contains($school->id);
                                    }
                                @endphp

                                @if ($isAssigned)
                                    <form action="{{ route('school.unassign', ['user' => $user->id, 'school' => $school->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                @else
                                    <form action="{{ route('school.assign', ['user' => $user->id, 'school' => $school->id]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Add</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
    </tbody>
            </table>
        </div>
    </div>  
@endsection

@section('js')
<script>
    $(document).ready(function () {

        $("#schoolTable").DataTable();

    });
</script>
@endsection