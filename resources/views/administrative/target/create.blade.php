@extends('administrative.layouts.master')
@section('page-css')

@endsection
@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0"> Sub Unit {{isset($data) ? 'Edit' : 'Create'}}</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="{{route('administrative.sub.unit')}}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="server"></i>
            Sub Unit List
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-8 grid-margin stretch-card offset-md-2">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Sub Unit {{isset($data) ? 'Edit' : 'Create'}}</h6>
                <form class="forms-sample" action="{{route('administrative.sub.unit.store')}} " method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{isset($data) ? $data->id : ''}}">
                    <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                        <label for="name">Name</label>
                        <input required type="text" class="form-control form-control-danger" id="name" name="name" autocomplete="off" placeholder="Name" value="{{ old('name', isset($data) ? $data->name : '') }}" aria-invalid="true">
                        @if($errors->has('name'))
                        <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a area name</label>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('zone_id') ? 'has-error' : '' }}">
                        <label for="zone_id">Select Zone</label>
                        <select name="zone_id" id="zone_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{  isset($data) && $data->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('zone_id'))
                        <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Zone</label>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('area_id') ? 'has-error' : '' }}">
                        <label for="zone_id">Select Area</label>
                        <select name="area_id" id="area_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{  isset($data) && $data->area_id == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('area_id'))
                        <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Area</label>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('unit_id') ? 'has-error' : '' }}">
                        <label for="unit_id">Select Unit</label>
                        <select name="unit_id" id="unit_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{  isset($data) && $data->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('unit_id'))
                        <label id="name-error" class="error mt-2 text-danger" for="name">Please enter a Unit</label>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="{{ route('administrative.unit') }}" class="btn btn-light">Cancel</a>
                </form>
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
            var $ae = $("#area_id");
            $ae.empty();
            $ae.append($("<option></option>")
                .attr("value", '').text('Select'));
            var $ut = $("#unit_id");
            $ut.empty();
            $ut.append($("<option></option>")
                .attr("value", '').text('Select'));
            return;
        }
        var url = "{{route('administrative.unit.get.area')}}"
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

    $("#area_id").change(function() {
        var areaId = $(this).val();
        if (areaId == '') {
            var $el = $("#unit_id");
            $el.empty();
            $el.append($("<option></option>")
                .attr("value", '').text('Select'));
            return;
        }
        var url = "{{route('administrative.sub.unit.get.unit')}}"
        $.get(url, {
            id: areaId,
        }, function(res) {
            var $el = $("#unit_id");

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