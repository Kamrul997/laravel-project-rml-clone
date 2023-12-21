@extends('administrative.layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/collection-details-page/collection-details.css') }}">
<style>
    .titles {
        color: #4944B9;
        margin-bottom: 1.56rem;

    }

    .detailed-card {
        background-color: #5D5FEF;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 1.2rem;
    }

    .collection-entry-btn {
        color: #4944B9;
        background: white;
        position: relative;
        top: 2px;
        border-radius: 5px !important;
        padding: 4px, 10px, 4px, 10px !important;
    }

    .collection-details-table {
        overflow: hidden;
        border-radius: 10px;
        background-color: #F7F7FC;
        text-align: center;
    }

    .collection-details-table td {
        border: 1px solid white !important;
        color: #526787;
        font-weight: 600;
    }

    .collec-d-table-header {
        background-color: #DEDEFF !important;
        color: #526787 !important;
        font-weight: 700 !important;
    }

    .attachment-files {
        word-break: break-all;
    }

    .show {
        display: flex !important;

    }

    @media(max-width: 440px) {

        .collection-details-table th,
        td {
            font-size: 11px !important;
            padding: 0.5rem 0.2rem !important;
        }
    }

    @media(max-width: 321px) {

        .collection-details-table th,
        td {
            font-size: 9px !important;
            padding: 0.5rem 0.2rem !important;
        }
    }

    .select2-container {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="">Collection</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">

    </div>
</div>
<div class="row">
    <div class="col-md-8 grid-margin stretch-card offset-md-2">
        <div class="card">
            <div class="card-body">
                <h3 class="titles text-center">Collection Details </h3>





                <div class="modal" id="imageModal" tabindex="-1" role="dialog" style="justify-content: center !important;
            align-items: center !important;
            padding: 0px !important;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Attachment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <img src="" alt="Zoomed Image" id="zoomedImage" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                <!----------------- view detailed page table section --------------------------------->
                <div class="d-flex justify-content-center">
                    <table class="table table-bordered collection-details-table">
                        <tbody>
                            <tr>
                                <td class="collec-d-table-header" width="50%">Collection Amount:</td>
                                
                                <td>{{ is_numeric($collection->collection_amount) ?  number_format($collection->collection_amount) : 0 }}</td>
                            </tr>

                            <tr>
                                <td class="collec-d-table-header">Vts Charge:</td>
                                <td>{{ is_numeric($collection->collection_amount) ? number_format($collection->vts_charge) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Ownership charge:</td>
                                <td>{{ is_numeric($collection->collection_amount) ? number_format($collection->ownership_charge) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Name transfer Fees:</td>
                                <td>{{ is_numeric($collection->collection_amount) ? number_format($collection->name_transfer_fees) :0  }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Penalty Charge:</td>
                                <td>{{ is_numeric($collection->collection_amount) ? number_format($collection->rotl) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Brta charge:</td>
                                <td>{{ is_numeric($collection->collection_amount) ? number_format($collection->brta_charge) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Others:</td>
                                <td>{{ is_numeric($collection->collection_amount) ? number_format($collection->others) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Total Collection Amount:</td>
                                <td>{{ number_format($collection->collection_amount + $collection->vts_charge + $collection->ownership_charge + $collection->name_transfer_fees + $collection->rotl + $collection->brta_charge + $collection->others) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Deposit type:</td>
                                <td>
                                    @if (isset($deposit))
                                    {{ $deposit->deposit_type }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Bank name:</td>
                                <td>
                                    @if (isset($bank))
                                    {{ $bank->bank_name }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Branch name:</td>
                                <td>
                                    {{$collection->branch_name}}

                                    <!-- @if (isset($branch))
                                            {{ $branch->branch_name }}
                                        @endif -->
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">FT Code/ Ch No/ OL Slip No:</td>
                                <td>{{ $collection->ft_code }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Deposit date:</td>
                                <td>{{ date('Y-m-d h:i:s a', strtotime($collection->deposit_date)) }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Created by:</td>
                                <td>{{ $createdBy }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Created at:</td>
                                <td>{{ date('Y-m-d h:i:s a', strtotime($collection->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Status:

                                </td>
                                <td>
                                    @if ($collection->status == 0)
                                    Pending
                                    @elseif ($collection->status == 1 && $collection->hod_approved_status == 1)
                                    Waiting for accounts approval
                                    @elseif ($collection->status == 1 && $collection->hod_approved_status == 2)
                                    Approved
                                    @elseif ($collection->status == 1 && $collection->hod_approved_status == 0)
                                    Pending for final Approval
                                    @else
                                    Disapproved
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Approved by:</td>
                                <td>{{ $approvedBy }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Approved_date:</td>
                                <td>
                                    @if (isset($collection->approved_date))
                                    {{ date('Y-m-d H:i:s a', strtotime($collection->approved_date)) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Reject by:</td>
                                <td>{{ $rejectBy }}</td>
                            </tr>
                            <tr>
                                <td class="collec-d-table-header">Reject date:</td>
                                <td>
                                    @if (isset($collection->reject_date))
                                    {{ date('Y-m-d h:i:s a', strtotime($collection->reject_date)) }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="attachment-wrapper mt-3" style="background-color: #F7F7FC; padding:10px; border-radius:10px;">
                    <h5 class="text-center mb-3 mt-2 text-decoration-underline">Attachment</h5>
                    <div class="d-flex justify-content-center align-items-end ">
                        @foreach ($attachment as $attachment)
                        @php
                        $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'PNG']))
                        {{-- Check if it's an image --}}
                        <img class="mx-2 clickable-image cursor-pointer" width="100px" src="{{ asset('collection_attachment/' . $attachment->attachment) }}" alt="Image">
                        @else
                        <a class="attachment-files" href="{{ asset('collection_attachment/' . $attachment->attachment) }}" download="{{ $attachment->attachment }}">
                            {{ $attachment->attachment }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>


                <div style="text-align: center;padding:20px 0;">
                    @if (Auth::user()->hasRole('Unit'))
                    @if ($collection->status==0)
                    <a href="{{route('collection.edit',$collection->id)}}"><button class="btn btn-warning btn-sm">Edit</button></a>
                    <a href="{{route('approved_collection',$collection->id)}}"><button class="btn btn-success btn-sm">Approved</button></a>
                    <!-- <a href="{{route('disapproved_collection',$collection->id)}}"><button class="btn btn-danger btn-sm disapprove" data-id="{{$collection->id}}" data-toggle="modal" data-target="#details-modal">Disapproved</button></a> -->
                    <a href="#"><button class="btn btn-danger btn-sm disapprove" data-id="{{$collection->id}}" data-toggle="modal" data-target="#details-modal">Disapproved</button></a>
                    @endif
                    @endif

                    @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('National'))
                    @if ($collection->hod_approved_status == 0)
                    <a href="{{route('collection.edit',$collection->id)}}"><button class="btn btn-warning btn-sm">Edit</button></a>
                    <a href="{{route('approved_collection',$collection->id)}}"><button class="btn btn-success btn-sm">Approved</button></a>
                    <a href="#"><button class="btn btn-danger btn-sm disapprove" data-id="{{$collection->id}}" data-toggle="modal" data-target="#details-modal">Disapproved</button></a>
                    @endif
                    @endif

                    @if (Auth::user()->hasRole('Accounts'))
                    @if ($collection->status == 1 && $collection->hod_approved_status == 1)
                    <a href="{{route('approved_collection',$collection->id)}}"><button class="btn btn-success btn-sm">Approved</button></a>
                    <a href="#"><button class="btn btn-danger btn-sm disapprove" data-id="{{$collection->id}}" data-toggle="modal" data-target="#details-modal">Disapproved</button></a>
                    @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Disapproved Modal -->
<div class="modal fade" id="details-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h6 class="modal-title" id="staticBackdropLabel">Disapproved Reason</h6>
                <i class="fas fa-close btnExit cursor-pointer" data-bs-dismiss="modal"></i>
            </div>
            <div class="modal-body" id="extend-assign-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="user">Select Reason</label>
                            <input type="hidden" name="collection_id" id="collection_id" value="{{$collection->id}}">
                            <select name="disapproved_note_id" id="disapproved_note_id" class="form-control js-example-basic-single w-100">
                                <option value="">Select</option>
                                @foreach($disapprovedNotes as $note)
                                <option value="{{ $note->id }}">{{ $note->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary mr-2 disapprove-submit">Submit</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('page-js')
<script>
    // Get references to the elements
    const imageContainer = document.querySelector('.attachment-wrapper');

    // Create the modal
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    document.body.appendChild(modal);

    // Create an image element inside the modal
    const modalImage = document.createElement('img');
    modalImage.id = 'zoomed-img';
    modal.appendChild(modalImage);

    // Add a click event listener to the image container
    imageContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('zoomable-image')) {
            // Set the source of the modal image
            modalImage.src = e.target.src;
            // Show the modal
            modal.style.display = 'block';
        }
    });

    // Close the modal when clicking outside the image
    modal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    $(document).ready(function() {
        // Handle image click
        $('.clickable-image').on('click', function() {
            // Get the source of the clicked image
            var imgSrc = $(this).attr('src');

            // Set the source of the modal image
            $('#zoomedImage').attr('src', imgSrc);

            // Show the modal
            $('#imageModal').modal('show');
        });
    });


    $(document).on("click", ".disapprove-submit", function(e) {
        if ($('#disapproved_note_id').val() == '') {
            Swal.fire(
                'Warning!',
                'Please select a reason',
                'question'
            )
            return;
        }

        e.preventDefault();
        $.get("/administrative/disapproved-collection", {
            collection_id: $('#collection_id').val(),
            disapproved_note_id: $('#disapproved_note_id').val(),
        }, function(data) {
            $('#datatables').DataTable().ajax.reload(null, false);
            $('#details-modal').modal('hide');
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Collection has been disapproved successfully',
                showConfirmButton: false,
                timer: 1600
            })
            setTimeout(function() {
                window.location.href = "{{ route('pending_collection') }}";
            }, 2100);
        });
    });
    $(document).on('click', '.btnExit', function() {
        $('#details-modal').modal('hide');
    });
</script>
@endsection