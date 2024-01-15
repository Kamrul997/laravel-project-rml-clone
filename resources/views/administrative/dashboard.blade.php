@extends('administrative.layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard-css/dashboard-css.css') }}">
@endsection
@section('content')
<div class="dash container">
    <div class="user-intro mt-4">
        <h2>Welcome Back!</h1>
            <h4>{{ auth()->user()->name }}</h3>
    </div>

    <!-- performance section ---->
    <div class="performance-section">
        <h4 class="title">Performance</h4>
        <div class="perf-cards">
            <div class=" perform-card text-white">
                <div class="card-wrapper">
                    <h4 class="text-white text-bold">{{ $lastMonth ? number_format($lastMonth) : 0 }} <span>BDT</span>
                    </h4>
                    <h6 class="text-white text-bold">Last Month</h6>
                </div>
            </div>
            <div class=" perform-card text-white">
                <div class="card-wrapper">
                    <h4 class="text-white text-bold">{{ $thisMonth ? number_format($thisMonth) : 0 }} <span>BDT</span>
                    </h4>
                    <h6 class="text-white text-bold">This Month</h6>
                </div>
            </div>
            <div class=" perform-card text-white">
                <div class="card-wrapper">
                    <h4 class="text-white text-bold">{{ $lastDay ? number_format($lastDay) : 0 }} <span>BDT</span></h4>
                    <h6 class="text-white text-bold">Last Day</h6>
                </div>
            </div>
            <div class=" perform-card red-card text-white">
                <div class="card-wrapper">
                    <h4 class="text-white text-bold">{{ $today ? number_format($today) : 0 }} <span>BDT</span></h4>
                    <h6 class="text-white text-bold">Today</h6>
                </div>
            </div>
            <div class=" perform-card text-white">
                <div class="card-wrapper">
                    <h4 class="text-white text-bold monthly-amount">
                        {{ $monthlyForcast ? number_format($monthlyForcast) : 0 }} <span>BDT</span>
                    </h4>
                    <h6 class="text-white text-bold">Monthly forecast</h6>
                </div>
                @if (\Auth::user()->hasRole('Subunit'))
                <div class="add-btn" type="button" data-toggle="modal" data-target="#monthlyForecastModal">
                    Add +
                </div>
                @endif
                <div style="top: 35vh;" class="modal fade" id="monthlyForecastModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close invisible" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title text-black text-center" id="exampleModalLabel">Add Monthly
                                    forecast</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-black">
                                <input name="month" id="month" type="month" style="border: none;
                         padding:5px;
                         background-color: #f3f3f3;
                         border-radius: 5px;
                         height: 35px;
                         width: 100%;">
                                <span class="month-error text-danger mt-1"></span>
                            </div>
                            <div class="modal-body text-black">
                                <input id="monthly-amount" placeholder="Amount" name="amount" type="number" style="padding:5px;border: none;background-color: #f3f3f3;border-radius: 5px;height: 35px;width: 100%;">
                                <span class="monthly-amount-error text-danger mt-1"></span>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary btn-monthly" style="background-color: #5d5fef;
                         width: 100%;
                         height: 35px;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" perform-card red-card text-white">
                <div class="card-wrapper">
                    <h4 class="text-white text-bold daily-amount">{{ number_format($dailyForcast) }} <span>BDT</span>
                    </h4>
                    <h6 class="text-white text-bold">Daily Forecast</h6>
                </div>
                @if (\Auth::user()->hasRole('Subunit'))
                <div class="add-btn" type="button" data-toggle="modal" data-target="#dailyForecastModal">
                    Add +
                </div>
                @endif
                <div style="top: 35vh;" class="modal fade" id="dailyForecastModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close invisible" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title text-black text-center" id="exampleModalLabel">Add Daily forecast
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-black">
                                <input id="date" name="date" type="date" style="padding: 5px;
                                        border: none;
                                        background-color: #f3f3f3;
                                        border-radius: 5px;
                                        height: 35px;
                                        width: 100%;">
                                <span class="date-error text-danger mt-1"></span>
                            </div>
                            <div class="modal-body text-black">
                                <input id="daily-amount" placeholder="Amount" name="amount" type="number" style="padding:5px;border: none;background-color: #f3f3f3;border-radius: 5px;height: 35px;width: 100%;">
                                <span class="daily-amount-error text-danger mt-1"></span>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-daily" style="background-color: #5d5fef;width: 100%;height: 35px;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- monthly target vs achievement -->
    <div class="">
        <div class="d-flex justify-content-between align-items-center mb-3" style="display: none;">
            <h4 class="title monthly-target-title">This Month Target vs Achivement</h4>
            <select name="myDropdown" id="targetvsachivement" class="unit-selection control js-example-basic-single ">
                <option value="0" selected>All Unit</option>
                @foreach ($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="chart">
            <div id="monthlyTvA">
                <canvas id="monthlyChartTvA"></canvas>
            </div>
            <div class="w-100" id="dailyTvA">
                <div class="w-100">
                    <canvas id="dailyChartTvA"></canvas>
                </div>
                <div class="d-flex align-items-center justify-content-center w-100 mt-3" style="color: #8b8b8b">
                    <div class="d-flex align-items-center mr-3 dailyTarget1">
                    </div>
                    <div class="d-flex align-items-center dailyAchievement1">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- forecast vs. achievement tab -->
    <h4 class="title mt-5 pt-2">Forecast vs Achievement</h4>
    <div class="tabset container">
        <!-- Tab 1 -->
        <input type="radio" name="tabset" id="tab1" aria-controls="Monthly" checked>
        <label for="tab1" style="float: right;">Monthly</label>
        <!-- Tab 2 -->
        <input type="radio" name="tabset" id="tab2" aria-controls="Weekly">
        <label for="tab2" style="float: right;">Weekly</label>
        <!-- Tab 3 -->
        <input type="radio" name="tabset" id="tab3" aria-controls="Daily">
        <label for="tab3" style="float: right;">Daily</label>

        <div class="tab-panels">
            <section id="Monthly" class="tab-panel container">
                <canvas id="monthlyChart"></canvas>
            </section>
            <section id="Weekly" class="tab-panel container">
                <canvas id="weeklyChart"></canvas>
            </section>
            <section id="Daily" class="tab-panel container">
                <canvas id="dailyChart"></canvas>
            </section>
            <div class="d-flex align-items-center justify-content-center w-100 mt-3 pb-2" style="color: #8b8b8b">
                <div class="d-flex align-items-center mr-3 dailyTarget2">
                </div>
                <div class="d-flex align-items-center dailyAchievement2">
                </div>
            </div>

        </div>

    </div>
    <div class="complete-pending-section">
        <div class="comp-pend-wrapper w-100">
            <div class="d-flex align-items-center complete-collect-card">
                <img class="mr-2" src="{{ asset('assets/images/dashboard-icons/check.svg') }}" alt="">
                <div>
                    <h4 class="text-white">{{ number_format($completeCollection) }}</h4>
                    <h6 class="text-white">Complete Collect</h6>
                </div>
            </div>
            <div class="d-flex align-items-center pending-collect-card">
                <img class="mr-2" src="{{ asset('assets/images/dashboard-icons/loading.svg') }}" alt="">
                <div>
                    <h4 class="text-white">{{ number_format($pendingCollection) }}</h4>
                    <h6 class="text-white">Pending Collect</h6>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('page-js')
<script>
    $(document).on("click", ".close", function() {
        $('#date').val('');
        $('#daily-amount').val('');
        $('#month').val('');
        $('#monthly-amount').val('');
        $('#dailyForecastModal').modal('hide');
        $('#monthlyForecastModal').modal('hide');
        $('.date-error').text('');
        $('.daily-amount-error').text('');
        $('.month-error').text('');
        $('.monthly-amount-error').text('');
    });
    $(document).on("click", ".btn-daily", function(e) {
        e.preventDefault();
        var date = $('#date').val();

        var givenDate = new Date(date);
        var currentDate = new Date();
        var diffDays = currentDate.getDate() - givenDate.getDate();


        var amount = parseFloat($('#daily-amount').val());

        if (date === '') {
            $('.date-error').text('Date Field is Required');
            return;
        }
        if (amount === '') {
            $('.daily-amount-error').text('Amount Field is Required');
            return;
        }

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Set the CSRF token in the default headers for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': csrfToken
            }
        });
        $.post("/administrative/forecast/store-date", {
            date: date,
            amount: amount
        }, function(data) {
            if (data.status == true) {
                swal.fire({
                    title: data.msg,
                    type: 'success',
                });
                if (diffDays >= 0) {
                    $('.daily-amount').text(data.amount);

                }
                $('#date').val('');
                $('#daily-amount').val('');
                $('#dailyForecastModal').modal('hide');

            } else {
                swal.fire({
                    title: data.msg,
                    type: 'error',
                });

            }
        });


    });

    $(document).on("change", "#date", function(e) {
        $(".date-error").text('');
        $.ajax({
            url: '/administrative/forecast/get-date-data/' + $('#date').val(),
            method: "GET",
            success: function(data) {
                $('#daily-amount').val(data.data);
            }
        });
    });

    $(document).on("click", "#daily-amount", function(e) {
        $(".daily-amount-error").text('');
    });
