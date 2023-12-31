@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
            <div>
                <h4 class="mb-3 mb-md-0">Credit Sales Organogram</h4>
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
                                            <th>Area</th>
                                            <th>Zone</th>
                                            <th>Unit</th>
                                            <th>National Emp Id</th>
                                            <th>National Emp Name</th>
                                            <th>Zone Emp Id</th>
                                            <th>Zone Emp Name</th>
                                            <th>Area Emp Id</th>
                                            <th>Area Emp Name</th>
                                            <th>Unit Emp Id</th>
                                            <th>Unit Emp Name</th>
                                            <th>Sub Unit Emp Id</th>
                                            <th>Sub Unit Emp Name</th>
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
                    ajax: "{{ route('c_s_organogram') }}",
                    columns: [

                        {
                            data: 'area',
                            name: 'area'
                        },

                        {
                            data: 'zone',
                            name: 'zone'
                        },

                        {
                            data: 'unit',
                            name: 'unit'
                        },
                        {
                            data: 'national_emp_id',
                            name: 'national_emp_id'
                        },

                        {
                            data: 'national',
                            name: 'national'
                        },

                        {
                            data: 'zone_manager_emp_id',
                            name: 'zone_manager_emp_id'
                        },

                        {
                            data: 'zone_manager',
                            name: 'zone_manager'
                        },

                        {
                            data: 'area_manager_emp_id',
                            name: 'area_manager_emp_id'
                        },

                        {
                            data: 'area_manager',
                            name: 'area_manager'
                        },

                        {
                            data: 'unit_manager_emp_id',
                            name: 'unit_manager_emp_id'
                        },

                        {
                            data: 'unit_manager',
                            name: 'unit_manager'
                        },
                        {
                            data: 'sub_unit_emp_id',
                            name: 'sub_unit_emp_id'
                        },
                        {
                            data: 'sub_unit',
                            name: 'sub_unit'
                        },
                    ]
                });
            });
        </script>
    @endsection
