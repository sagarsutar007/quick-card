@extends('layouts.main')

@section('title', 'Blocks')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Blocks</h4>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Blocks</li>
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
                <h5 class="mb-3">Blocks</h5>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#blocksModal">
                    <i class="ti ti-plus fs-5 me-2"></i> Add New
                </button>
            </div>
            <table id="blocksTable" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>District</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    <!-- Modal -->
    <form id="manageBlockForm" action="{{ route('blocks.create') }}" method="POST">
        @csrf
        <input type="hidden" name="block_id" id="block_id">
        <div class="modal fade" id="blocksModal" tabindex="-1" aria-labelledby="blocksModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="blocksModalLabel">Manage Block</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <select name="district_id" id="district_id" class="select2 form-control" style="width: 100%; height: 36px">
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <label for="district_id">District</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="blockName" name="name" placeholder="Enter Name here" autocomplete="off">
                            <label for="blockName">Block Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="description" placeholder="Enter description here" id="blockDesc" style="height: 135px;"></textarea>
                            <label for="blockDesc">Description</label>
                        </div>
                        <div class="form-floating mb-3 border rounded-2" style="border-color: #dfe5ef!important;">
                            <label class="fs-2 text-muted mb-2" for="active-radio">Status</label>
                            <br>
                            <br>
                            <div class="form-check form-check-inline ms-3 mb-3">
                                <input class="form-check-input danger check-outline outline-danger" type="radio" id="active-radio" name="status" value="1" checked>
                                <label class="form-check-label" for="active-radio">Active</label>
                            </div>
                            <div class="form-check form-check-inline mb-3">
                                <input class="form-check-input danger check-outline outline-danger" type="radio" id="in-active-radio" name="status" value="0">
                                <label class="form-check-label" for="in-active-radio">In-active</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary text-danger font-medium waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-medium waves-effect btn-loading">Save Block</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
<script>
    $(document).ready(function () {

        $('#blocksTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('blocks.getAll') }}',
            stateSave: true,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Blocks',
                    filename: 'blocks',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Blocks',
                    filename: 'blocks',
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
                    title: 'Blocks',
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
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'district', name: 'district' },
                { data: 'status', name: 'status' },
                { data: 'created_by', name: 'created_by' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        
        const defaultFormAction = "{{ route('blocks.create') }}";

        $(document).on('click', '.edit-btn', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const description = $(this).data('description');
            const districtId = $(this).data('district');
            const status = $(this).data('status');

            $('#block_id').val(id);
            $('#blockName').val(name);
            $('#blockDesc').val(description);
            $('#district_id').val(districtId).trigger('change');
            $('input[name="status"][value="' + status + '"]').prop('checked', true);

            $('#manageBlockForm').attr('action', '/blocks/' + id + '/update');
            $('#blocksModal').modal('show');
        });
        
        $('#blocksModal').on('hidden.bs.modal', function () {
            $('#manageBlockForm').attr('action', defaultFormAction);
            $('#manageBlockForm')[0].reset();
            $('#district_id').val('').trigger('change');
            $('#block_id').val('');
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
        
    });
</script>
@endsection
