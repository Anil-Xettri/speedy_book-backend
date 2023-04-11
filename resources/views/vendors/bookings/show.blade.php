@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row my-2">
        <div class="col-md-6 my-2">
            <label for=""><span
                    class="show-text">Customer:</span></label> {{ $item->customer ? $item->customer->name : '---' }}<br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span
                    class="show-text">Theater:</span></label> {{ $item->theater ? $item->theater->name : '---' }}
            <br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Movie:</span></label> {{ $item->movie ? $item->movie->title : '---' }}
            <br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Show Time:</span></label> {{ $item->show_time ?: '---' }}
            <br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Quantity:</span></label> {{ $item->quantity ?: '---'}}<br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Price:</span></label> {{ $item->price ?: '---'}}<br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Sub Total:</span></label> {{$item->sub_total ?: '---'}}<br>
        </div>
        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Total:</span></label> {{$item->total ?: '---'}}<br>
        </div>
        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Status:</span></label>
            @if($item->status == 'Unpaid')
                <span class="badge badge-danger">Unpaid</span>
            @elseif($item->status == 'Paid')
                <span class="badge badge-warning">Paid</span>
            @endif
            <br>
        </div>
    </div>

    <h5>Seat Details</h5>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table w-100">
                    <thead>
                    <tr>
                        <th>Ticket Number</th>
                        <th>Seat</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->seats ?? [] as $seat)
                        <tr>
                            <td>{{$seat->pivot ? $seat->pivot->ticket_number : 'N/A'}}</td>
                            <td>{{$seat->seat_name ?: 'N/A'}}</td>
                            <td>
                                @if($seat->pivot)
                                    @if($seat->pivot->status == "Reserve")
                                        <span class="badge badge-warning">Reserve</span>
                                    @elseif($seat->pivot->status == "Sold Out")
                                        <span class="badge badge-danger">Sold Out</span>
                                    @elseif($seat->pivot->status == "Unavailable")
                                        <span class="badge badge-secondary">Unavailable</span>
                                    @elseif($seat->pivot->status == "Available")
                                        <span class="badge badge-success">Available</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
