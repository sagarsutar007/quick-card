@extends('layouts.main')

@section('title', 'Add Student')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" id="full-width-container">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Add Student</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            @if(!empty($school))
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('management.schools') }}">Schools</a></li>
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('school.students', $school->id) }}">{{ $school->school_name }}</a></li>
                            @else
                            <li class="breadcrumb-item" aria-current="page">Add Student</li>
                            @endif
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
                <h5 class="mb-3">Add Student</h5>
            </div>
            <form id="manageStudentForm" action="{{ route('student.save') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="student_id">
                <input type="hidden" class="form-control" id="school_id" name="school_id" value="{{ $school->id}}">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="student_code" name="student_code" placeholder="Enter Student Code" autocomplete="off">
                            <label for="student_code">Student Code</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="studentName" name="name" placeholder="Enter Name here" autocomplete="off">
                            <label for="studentName">Student Name</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="class" name="class" placeholder="Enter Class">
                            <label for="class">Class</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="dob" name="dob" placeholder="Enter dob">
                            <label for="dob">Date of Birth</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Upload Student Photo</label>
                        <div id="studentDropzone" class="dropzone border border-2 rounded" style="min-height: 150px;"></div>
                        <input type="hidden" name="photo" id="photo" />
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary font-medium waves-effect btn-loading"><i class="bi bi-floppy-fill me-2"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>    
@endsection

@section('js')
<script>
    Dropzone.autoDiscover = false;

    const studentDropzone = new Dropzone("#studentDropzone", {
        url: "{{ route('student.uploadPhoto') }}",
        paramName: "file",
        maxFiles: 1,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            $('#photo').val(response.file_path); 
        },
        removedfile: function (file) {
            $('#photo').val('');
            file.previewElement.remove();
        }
    });
</script>

@endsection