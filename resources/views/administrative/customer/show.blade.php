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
                <h3 class="titles text-center">Customer Info Details</h3>
                <!-- <div class="text-white detailed-card">
                    <ul>
                        <li>Customer Statement: <a href="{{route('get_statement')}}"><button class="btn btn-primary btn-sm mt-1 ml-3">View</button></a></li>
                        <li>Payment Schedule: <a href="{{route('get_payment')}}"><button class="btn btn-primary btn-sm mt-1 ml-3">View</button></a></li>
                        <li>Order ID: {{$data->id}}</li>
                        <li>Customer Name: {{$data->customer_name}}</li>
                        <li>Customer father name: {{$data->customer_father_name}}</li>
                        <li>Mobile no: {{$data->mobile_no}}</li>
                        <li>Company: {{$data->company}}</li>
                        <li>Model: {{$data->model}}</li>
                        <li>Delivery date: {{$data->delivery_date}}</li>
                        <li>Installment due date: {{$data->installment_due_date}}</li>
                        <li>Sub unit: {{$data->sub_unit}}</li>
                        <li>Installment id: {{$data->installment_id}}</li>
                        <li>Terms: {{$data->terms}}</li>
                        <li>Delear name: {{$data->delear_name}}</li>
                        <li>Market receivable: {{$data->market_receivable}}</li>
                        <li>Target current: {{$data->target_current}}</li>
                        <li>Target arrear: {{$data->target_arrear}}</li>
                        <li>Target total: {{$data->target_total}}</li>
                        <li>Penalty target: {{$data->penalty_target}}</li>
                        <li>Coll current: {{$data->coll_current}}</li>
                        <li>Collection arrear: {{$data->collection_arrear}}</li>
                        <li>Collection total no adv: {{$data->collection_total_no_adv}}</li>
                        <li>Collection advance: {{$data->collection_advance}}</li>
                        <li>Collection total with adv: {{$data->collection_total_with_adv}}</li>
                        <li>Penalty coll: {{$data->penalty_coll}}</li>
                        <li>Close balance: {{$data->close_balance}}</li>
                        <li>Due date current coll: {{$data->due_date_current_coll}}</li>
                        <li>Employee ID: {{$data->employee_id}}</li>
                        <li>Total orders: {{$data->total_orders}}</li>
                        <li>Finance by: {{$data->finance_by}}</li>
                        <li>Employee ID: {{$data->employee_id}}</li>
                        <li>No of dues installment: {{$data->no_of_dues_installment}}</li>
                        <li>Four above due: {{$data->four_above_due}}</li>
                        <li>First second due: {{$data->first_second_due}}</li>
                        <li>Dp percentage: {{$data->dp_percentage}}</li>
                        <li>Term changed: {{$data->term_changed}}</li>
                        <li>Customer change: {{$data->customer_change}}</li>
                        <li>Resold: {{$data->resold}}</li>
                        <li>Seize_v: {{$data->seize_v}}</li>
                        <li>Thana upazila name: {{$data->thana_upazila_name}}</li>
                        <li>District name: {{$data->district_name}}</li>
                        <li>Division name: {{$data->division_name}}</li>
                        <li>Vtd status: {{$data->vtd_status}}</li>
                        <li>Dealer commission status: {{$data->dealer_commission_status}}</li>
                        <li>Registration no: {{$data->registration_no}}</li>
                        <li>Vehicle type: {{$data->vehicle_type}}</li>
                        <li>Term changed date: {{$data->term_changed_date}}</li>
                        <li>Resold date: {{$data->resold_date}}</li>
                        <li>Profit type: {{$data->profit_type}}</li>
                        <li>Payment term: {{$data->payment_term}}</li>
                        <li>Sales condition: {{$data->sales_condition}}</li>
                        <li>Emi change: {{$data->emi_change}}</li>
                        <li>Eft status: {{$data->eft_status}}</li>
                        <li>Salesman id: {{$data->salesman_id}}</li>
                        <li>Salesman name: {{$data->salesman_name}}</li>
                        <li>Installment end date: {{$data->installment_end_date}}</li>
                        <li>Last pay date: {{$data->last_pay_date}}</li>
                        <li>Customer address: {{$data->customer_address}}</li>
                        <li>Nid: {{$data->nid}}</li>
                    </ul>
                </div> -->
                <div>
                    <table class="table table-bordered collection-details-table">
                        <tbody>
                            <tr>
                                <td class="collec-d-table-header left-part" width="50%">Customer Statement:</td>
                                <td class="right-part"><a href="{{route('get_statement')}}"><button class="btn btn-primary btn-sm ">View</button></a></td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part" width="50%">Payment Schedule:</td>
                                <td class="right-part"><a href="{{route('get_payment')}}"><button class="btn btn-primary btn-sm mt-1 ">View</button></a></td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part" width="50%">Order ID:</td>
                                <td class="right-part">{{$data->order_no}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Customer Name:</td>
                                <td class="right-part">{{$data->customer_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Customers' Father name:</td>
                                <td class="right-part"> {{$data->customer_father_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Mobile no:</td>
                                <td class="right-part">{{$data->mobile_no}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Company:</td>
                                <td class="right-part">{{$data->company}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Model:</td>
                                <td class="right-part">{{$data->model}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Delivery date:</td>
                                <td class="right-part">{{$data->delivery_date}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Installment Due Date:</td>
                                <td class="right-part">{{$data->installment_due_date}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Sub Unit:</td>
                                <td class="right-part">
                                {{$data->sub_unit}}
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Installment ID:</td>
                                <td class="right-part">
                                {{$data->installment_id}}
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Terms:</td>
                                <td class="right-part">
                                {{$data->terms}}
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Delear Name:</td>
                                <td class="right-part">{{$data->delear_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Market Receivable:</td>
                                <td class="right-part">{{$data->market_receivable}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Target Current:</td>
                                <td class="right-part">{{$data->target_current}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">
                                  Target Arrear:
                                </td>
                                <td class="right-part">
                                {{$data->target_arrear}}
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Target Total:</td>
                                <td class="right-part">{{$data->target_total}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Penalty Target:</td>
                                <td class="right-part">{{$data->penalty_target}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Coll Current:</td>
                                <td class="right-part">{{$data->coll_current}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Collection Arrear:</td>
                                <td class="right-part">{{$data->collection_arrear}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Collection Total No. Adv.:</td>
                                <td class="right-part"> {{$data->collection_total_no_adv}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Collection Advance:</td>
                                <td class="right-part">{{$data->collection_advance}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Collection Total With Adv.:</td>
                                <td class="right-part">{{$data->collection_total_with_adv}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Penalty Coll:</td>
                                <td class="right-part">{{$data->penalty_coll}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Close Balance:</td>
                                <td class="right-part">{{$data->close_balance}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Due Date Current Coll:</td>
                                <td class="right-part"> {{$data->due_date_current_coll}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Employee ID:</td>
                                <td class="right-part">{{$data->employee_id}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Total Orders:</td>
                                <td class="right-part">{{$data->total_orders}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Finance By:</td>
                                <td class="right-part">{{$data->finance_by}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">No of Dues Installment:</td>
                                <td class="right-part">{{$data->no_of_dues_installment}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Four Above Due:</td>
                                <td class="right-part">{{$data->four_above_due}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">First Second Due:</td>
                                <td class="right-part">{{$data->first_second_due}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Dp Percentage:</td>
                                <td class="right-part">{{$data->dp_percentage}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Term Changed:</td>
                                <td class="right-part">{{$data->term_changed}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Customer Change:</td>
                                <td class="right-part">{{$data->customer_change}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Resold:</td>
                                <td class="right-part">{{$data->resold}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Seize_v:</td>
                                <td class="right-part">{{$data->seize_v}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Thana Upazila Name:</td>
                                <td class="right-part">{{$data->thana_upazila_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">District Name:</td>
                                <td class="right-part"> {{$data->district_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Division Name:</td>
                                <td class="right-part">{{$data->division_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">VTD Status:</td>
                                <td class="right-part">{{$data->vtd_status}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Dealer Commission Status:</td>
                                <td class="right-part">{{$data->dealer_commission_status}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Registration No.:</td>
                                <td class="right-part">{{$data->registration_no}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Vehicle Type:</td>
                                <td class="right-part">{{$data->vehicle_type}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Term Changed Date:</td>
                                <td class="right-part">{{$data->term_changed_date}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Resold Date:</td>
                                <td class="right-part">{{$data->resold_date}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Profit Type:</td>
                                <td class="right-part">{{$data->profit_type}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Payment Term:</td>
                                <td class="right-part">{{$data->payment_term}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Sales Condition:</td>
                                <td class="right-part">{{$data->sales_condition}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">EMI Change:</td>
                                <td class="right-part">{{$data->emi_change}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">EFT Status:</td>
                                <td class="right-part">{{$data->eft_status}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Salesman ID:</td>
                                <td class="right-part">{{$data->salesman_id}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Salesman Name:</td>
                                <td class="right-part">{{$data->salesman_name}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Installment End Date:</td>
                                <td class="right-part">{{$data->installment_end_date}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Last Pay Date:</td>
                                <td class="right-part">{{$data->last_pay_date}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">Customer Address:</td>
                                <td class="right-part">{{$data->customer_address}}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header left-part">NID:</td>
                                <td class="right-part">{{$data->nid}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
