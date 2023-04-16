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

    <div class="row mt-3">
        <div class="col-md-8">
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
@stop