</script>

<script>
    $(document).on("click", ".btn-monthly", function(e) {
        e.preventDefault();
        var month = $('#month').val();
        var givenMonth = new Date($('#month').val());
        var currentMonth = new Date();

        // Get the month component for each date
        var givenMonth = givenMonth.getMonth();
        var currentMonth = currentMonth.getMonth();

        var amount = parseFloat($('#monthly-amount').val());

        if (month === '') {
            $('.month-error').text('Month Field is Required');
            return;
        }
        if (amount === '') {
            $('.monthly-amount-error').text('Amount Field is Required');
            return;
        }

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Set the CSRF token in the default headers for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': csrfToken
            }
        });
        $.post("/administrative/forecast/store-month", {
            month: month,
            amount: amount
        }, function(data) {
            if (data.status == true) {
                swal.fire({
                    title: data.msg,
                    type: 'success',
                });

                if (givenMonth <= currentMonth) {
                    $('.monthly-amount').text(data.amount);
                }
                $('#month').val('');
                $('#monthly-amount').val('');
                $('#monthlyForecastModal').modal('hide');

            } else {
                swal.fire({
                    title: data.msg,
                    type: 'error',
                });

            }
        });


    });

    $(document).on("change", "#month", function(e) {
        $(".month-error").text('');
        $.ajax({
            url: '/administrative/forecast/get-month-data/' + $('#month').val(),
            method: "GET",
            success: function(data) {
                $('#monthly-amount').val(data.data);
            }
        });
    });

    $(document).on("click", "#monthly-amount", function(e) {
        $(".monthly-amount-error").text('');
    });
