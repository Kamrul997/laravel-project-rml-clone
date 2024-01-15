@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
    <div class="mt-2">
        <ul class="dm-breadcrumb nav">
            <li class="dm-breadcrumb__item">
                <a href="#">
                    Report
                </a>
                <span class="slash">/</span>
            </li>
            <li class="dm-breadcrumb__item">
                <a href="#">Deposit Slip Wise Collection</a>
            </li>
        </ul>
    </div>
    <div class="d-flex mt-4 mb-2 justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Deposit Slip Wise Report</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">

        </div>
    </div>
    <form method="post" action="{{ route('administrative.data.deposit.slip.wise.report.download') }}">
        @csrf
        <div class="row">
            <div class="col-md-5">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text ">Date From</div>
                    </div>
                    <input type="date" name="from" class="form-control form-control-danger" id="start_time">
                </div>
            </div>


            <div class="col-md-5">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Date To</div>
                    </div>
                    <input type="date" name="to" class="form-control form-control-danger" id="end_time">
                </div>
            </div>


            <div class="col-md-2 d-flex justify-content-end align-self-baseline">
                <button type="button" id="searchDate" class="ml-2 btn btn-primary btn-icon-text mb-2 mb-md-0 btn-xs mx-2">
                    <i class="btn-icon-prepend" data-feather="search"></i>
                    Search
                </button>
                <button type="submit" class="btn btn-primary btn-icon-text mb-2 mb-md-0 btn-xs">
                    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                    Download
                </button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title"> </h6>
                    <div class="table-responsive">
                        <table id="datatables" class="table">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Unit Id</th>
                                    <th>Bank Name</th>
                                    <th>Branch Name</th>
                                    <th>Status</th>
                                    <th>Entry Date</th>
                                    <th>Deposit Date</th>
                                    <th>Final Approval Date</th>
                                    <th>Collection Amount</th>
                                    <th>Note</th>
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
                    "url": "{{ route('administrative.data.deposit.slip.wise.report') }}",
                    "type": "POST",
                    "data": function(d) {
                        $.extend(d, postData);
                        var dt_params = $('#datatables').data('dt_params');
                        if (dt_params) {
                            $.extend(d, dt_params);
                        }
                    }
                },
                columns: [{
                        data: 'ord_no',
                        name: 'ord_no'
                    },

                    {
                        data: 'unit_id',
                        name: 'unit_id'
                    },

                    {
                        data: 'bank_name',
                        name: 'bank_name'
                    },

                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },

                    {
                        data: 'details',
                        name: 'details'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'deposit_date',
                        name: 'deposit_date'
                    },

                    {
                        data: 'approved_date',
                        name: 'approved_date'
                    },

                    {
                        data: 'collection_amount',
                        name: 'collection_amount'
                    },

                    {
                        data: 'note',
                        name: 'note'
                    },

                ]
            });
        });
    </script>
    <script>
        $("#searchDate").click(function() {
            var from = $('#start_time').val();
            var to = $('#end_time').val();
            if (from == '' || to == '') {
                Swal.fire('Please Select Date!', '', '');
                return;
            }

            var previousFilter = $('#datatables').data('dt_params');
            var filterables = {};
            if (previousFilter != undefined) {
                filterables = $('#datatables').data('dt_params');
            }
            var fromDateSelected = from;
            var toDateSelected = to;
            if (fromDateSelected != "" && toDateSelected != "") {
                filterables.from = fromDateSelected;
                filterables.to = toDateSelected;
            } else {
                filterables.status = 0;
            }
            $('#datatables').data('dt_params', filterables);
            $('#datatables').DataTable().draw();
        });
    </script>
@endsection
