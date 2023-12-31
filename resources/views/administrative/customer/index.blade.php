@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')

    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
            <div>
                <h4 class="mb-3 mb-md-0">Customer</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <a href="{{ route('c.download') }}" class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="fas fa-plus-square" data-feather="plus-square"></i>
                    Download
                </a>
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
                                            <th>Order No</th>
                                            <th>Customer Name</th>
                                            <th>Customer Address</th>
                                            <th>Thana Upazila</th>
                                            <th>District Name</th>
                                            <th>Division Name</th>
                                            <th>Mobile No</th>
                                            <th>Part No</th>
                                            <th>Delivery Date</th>
                                            <th>Engine No</th>
                                            <th>Chesis No</th>
                                            <th>Installment Start</th>
                                            <th>Installment End</th>
                                            <th>Unit</th>
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
                        ajax: "{{ route('customer.index') }}",
                        columns: [

                            {
                                data: 'order_no',
                                name: 'order_no'
                            },

                            {
                                data: 'customer_name',
                                name: 'customer_name'
                            },

                            {
                                data: 'customer_address',
                                name: 'customer_address',
                                searchable: false
                            },

                            {
                                data: 'thana_upazila_name',
                                name: 'thana_upazila_name',
                                searchable: false
                            },

                            {
                                data: 'district_name',
                                name: 'district_name',
                                searchable: false
                            },

                            {
                                data: 'division_name',
                                name: 'division_name',
                                searchable: false
                            },

                            {
                                data: 'mobile_no',
                                name: 'mobile_no'
                            },

                            {
                                data: 'part_no',
                                name: 'part_no',
                                searchable: false
                            },

                            {
                                data: 'delivery_date',
                                name: 'delivery_date',
                                searchable: false
                            },

                            {
                                data: 'engine_no',
                                name: 'engine_no',
                                searchable: false
                            },


                            {
                                data: 'chesis_no',
                                name: 'chesis_no',
                                searchable: false
                            },

                            {
                                data: 'installment_start_date',
                                name: 'installment_start_date',
                                searchable: false
                            },

                            {
                                data: 'installment_end_date',
                                name: 'installment_end_date',
                                searchable: false
                            },

                            {
                                data: 'sub_unit',
                                name: 'sub_unit',
                                searchable: false
                            },

                        ]
                    });
                });
            </script>
        @endsection
