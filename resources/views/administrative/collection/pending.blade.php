@extends('administrative.layouts.master')
@section('page-css')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
        rel="stylesheet" />
@endsection
@section('content')
    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2">
            <div>
                <h4 class="mb-3 mb-md-0">Collection Approval</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                @if (!auth()->user()->hasRole('Subunit'))
                    <a href="#" class="btn btn-xs btn-primary btn-icon-text mx-2 mb-2 mb-md-0">
                        <i class="fas fa-plus-square" data-feather="plus-square"></i>
                        Approved All
                    </a>
                @endif
                <a href="{{ route('pending_collection_download') }}"
                    class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="fas fa-plus-square" data-feather="plus-square"></i>
                    Download
                </a>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text search-params text-white" style="background: #8231D3;">Deposit
                                    Date
                                    Start</div>
                            </div>
                            <input type="date" class="form-control form-control-danger" id="start">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text search-params text-white" style="background-color: #8231D3;">
                                    Deposit Date End</div>
                            </div>
                            <input type="date" class="form-control form-control-danger" id="end">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <div class="input-group mb-2">
                            <a href="#" id="btn-search" class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">
                                <i class="btn-icon-prepend" data-feather="search"></i>
                                Search
                            </a>
                        </div>
                    </div>
                </div>
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
                                            <th>Deposit Date</th>
                                            <th>Arrer Collection</th>
                                            <th>Current Collection</th>
                                            <th>Advance Collection</th>
                                            <th>Deposit Type</th>
                                            <th>Collection Amount</th>
                                            <th>Created at</th>
                                            <th>Approval date</th>
                                            <th>Action</th>
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
        <script type="text/javascript"
            src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

        <script>
            $(document).ready(function() {

                window.csrfToken = '<?php echo csrf_token(); ?>';
                var postData = {};
                postData._token = window.csrfToken;

                $('#datatables').DataTable({
                    'columnDefs': [{
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }],
                    'select': {
                        'style': 'multi'
                    },
                    'order': [
                        [1, 'asc']
                    ],
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
                        "url": "{{ route('pending_collection.data') }}",
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

                        {
                            data: 'sid',
                            name: 'sid'
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
                            data: 'deposit_date',
                            name: 'deposit_date'
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

                        {
                            data: 'created_at',
                            name: 'created_at'
                        },

                        {
                            data: 'approved_date',
                            name: 'approved_date'
                        },


                        {
                            data: 'action',
                            name: 'action'
                        },
                    ]
                });
            });
        </script>
        <script>
            // Handle form submission event
            $('.approved-all').on('click', function(e) {
                e.preventDefault();
                // console.log($(this).attr('data-type'));
                var form = $('<form>', {
                    id: 'approve-all-form',
                });
                var table = $('#datatables').DataTable();
                var rows = table.column(0).checkboxes.selected();
                let filteredArray = rows.filter(item => item.includes("a"));
                let rows_selected = filteredArray.map(item => (item.replace("a", "")));
                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId) {
                    // Create a hidden element
                    form.append(
                        $('<input>', {
                            type: 'hidden',
                            name: 'collection_ids[]',
                            value: rowId
                        })
                    );
                });

                // Append the form to the body (or any other element)
                $('body').append(form);

                if (rows.length == 0) {
                    Swal.fire(
                        'Warning!',
                        'Please select a row?',
                        'question'
                    )
                    return;
                }
                if (filteredArray.length == 0) {
                    Swal.fire(
                        'Warning!',
                        'You currently do not have any pending approvals',
                        'question'
                    )
                    table.column(0).checkboxes.deselectAll();
                    return;
                }


                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing',
                            html: 'Please wait for a while...',
                            didOpen: () => {
                                Swal.showLoading()
                                const b = Swal.getHtmlContainer().querySelector('b')
                            },
                        });
                        $.ajax({
                            headers: {
                                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('multi_approved_collection') }}",
                            type: 'post',
                            // dataType: 'json',
                            data: form.serialize(),
                            success: function(data) {
                                // ... do something with the data...
                                if (data.status == 'success') {
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    table.column(0).checkboxes.deselectAll();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: data.message,
                                        // footer: '<a href="">Why do I have this issue?</a>'
                                    })
                                }
                                table.ajax.reload(null, false);
                            }
                        });
                    }
                })

                // FOR DEMONSTRATION ONLY
                // The code below is not needed in production

                // Output form data to a console
                // $('#example-console-rows').text(rows_selected.join(","));

                // Output form data to a console
                // $('#example-console-form').text($(form).serialize());

                // Remove added elements
                $('input[name="id\[\]"]', form).remove();

                // Prevent actual form submission
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
        </script>
    @endsection
