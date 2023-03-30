@extends('adminlte::page')

@section('title', 'Profile')

@section('css')
    <style>
        body {
            background-color: #DCFFFB;
        }

        .padding {
            padding: 3rem !important;
        }

        .box {
            margin-right: 2rem !important;
        }

        .card-img-top {
            /*width: 800px;*/
            height: 300px;
            object-fit: cover;
        }

        .card-no-border .card {
            border-color: #d7dfe3;
            border-radius: 4px;
            margin-bottom: 30px;
            -webkit-box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05);
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05)
        }

        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem
        }

        .pro-img {
            margin-top: -80px;
            margin-bottom: 20px
        }

        .little-profile .pro-img img {
            width: 128px;
            height: 128px;
            -webkit-box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 100%
        }

        html body .m-b-0 {
            margin-bottom: 0px
        }

        h3 {
            line-height: 30px;
            font-size: 21px
        }

        .btn-rounded.btn-md {
            padding: 12px 35px;
            font-size: 16px
        }

        html body .m-t-10 {
            margin-top: 10px
        }

        .btn-primary,
        .btn-primary.disabled {
            background: #7460ee;
            border: 1px solid #7460ee;
            -webkit-box-shadow: 0 2px 2px 0 rgba(116, 96, 238, 0.14), 0 3px 1px -2px rgba(116, 96, 238, 0.2), 0 1px 5px 0 rgba(116, 96, 238, 0.12);
            box-shadow: 0 2px 2px 0 rgba(116, 96, 238, 0.14), 0 3px 1px -2px rgba(116, 96, 238, 0.2), 0 1px 5px 0 rgba(116, 96, 238, 0.12);
            -webkit-transition: 0.2s ease-in;
            -o-transition: 0.2s ease-in;
            transition: 0.2s ease-in
        }

        .btn-rounded {
            border-radius: 60px;
            padding: 7px 18px
        }

        .m-t-20 {
            margin-top: 20px
        }

        .text-center {
            text-align: center !important
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #455a64;
            font-family: "Poppins", sans-serif;
            font-weight: 400
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="padding">
                <div class="text-center">
                    <!-- Column -->
                    <div class="card box" style="width: 60%; margin-left: 20%"><a
                            href="{{$item->getImage('cover-image') ?: asset('img/placeholder.png')}}"><img
                                class="card-img-top"
                                src="{{$item->getImage('cover-image') ?: asset('img/placeholder.png')}}"
                                alt="Cover"></a>
                        <div class="card-body little-profile">
                            <div class="text-center">
                                <div class="pro-img"><a
                                        href="{{$item->getImage('users-avatar') ?: asset('img/user-placeholder.jpg')}}"><img
                                            src="{{$item->getImage('users-avatar') ?: asset('img/user-placeholder.jpg')}}"
                                            alt="user"></a></div>
                                <h3 class="m-b-0">{{$item->name}}</h3>
                                <p>{{$item->email}}</p>
                                <a href="{{route('profile.edit', auth()->user()->id)}}"
                                   class="m-t-10 waves-effect waves-dark btn btn-primary btn-md btn-rounded"
                                   data-abc="true">Edit Profile</a>

                                <div class="row text-center m-t-20">
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">
                                            @if($item->gender == "male")
                                                Male
                                            @elseif($item->gender == "female")
                                                Female
                                            @else
                                                Other
                                            @endif

                                        </h3><small>Gender</small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">{{$item->address ?: 'N/A'}}</h3><small>Address</small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">{{$item->phone ?: 'N/A'}}</h3><small>Phone</small>
                                    </div>
                                </div>

                                <div class="row text-center m-t-20">
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">{{$item->program ? $item->program->name : 'N/A'}}</h3><small>Program</small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">{{$item->semester ?: 'N/A'}}</h3>
                                        <small>Semester</small>
                                    </div>
                                    <div class="col-lg-4 col-md-4 m-t-20">
                                        <h3 class="m-b-0 font-light">{{$sharedNotesCount}}</h3><small>Total Notes
                                            Shared</small>
                                    </div>
                                </div>
                                <hr>
                            </div>
{{--                            <div class="card-title d-flex justify-content-start" style="margin-left: 60px!important;"><h3><b>Interactions</b></h3></div>--}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header bg-success">--}}
{{--                                        <h3 class="card-title text-white">Total Notes Shared According to Types</h3>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.card-header -->--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="chart-container" style="position: relative; height:350px; width:350px;margin-left: 260px!important;">--}}
{{--                                            <canvas id="noteTypeChart"></canvas>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-12 mt-3">--}}
{{--                                <div class="card card-danger">--}}
{{--                                    <div class="card-header bg-warning">--}}
{{--                                        <h3 class="card-title text-white">Graph of Sharing notes each month of current year.</h3>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.card-header -->--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="chart-container" style="position: relative; height:450px; width:800px;">--}}
{{--                                            <canvas id="monthWiseNoteShared"></canvas>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
