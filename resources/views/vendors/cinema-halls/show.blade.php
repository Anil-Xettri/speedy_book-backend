@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row">
        @if($item->image)
            <div class="col-md-6">
                <label>Image: </label><br>
                <img class="img-fluid" style="width: 150px; height: 150px"
                     src="{{$item->image_url}}"
                     alt="Hall Image">
            </div>
        @endif
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Name:</span></label> {{ $item->name }}<br>
        </div>
        @if($item->seat_calculation === "Number_of_Seats")
            <div class="col-md-6">
                <label for=""><span class="show-text">Total Seats:</span></label> {{ $item->total_seats ?: '---'}}<br>
            </div>
        @else
            <div class="col-md-6">
                <label for=""><span class="show-text">Total Rows:</span></label> {{ $item->rows ?: '---'}}<br>
            </div>

            <div class="col-md-6 my-2">
                <label for=""><span class="show-text">Total Column:</span></label> {{ $item->columns ?: '---'}}<br>
            </div>
        @endif

        <div class="col-md-6 my-3">
            <label for=""><span class="show-text">Status:</span></label>
            @if($item->status == 'Active')
                <span class="badge badge-success">Active</span>
            @elseif($item->status == 'Inactive')
                <span class="badge badge-secondary">Inactive</span>
            @endif
            <br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Seat Details:</span></label>
            <hr>
            {{ $item->seat_details}}
        </div>
    </div>
@endsection