</script>

<!-- This Month Target vs Achivement -->
<script>
    $(document).ready(function() {
        var userType = "{{ Auth::user()->roles->pluck('name')[0] ?? '' }}";
        if (userType == 'Admin' || userType == 'National' || userType == 'Accounts' || userType == 'Area' ||
            userType == 'Zone' || userType == 'Unit') {
            $('#targetvsachivement').addClass('d-block');
            ajaxCallBarChart(0);
        }
        if (userType == 'Subunit') {
            $('#monthlyTvA').addClass('d-none');
            $('#targetvsachivement').addClass('d-none');

            ajaxCallDonutChart("{{ auth()->user()->unit_id }}");
        }
    });

    $(document).on("change", "#targetvsachivement", function(e) {
        var value = $(this).val();
        if (value == 0) {
            $('#dailyTvA').removeClass('d-block');
            $('#dailyTvA').addClass('d-none');
            $('#monthlyTvA').removeClass('d-none');
            $('#monthlyTvA').addClass('d-block');
            ajaxCallBarChart(value);
        } else {
            $('#monthlyTvA').removeClass('d-block');
            $('#monthlyTvA').addClass('d-none');
            $('#dailyTvA').removeClass('d-none');
            $('#dailyTvA').addClass('d-block');
            ajaxCallDonutChart(value);
        }
    });

    function ajaxCallBarChart(value) {
        var unit = value;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Set the CSRF token in the default headers for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': csrfToken
            }
        });
        $.post("/administrative/monthly-target-achivement", {
            unit: unit,
        }, function(data) {
            var ctx = document.getElementById("monthlyChartTvA").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.unitArray,
                    datasets: [{
                        label: 'Target',
                        data: data.targetArray,
                        backgroundColor: "#5D5FEF"
                    }, {
                        label: 'Achievement',
                        data: data.collectionArray,
                        backgroundColor: "#00D7C4",
                    }]
                },
            });
        });
    }

    function ajaxCallDonutChart(value) {
        var userType = "{{ Auth::user()->roles->pluck('name')[0] ?? '' }}";
        if (userType == 'Unit') {
            var unit = value;
            var isSubuser = 0;
        } else if (userType == 'Subunit') {
            var unit = value;
            var isSubuser = 1;
        } else {
            var unit = value;
            var isSubuser = 0;
        }
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Set the CSRF token in the default headers for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': csrfToken
            }
        });
        $.post("/administrative/monthly-target-achivement", {
            unit: unit,
            isSubuser: isSubuser,
        }, function(data) {
            var html1 = '<div class="mr-1" style="width: 15px; height: 15px; background-color: #5D5FEF;"></div><h5>Target: <strong>' + data.target + '</strong></h5>';
            var html2 = '<div class="mr-1" style="width: 15px; height: 15px; background-color: #00D7C4;"></div><h5>Achievement: <strong>' + data.collection + '</strong></h5>';

            $('.dailyTarget1').html(html1);
            $('.dailyAchievement1').html(html2);

            var ctx = document.getElementById("dailyChartTvA").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        'Target (Monthly)',
                        'Achievement (Monthly)',

                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [data.target, data.collection],
                        backgroundColor: [
                            '#5D5FEF',
                            '#00D7C4',
                        ],
                        hoverOffset: 4
                    }]

                },

            });
        });
    }
