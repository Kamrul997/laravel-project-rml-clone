@extends('administrative.layouts.master')
@section('page-css')

@endsection
@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0"> Import Target Sheet</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-8 grid-margin stretch-card offset-md-2">
        <div class="card">
            <div class="card-body">
                @if ($errors->has('file'))
                <label id="file-error" class="error mt-2 text-danger" for="file">{{ $errors->first('file') }}</label>
                @endif
                <form class="forms-sample" action="{{route('store_target')}} " method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                        <label for="name">Select File</label>
                        <input required style="height: 35px;" type="file" id="file" name="file" class="file-upload-default">
                        <div style="height: 35px;" class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Name">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Import</button>
                    <a href="{{asset('/csv/target_sheet_import_example.csv')}}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                        Demo File Download
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')

@endsection