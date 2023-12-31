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
                    <h3 class="titles text-center">Payment Schedule</h3>
                    <!-- <div class="text-white detailed-card">
                      <ul>
                        <li>Order No: L5235</li>
                        <li>Installment ID: 1</li>
                        <li>Due Date: 30-sep-2018</li>
                        <li>Amount: 25,000</li>
                        {{-- <a href="{{route('view_payment')}}" class="btn btn-primary btn-icon-text mb-2 mb-md-0"> View Payment </a> --}}
                      </ul>
                    </div> -->
                    <div>
                    <table class="table table-bordered collection-details-table">
                            <tbody>
                            <tr>
                                    <td class="collec-d-table-header left-part" width="50%">Order No:</td>
                                    <td class="right-part">L5235</td>
                                </tr>
                                <tr>
                                    <td class="collec-d-table-header left-part">Installment ID:</td>
                                    <td class="right-part">1</td>
                                </tr> 
                                <tr>
                                    <td class="collec-d-table-header left-part">Due Date:</td>
                                    <td class="right-part">30-sep-2018</td>
                                </tr> 
                             
                                <tr>
                                    <td class="collec-d-table-header left-part">Amount:</td>
                                    <td class="right-part">25,000</td>
                                </tr>
                            </tbody>
                        </table> 
                        <div class="text-center mt-3">
                        <a href="{{route('view_statement')}}" class="btn btn-primary btn-icon-text mb-2 mb-md-0"> View Payment </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

