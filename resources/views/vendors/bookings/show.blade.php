@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Customer:</span></label> {{ $item->customer ? $item->customer->name : '---' }}<br>
        </div>

        <div class="col-md-6">
            <label for=""><span class="show-text">Movie:</span></label> {{ $item->movie ? $item->movie->name : '---' }}<br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Quantity:</span></label> {{ $item->quantity ?: '---'}}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Discount:</span></label> {{ $item->discount ?: '---' }}<br>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span
                    class="show-text">Tax Amount:</span></label> {{ $item->tax_amount ?: '---' }}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Sub Total:</span></label> {{$item->tax_amount ?: '---'}}<br>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Total:</span></label> {{$item->total ?: '---'}}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Status:</span></label>
            @if($item->status == 'Available')
                <span class="badge badge-success">Available</span>
            @elseif($item->status == 'Reserve')
                <span class="badge badge-warning">Reserve</span>
            @elseif($item->status == 'Sold Out')
                <span class="badge badge-warning">Sold Out</span>
            @elseif($item->status == 'Unavailable')
                <span class="badge badge-secondary">Unavailable</span>
            @endif
            <br>
        </div>
    </div>
@endsection
