@extends('layouts.main')

@section('title', 'Schools')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" id="full-width-container">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Schools</h4>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Schools</li>
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
                <h5 class="mb-3">Schools</h5>
                <a class="btn btn-primary mb-3" href="{{ route('schools.create') }}">
                    <i class="ti ti-plus fs-5 me-2"></i> Add New
                </a>
            </div>
            <table id="schoolTable" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl.</th>
                        <th>Code</th>
                        <th>School Name</th>
                        <th>District</th>
                        <th>Block</th>
                        <th>Cluster</th>
                        <th>Data</th>
                        <th>Photos</th>
                        <th>Send ID Card</th>
                        <th>Amount</th>
                        <th>Payment Details</th>
                        <th>Description</th>
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

    <!-- Edit School Modal -->
    <div class="modal fade" id="editSchoolModal" tabindex="-1" aria-labelledby="editSchoolModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editSchoolForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editSchoolModalLabel">Edit School Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="school_id" id="edit_school_id">

                    <div class="form-floating  mb-3">
                        <input type="text" class="form-control" name="id_card" id="edit_id_card">
                        <label for="edit_id_card" class="form-label">ID Card</label>
                    </div>

                    <div class="form-floating  mb-3">
                        <input type="text" class="form-control" name="amount" id="edit_amount">
                        <label for="edit_amount" class="form-label">Amount</label>
                    </div>

                    <div class="form-floating  mb-3">
                        <textarea class="form-control" name="payment_details" id="edit_payment_details" rows="3"></textarea>
                        <label for="edit_payment_details" class="form-label">Payment Details</label>
                    </div>
                    <div class="form-floating  mb-3">
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        <label for="edit_description" class="form-label">Descrition</label>
                    </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
<script>
    $(document).ready(function () {

        $("#full-width-container").parent().css('max-width', '100%');

        $('#schoolTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('schools.getAll') }}',
            stateSave: true,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Schools List',
                    filename: 'schools_list',
                    text: '<i class="ti ti-file-spreadsheet me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Schools List',
                    filename: 'schools_list',
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
                    title: 'Schools List',
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
                { data: 'code', name: 'school_code' },
                { data: 'name', name: 'school_name' },
                { data: 'district', name: 'districts.name' },
                { data: 'block', name: 'blocks.name' },
                { data: 'cluster', name: 'clusters.name' },
                { data: 'students_count', name: 'students_count', orderable: false, searchable: false},
                { data: 'photo_count', name: 'photo_count', orderable: false, searchable: false},
                { data: 'id_card', name: 'id_card' },
                { data: 'amount', name: 'amount' },
                { data: 'payment_details', name: 'payment_details' },
                { data: 'description', name: 'description', orderable: false, searchable: false },
                { data: 'status', name: 'status' },
                { data: 'created_by', name: 'created_by' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
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

        $('#editSchoolModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);

            const id = button.data('id');
            const id_card = button.data('id_card');
            const amount = button.data('amount');
            const payment = button.data('payment');
            const description = button.data('description');

            const modal = $(this);

            modal.find('#edit_school_id').val(id);
            modal.find('#edit_id_card').val(id_card || '');
            modal.find('#edit_amount').val(amount || '');
            modal.find('#edit_payment_details').val(payment || '');
            modal.find('#edit_description').val(description || '');

            $('#editSchoolForm').attr('action', `/schools/${id}`);
        });
    });
</script>
@endsection
