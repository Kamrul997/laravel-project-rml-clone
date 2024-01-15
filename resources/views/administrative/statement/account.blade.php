@extends('administrative.layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/css/forecast-page/forecast-page.css') }}">
    <style>
        .title {
            color: #8231D3;
        }

        .card-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;

        }

        #search-wrapper {
            display: flex;
            border: 1px solid rgba(0, 0, 0, 0.276);
            align-items: stretch;
            border-radius: 50px;
            background-color: #fff;
            overflow: hidden;
            max-width: 400px;
            box-shadow: 2px 1px 5px 1px rgba(0, 0, 0, 0.273);
            margin-top: 50px;

        }

        #search {
            border: none;
            width: 350px;
            font-size: 14px;
            font-weight: 600;
        }

        #search::placeholder {
            padding-top: 2px;
        }

        #search:focus {
            outline: none;
        }

        .search-icon {
            margin: 10px;
            color: rgba(0, 0, 0, 0.564);
        }

        #search-button {
            border: none;
            cursor: pointer;
            color: #fff;
            padding: 0px 10px;
        }

        .atachments {
            margin-top: 100px;
            background: #f7f7fe;
            padding: 80px;
            border-radius: 7px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
        }

        .pdf-text {
            color: #6E7191
        }

        .download-btn {
            margin-top: 30px;

            border: none !important;
            color: #fff;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')

    <div class="mt-2">
        <ul class="dm-breadcrumb nav">

            <li class="dm-breadcrumb__item">
                <a href="#">Statement</a>
                <span class="slash">/</span>
            </li>
            <li class="dm-breadcrumb__item">
                <span>Account Statement</span>
            </li>
        </ul>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>

        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body card-wrapper">
                    <h3 class="title">Account Statement</h3>
                    <div id="search-wrapper">

                        <i class="search-icon fas fa-search"></i>
                        <input type="text" id="search" placeholder="Enter Order No">
                        <button class="btn-primary" id="search-button">Download</button>
                    </div>
                    <div class="d-none" id="download-body">
                        <div class="atachments">
                            <svg width="85" height="92" viewBox="0 0 85 92" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="1.20421" y="0.325301" width="82.4819" height="91.3494" rx="9.6747"
                                    stroke="#C1C5D6" stroke-opacity="0.4" stroke-width="0.650602" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M27.2532 12.1938H49.5109L68.3462 31.8544V70.0347C68.3462 74.8163 64.4869 78.6999 59.7052 78.6999H27.2532C22.4958 78.6999 18.6123 74.8163 18.6123 70.0347V20.8348C18.6122 16.0774 22.4958 12.1938 27.2532 12.1938Z"
                                    fill="#E31019" />
                                <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M49.4873 12.1943V31.685H68.3469L49.4873 12.1943Z" fill="white" />
                                <path
                                    d="M31.9247 60.8636V50.3636H33.6946V51.6264H33.799C33.8918 51.4408 34.0227 51.2436 34.1918 51.0348C34.3608 50.8227 34.5895 50.642 34.8778 50.4929C35.1662 50.3404 35.5341 50.2642 35.9815 50.2642C36.5715 50.2642 37.1035 50.415 37.5774 50.7166C38.0547 51.0149 38.4325 51.4574 38.7109 52.044C38.9927 52.6274 39.1335 53.3433 39.1335 54.1918C39.1335 55.0303 38.996 55.7429 38.7209 56.3295C38.4458 56.9162 38.0713 57.3636 37.5973 57.6719C37.1233 57.9801 36.5864 58.1342 35.9865 58.1342C35.549 58.1342 35.1861 58.0613 34.8977 57.9155C34.6094 57.7696 34.3774 57.594 34.2017 57.3885C34.0294 57.1797 33.8951 56.9825 33.799 56.7969H33.7244V60.8636H31.9247ZM33.6896 54.1818C33.6896 54.6757 33.7592 55.1082 33.8984 55.4794C34.041 55.8506 34.2448 56.1406 34.5099 56.3494C34.7784 56.5549 35.1032 56.6577 35.4844 56.6577C35.8821 56.6577 36.2152 56.5516 36.4837 56.3395C36.7521 56.1241 36.9543 55.8307 37.0902 55.4595C37.2294 55.085 37.299 54.6591 37.299 54.1818C37.299 53.7079 37.2311 53.2869 37.0952 52.919C36.9593 52.5511 36.7571 52.2628 36.4886 52.054C36.2202 51.8452 35.8854 51.7408 35.4844 51.7408C35.0999 51.7408 34.7734 51.8419 34.505 52.044C34.2365 52.2462 34.0327 52.5296 33.8935 52.8942C33.7576 53.2588 33.6896 53.688 33.6896 54.1818ZM43.5085 58.1342C42.9086 58.1342 42.3717 57.9801 41.8977 57.6719C41.4238 57.3636 41.0492 56.9162 40.7741 56.3295C40.4991 55.7429 40.3615 55.0303 40.3615 54.1918C40.3615 53.3433 40.5007 52.6274 40.7791 52.044C41.0608 51.4574 41.4403 51.0149 41.9176 50.7166C42.3949 50.415 42.9268 50.2642 43.5135 50.2642C43.9609 50.2642 44.3288 50.3404 44.6172 50.4929C44.9055 50.642 45.1342 50.8227 45.3033 51.0348C45.4723 51.2436 45.6032 51.4408 45.696 51.6264H45.7706V47.8182H47.5753V58H45.8054V56.7969H45.696C45.6032 56.9825 45.469 57.1797 45.2933 57.3885C45.1177 57.594 44.8857 57.7696 44.5973 57.9155C44.3089 58.0613 43.946 58.1342 43.5085 58.1342ZM44.0107 56.6577C44.3918 56.6577 44.7166 56.5549 44.9851 56.3494C45.2536 56.1406 45.4574 55.8506 45.5966 55.4794C45.7358 55.1082 45.8054 54.6757 45.8054 54.1818C45.8054 53.688 45.7358 53.2588 45.5966 52.8942C45.4607 52.5296 45.2585 52.2462 44.9901 52.044C44.7249 51.8419 44.3984 51.7408 44.0107 51.7408C43.6096 51.7408 43.2749 51.8452 43.0064 52.054C42.7379 52.2628 42.5357 52.5511 42.3999 52.919C42.264 53.2869 42.196 53.7079 42.196 54.1818C42.196 54.6591 42.264 55.085 42.3999 55.4595C42.5391 55.8307 42.7429 56.1241 43.0114 56.3395C43.2831 56.5516 43.6162 56.6577 44.0107 56.6577ZM53.361 50.3636V51.7557H48.8468V50.3636H53.361ZM49.9753 58V49.6428C49.9753 49.129 50.0814 48.7015 50.2935 48.3601C50.5089 48.0187 50.7973 47.7635 51.1586 47.5945C51.5198 47.4254 51.9209 47.3409 52.3617 47.3409C52.6732 47.3409 52.95 47.3658 53.1919 47.4155C53.4339 47.4652 53.6129 47.5099 53.7289 47.5497L53.3709 48.9418C53.2947 48.9186 53.1986 48.8954 53.0826 48.8722C52.9666 48.8456 52.8373 48.8324 52.6948 48.8324C52.36 48.8324 52.123 48.9136 51.9838 49.076C51.848 49.2351 51.78 49.4638 51.78 49.7621V58H49.9753Z"
                                    fill="white" />
                            </svg>
                            <p class="pdf-text">Standard Text</p>
                        </div>
                        <div>
                            <a href="" id="btn-download"><button
                                    class="btn btn-primary download-btn">Download</button></a>
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
        $("#search").on("keyup", function() {
            $('#download-body').addClass('d-none');
        });
        $("#search-button").click(function() {
            var order_id = $('#search').val();
            if (order_id == '') {
                Swal.fire({
                    title: "Order no?",
                    text: "Please provide a order no.",
                    icon: "question"
                });
                return;
            }

            let url = '{{ route('administrative.download.statement.account', ':id') }}';
            url = url.replace(':id', order_id);
            window.location.href = url;
        });
    </script>
@endsection
