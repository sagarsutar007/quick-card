@extends('layouts.main')

@section('title', 'Add User')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Add User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.users') }}">User</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add User</li>
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
                <h5 class="mb-3">Add User</h5>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-floating  mb-3">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter name here">
                    <label for="name">Name <span class="text-danger">*</span></label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email here">
                    <label for="email">Email <span class="text-danger">*</span></label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter phone here">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Enter password here">
                    <label for="password">Password <span class="text-danger">*</span></label>
                </div>
                
                <span>Role <span class="text-danger">*</span></span>
                <div class="mb-3">
                    <select name="role_id" class="form-select">
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $id => $role)
                            <option value="{{ $id }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <span>Profile Image</span>
                <div class="mb-3">
                    <input type="file" name="profile_image" class="form-control">
                </div>

                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>    
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $(document).on('change', '#district_id', function () {
            var districtId = $(this).val();
            $('#block_id').html('<option value="">Loading...</option>');
            if (districtId) {
                $.ajax({
                    url: '{{ url("/get-blocks") }}/' + districtId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#block_id').empty();
                        $.each(data, function (key, value) {
                            $('#block_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function () {
                        $('#block_id').html('<option value="">Error loading blocks</option>');
                    }
                });
            } else {
                $('#block_id').html('<option value="">Select Block</option>');
            }
        });

        $(document).on('change', '#block_id', function () {
            var blockId = $(this).val();
            $('#cluster_id').html('<option value="">Loading...</option>');
            if (blockId) {
                $.ajax({
                    url: '{{ url("/get-clusters") }}/' + blockId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#cluster_id').empty();
                        $.each(data, function (key, value) {
                            $('#cluster_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function () {
                        $('#cluster_id').html('<option value="">Error loading blocks</option>');
                    }
                });
            } else {
                $('#cluster_id').html('<option value="">Select Block</option>');
            }
        });
    });
</script>
@endsection
