<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="version" content="version=1.01">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>RML</title>

    <!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap">


    <!-- inject:css-->
    <link rel="stylesheet" href="{{asset('assets/vendor_assets/select2/select2.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/bootstrap/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/daterangepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/fontawesome.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/footable.standalone.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/fullcalendar@5.2.0.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/jquery-jvectormap-2.0.5.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/jquery.mCustomScrollbar.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/leaflet.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/MarkerCluster.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/MarkerCluster.Default.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/slick.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/star-rating-svg.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/trumbowyg.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/wickedpicker.min.css') }}">

    <link rel="stylesheet" href="{{asset('assets/vendor_assets/js/datatables.net-bs4/dataTables.bootstrap4.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor_assets/css/hexa-toaster.css') }}">

    <link href="
https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css
" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/font-awesome-line-awesome/css/all.min.css" integrity="sha512-dC0G5HMA6hLr/E1TM623RN6qK+sL8sz5vB+Uc68J7cBon68bMfKcvbkg6OqlfGHo1nMmcCxO5AinnRTDhWbWsA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/fontawesome.min.js" integrity="sha512-c41hNYfKMuxafVVmh5X3N/8DiGFFAV/tU2oeNk+upk/dfDAdcbx5FrjFOkFhe4MOLaKlujjkyR4Yn7vImrXjzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="
https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/script/monochrome/bundle.min.js
"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- endinject -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">

    @yield('page-css')
</head>

<body>
    <div class="layout-light side-menu">

        {{-- navbar --}}
        @include('administrative.layouts.partial._navbar')


        <main class="main-content">

            {{-- sidebar --}}
            @include('administrative.layouts.partial._sidebar')

            <div class="contents">

                <div class="crm mb-25">
                    <div class="container-fluid">

                        <!-- toaster Start -->
                        <div class="hs-toast-wrapper  hs-toast-fixed-top " id="hexa-toaster"></div>
                        <!-- toaster End -->

                        @yield('content')
                    </div>
                </div>

            </div>
            {{-- footer --}}
            @include('administrative.layouts.partial._footer')
        </main>
        <div id="overlayer">
            <div class="loader-overlay">
                <div class="dm-spin-dots spin-lg">
                    <span class="spin-dot badge-dot dot-primary"></span>
                    <span class="spin-dot badge-dot dot-primary"></span>
                    <span class="spin-dot badge-dot dot-primary"></span>
                    <span class="spin-dot badge-dot dot-primary"></span>
                </div>
            </div>
        </div>
        <div class="overlay-dark-sidebar"></div>
        <div class="customizer-overlay"></div>
    </div>

    <!-- inject:js-->
    {{-- <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBgYKHZB_QKKLWfIRaYPCadza3nhTAbv7c"></script> --}}

    <script src="{{ asset('assets/vendor_assets/js/jquery/jquery-3.5.1.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery/jquery-ui.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/bootstrap/popper.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/bootstrap/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/moment/moment.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/accordion.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/apexcharts.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/autoComplete.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/Chart.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/daterangepicker.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/drawer.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/dynamicBadge.js') }}"></script>

    <script src="{{asset('assets/vendor_assets/select2/select2.min.js')}}"></script>

    <script src="{{ asset('assets/vendor_assets/js/dynamicCheckbox.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/footable.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/fullcalendar@5.2.0.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/google-chart.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery-jvectormap-2.0.5.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery-jvectormap-world-mill-en.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery.countdown.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery.filterizr.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery.magnific-popup.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery.peity.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/jquery.star-rating-svg.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/leaflet.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/leaflet.markercluster.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/loader.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/message.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/moment.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/muuri.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/notification.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/popover.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/slick.min.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/trumbowyg.min.js') }}"></script>

    <script src="{{asset('assets/vendor_assets/js/datatables.net/jquery.dataTables.js')}}"></script>

    <script src="{{asset('assets/vendor_assets/js/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>

    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>

    <script src="{{ asset('assets/vendor_assets/js/wickedpicker.min.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/apexmain.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/charts.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/drag-drop.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/footable.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/full-calendar.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/googlemap-init.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/jvectormap-init.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/leaflet-init.js') }}"></script>

    <script src="{{ asset('assets/theme_assets/js/main.js') }}"></script>

    <script src="{{ asset('assets/vendor_assets/js/hexa-toaster.js') }}"></script>

    @include('administrative.layouts.partial._toaster')

    <!-- toaster code for js -->
    <!-- showToast({
      eleWrapper: '#hexa-toaster',
      msg: 'This is a message',
      // error warning info success
      theme: 'success',
      closeButton: true,
      autoClose: true
    }); -->

    @yield('page-js')
    <!-- endinject-->
</body>

</html>
