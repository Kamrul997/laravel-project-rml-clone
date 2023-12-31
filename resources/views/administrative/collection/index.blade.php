@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
            <div>
                <h4 class="mb-3 mb-md-0">Collection</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                @can('permission_create')
                    <a href="{{ route('collection.download') }}" class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">
                        <i class="fas fa-plus-square" data-feather="plus-square"></i>
                        Download
                    </a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-30">
                <div class="card">
                    <div class="card-body ">
                        <h6 class="card-title"> </h6>
                        <div class="userDatatable userDatatable--ticket userDatatable--ticket--2 mt-1">
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless" id="datatables">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Status</th>
                                            <th>Collection Details</th>
                                            <th>Mobile No</th>
                                            <th>Unit ID</th>
                                            <th>Order No</th>
                                            <th>Customer Name</th>
                                            <th>Arrear Collection</th>
                                            <th>Current Collection</th>
                                            <th>Advance Collection</th>
                                            <th>Deposit Type</th>
                                            <th>Collection Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="p-3">
                                            <span>Total Order : {{ $total_order }}</span>
                                            <span>Total Submission : {{ number_format($total_amount) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="p-3">
                                            <span>Total Approved Order : {{ $total_approved_order }}</span>
                                            <span>Total Approved : {{ number_format($total_approved_amount) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="p-3">
                                            <span>Total Disapproved Order : {{ $total_disapproved_order }}</span>
                                            <span>Total Disapproved : {{ number_format($total_disapproved_amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('page-js')
        <script>
            $(document).ready(function() {
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
                    ajax: "{{ route('collection.index') }}",
                    columns: [

                        {
                            data: 'id',
                            name: 'id'
                        },

                        {
                            data: 'employee_id',
                            name: 'employee_id'
                        },

                        {
                            data: 'employee_name',
                            name: 'employee_name'
                        },

                        {
                            data: 'status',
                            name: 'status'
                        },

                        {
                            data: 'details',
                            name: 'details'
                        },

                        {
                            data: 'mobile_no',
                            name: 'mobile_no'
                        },

                        {
                            data: 'unit_id',
                            name: 'unit_id'
                        },

                        {
                            data: 'ord_no',
                            name: 'ord_no'
                        },

                        {
                            data: 'cutomer_name',
                            name: 'cutomer_name'
                        },

                        {
                            data: 'target_arrear',
                            name: 'target_arrear'
                        },

                        {
                            data: 'target_current',
                            name: 'target_current'
                        },

                        {
                            data: 'advanced_collection',
                            name: 'advanced_collection'
                        },

                        {
                            data: 'deposit_name',
                            name: 'deposit_name'
                        },

                        {
                            data: 'collection_amount',
                            name: 'collection_amount'
                        },

                    ]
                });
            });
        </script>
    @endsection
