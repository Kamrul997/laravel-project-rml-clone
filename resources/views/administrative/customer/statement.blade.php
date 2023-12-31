@extends('administrative.layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/css/target-sheet-pages/target-sheet-view-details.css')}}">
<style>
    .titles {
        color: #4944B9;
        margin-bottom: 1.56rem;

    }

    .detailed-card {
        background-color: #5D5FEF;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 1.2rem;
    }

    .collection-entry-btn {
        color: #4944B9;
        background: white;
        position: relative;
        top: 2px;
        border-radius: 5px !important;
        padding: 4px, 10px, 4px, 10px !important;
    }

    .collection-details-table {
        overflow: hidden;
        border-radius: 10px;
        background-color: #F7F7FC;
        text-align: center;
    }

    .collection-details-table td {
        border: 1px solid white !important;
        color: #526787;
        font-weight: 600;
        white-space: normal !important;

    }

    .collec-d-table-header {
        background-color: #DEDEFF !important;
        color: #526787 !important;
        font-weight: 700 !important;
    }

    .attachment-files {
        word-break: break-all;
    }

    .right-part{
        text-align: left;
    }
    .left-part{
        text-align: right;
    }
</style>

@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ route('customer.index') }}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="server"></i>
                Customer List
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h3 class="titles text-center">Customer Statement</h3>
                    <!-- <div class="text-white detailed-card">
                      <ul>
                        <li>Unit: @if(isset($unit)) {{$unit->name}} @endif </li>
                        <li>Debit Amount: {{$data->debit_amount}}</li>
                        <li>Net Receivable: {{$data->net_receiveable}}</li>
                        <a href="{{route('view_statement')}}" class="btn btn-primary btn-icon-text mb-2 mb-md-0"> View Statement </a>
                      </ul>
                    </div> -->
                    <div>
                        <table class="table table-bordered collection-details-table">
                            <tbody>
                            <tr>
                                    <td class="collec-d-table-header left-part" width="50%">Unit:</td>
                                    <td class="right-part">@if(isset($unit)) {{$unit->name}} @endif</td>
                                </tr>
                                <tr>
                                    <td class="collec-d-table-header left-part">Debit Amount:</td>
                                    <td class="right-part">{{$data->debit_amount}}</td>
                                </tr> 
                                <tr>
                                    <td class="collec-d-table-header left-part">Net Receivable:</td>
                                    <td class="right-part">{{$data->net_receiveable}}</td>
                                </tr> 
                            </tbody>
                        </table>
                        <div class="mt-3 text-center">
                        <a href="{{route('view_statement')}}" class="btn btn-primary btn-icon-text mb-2 mb-md-0"> View Statement </a>

                        </div>        
                </div>
            </div>
        </div>
    </div>
@endsection

