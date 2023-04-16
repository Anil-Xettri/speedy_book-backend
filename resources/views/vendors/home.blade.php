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
                    <h3>{{$theaters}}</h3>
                    <p>Theaters</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tv"></i>
                </div>
                <a href="{{route('theaters.index')}}" class="small-box-footer">More Info <i
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
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-yellow">
                <div class="inner">
                    <h3>{{$collection}}</h3>
                    <p>Total Collection</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-alt"></i>
                </div>
                <a href="{{route('payments.index')}}" class="small-box-footer">More Info <i
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
                    @forelse($nowShowings as $nowShowing)
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
                                           style="color: rgba(255,9,0,0.78)!important;">{{$nowShowing['theater']}}</p>
                                        <p class="text-muted text-sm font-weight-bold my-0">{{$nowShowing['start_time']}}
                                            - {{$nowShowing['end_time']}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </a>
                    @empty
                        <div class="alert alert-danger text-center">
                            No movies are now showing
                        </div>
                    @endforelse

                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Next Showing</div>
                </div>
                <div class="card-body hide-scroll" style="overflow-y: scroll; height: 390px">
                    @forelse($nextShowings ?? [] as $nextShowing)
                        <a target="_blank" href="{{route('movies.show', $nextShowing['id'])}}">
                            <div class="row no-gutters m-1">
                                <div class="col-auto">
                                    <img src="{{$nextShowing['image'] ?: asset('images/placeholder-image.jpg')}}" alt=""
                                         style="object-fit: cover; border-radius: 3px" height="80px" width="80px">
                                </div>
                                <div class="col">
                                    <div class="card-block px-2">
                                        <p class="text-lg font-weight-bold my-0">{{$nextShowing['title']}}</p>
                                        <p class="text-muted text-success text-md font-weight-bold my-0"
                                           style="color: rgba(255,9,0,0.78)!important;">{{$nextShowing['theater']}}</p>
                                        <p class="text-muted text-sm font-weight-bold my-0">{{$nextShowing['start_time']}}
                                            - {{$nextShowing['end_time']}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </a>
                    @empty
                        <div class="alert alert-danger text-center">
                            No movies are next showing
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Coming Soon</div>
                </div>
                <div class="card-body hide-scroll" style="overflow-y: scroll; height: 390px">
                    @forelse($newMovies as $newMovie)
                        <a target="_blank" href="{{route('movies.show', $newMovie['id'])}}">
                            <div class="row no-gutters m-1">
                                <div class="col-auto">
                                    <img src="{{$newMovie['image'] ?: asset('images/placeholder-image.jpg')}}" alt=""
                                         style="object-fit: cover; border-radius: 3px" height="80px" width="80px">
                                </div>
                                <div class="col">
                                    <div class="card-block px-2">
                                        <p class="text-lg font-weight-bold my-0">{{$newMovie['title']}}</p>
                                        <p class="text-muted text-success text-md font-weight-bold my-0"
                                           style="color: rgba(208,0,255,0.67)!important;">{{$newMovie['theater']}}</p>
                                        <p class="text-muted text-sm font-weight-bold my-0">{{$newMovie['start_time']}}
                                            - {{$newMovie['end_time']}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </a>
                    @empty
                        <div class="alert alert-danger text-center">
                            No movies are coming soon
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Total Number of Movies Releasing in this Month</h3>
                    <div class="card-tools">
                        <!-- Maximize Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="maximize" data-max-size="50px"><i
                                class="fas fa-expand"></i></button>
                        <!-- Collapse Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i></button>
                        <!-- Remove Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                class="fas fa-times"></i></button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <canvas id="monthWiseMovies"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Total Number of Collections in each Month of
                        year {{\Carbon\Carbon::now()->year}}</h3>
                    <div class="card-tools">
                        <!-- Maximize Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="maximize" data-max-size="50px"><i
                                class="fas fa-expand"></i></button>
                        <!-- Collapse Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i></button>
                        <!-- Remove Button -->
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                class="fas fa-times"></i></button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <canvas id="monthWiseCollections"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <input type="hidden" id="weeks" value='@json($weeks)'>
    <input type="hidden" id="weekWiseMovies" value='@json($weekWiseMovies)'>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let weeks = JSON.parse($('#weeks').val());
        let weekWiseMovies = JSON.parse($('#weekWiseMovies').val());
        let backgroundColors = [];
        let borderColors = [];
        weeks.forEach(function (week, index) {
            if (index === 0) {
                backgroundColors.push('rgba(255, 99, 132, 0.2)');
                borderColors.push('rgb(255, 99, 132)');
            } else if (index === 1) {
                backgroundColors.push('rgba(255, 159, 64, 0.2)');
                borderColors.push('rgb(255, 159, 64)');
            } else if (index === 2) {
                backgroundColors.push('rgba(255, 205, 86, 0.2)');
                borderColors.push('rgb(255, 205, 86)');
            } else if (index === 3) {
                backgroundColors.push('rgba(75, 192, 192, 0.2)');
                borderColors.push('rgb(75, 192, 192)');
            } else if (index === 4) {
                backgroundColors.push('rgba(54, 162, 235, 0.2)');
                borderColors.push('rgb(54, 162, 235)');
            }

        });

        const barData = {
            labels: weeks,
            datasets: [{
                label: "Total Releasing Movies",
                data: weekWiseMovies,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1,
                hoverOffset: 4,
            }]
        };
        const barConfig = {
            type: 'bar',
            data: barData,
            options: {
                scales: {
                    x: {
                        grid: {
                            offset: true
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        const barChart = new Chart(
            document.getElementById('monthWiseMovies'),
            barConfig
        );
    </script>

    <script>
        const lineData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: "Total Collections",
                data: [{{$monthWiseCollections[1]}}, {{$monthWiseCollections[2]}}, {{$monthWiseCollections[3]}}, {{$monthWiseCollections[4]}}, {{$monthWiseCollections[5]}}, {{$monthWiseCollections[6]}}, {{$monthWiseCollections[7]}}, {{$monthWiseCollections[8]}}, {{$monthWiseCollections[9]}}, {{$monthWiseCollections[10]}}, {{$monthWiseCollections[11]}}, {{$monthWiseCollections[12]}}],
                backgroundColor: [
                    'rgb(241,13,13)',
                    'rgba(255,159,64,0.99)',
                    'rgb(163,253,3)',
                    'rgb(9,243,243)',
                    'rgb(10,38,57)',
                    'rgb(84,3,241)',
                    'rgb(200,19,236)',
                    'rgb(36,110,238)',
                    'rgb(245,98,85)',
                    'rgba(0,255,4,0.87)',
                    'rgba(135,57,1,0.87)',
                    'rgba(108,97,172,0.87)',
                ],
                fill: false,
                hoverOffset: 4,
                tension: 0.1
            }]
        };
        const dataConfig = {
            type: 'line',
            data: lineData,
            options: {
                scales: {
                    x: {
                        grid: {
                            offset: true
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        const chartDiagram = new Chart(
            document.getElementById('monthWiseCollections'),
            dataConfig
        );
    </script>
@stop
