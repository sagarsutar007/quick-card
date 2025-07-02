@extends('layouts.main')

@section('title', 'Cluster')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Cluster</h4>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Cluster</li>
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
                <h5 class="mb-3">Cluster</h5>
                @can('add cluster')
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#clusterModal">
                    <i class="ti ti-plus fs-5 me-2"></i> Add New
                </button>
                @endcan
            </div>
            <table id="clusterTable" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>District</th>
                        <th>Block</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    <!-- Modal -->
    <form id="manageClusterForm" action="{{ route('cluster.create') }}" method="POST">
        @csrf
        <input type="hidden" name="cluster_id" id="cluster_id">
        <div class="modal fade" id="clusterModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clusterModalabel">Manage Cluster</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <select name="district_id" id="district_id" class="form-control" style="width: 100%; height: 36px">
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <label for="district_id">District</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="block_id" id="block_id" class="form-control" style="width: 100%; height: 36px">
                                @foreach ($firstDistrictBlocks as $block)
                                    <option value="{{ $block->id }}">{{ $block->name }}</option>
                                @endforeach
                            </select>
                            <label for="block_id">Block</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="clusterName" name="name" placeholder="Enter Name here" autocomplete="off">
                            <label for="clusterName">Cluster Name</label>
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
                        <button type="submit" class="btn btn-primary font-medium waves-effect btn-loading">Save Cluster</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
@endsection

@section('js')
<script>
    $(document).ready(function () {

        $('#clusterTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('cluster.getAll') }}',
            stateSave: true,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Cluster List',
                    filename: 'cluster_list',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Cluster List',
                    filename: 'cluster_list',
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
                    title: 'Cluster List',
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
                { data: 'district', name: 'districts.name' },
                { data: 'block', name: 'block.name' },
                { data: 'status', name: 'status' },
                { data: 'created_by', name: 'created_by' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        
        const defaultFormAction = "{{ route('cluster.create') }}";

        $(document).on('click', '.edit-btn', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const description = $(this).data('description');
            const districtId = $(this).data('district');
            const blockId = $(this).data('block');
            const status = $(this).data('status');

            console.log("blockId",blockId);

            $('#cluster_id').val(id);
            $('#clusterName').val(name);
            $('#clusterDesc').val(description);
            $('#district_id').val(districtId).trigger('change');
            const checkAndSetBlock = setInterval(() => {
                const blockOptions = $('#block_id option').map(function () {
                    return $(this).val();
                }).get();

                if (blockOptions.includes(String(blockId))) {
                    $('#block_id').val(blockId).trigger('change');
                    clearInterval(checkAndSetBlock);
                }
            }, 100);
            $('input[name="status"][value="' + status + '"]').prop('checked', true);

            $('#manageClusterForm').attr('action', '/cluster/' + id + '/update');
            $('#clusterModal').modal('show');
        });
        
        $('#clusterModal').on('hidden.bs.modal', function () {
            $('#manageClusterForm').attr('action', defaultFormAction);
            $('#manageClusterForm')[0].reset();
            $('#district_id').val('').trigger('change');
            $('#cluster_id').val('');
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
    });
</script>
@endsection
