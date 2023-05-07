@extends('templates.index')

@section('title', 'Collections')

@section('content_header')
    <h1>Collections</h1>
@stop

@section('ext_css')
@stop

@section('index_content')
    <form action="{{route('collections')}}" method="GET">
        <div class="row my-3">
            <div class="col-md-3">
                <label>Movie</label>
                <select required class="form-control" name="movie_id">
                    <option value="{{null}}">Select Movie</option>
                    @foreach($movies ?? [] as $movie)
                        <option
                            value="{{$movie->id}}" {{isset($selectedMovie) ? ($selectedMovie == $movie->id ? 'selected' : "") : ""}}>{{$movie->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Month</label>
                <select required class="form-control" name="month">
                    <option value="{{null}}">Select Month</option>
                    <option value="01" {{isset($selectedMonth) ? ($selectedMonth == "01" ? 'selected' : "") : ""}}>
                        January
                    </option>
                    <option value="02" {{isset($selectedMonth) ? ($selectedMonth == "02" ? 'selected' : "") : ""}}>
                        February
                    </option>
                    <option value="03" {{isset($selectedMonth) ? ($selectedMonth == "03" ? 'selected' : "") : ""}}>
                        March
                    </option>
                    <option value="04" {{isset($selectedMonth) ? ($selectedMonth == "04" ? 'selected' : "") : ""}}>
                        April
                    </option>
                    <option value="05" {{isset($selectedMonth) ? ($selectedMonth == "05" ? 'selected' : "") : ""}}>May
                    </option>
                    <option value="06" {{isset($selectedMonth) ? ($selectedMonth == "06" ? 'selected' : "") : ""}}>
                        June
                    </option>
                    <option value="07" {{isset($selectedMonth) ? ($selectedMonth == "07" ? 'selected' : "") : ""}}>
                        July
                    </option>
                    <option value="08" {{isset($selectedMonth) ? ($selectedMonth == "08" ? 'selected' : "") : ""}}>
                        August
                    </option>
                    <option value="09" {{isset($selectedMonth) ? ($selectedMonth == "09" ? 'selected' : "") : ""}}>
                        September
                    </option>
                    <option value="10" {{isset($selectedMonth) ? ($selectedMonth == "10" ? 'selected' : "") : ""}}>
                        October
                    </option>
                    <option value="11" {{isset($selectedMonth) ? ($selectedMonth == "11" ? 'selected' : "") : ""}}>
                        November
                    </option>
                    <option value="12" {{isset($selectedMonth) ? ($selectedMonth == "12" ? 'selected' : "") : ""}}>
                        December
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Month</label>
                <select required class="form-control" name="year">
                    <option value="{{null}}">Select Year</option>
                    @for($i=0; $i<=20; $i++)
                        <option value="{{2020 + $i}}" {{isset($selectedYear) ? ($selectedYear == 2020 + $i ? 'selected' : "") : ""}}>
                            {{2020 + $i}}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm" style="margin-top: 35px"><i class="fas fa-search"></i> Get
                    Collections
                </button>
            </div>
        </div>
    </form>
    @if(isset($selectedMovie) && isset($selectedMonth))
        <hr>
        <div class="row">
            <div class="col-md-12">
                <canvas id="collections"></canvas>
            </div>
        </div>
        <input type="hidden" id="days" value='@json($totalDays)'>
        <input type="hidden" id="dayWiseCollections" value='@json($dayWiseCollections)'>
    @endif
@stop

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let days = JSON.parse($('#days').val());
        let dayWiseCollections = JSON.parse($('#dayWiseCollections').val());

        const lineData = {
            labels: days,
            datasets: [{
                label: "Total Collections",
                data: dayWiseCollections,
                fill: false,
                borderWidth: 3,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgb(62,243,92)',
                tension: 0.1
            }]
        };
        const lineConfig = {
            type: 'line',
            data: lineData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }

        const lineChart = new Chart(
            document.getElementById('collections'),
            lineConfig
        );
    </script>
@endpush
