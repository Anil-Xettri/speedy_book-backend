@extends('adminlte::page')

@section('title', 'Add '.$title)

@if(!isset($hideCardFooter))
@section('content_header')
    <div class="navbar bg-white p-2" id="head" style="border-radius: 2px">
        @if(!isset($hideCancel))
            <a href="javascript:history.back();"
               class="btn btn-default btn-sm btn-cancel"><i class="fas fa-arrow-left"></i> Back</a>
        @endif

        <button type="submit"
                id="button_submit" class="button_submit btn btn-sm btn-primary" style="margin-right: 18%!important;"
                name="action" value="submit"><i class="fas fa-plus"></i> Submit
        </button>

        @if(isset($addMoreButton))
            <button type="submit" id="button_submit_add"
                    class="button_submit btn btn-primary"
                    name="action" value="add"><i class="fas fa-plus-circle"></i>
                Submit & Add new
            </button>
        @endif
    </div>
@stop
@endif

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        label.error {
            color: rgba(234, 52, 52, 0.84);
        }

        body::-webkit-scrollbar {
            display: none; /* Safari and Chrome */
        }

        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .content-header {
            padding-top: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        .content {
            padding-top: 40px !important;
        }

        #head {
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .1);
        }
    </style>
    <style>

        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-selection__arrow {
            margin-top: 6px !important;
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col">
                    <!-- general form elements -->
                    <div class="card mx-4 mb-4">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}} | Create</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" name="myForm" action="{{route($route.'store')}}"
                              method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                                <input name="add_more" type="hidden" id="add-more" value="{{false}}">

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="">Theater <span class="text-danger">*</span></label>
                                        <input type="hidden" id="theaters" value='@json($theaters)'>
                                        <select name="theater_id" id="theater" class="form-control required-field"
                                                style="width: 100%">
                                            <option value="">--Select Cinema Hall--</option>
                                            @foreach($theaters as $theater)
                                                <option
                                                    value="{{$theater->id}}" {{old('theater_id', $item->theater_id) === $theater->id ? 'selected' : ''}}>{{$theater->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" id="movies" value='@json($movies)'>
                                    <div class="col-md-6">
                                        <label for="">Movie <span class="text-danger">*</span></label>
                                        <select name="movie_id" id="movie" class="form-control required-field"
                                                style="width: 100%">
                                            <option value="">--Select Movie--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <div class="d-flex justify-content-between">
                                            <h4>Add Time and Price Details</h4>
                                            <button id="add-new"
                                                    class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add New Row
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label style="margin-left: 70px">Movie Date <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label style="margin-left: 70px">Movie Time <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label style="margin-left: 70px">Ticket Price <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" required class="form-control show-date"
                                               onfocus="(this.type='date')"
                                               placeholder="Enter Movie Date"
                                               name="show_date[]">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="text" required class="form-control show-time"
                                               onfocus="(this.type='time')"
                                               placeholder="Enter Movie Time"
                                               name="show_time[]">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" required class="form-control show-price"
                                               placeholder="Enter Ticket Price"
                                               name="ticket_price[]">
                                    </div>
                                </div>

                                <div id="new-row">

                                </div>
                                <div class="row my-5">
                                    <div class="col-md-12">
                                        <label for="description">Description</label>
                                        <textarea id="description" class="form-control" name="description"
                                                  rows="4">{{$item->description}}</textarea>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection


@section('js')
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            let theaters = JSON.parse($("#theaters").val());
            let movies = JSON.parse($("#movies").val());

            $('#theater').select2({
                placeholder: "Select Theater",
                width: 'resolve'
            });
            $('#movie').select2({
                placeholder: "Select Movie",
                width: 'resolve'
            });


            let theaterId = $('#theater').val();
            let oldMovieId = "{{$item->movie_id}}";

            let index = theaters.findIndex(item => item.id == theaterId);
            let theater = theaters[index];

            if (index !== -1) {
                $('#movie').empty();
                $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
                $.each(theater.movies, function (key, movie) {
                    $('select[name="movie_id"]').append(`<option value='${movie.id}' ${movie.id == oldMovieId ? "selected" : ""}>${movie.title}</option>`);
                });
            } else {
                $('#movie').empty();
                $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
            }

            $(document).on('change', '#theater', function () {
                let theaterId = $(this).val();

                let index = theaters.findIndex(item => item.id == theaterId);
                let theater = theaters[index];

                if (index !== -1) {
                    $('#movie').empty();
                    $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
                    $.each(theater.movies, function (key, movie) {
                        $('select[name="movie_id"]').append('<option value="' + movie.id + '">' + movie.title + '</option>');
                    });
                } else {
                    $('#movie').empty();
                    $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
                }
            });

            $(document).on('click', '#add-new', function (e) {
                e.preventDefault();
                $('#new-row').append(`
                                    <hr>
                                    <div class="row">
    <div class="col-md-3">
        <input type="text" required class="form-control required-field show-date" onfocus="(this.type='date')" placeholder="Enter Movie Date"
               name="show_date[]" data-message="Movie Date">
    </div>

    <div class="col-md-3">
        <input type="text" required class="form-control required-field show-time" onfocus="(this.type='time')" placeholder="Enter Movie Time"
               name="show_time[]" data-message="Movie Time">
    </div>

    <div class="col-md-3">
        <input type="number" required class="form-control required-field show-price" placeholder="Enter Ticket Price" name="ticket_price[]" data-message="Ticket Price">
    </div>
<div class="col-md-3">
        <button class="remove btn btn-danger">Remove</button>
    </div>
</div>
`);
            });

            $(document).on('click', '.remove', function (e) {
                e.preventDefault();
                $(this).parent().parent().prev().remove();
                $(this).parent().parent().remove();
            });

            // $(document).find('.show-time').each(function (index, element) {
            //     $(document).find(this).change(function () {
            //         $(this).removeClass('border border-danger');
            //         $(this).parent().children().next().empty();
            //     });
            // });

            // var oldId = $(document).find('#movie').val();
            // var oldMovieIndex = movies.findIndex(item => item.id == oldId);
            // var oldMovie = movies[oldMovieIndex];
            // $(document).find('.show-date').each(function () {
            //     $(this).attr({
            //         "min": `${oldMovie.release_date}`
            //     });
            // });

            $(document).on('change', '#movie', function () {
                var id = $(this).val();
                var movieIndex = movies.findIndex(item => item.id == id);
                var movie = movies[movieIndex];
                $(document).find('.show-date').each(function () {
                    this.value = null;
                    $(this).attr({
                        "min": `${movie.release_date}`
                    });
                });

            });


            let seatDetails = [];
            $('#button_submit').click(
                function (e) {
                    var form = $('#form');
                    let valid = true;

                    $(document).find('.required-field').each(function () {
                        if (!$(this).val()) {
                            $(this).parent().append(`<span style="color: rgba(234, 52, 52, 0.84);font-weight: bold">This field is required.</span>`);
                            valid = false;
                        }
                    });

                    let movieId = $('#movie').val();
                    var oldMovieIndex = movies.findIndex(item => item.id == movieId);
                    var oldMovie = movies[oldMovieIndex];
                    var showDateTime = [];

                    oldMovie.show_times.forEach(function (showTime,index) {
                        JSON.parse(showTime.show_details).forEach(function(showDetails) {
                            let showDateTimeData = showDetails.show_date+ " "+showDetails.show_time;
                            showDateTime.push(showDateTimeData);
                        });
                    });

                    $(document).find('.show-time').each(function (index, element) {
                        let showTime = element.value;
                        let showDate = $(this).parent().prev().children().val();
                        let selectedDateTime = showDate+ " "+showTime;
                        if (jQuery.inArray(selectedDateTime, showDateTime) !== -1) {
                            $(this).addClass('border border-danger');
                            valid = false;
                            $(this).parent().children().next().empty();
                            $(this).parent().append(`<span style="color: rgba(234, 52, 52, 0.84);font-weight: bold">This show time already has a movie to show.</span>`);
                        }
                    });

                    // return;

                    if (!form.valid()) {
                        valid = false;
                    }

                    if (!valid) {
                        return;
                    }

                    let seats = [...$('.seats')];
                    let seatIds = [...$('.seat-ids')];
                    let rows = [...$('.rows')];
                    let columns = [...$('.columns')];

                    rows.forEach(function (row, obj) {
                        let keyValue = {
                            seat: seats[obj].value,
                            seatIds: seatIds[obj].value,
                            row: row.value,
                            column: columns[obj].value,
                        }

                        seatDetails.push(keyValue)

                    });

                    form.append(`
                        <input name="seats" type="hidden" value='${JSON.stringify(seatDetails)}'>
                    `);

                    form.submit();
                }
            );


        });
    </script>
@endsection

