@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
            <div>
                <h4 class="mb-3 mb-md-0">Reject Collections</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">

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
                                            <th>Arrer Collection</th>
                                            <th>Current Collection</th>
                                            <th>Advance Collection</th>
                                            <th>Deposit Type</th>
                                            <th>Total Collection Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
                    ajax: "{{ route('reject_collection') }}",
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
