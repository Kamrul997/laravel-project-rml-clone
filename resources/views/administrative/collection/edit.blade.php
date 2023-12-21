@extends('administrative.layouts.master')
@section('page-css')
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Collection</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">


                    <div id="collection_form" class="mt-3">
                        <form class="forms-sample" action="{{ route('collection.update', $data['collection']->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PATCH') }}
                            <div class="form-group">
                                <label>Collection Amount <span style="color: red">*</span></label>
                                <input type="text" name="collection_amount" class="form-control" required
                                    value="{{ $data['collection']->collection_amount }}">
                                @error('collection_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <label>Advance Collection</label>
                                <input type="text" name="advanced_collection" class="form-control" required value="{{ $data['collection']->advanced_collection }}">
                                @error('advanced_collection')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="form-group">
                                <label>Vts Charge</label>
                                <input type="text" name="vts_charge" class="form-control"
                                    value=" {{ $data['collection']->vts_charge }}">
                                @error('vts_charge')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ownership Charge</label>
                                <input type="text" name="ownership_charge" class="form-control"
                                    value="{{ $data['collection']->ownership_charge }}">
                                @error('ownership_charge')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Name transfer Fees</label>
                                <input type="text" name="name_transfer_fees" class="form-control"
                                    value="{{ $data['collection']->name_transfer_fees }}">
                                @error('name_transfer_fees')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Penalty Charge</label>
                                <input type="text" name="rotl" class="form-control"
                                    value="{{ $data['collection']->rotl }}">
                                @error('rotl')
                                  <span class="text-danger">This field is required</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>BRTA Charge</label>
                                <input type="text" name="brta_charge" class="form-control"
                                    value="{{ $data['collection']->brta_charge }}">
                                @error('brta_charge')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Others</label>
                                <input type="text" name="others" class="form-control"
                                    value="{{ $data['collection']->others }}">
                                @error('others')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Deposit Type</label>
                                <select class="form-control js-example-basic-single w-100" name="deposit_type" required>
                                    <option value="">Select Deposit Type</option>
                                    @foreach ($data['depositType'] as $dt)
                                        <option value="{{ $dt->id }}"
                                            {{ $data['collection']->deposit_type == $dt->id ? 'selected' : '' }}>
                                            {{ $dt->deposit_type }}</option>
                                    @endforeach
                                </select>
                                @error('deposit_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Bank Name <span style="color: red">*</span></label>
                                <select id="bank_id" class="form-control js-example-basic-single w-100" name="bank_name" required>
                                    <option value="">Select Bank</option>
                                    @foreach ($data['bank'] as $banks)
                                        <option value="{{ $banks->id }}"
                                            {{ $data['collection']->bank_name == $banks->id ? 'selected' : '' }}>
                                            {{ $banks->bank_name }}</option>
                                    @endforeach
                                </select>
                                @error('bank_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- <div class="form-group">
                                <label>Branch Name <span style="color: red">*</span></label>
                                <select id="branch_id" class="form-control" name="branch_name" required>
                                    @if(isset($data['branch']))
                                      <option value="{{$data['collection']->branch_name}}">{{$data['branch']->branch_name}}</option>
                                    @endif
                                </select>
                                @error('branch_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->

                            <div class="form-group">
                                <label>FT Code/ Ch No/ OL Slip No</label>
                                <input type="text" name="ft_code" class="form-control"
                                    value="{{ $data['collection']->collection_amount }}{{ old('ft_code') }}">
                                @error('ft_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            @if(Auth::user()->hasRole('Unit'))

                            <div class="form-group">
                                <label>Deposit Date <span style="color: red">*</span></label>
                                <input readonly type="datetime" name="deposit_date" class="form-control" required
                                    value="{{ date('Y-m-d h:i:s',strtotime($data['collection']->deposit_date)) }}">
                                @error('deposit_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Approved Date <span style="color: red">*</span></label>
                                <input readonly type="datetime" name="approved_date" class="form-control"
                                    value="@if(isset($data['collection']->approved_date)) {{$data['collection']->approved_date}} @endif">
                                @error('approved_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @else
                            <div class="form-group">
                                <label>Deposit Date <span style="color: red">*</span></label>
                                <input  type="datetime-local" name="deposit_date" class="form-control" required
                                    value="{{ $data['collection']->deposit_date }}">
                                @error('deposit_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Approved Date <span style="color: red">*</span></label>
                                <input type="datetime-local" name="approved_date" class="form-control" required
                                    value="@if(isset($data['collection']->approved_date)) {{$data['collection']->approved_date}} @endif">
                                @error('approved_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            <div class="form-group">
                                <label>Attach File</label>
                                <input type="file" name="attachment[]" class="form-control" multiple>
                                @error('attachment.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="attachment-wrapper mt-3" style="background-color: #F7F7FC; padding:10px; border-radius:10px;">
                                <h5 class="text-center mb-3 mt-2 text-decoration-underline">Attachment</h5>
                                <div class="d-flex justify-content-center align-items-end ">
                                    @foreach ($data['attachment'] as $attachment)
                                    @php
                                    $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                    @endphp

                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    {{-- Check if it's an image --}}
                                    <img class="mx-2" class="open-img" data-img="{{ asset('collection_attachment/' . $attachment->attachment) }}" width="100px" data-bs-toggle="modal" data-bs-target="#image-modal" src="{{ asset('collection_attachment/' . $attachment->attachment) }}" alt="Image">
                                    @else
                                    <a class="attachment-files" href="{{ asset('collection_attachment/' . $attachment->attachment) }}" download="{{ $attachment->attachment }}">
                                        {{ $attachment->attachment }}
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
    <!-- <script>
        $("#bank_id").change(function() {
            var bank_id = $(this).val();
            if (bank_id == '') {
                var $el = $("#branch_id");
                $el.empty();
                $el.append($("<option></option>")
                    .attr("value", '').text('Select Branch'));
                return;
            }

            var url = "{{ route('get.branch') }}";
            $.get(url, {
                    id: bank_id
                }, function(res) {
                    var $el = $("#branch_id");
                    $el.empty();
                    $el.append($("<option></option>")
                        .attr("value", '').text('Select Branch'));

                    // Loop through the response array and populate the dropdown
                    $.each(res, function(index, branch) {
                        // Assuming branch.id and branch.branch_name are the correct keys
                        $el.append($("<option></option>")
                            .attr("value", branch.id).text(branch.branch_name));
                    });
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX request failed:", textStatus, errorThrown);
                });
        });
    </script> -->
@endsection
