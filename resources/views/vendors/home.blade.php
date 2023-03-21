@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('css')
    <style>
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-teal">
                <div class="inner">
                    <h3>{{$cinemaHalls}}</h3>
                    <p>Cinema Halls</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tv"></i>
                </div>
                <a href="{{route('cinema-halls.index')}}" class="small-box-footer">More Info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-lightblue">
                <div class="inner">
                    <h3>{{$movies}}</h3>
                    <p>Movies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-film"></i>
                </div>
                <a href="{{route('movies.index')}}" class="small-box-footer">More Info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-purple">
                <div class="inner">
                    <h3>{{$bookings}}</h3>
                    <p>Bookings</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <a href="{{route('bookings.index')}}" class="small-box-footer">More Info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Now Showing</div>
                </div>
                <div class="card-body hide-scroll" style="overflow-y: scroll; height: 390px">
                    @foreach($nowShowings as $nowShowing)
                        <a target="_blank" href="{{route('movies.show', $nowShowing['id'])}}">
                            <div class="row no-gutters m-1">
                                <div class="col-auto">
                                    <img src="{{$nowShowing['image'] ?: asset('images/placeholder-image.jpg')}}" alt=""
                                         style="object-fit: cover; border-radius: 3px" height="80px" width="80px">
                                </div>
                                <div class="col">
                                    <div class="card-block px-2">
                                        <p class="text-lg font-weight-bold my-0">{{$nowShowing['title']}}</p>
                                        <p class="text-muted text-success text-md font-weight-bold my-0"
                                           style="color: red!important;">{{$nowShowing['cinema_hall']}}</p>
                                        <p class="text-muted text-sm font-weight-bold my-0">{{$nowShowing['start_time']}}
                                            - {{$nowShowing['end_time']}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </a>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Coming Soon</div>
                </div>
                <div class="card-body hide-scroll" style="overflow-y: scroll; height: 390px">
                    @foreach($nowShowings as $nowShowing)
                        <a target="_blank" href="{{route('movies.show', $nowShowing['id'])}}">
                            <div class="row no-gutters m-1">
                                <div class="col-auto">
                                    <img src="{{$nowShowing['image'] ?: asset('images/placeholder-image.jpg')}}" alt=""
                                         style="object-fit: cover; border-radius: 3px" height="80px" width="80px">
                                </div>
                                <div class="col">
                                    <div class="card-block px-2">
                                        <p class="text-lg font-weight-bold my-0">{{$nowShowing['title']}}</p>
                                        <p class="text-muted text-success text-md font-weight-bold my-0"
                                           style="color: red!important;">{{$nowShowing['cinema_hall']}}</p>
                                        <p class="text-muted text-sm font-weight-bold my-0">{{$nowShowing['start_time']}}
                                            - {{$nowShowing['end_time']}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </a>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
@stop