</script>

<!-- Forecast vs achievement -->

<script>
    $(document).ready(function() {
        var userType = "{{ Auth::user()->roles->pluck('name')[0] ?? '' }}";

        if (userType == 'Admin' || userType == 'National' || userType == 'Accounts') {
            monthlyChart();
        } else {
            monthlyPieChart();
        }

        $("#tab1").on("click", function() {
            if (userType == 'Admin' || userType == 'National' || userType == 'Accounts') {
                monthlyChart();
            } else {
                monthlyPieChart();
            }
        });
        $("#tab2").on("click", function() {
            if (userType == 'Admin' || userType == 'National' || userType == 'Accounts') {
                weeklyChart();
            } else {
                weeklyPieChart();
            }
        });
        $("#tab3").on("click", function() {
            if (userType == 'Admin' || userType == 'National' || userType == 'Accounts') {
                dailyChart();
            } else {
                dailyPieChart();
            }
        });

        //  forecast monthly chart || Dashboard
        function monthlyChart() {
            var ctx = document.getElementById("monthlyChart").getContext('2d');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            });

            $.post("/administrative/forecast-achivement", {
                    type: 'monthly',
                },

                function(data) {
                    var units = data.units;
                    var achievement = data.achievement;
                    var forecast = data.forecast;

                    // Create dictionaries to map unit names to their respective amounts
                    var achievementMap = {};
                    var forecastMap = {};

                    achievement.forEach(item => {
                        achievementMap[item.unit_name] = item.total_amount;
                    });

                    forecast.forEach(item => {
                        forecastMap[item.unit_name] = item.total_amount;
                    });

                    var newUnitNames = units.map(item => item.name);
                    var newAch = newUnitNames.map(unitName => achievementMap[unitName] || 0);
                    var newForecast = newUnitNames.map(unitName => forecastMap[unitName] || 0);

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: newUnitNames,
                            datasets: [{
                                label: 'Forecast (monthly)',
                                data: newForecast,
                                backgroundColor: "#5D5FEF"
                            }, {
                                label: 'Achievement (monthly)',
                                data: newAch,
                                backgroundColor: "#00D7C4",
                            }]
                        },
                    });
                });
        }


        //  forecast weekly chart || Dashboard

        function weeklyChart() {
            var ctx = document.getElementById("weeklyChart").getContext('2d');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            });
            $.post("/administrative/forecast-achivement", {
                    type: 'weekly',
                },


                function(data) {
                    var units = data.units;
                    var achievement = data.achievement;
                    var forecast = data.forecast;

                    // Create dictionaries to map unit names to their respective amounts
                    var achievementMap = {};
                    var forecastMap = {};

                    achievement.forEach(item => {
                        achievementMap[item.unit_name] = item.total_amount;
                    });

                    forecast.forEach(item => {
                        forecastMap[item.unit_name] = item.total_amount;
                    });

                    var newUnitNames = units.map(item => item.name);
                    var newAch = newUnitNames.map(unitName => achievementMap[unitName] || 0);
                    var newForecast = newUnitNames.map(unitName => forecastMap[unitName] || 0);

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: newUnitNames,
                            datasets: [{
                                label: 'Forecast (weekly)',
                                data: newForecast,
                                backgroundColor: "#5D5FEF"
                            }, {
                                label: 'Achievement (weekly)',
                                data: newAch,
                                backgroundColor: "#00D7C4",
                            }]
                        },
                    });
                });

        }

        //  forecast daily chart || Dashboard
        function dailyChart() {
            var ctx = document.getElementById("dailyChart").getContext('2d');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            });
            $.post("/administrative/forecast-achivement", {
                    type: 'daily',
                },


                function(data) {
                    var units = data.units;
                    var achievement = data.achievement;
                    var forecast = data.forecast;

                    // Create dictionaries to map unit names to their respective amounts
                    var achievementMap = {};
                    var forecastMap = {};

                    achievement.forEach(item => {
                        achievementMap[item.unit_name] = item.total_amount;
                    });

                    forecast.forEach(item => {
                        forecastMap[item.unit_name] = item.total_amount;
                    });

                    var newUnitNames = units.map(item => item.name);
                    var newAch = newUnitNames.map(unitName => achievementMap[unitName] || 0);
                    var newForecast = newUnitNames.map(unitName => forecastMap[unitName] || 0);

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: newUnitNames,
                            datasets: [{
                                label: 'Forecast (daily)',
                                data: newForecast,
                                backgroundColor: "#5D5FEF"
                            }, {
                                label: 'Achievement (daily)',
                                data: newAch,
                                backgroundColor: "#00D7C4",
                            }]
                        },
                    });
                });

        }

        function monthlyPieChart() {
            var ctx = document.getElementById("monthlyChart").getContext('2d');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            });
            $.post("/administrative/forecast-achivement", {
                type: 'monthly',
            }, function(data) {

                var html1 = '<div class="mr-1" style="width: 15px; height: 15px; background-color: #5D5FEF;"></div><h5>Forcast: <strong>' + (data.forecast.length > 0 ? data.forecast[0].total_amount : 0) + '</strong></h5>';
                var html2 = '<div class="mr-1" style="width: 15px; height: 15px; background-color: #00D7C4;"></div><h5>Achievement: <strong>' + (data.achievement.length > 0 ? data.achievement[0].total_amount : 0) + '</strong></h5>';

                $('.dailyTarget2').html(html1);
                $('.dailyAchievement2').html(html2);
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            'Forecast (monthly)',
                            'Achievement (monthly)',

                        ],
                        datasets: [{
                            label: 'My First Dataset',
                            data: [data.forecast.length > 0 ? data.forecast[0]
                                .total_amount : 0, data.achievement.length > 0 ?
                                data.achievement[0].total_amount : 0
                            ],
                            backgroundColor: [
                                '#5D5FEF',
                                '#00D7C4',
                            ],
                            hoverOffset: 4
                        }]
                    },
                });
            });

        }

        function weeklyPieChart() {
            var ctx = document.getElementById("weeklyChart").getContext('2d');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            });
            $.post("/administrative/forecast-achivement", {
                type: 'weekly',
            }, function(data) {
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            'Forecast (weekly)',
                            'Achievement (weekly)',

                        ],
                        datasets: [{
                            label: 'My First Dataset',
                            data: [data.forecast.length > 0 ? data.forecast[0]
                                .total_amount : 0, data.achievement.length > 0 ?
                                data.achievement[0].total_amount : 0
                            ],
                            backgroundColor: [
                                '#5D5FEF',
                                '#00D7C4',
                            ],
                            hoverOffset: 4
                        }]
                    },
                });
            });

        }

        function dailyPieChart() {
            var ctx = document.getElementById("dailyChart").getContext('2d');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            });
            $.post("/administrative/forecast-achivement", {
                type: 'daily',
            }, function(data) {
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            'Forecast (daily)',
                            'Achievement (daily)',

                        ],
                        datasets: [{
                            label: 'My First Dataset',
                            data: [data.forecast.length > 0 ? data.forecast[0]
                                .total_amount : 0, data.achievement.length > 0 ?
                                data.achievement[0].total_amount : 0
                            ],
                            backgroundColor: [
                                '#5D5FEF',
                                '#00D7C4',
                            ],
                            hoverOffset: 4
                        }]
                    },
                });
            });

        }

        function loading() {
            Swal.fire({
                title: 'Loading!',
                html: 'Please wait for a while...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                },
            });
        }
    });
</script>
@endsection
