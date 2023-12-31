@extends('administrative.layouts.master')
@section('page-css')
    <style>
        .profile-card {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }

        .profile-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 2px solid #007BFF;
        }

        .profile-info h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .profile-info p {
            font-size: 16px;
            color: #777;
            margin-top: 5px;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-4">
        <div>
            <h4 class="mb-3 mb-md-0">User Profile</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">

        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-center align-items-center mt-4">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title"> </h6>
                        <div class="profile-card">
                            <div class="profile-image">
                                <img src="{{asset('assets/img/ug1.png')}}" alt="User Profile Picture">
                            </div>
                            <div class="profile-info">
                                <h1>{{$data->name}}</h1>
                                <p>Employee ID: {{$data->employee_id}}</p>
                                <p>Unit: @if(isset($unit)) {{$unit->name}} @endif</p>
                                <p>Mobile No: {{$data->phone}}</p>
                                <a href="javascript:;" onclick="document.getElementById('logoutForm').submit();" class="nav-link">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    <span>Log Out</span>
                                </a>
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
