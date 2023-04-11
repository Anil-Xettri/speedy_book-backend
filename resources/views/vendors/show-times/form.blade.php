@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>

        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-selection__arrow {
            margin-top: 6px !important;
        }
    </style>
@endpush
<div class="form-group row">
    <div class="col-md-6">
        <label for="">Theater <span class="text-danger">*</span></label>
        <input type="hidden" id="theaters" value='@json($theaters)'>
        <select name="theater_id" id="theater" class="form-control required-field" style="width: 100%">
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
        <select name="movie_id" id="movie" class="form-control required-field" style="width: 100%">
            <option value="">--Select Movie--</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <div class="d-flex justify-content-between">
            <h4>Add Time and Price Details</h4>
            <button id="add-new" class="btn btn-primary @if($routeName == "Create") disabled @endif"><i class="fas fa-plus"></i> Add New Row</button>
        </div>
    </div>
</div>
<hr>

@if($routeName == "Create")
    <div class="row">
        <div class="col-md-3">
            <label style="margin-left: 70px">Movie Date</label>
            <input type="text" required class="form-control show-date" onfocus="(this.type='date')"
                   placeholder="Enter Movie Date"
                   name="show_date[]">
        </div>

        <div class="col-md-3">
            <label style="margin-left: 70px">Movie Time</label>
            <input type="text" required class="form-control show-time" onfocus="(this.type='time')"
                   placeholder="Enter Movie Time"
                   name="show_time[]">
        </div>

        <div class="col-md-3">
            <label style="margin-left: 70px">Ticket Price</label>
            <input type="number" required class="form-control show-price" placeholder="Enter Ticket Price"
                   name="ticket_price[]">
        </div>
    </div>
@elseif($routeName == "Edit")
    @foreach(json_decode($item->show_details,true) ?? [] as $i => $showDetails)
        <div class="row">
            <div class="col-md-3">
                @if ($loop->first)
                    <label style="margin-left: 70px">Movie Date</label>
                @endif
                <input type="text" required class="form-control  show-date" onfocus="(this.type='date')"
                       placeholder="Enter Movie Date"
                       name="show_date[]" value="{{$showDetails['show_date']}}">
            </div>

            <div class="col-md-3">
                @if ($loop->first)
                    <label style="margin-left: 70px">Movie Time</label>
                @endif
                <input type="text" required class="form-control show-time" onfocus="(this.type='time')"
                       placeholder="Enter Movie Time"
                       name="show_time[]" value="{{$showDetails['show_time']}}">
            </div>

            <div class="col-md-3">
                @if ($loop->first)
                    <label style="margin-left: 70px">Ticket Price</label>
                @endif
                <input type="number" required class="form-control" placeholder="Enter Ticket Price"
                       name="ticket_price[]" value="{{$showDetails['ticket_price']}}">
            </div>

            @if (!$loop->first)
                <div class="col-md-3">
                    <button class="remove btn btn-danger">Remove</button>
                </div>
            @endif

        </div>
        @if(!$loop->last)
            <hr>
        @endif
    @endforeach
@endif

<div id="new-row">

</div>
<div class="row my-5">
    <div class="col-md-12">
        <label for="description">Description</label>
        <textarea id="description" class="form-control" name="description"
                  rows="4">{{$item->description}}</textarea>
    </div>
</div>
@push('scripts')
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

            var oldId = $(document).find('#movie').val();
            var oldMovieIndex = movies.findIndex(item => item.id == oldId);
            var oldMovie = movies[oldMovieIndex];
            $(document).find('.show-date').each(function () {
                $(this).attr({
                    "min": `${oldMovie.release_date}`
                });
            });

            $(document).on('change', '#movie', function () {
                var id = $(this).val();
                var movieIndex = movies.findIndex(item => item.id == id);
                var movie = movies[movieIndex];
                $(document).find('.show-date').each(function () {
                    $(this).attr({
                        "min": `${movie.release_date}`
                    });
                });

            });
        });
    </script>
@endpush
