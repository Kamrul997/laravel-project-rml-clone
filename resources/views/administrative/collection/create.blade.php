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
    <div class="col-md-8 grid-margin stretch-card offset-md-2">
        <div class="card">
            <div class="card-body">


                <div id="collection_form" class="mt-3">
                    <form class="forms-sample" action="{{ route('collection.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="target_sheet_id" value="{{ $data->id }}">
                        <input type="hidden" name="unit" value="{{ $data->unit }}">

                        <div class="form-group">
                            <label>Collection Amount <span style="color: red">*</span></label>
                            <input type="number" name="collection_amount" class="form-control" required value="{{ old('collection_amount') }}">
                            @error('collection_amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Vts Charge</label>
                            <input type="number" name="vts_charge" class="form-control" value="{{ old('vts_charge') }}">
                            @error('vts_charge')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Ownership Charge</label>
                            <input type="number" name="ownership_charge" class="form-control" value="{{ old('ownership_charge') }}">
                            @error('ownership_charge')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Name transfer Fees</label>
                            <input type="number" name="name_transfer_fees" class="form-control" value="{{ old('name_transfer_fees') }}">
                            @error('name_transfer_fees')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Penalty Charge</label>
                            <input type="number" name="rotl" class="form-control" value="{{ old('rotl') }}">
                            @error('rotl')
                            <span class="text-danger">This field is required</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>BRTA Charge</label>
                            <input type="number" name="brta_charge" class="form-control" value="{{ old('brta_charge') }}">
                            @error('brta_charge')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Others</label>
                            <input type="number" name="others" class="form-control" value="{{ old('others') }}">
                            @error('others')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Deposit Type <span style="color: red">*</span></label>
                            <select class="form-contro js-example-basic-single w-100" name="deposit_type" required>
                                <option value="">Select Deposit Type</option>
                                @foreach($depositType as $dt)
                                <option value="{{ $dt->id }}" {{ old('deposit_type') == $dt->id ? 'selected' : '' }}>{{ $dt->deposit_type }}</option>
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
                                @foreach($bank as $banks)
                                <option value="{{ $banks->id }}">{{ $banks->bank_name }}</option>
                                @endforeach
                            </select>
                            @error('bank_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <!-- <label>Branch Name <span style="color: red">*</span></label> -->
                            <label>Branch Name</label>
                            <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name') }}">
                            @error('branch_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>FT Code/ Ch No/ OL Slip No</label>
                            <input type="text" name="ft_code" class="form-control" value="{{ old('ft_code') }}">
                            @error('ft_code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Deposit Date <span style="color: red">*</span></label>
                            <input type="datetime-local" name="deposit_date" class="form-control" required value="{{ old('deposit_date') }}">
                            @error('deposit_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Attach File <span style="color: red">*</span></label>
                            <input type="file" name="attachment[]" class="form-control" multiple required>
                            @error('attachment.*')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
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