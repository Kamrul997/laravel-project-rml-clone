@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
            <div>
                <h4 class="mb-3 mb-md-0">Target Sheet</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <a href="{{ route('administrative.target.download') }}"
                    class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">
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
                                            <th>Employee Id</th>
                                            <th>Employee Name</th>
                                            <th>Collection Entry</th>
                                            {{-- <th>Customer Info</th>
                                    <th>Ledger</th> --}}
                                            <th>Unit Id</th>
                                            <th>Order No</th>
                                            <th>Customer Name</th>
                                            <th>Market Receiveable</th>
                                            <th>Target Arrear</th>
                                            <th>Target Current</th>
                                            <th>Penalty Target</th>
                                            <th>Total Target Amount</th>
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
                ajax: "{{ route('administrative.target') }}",
                columns: [

                    {
                        data: 'employee_id',
                        name: 'employee_id'
                    },

                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },

                    {
                        data: 'target_show',
                        name: 'target_show',
                        searchable: false
                    },


                    {
                        data: 'name',
                        name: 'name',
                        searchable: false
                    },

                    {
                        data: 'ord_no',
                        name: 'ord_no'
                    },

                    {
                        data: 'cutomer_name',
                        name: 'cutomer_name',
                        searchable: false
                    },

                    {
                        data: 'market_receivabl',
                        name: 'market_receivabl',
                        searchable: false
                    },

                    {
                        data: 'target_arrear',
                        name: 'target_arrear',
                        searchable: false
                    },

                    {
                        data: 'target_current',
                        name: 'target_current',
                        searchable: false
                    },

                    {
                        data: 'penalty_target',
                        name: 'penalty_target',
                        searchable: false
                    },

                    {
                        data: 'target_total',
                        name: 'target_total',
                        searchable: false
                    },


                ]
            });
        });
    </script>
@endsection
