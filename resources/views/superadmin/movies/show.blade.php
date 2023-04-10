@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row">
        @if($item->image_url)
            <div class="col-md-6">
                <label>Image: </label><br>
                <img class="" style="width: 150px; height: 150px"
                     src="{{$item->image_url}}"
                     alt="Movie Image">
            </div>
        @endif

        @if($item->trailer_url)
            <div class="col-md-6">
                <label>Trailer: </label><br>
                <video src="{{$item->trailer_url}}" style="width: 250px; height: 150px" controls>
                </video>
            </div>
        @endif
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Title:</span></label> {{ $item->title }}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span
                    class="show-text">Cinema Hall:</span></label> {{ $item->vendor ? $item->vendor->name : '---'}}
            <br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span
                    class="show-text">Theater:</span></label> {{ $item->theater ? $item->theater->name : '---'}}
            <br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Duration:</span></label> {{ $item->duration.' Minutes' ?: '---'}}<br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Release Date:</span></label> {{ $item->release_date.' Minutes' ?: '---'}}<br>
        </div>
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
