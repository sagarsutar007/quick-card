@extends('layouts.main')

@section('title', 'Students')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" id="full-width-container">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Students</h4>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('management.dashboard') }}">Dashboard</a></li>
                        @if(!empty($school))
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('management.schools') }}">Schools</a></li>
                        <li class="breadcrumb-item"><a class="text-muted">{{ $school->school_name }}</a></li>
                        @else
                        <li class="breadcrumb-item" aria-current="page">Students</li>
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

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-3">Students</h5>
                <div class="btn-group flex-wrap">
                    <a class="btn btn-primary mb-3" href="{{ route('student.add', ['school_id' => $school->id]) }}">
                        <i class="ti ti-plus fs-5 me-2"></i>
                        <span class="d-none d-sm-inline">Add New</span>
                    </a>
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#excelModal">
                        <i class="ti ti-file-spreadsheet fs-5 me-2"></i>
                        <span class="d-none d-sm-inline">Import Excel</span>
                    </button>
                    <button id="downloadSelectedBtn" class="btn btn-secondary mb-3">
                        <i class="ti ti-download me-1"></i>
                        <span class="d-none d-sm-inline">Download Selected</span>
                    </button>
                    <button id="lockSelectedBtn" class="btn btn-dark mb-3">
                        <i class="ti ti-lock me-1"></i>
                        <span class="d-none d-sm-inline">Lock Selected</span>
                    </button>
                    <button id="unlockSelectedBtn" class="btn btn-info mb-3">
                        <i class="ti ti-lock-off me-1"></i>
                        <span class="d-none d-sm-inline">Unlock Selected</span>
                    </button>
                </div>
            </div>
            <div id="lg-temp" style="display: none;"></div>
            <table id="studentsTable" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Sl.</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>DOB</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                    
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Student Code</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>DOB</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>    

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="uploadPhotoForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="student_id" id="upload_student_id">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="photo" class="form-control" accept="image/*" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Excel Import Modal -->
    <div class="modal fade" id="excelModal" tabindex="-1" aria-labelledby="excelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('students.importExcel') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="school_id" value="{{ $school->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excelModalLabel">Import Students from Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Select Excel File (.xlsx or .xls)</label>
                            <input class="form-control" type="file" name="excel_file" id="excel_file" accept=".xls,.xlsx" required>
                            <div class="form-text">Only <strong>name, class, dob</strong> columns are required.</div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('libs/vanilla-datatables-editable/datatable.editable.min.css') }}"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
@endsection

@section('js')
<script src="{{ asset('js/plugins/mindmup-editabletable.js') }}"></script>
<script src="{{ asset('js/plugins/numeric-input-example.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<script>
    $(document).ready(function () {

        $("#full-width-container").parent().css('max-width', '100%');

        $(document).on('click', '.upload-photo', function () {
            let studentId = $(this).data('id');
            $('#upload_student_id').val(studentId);
            $('#uploadPhotoModal').modal('show');
        });

        $('#uploadPhotoForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('students.uploadStudentPhoto') }}", 
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    toastr.success('Photo uploaded successfully!');
                    $('#uploadPhotoModal').modal('hide');
                    $('#studentsTable').DataTable().ajax.reload(null, false);
                },
                error: function (err) {
                    toastr.error('Upload failed');
                }
            });
        });

        const table = $('#studentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/schools/{{ $id }}/students',
            stateSave: true,
            dom: 'lBfrtip',
            lengthMenu: [[10, 25, 50, 100, -1], [ 10, 25, 50, 100, "All"]],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Students List',
                    filename: 'students_list',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Students List',
                    filename: 'students_list',
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
                    title: 'Students List',
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
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="student-checkbox" value="${data}">`;
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'student_code', name: 'student_code'},
                { data: 'name', name: 'name' },
                { data: 'class', name: 'class' },
                { data: 'dob', name: 'dob' },
                { data: 'photo', name: 'photo' },
                { data: 'status', name: 'status' },
                { data: 'created_by', name: 'created_by' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            drawCallback: function () {

                $('#studentsTable .editable').off('blur').on('blur', function () {
                    let id = $(this).data('id');
                    let field = $(this).data('field');
                    let value = $(this).text().trim();

                    $.ajax({
                        url: '/students/' + id + '/inline-update',
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            field: field,
                            value: value
                        },
                        success: function (response) {
                            if (response.info) {
                                toastr.info(response.info);
                            } else {
                                toastr.success(response.message);
                            }
                            $('#studentsTable').DataTable().ajax.reload(null, false);
                        },
                        error: function () {
                            toastr.error('Update failed');
                        }
                    });
                });

                Fancybox.bind('[data-fancybox]');
            },
            initComplete: function () {
                this.api()
                    .columns()
                    .every(function () {
                        const that = this;
                        $("input", this.footer()).on("keyup change clear", function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
            },
        });
        
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $(document).on('click', '#downloadSelectedBtn', function () {
            let selectedIds = [];

            $('.student-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                toastr.warning('Please select at least one student.');
                return;
            }
            
            if (!confirm(`Download photos for ${selectedIds.length} student(s)?`)) return;
            
            $.ajax({
                url: '/students/download-photos',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: selectedIds
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (blob) {
                    const link = document.createElement('a');
                    const url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = 'student_photos.zip';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);
                },
                error: function () {
                    toastr.error('Download failed.');
                }
            });
        });

        $(document).on('change', '#selectAll', function () {
            const isChecked = $(this).is(':checked');
            $('.student-checkbox').prop('checked', isChecked);
        });

        $(document).on('change', '.student-checkbox', function () {
            const all = $('.student-checkbox').length;
            const checked = $('.student-checkbox:checked').length;

            $('#selectAll').prop('checked', all === checked);
        });

        function getSelectedStudentIds() {
            return $('.student-checkbox:checked').map(function () {
                return $(this).val();
            }).get();
        }

        $(document).on('click', '.remove-photo', function () {
            const id = $(this).data('id');

            if (!confirm('Are you sure you want to remove this photo?')) return;

            $.ajax({
                url: '/students/' + id + '/remove-photo',
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    toastr.success(res.message);
                    $('#studentsTable').DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    const err = xhr.responseJSON?.error || 'Something went wrong';
                    toastr.error(err);
                }
            });
        });

        $("#studentsTable tfoot th").each(function () {
            const title = $(this).text().trim();
            if (title !== "") {
                $(this).html(
                    `<input type="text" class="form-control" placeholder="Search ${title}" />`
                );
            }
        });

        $(document).on('click', '.toggle-lock', function () {
            const studentId = $(this).data('id');

            $.ajax({
                url: '/students/' + studentId + '/toggle-lock',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#studentsTable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    toastr.error('Failed to toggle lock.');
                }
            });
        });

        $('#lockSelectedBtn').on('click', function () {
            const ids = getSelectedStudentIds();
            if (ids.length === 0) return toastr.warning("No students selected.");

            $.ajax({
                url: '/students/lock-multiple',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids,
                    lock: 1
                },
                success: function (res) {
                    toastr.success(res.message || "Students locked.");
                    $('#studentsTable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    toastr.error("Failed to lock students.");
                }
            });
        });

        $('#unlockSelectedBtn').on('click', function () {
            const ids = getSelectedStudentIds();
            if (ids.length === 0) return toastr.warning("No students selected.");

            $.ajax({
                url: '/students/lock-multiple',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids,
                    lock: 0
                },
                success: function (res) {
                    toastr.success(res.message || "Students unlocked.");
                    $('#studentsTable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    toastr.error("Failed to unlock students.");
                }
            });
        });

    });
</script>
@endsection
