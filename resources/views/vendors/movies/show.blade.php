@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row">
        @if($item->image)
            <div class="col-md-6">
                <label>Image: </label><br>
                <img class="" style="width: 150px; height: 150px"
                     src="{{$item->image_url}}"
                     alt="Movie Image">
            </div>
        @endif
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Title:</span></label> {{ $item->title }}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span
                    class="show-text">Cinema Hall:</span></label> {{ $item->cinemaHall ? $item->cinemaHall->name : '---'}}
            <br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Duration:</span></label> {{ $item->duration ?: '---'}}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Ticket Price:</span></label> {{ $item->ticket_price ?: '---' }}<br>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span
                    class="show-text">Show Date:</span></label> {{ $item->show_date ?: '---' }}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Show Time:</span></label> {{$item->show_time ?: '---'}}<br>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-md-6">
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
            <label for=""><span class="show-text">Description:</span></label>
            <hr>
            {{ $item->description}}
        </div>
    </div>
@endsection
