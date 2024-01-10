@extends('administrative.layouts.master')
@section('page-css')
<style>
    .progress {
        margin: 10px;
        width: 700px;
    }

    @keyframes progressAnimation {
        0% {
            width: 0%;
        }

        100% {
            width: 100%;
        }
    }

    .progress-bar-infinite {
        animation: progressAnimation 9s infinite;
    }

    .progress-bar.active,
    .progress.active .progress-bar {
        -webkit-animation: progress-bar-stripes 2s linear infinite;
        -o-animation: progress-bar-stripes 2s linear infinite;
        animation: progress-bar-stripes 2s linear infinite;
        animation-duration: 1s;
        animation-timing-function: linear;
        animation-delay: 0s;
        animation-iteration-count: infinite;
        animation-direction: normal;
        animation-fill-mode: none;
        animation-play-state: running;
        animation-name: progress-bar-stripes;
        animation-timeline: auto;
        animation-range-start: normal;
        animation-range-end: normal;
    }

    @keyframes progress-bar-stripes {

        0% {
            background-position: 40px 0;
        }

        100% {
            background-position: 0 0;
        }
    }
</style>
@endsection
@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0"> Import Account Statement Bank</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-8 grid-margin stretch-card offset-md-2">
        <div class="card">
            <div class="card-body">
                @if ($errors->has('file'))
                <label id="file-error" class="error mt-2 text-danger" for="file">{{ $errors->first('file') }}</label>
                @endif
                @if(!empty($file) && $file->status == 'pending')
                <!-- <h3>Dynamic Progress Bar</h3> -->
                <p>{{$file->file_original_name}} upload {{$file->status}}</p>
                <div class="progress">
                    <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        <span id="current-progress">{{$file->status}}</span>
                    </div>
                </div>
                @elseif(!empty($file) && $file->status == 'processing')
                <!-- <h3>Dynamic Progress Bar</h3> -->
                <p>{{$file->file_original_name}} upload {{$file->status}}</p>
                <div class="progress">
                    <div id="dynamic" class="progress-bar-infinite progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span id="current-progress">{{$file->status}}</span>
                    </div>
                </div>
                @else
                @if(!empty($file) && $file->status == 'successful')
                <div class="alert alert-primary" role="alert">
                    {{$file->file_original_name}} successfully uploaded
                </div>
                @elseif(!empty($file) && $file->status == 'failed')
                <div class="alert alert-danger" role="alert">{{$file->file_original_name}} failed to upload</div>
                @endif
                <form class="forms-sample" action="{{route('store_account_statement')}} " method="POST" enctype="multipart/form-data">
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

                    <div class="d-flex">
                        <button type="submit" class="btn btn-xs btn-primary btn-icon-text me-2 mb-md-0">Import</button>

                        <a href="{{asset('/csv/account_statement_bank.csv')}}" class="btn btn-xs btn-primary btn-icon-text me-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                            Demo File Download
                        </a>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
<script>
    // $(function() {
    //     var current_progress = 0;
    //     var interval = setInterval(function() {
    //         current_progress += 10;
    //         $("#dynamic")
    //             .css("width", current_progress + "%")
    //             .attr("aria-valuenow", current_progress)
    //             .text(current_progress + "% Complete");
    //         if (current_progress >= 100)
    //             clearInterval(interval);
    //     }, 1000);
    // });
</script>
@endsection
