@extends('administrative.layouts.master')
@section('page-css')

@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2 ">
    <div class="my-4">
        <h4 class="mb-3 mb-md-0">Reset Password</h4>
    </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div  id="collection_form" class="mt-3">
            <form method="POST" action="{{ route('reset_password_update') }}">
                @csrf
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input
                        id="current_password"
                        type="password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        name="current_password"
                        required
                    >

                    @error('current_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input
                        id="new_password"
                        type="password"
                        class="form-control @error('new_password') is-invalid @enderror"
                        name="new_password"
                        required
                    >
                    @error('new_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm Password</label>
                    <input
                        id="new_password_confirmation"
                        type="password"
                        class="form-control"
                        name="new_password_confirmation"
                        required
                    >
                </div>
                <button type="submit" class="btn btn-xs btn-primary btn-icon-text mb-2 mb-md-0">Reset Password</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

