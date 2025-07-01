@extends('layouts.main')

@section('title', 'Edit User')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Edit User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.users') }}">User</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit User</li>
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
        <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button" role="tab" aria-controls="pills-account" aria-selected="true">
                    <i class="ti ti-user-circle me-2 fs-6"></i>
                    <span class="d-none d-md-block">Account</span> 
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-permissions-tab" data-bs-toggle="pill" data-bs-target="#pills-permissions" type="button" role="tab" aria-controls="pills-permissions" aria-selected="false">
                    <i class="ti ti-bell me-2 fs-6"></i>
                    <span class="d-none d-md-block">Permissions</span> 
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4" id="pills-schools-tab" data-bs-toggle="pill" data-bs-target="#pills-schools" type="button" role="tab" aria-controls="pills-schools" aria-selected="false">
                    <i class="ti ti-building-bank me-2 fs-6"></i>
                    <span class="d-none d-md-block">Schools</span> 
                </button>
            </li>
        </ul>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold">Change Profile</h5>
                                <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                <div class="text-center">
                                    @php
                                        $profilePath = public_path('uploads/images/profile/' . $user->profile_image);
                                    @endphp
                                    <img  id="profilePreview" src="{{ $user->profile_image && file_exists($profilePath) ? asset('uploads/images/profile/' . $user->profile_image) : asset('images/profile/user-1.jpg') }}" alt="" class="img-fluid rounded-circle" width="120" height="120">
                                    <input type="file" id="profileInput" accept="image/*" style="display: none;">
                                    <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                        <button id="uploadBtn" class="btn btn-primary">Upload</button>
                                    </div>
                                    <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 20MB</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 position-relative overflow-hidden">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-semibold">Change Password</h5>
                                    <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                    <form method="POST" action="{{ route('users.update-password', $user->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="toggle-password-field position-relative input-wrapper mb-3">
                                                    <div class="form-floating">
                                                        <input type="password" name="password" class="form-control" placeholder="Password">
                                                        <label><i class="ti ti-lock me-2 fs-4"></i>Password</label>
                                                    </div>
                                                    <span class="toggle-password-icon"><i class="ti ti-eye fs-5"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="toggle-password-field position-relative input-wrapper mb-3">
                                                    <div class="form-floating">
                                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                                                        <label><i class="ti ti-lock me-2 fs-4"></i>Confirm Password</label>
                                                    </div>
                                                    <span class="toggle-password-icon"><i class="ti ti-eye fs-5"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary font-medium px-4 float-end mt-3 btn-loading">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-check me-2 fs-4"></i> Update Password
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card w-100 position-relative overflow-hidden mb-0">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold">Personal Details</h5>
                                <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>
                                <form action="{{ route('users.updateProfile', $user->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
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
                                        <div class="col-lg-8 col-md-12">
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
                                    <button type="submit" class="btn btn-primary font-medium px-4 float-end mt-3 btn-loading">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-check me-2 fs-4"></i> Save Details
                                        </div>
                                    </button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-permissions" role="tabpanel" aria-labelledby="pills-permissions-tab" tabindex="0">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="row">
                                <!-- Roles Section -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h5 class="card-title fw-semibold mb-3">Change Role</h5>
                                            
                                            <form method="POST" action="{{ route('users.update-role', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="mb-4">
                                                    <select class="form-select" id="role" name="role">
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                                {{ Ucfirst($role->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Permissions Section -->
                                <div class="col-lg-8 col-md-8">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h5 class="card-title fw-semibold">Change Permissions</h5>
                                            
                                            <form method="POST" action="{{ route('users.update-permissions', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        <div class="col-md-4 mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                    id="permission-{{ $permission->id }}" 
                                                                    name="permissions[]" 
                                                                    value="{{ $permission->name }}"
                                                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                                    {{ ucwords($permission->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update Permissions</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-schools" role="tabpanel" aria-labelledby="pills-schools-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body p-4">
                            <table id="schoolTable" class="table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Code</th>
                                        <th>School Name</th>
                                        <th>Cluster</th>
                                        <th>Block</th>
                                        <th>District</th>
                                        <th>Status</th>
                                        <th class="text-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedSchools as $index => $school)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $school->school_code }}</td>
                                            <td>{{ $school->school_name }}</td>
                                            <td>{{ $school->cluster->name ?? '-' }}</td>
                                            <td>{{ $school->block->name ?? '-' }}</td>
                                            <td>{{ $school->district->name ?? '-' }}</td>
                                            <td>{{ $school->status?'Active':'Not Active' }}</td>
                                            <td>
                                                <form action="{{ route('users.removeSchool', ['user' => $user->id, 'school' => $school->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this school?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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

        $('#uploadBtn').on('click', function () {
            $('#profileInput').click();
        });

        $('#profileInput').on('change', function () {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('profile_image', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '{{ route("users.uploadProfileImage", $user->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.image_url) {
                        $('#profilePreview').attr('src', response.image_url);
                        toastr.success('Profile picture updated');
                    } else {
                        toastr.error('Upload failed');
                    }
                },
                error: function () {
                    toastr.error('Upload error');
                }
            });
        });

        $("#schoolTable").DataTable();
    });
</script>
@endsection
