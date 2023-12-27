@extends('administrative.layouts.master')
@section('page-css')
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
                <a href="{{ route('administrative.unit') }}">Unit</a>
                <span class="slash">/</span>
            </li>
            <li class="dm-breadcrumb__item">
                <span>{{ isset($data) ? 'Edit' : 'Create' }}</span>
            </li>
        </ul>
    </div>
    <div class="message-wrapper">

    </div>
    <div class="mt-4"></div>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-2 mb-2 ">
        <div>
            <h4 class="mb-3 mb-md-0">Unit {{ isset($data) ? 'Edit' : 'Create' }}</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ route('administrative.unit') }}" class="btn btn-primary btn-xs btn-icon-text mb-2 mb-md-0">
                <i class="uil uil-align-alt" data-feather="server"></i>
                Unit
            </a>
        </div>
    </div>
    <div class="mt-4"></div>
    <div class="row d-flex justify-content-center">
        <div class="col-md-8 grid-margin stretch-card">

            <div class="card card-Vertical card-default card-md mb-4">
                <div class="card-header">
                    <h6>Unit {{ isset($data) ? 'Edit' : 'Create' }}</h6>
                </div>
                <div class="card-body pb-md-30">
                    <div class="Vertical-form">
                        <form class="forms-sample" action="{{ route('administrative.unit.store') }} " method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ isset($data) ? $data->id : '' }}">
                            <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                <label for="name">Name</label>
                                <input required type="text" class="form-control form-control-danger" id="name"
                                    name="name" autocomplete="off" placeholder="Name"
                                    value="{{ old('name', isset($data) ? $data->name : '') }}" aria-invalid="true">
                                @if ($errors->has('name'))
                                    <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a area
                                        name</label>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('zone_id') ? 'has-error' : '' }}">
                                <label for="zone_id">Select Zone</label>
                                <select name="zone_id" id="zone_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach ($zones as $zone)
                                        <option value="{{ $zone->id }}"
                                            {{ isset($data) && $data->zone_id == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('zone_id'))
                                    <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a
                                        Zone</label>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('area_id') ? 'has-error' : '' }}">
                                <label for="zone_id">Select Area</label>
                                <select name="area_id" id="area_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ isset($data) && $data->area_id == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('area_id'))
                                    <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a
                                        Area</label>
                                @endif
                            </div>

                            <div class="layout-button mt-25">
                                <button type="submit" class="btn btn-primary px-20">
                                    save
                                </button>
                                <a href="{{ route('administrative.unit') }}">
                                    <button type="button" class="btn btn-default btn-squared btn-light px-20 ">
                                        cancel
                                    </button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
    <script>
        $("#zone_id").change(function() {
            var zoneId = $(this).val();
            if (zoneId == '') {
                var $el = $("#area_id");
                $el.empty();
                $el.append($("<option></option>")
                    .attr("value", '').text('Select'));
                return;
            }
            var url = "{{ route('administrative.unit.get.area') }}"
            $.get(url, {
                id: zoneId,
            }, function(res) {
                var $el = $("#area_id");

                $el.empty(); // remove old options

                $el.append($("<option></option>")
                    .attr("value", '').text('Select'));
                $.each(res.data, function(key, value) {
                    $el.append($("<option></option>")
                        .attr("value", value).text(key));
                });
            });
        });
    </script>
@endsection
