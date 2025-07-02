@extends('layouts.main')

@section('title', 'Users')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" id="full-width-container">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Users</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Users</li>
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
                <h5 class="mb-3">Users</h5>
                <div class="btn-group flex-wrap">
                    <a class="btn btn-primary mb-3" href="{{ route('users.create') }}">
                        <i class="ti ti-users text-white me-1 fs-5"></i>
                        <span class="d-none d-sm-inline">Add User</span>
                    </a>
                </div>
            </div>
            <table class="table table-bordered" style="width:100%" id="usersTable">
                <thead class="header-item">
                    <th>Sl.</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Phone</th>
                    <th>Action</th>
                </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->profile_image ? asset('uploads/images/profile/' . $user->profile_image) : asset('images/profile/user-4.jpg') }}" alt="avatar" class="rounded-circle" width="35" />
                                        <div class="ms-3">
                                            <div class="user-meta-info">
                                                <h6 class="user-name mb-0">{{ $user->name }}</h6>
                                                <span class="user-work fs-2 fst-italic">
                                                    {{ ucfirst($user->designation) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst(optional($user->roles->first())->name ?? 'N/A') }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->address ?? 'N/A' }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    <div class="action-btn">
                                        @can('view user')
                                        <a href="{{ route('users.view', $user->id) }}" class="btn btn-info btn-sm" title="View">
                                            <i class="ti ti-eye fs-5"></i>
                                        </a>
                                        @endcan
                                        @can('edit user')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm ms-2" title="Edit">
                                            <i class="ti ti-pencil fs-5"></i>
                                        </a>
                                        @endcan
                                        @can('view user permissions')
                                        <a href="{{ route('users.edit', $user->id) . '?tab=permissions' }}" class="btn btn-warning btn-sm ms-2" title="Permissions">
                                            <i class="ti ti-key fs-5"></i>
                                        </a>
                                        @endcan
                                        @can('delete user')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm ms-2" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function () {

        $("#full-width-container").parent().css('max-width', '100%');

        $("#usersTable").DataTable();
        
    });
</script>
@endsection