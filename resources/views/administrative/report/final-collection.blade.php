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
                <a href="#">Final Collection</a>
            </li>
        </ul>
    </div>
    <div class="d-flex mt-4 mb-2 justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Final Collection</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ route('administrative.data.final.collection.download') }}"
                class="btn btn-primary btn-icon-text mb-2 mb-md-0 btn-xs">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
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
                                    <tr class="userDatatable-header">
                                        <th>Id</th>
                                        <th>Employee ID</th>
                                        <th>Employee Name</th>
                                        <th>Collection VIew</th>
                                        <th>Mobile No</th>
                                        <th>Unit ID</th>
                                        <th>Order No</th>
                                        <th>Customer Name</th>
                                        <th>Arrer Collection</th>
                                        <th>Current Collection</th>
                                        <th>Advance Collection</th>
                                        <th>Deposit Type</th>
                                        <th>Collection Amount</th>
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
                ajax: "{{ route('administrative.data.final.collection') }}",
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
