@extends('administrative.layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/css/target-sheet-pages/target-sheet-view-details.css')}}">

@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="{{ route('administrative.target') }}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="server"></i>
            Target Sheet List
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-8 grid-margin stretch-card offset-md-2">
        <div class="card">
            <div class="card-body">
                <h3 class="titles text-center">Target Sheet View Details</h3>
                <div class="text-white detailed-card">
                    <h4 class="mb-2 pl-2 pt-2">{{ $data->employee_name }}</h4>
                    <ul>
                        <li>Total Target Amount: {{ $data->target_total }} </li>
                        <li>Unid Id: @if (isset($unit))
                            {{ $unit->name }}
                            @endif
                        </li>
                        <li>Customer Name: {{ $data->cutomer_name }}</li>
                        <li>Employee Id: {{ $data->employee_id }}</li>
                        <li>Order No: {{ $data->ord_no }}</li>
                        <li>Employee Name: {{ $data->employee_name }}</li>
                        <li>Mobile No: {{ $data->mobile_no }}</li>
                        <li>Entry Date: {{ $data->created_at }}</li>

                        @if(Auth::user()->hasRole('Subunit'))
                        <a href="{{route('collect_entry',$data->id)}}" class="btn btn-light btn-icon-text mb-2 mt-2 collection-entry-btn" style="color: #4944B9;
                         background: white;
                          position: relative;
                           top: 2px;
                            left: -13px;">
                            Collection Entry
                        </a>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
