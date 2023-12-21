@extends('administrative.layouts.master')
@section('page-css')
    <style>

    </style>
@endsection

@section('content')
    <div class="mt-2">
        <ul class="dm-breadcrumb nav">
            <li class="dm-breadcrumb__item">
                <a href="#">
                    Home
                </a>
                <span class="slash">/</span>
            </li>
            <li class="dm-breadcrumb__item">
                <span>Role</span>
            </li>
        </ul>
    </div>
    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
            <div>
                <h4 class="mb-3 mb-md-0">Permissions</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                @can('permission_create')
                    <a href="{{ route('administrative.role.create') }}"
                        class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">
                        <i class="fas fa-plus-square" data-feather="plus-square"></i>
                        Add New
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
                                        <tr class="userDatatable-header">
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Permissions</th>
                                            <th class="disabled-sorting text-left" style="width: 100px">Action</th>
                                            <!-- <th class="disabled-sorting text-left" style="width: 100px">delete</th> -->
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
                ajax: "{{ route('administrative.role.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permission',
                        name: 'permission'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    // {
                    //     data: 'delete',
                    //     name: 'delete'
                    // },
                ]
            });
        });

        function deleteData(rowid) {
            let url = '{{ route('administrative.role.destroy', ':id') }}';
            url = url.replace(':id', rowid);
            Swal.fire({
                title: 'Do you want to delete this?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.delete(url).then(res => {
                        if (res.data == 'success') {
                            Swal.fire('Saved!', '', 'success')
                            $('#datatables').DataTable().ajax.reload(null, false);
                        }
                    });
                }
            })
        }
    </script>
@endsection
