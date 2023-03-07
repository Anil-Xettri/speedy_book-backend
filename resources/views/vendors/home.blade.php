@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('css')
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
@stop

@section('js')
@stop
