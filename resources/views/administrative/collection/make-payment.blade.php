@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Make payment list </h4>
    </div>
    {{-- <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ route('administrative.target.download') }}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
    Download
    </a>
</div> --}}
</div>
<div class="card mb-2">
    <div class="card-body">
        <form method="post" action="{{route('download.payment')}}">
            @csrf
            <div class="row mt-2">

                <div class="col-md-4">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text search-params">Date From</div>
                        </div>
                        <input type="datetime-local" class="form-control form-control-danger" name="start">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text search-params">Date to</div>
                        </div>
                        <input type="datetime-local" class="form-control form-control-danger" name="end">
                    </div>
                </div>
                <!-- <div class="col-md-4">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text search-params">Month</div>
                    </div>
                    <input type="month" class="form-control form-control-danger" id="month_value">
                </div>
            </div> -->
                <div class="col-md-4 d-flex justify-content-end align-self-baseline">
                    <button style="height: fit-content;" type="button" id="btn-search" class="d-flex align-items-center ml-2 btn btn-primary btn-icon-text mb-2 mb-md-0">
                        <i class="btn-icon-prepend" data-feather="search"></i>
                        <span>Search</span>
                    </button>

                    <button style="height: fit-content;" type="submit" class="d-flex align-items-center btn btn-primary btn-icon-text mb-2 mb-md-0 ml-1">
                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                        <span>Download</span>
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"> </h6>
                <div class="table-responsive">
                    <table id="datatables" class="table">
                        <thead>
                            <tr>
                                <!-- <th>Sub Unit</th> -->
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <th>GL Code</th>
                                <th>Amount</th>
                                <!-- <th>Vts</th> -->
                                <th>Deposit Date</th>
                                <th>Deposit Type</th>
                                <th>Bank Name</th>
                                <th>Branch Name</th>
                                <!-- <th>Total</th> -->
                                <th>Naration</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
<script>
    $(document).ready(function() {
        window.csrfToken = '<?php echo csrf_token(); ?>';
        var postData = {};
        postData._token = window.csrfToken;

        $('#datatables').DataTable({
            "aLengthMenu": [
                [10, 30, 50, -1],
                [10, 30, 50, "All"]
            ],
            "iDisplayLength": 10,
            "language": {
                search: ""
            },
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{{route('make_payment_data')}}",
                "type": "POST",
                "data": function(d) {
                    $.extend(d, postData);
                    var dt_params = $('#datatables').data('dt_params');
                    if (dt_params) {
                        $.extend(d, dt_params);
                    }
                }
            },
            columns: [

                // {
                //     data: 'sub_unit_name',
                //     name: 'sub_unit_name'
                // },
                {
                    data: 'order_no',
                    name: 'order_no',
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'gl_code',
                    name: 'gl_code'
                },

                {
                    data: 'total',
                    name: 'total',
                    searchable: false
                },

                {
                    data: 'deposit_date',
                    name: 'deposit_date',
                    searchable: false
                },

                {
                    data: 'deposit_type',
                    name: 'deposit_type',
                },

                // {
                //     data: 'emi',
                //     name: 'emi',
                //     searchable: false
                // },

                // {
                //     data: 'vts',
                //     name: 'vts',
                //     searchable: false
                // },

                {
                    data: 'bank_name',
                    name: 'bank_name'
                },

                {
                    data: 'branch_name',
                    name: 'branch_name',
                },
                {
                    data: 'naration',
                    name: 'naration',
                    searchable: false
                },

                {
                    data: 'created_at',
                    name: 'created_at',
                },

            ]
        });
    });

    $("#btn-search").click(function() {
        var start = $('#start').val();
        var end = $('#end').val();

        var previousFilter = $('#datatables').data('dt_params');
        var filterables = {};
        if (previousFilter != undefined) {
            filterables = $('#datatables').data('dt_params');
        }

        filterables.start_date = start;
        filterables.end_date = end;

        $('#datatables').data('dt_params', filterables);
        $('#datatables').DataTable().draw();
    });

    $("#btn-export").click(function() {
        var start = $('#start').val();
        var end = $('#end').val();
        let url = '{{ route("download.payment", ":start",":end") }}';
        url = url.replace(':start', start);
        url = url.replace(':end', end);
        window.location.href = url;

    });
</script>
@endsection