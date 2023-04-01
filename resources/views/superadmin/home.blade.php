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
                    <h3>{{$vendors}}</h3>
                    <p>Vendors</p>
                </div>
                <div class="icon">
                    <i class="fas fa-store"></i>
                </div>
                <a href="{{route('vendors.index')}}" class="small-box-footer">More Info <i
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
                <a href="{{route('movie.index')}}" class="small-box-footer">More Info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-purple">
                <div class="inner">
                    <h3>{{$customers}}</h3>
                    <p>Customers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="{{route('customers.index')}}" class="small-box-footer">More Info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@stop

@section('js')
@stop
