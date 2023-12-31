@extends('administrative.layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/css/forecast-page/forecast-page.css') }}">
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>

        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card" style="width: 100vw;">
                <div class="card-body">
                    <h3 class="titles text-center">Forecast</h3>
                    <!-- tabs -------------->
                    <div class="active-tabs">
                        <input type="radio" id="tab1" name="tab" checked>
                        <label for="tab1"> Monthly</label>
                        <input type="radio" id="tab2" name="tab"
                            @if (request()->has('date_wise')) checked @endif>
                        <label for="tab2"> Daily</label>

                        <div class="line"></div>
                        <div class="content-container">
                            <div class="content " id="c1">
                                <!-- monthly tab---------->
                                <div class="tab-wrapper">

                                    <!-- monthly table ------------------>
                                    <table class="table table-bordered view-detailed-table" width="60%">
                                        <tbody>
                                            <tr>
                                                <th class="view-d-table-header" width="50%">Date </th>
                                                <th class="view-d-table-header"> Amount</th>
                                            </tr>
                                            @php
                                                $monthNames = [
                                                    '01' => 'January',
                                                    '02' => 'February',
                                                    '03' => 'March',
                                                    '04' => 'April',
                                                    '05' => 'May',
                                                    '06' => 'June',
                                                    '07' => 'July',
                                                    '08' => 'August',
                                                    '09' => 'September',
                                                    '10' => 'October',
                                                    '11' => 'November',
                                                    '12' => 'December',
                                                ];
                                            @endphp
                                            @foreach ($month as $months)
                                                <tr>
                                                    <td>{{ $monthNames[$months->month] }}</td>
                                                    <td>{{ number_format($months->total) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="content" id="c2">
                                <div class="tab-wrapper">
                                    <form method="get" action="{{ route('administrative.get_daily_data') }}">
                                        <div class="date-picker-n-submit">

                                            <div class="position-relative">
                                                <input class="date-picker" name="from" style="display: block;"
                                                    type="date" placeholder="From" required>
                                                <div>
                                                    <label class="date-labels" style="left: -15px;"
                                                        for="">From</label>
                                                </div>
                                            </div>

                                            <div class="position-relative">
                                                <input class="date-picker" name="to" style="display: block;"
                                                    type="date" placeholder="From" required>
                                                <div>
                                                    <label class="date-labels" style="left: -24px;"
                                                        for="">To</label>
                                                </div>
                                            </div>

                                            <button type="submit" name="date_wise" value="date_wise"
                                                class="submit-btn">Submit</button>

                                        </div>
                                    </form>
                                    <!-- daily table ------>
                                    <table class="table table-bordered view-detailed-table" width="60%">
                                        <tbody>
                                            <tr>
                                                <th class="view-d-table-header" width="50%">Date </th>
                                                <th class="view-d-table-header"> Amount</th>
                                            </tr>
                                            @foreach ($date as $dates)
                                                <tr>
                                                    <td>{{ date('d-M-Y', strtotime($dates->date)) }}</td>
                                                    <td>{{ number_format($dates->total) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('page-js')
@endsection
