@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span
                    class="show-text">Theater:</span></label> {{ $item->theater ? $item->theater->name : '---'}}
            <br>
        </div>

        <div class="col-md-6">
            <label for=""><span class="show-text">Movie:</span></label> {{ $item->movie ? $item->movie->title : '---' }}
            <br>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-12">
            <label>Show Details:</label>
            <div class="table-responsive">
                <table class="table table-active w-100">
                    <thead>
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Show Date</th>
                        <th scope="col">Show Time</th>
                        <th scope="col">Ticket Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(json_decode($item->show_details,true) ?? [] as $i => $showDetails)
                        <tr>
                            <td>{{$i + 1}}</td>
                            <td>{{$showDetails['show_date']}}</td>
                            <td>{{$showDetails['show_time']}}</td>
                            <td>{{$showDetails['ticket_price']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-4">
            <label for=""><span class="show-text">Description:</span></label>
            <hr>
            {{ $item->description}}
        </div>
    </div>
@endsection
