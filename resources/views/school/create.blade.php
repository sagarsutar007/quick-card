@extends('layouts.main')

@section('title', 'Add School')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Add School</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('management.schools') }}">Schools</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add School</li>
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
                <h5 class="mb-3">Add School</h5>
            </div>
            <form method="post" action="{{ route('schools.add') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="schoolCode" name="school_code" placeholder="Enter code here">
                            <label for="schoolCode">School Code</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="schoolName" name="school_name" placeholder="Enter name here">
                            <label for="schoolName">School Name</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="udiseNo" name="udise_no" placeholder="Enter udise no here">
                            <label for="udiseNo">UDISE No</label>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <select name="district_id" id="district_id" class="select2 form-control mb-3" style="width: 100%; height: 58px">
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <select name="block_id" id="block_id" class="select2 form-control mb-3" style="width: 100%; height: 58px">
                            @foreach ($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <select name="cluster_id" id="cluster_id" class="select2 form-control mb-3" style="width: 100%; height: 58px">
                            @foreach ($clusters as $cluster)
                                <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="schoolAddress" name="school_address" placeholder="Enter address here">
                            <label for="schoolAddress">School Address</label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="form-floating mb-3 border rounded-2" style="border-color: #dfe5ef!important;">
                            <label class="fs-2 text-muted mb-2" for="active-radio" style="padding: 0px 14px; margin: 3px 0px;">Status</label>
                            <br>
                            <div class="form-check form-check-inline ms-3 mb-2 mt-1">
                                <input class="form-check-input danger check-outline outline-danger" type="radio" id="active-radio" name="status" value="1" checked>
                                <label class="form-check-label" for="active-radio">Active</label>
                            </div>
                            <div class="form-check form-check-inline mb-2 mt-1">
                                <input class="form-check-input danger check-outline outline-danger" type="radio" id="in-active-radio" name="status" value="0">
                                <label class="form-check-label" for="in-active-radio">In-active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary font-medium waves-effect btn-loading">Save & Next <i class="bi bi-arrow-right ms-2"></i></button>
                    </div>
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
