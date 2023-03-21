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

<h4>Customer Details</h4>
<hr>
<div class="form-group row">
    <div class="col-md-6">
        <label for="">Customer Name <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="customer_name"
               value="{{ old('customer_name',$item->customer_name) }}"
               placeholder="Enter Customer Name">
    </div>
    <div class="col-md-6">
        <label for="">Email <span class="text-danger">*</span></label>
        <input type="email" required class="form-control" name="customer_email"
               value="{{ old('customer_email',$item->customer_email) }}"
               placeholder="Enter Email">
    </div>
    <div class="col-md-6 my-2">
        <label for="">Phone Number <span class="text-danger">*</span></label>
        <input type="number" required class="form-control" name="customer_phone"
               value="{{ old('customer_phone',$item->customer_phone) }}"
               placeholder="Enter Phone Number">
    </div>
</div>

<h4>Booking Details</h4>
<hr>
<div class="form-group row">
    <div class="col-md-6">
        <label for="">Cinema Hall <span class="text-danger">*</span></label>
        <input type="hidden" id="halls" value='@json($cinemaHalls)'>
        <select name="cinema_hall_id" id="cinema-hall" required class="form-control" style="width: 100%">
            <option value="">--Select Cinema Hall--</option>
            @foreach($cinemaHalls as $cinemaHall)
                <option
                    value="{{$cinemaHall->id}}" {{old('cinema_hall_id', $item->cinema_hall_id) === $cinemaHall->id ? 'selected' : ''}}>{{$cinemaHall->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <input type="hidden" id="movies" value='@json($movies)'>
        <label for="">Movie <span class="text-danger">*</span></label>
        <select name="movie_id" id="movie" required class="form-control" style="width: 100%">
            <option value="">--Select Movie--</option>
        </select>
    </div>

    <div class="col-md-6 my-2">
        <label for="">Time and Ticket Price <span class="text-danger">*</span></label>
        <select name="time_price" id="time-price" required class="form-control" style="width: 100%">
            <option value="">--Select Time and Price--</option>
        </select>
    </div>
    <input type="hidden" id="date" value="">
    <input type="hidden" id="time" value="">
    <input type="hidden" id="price" value="">

    <div class="col-md-6 my-2">
        <label>Total Tickets</label>
        <input type="number" step="1" id="quantity" value="{{$item->quantity}}" name="quantity" class="form-control" placeholder="Enter Total ticket quantity">
    </div>
</div>

<div class="row">
    <div class="col-md-6 my-1">
        <label for="description">Notes</label>
        <textarea id="description" class="form-control" name="notes"
                  rows="4">{{$item->notes}}</textarea>
    </div>
</div>
<hr>
<div class="float-right">
    <label>Sub Total: </label>
    <input type="number" step="0.01" id="sub-total" style="height: 30px;border-radius: 5px">
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            let halls = JSON.parse($("#halls").val());
            let movies = JSON.parse($("#movies").val());

            $('#cinema-hall').select2({
                placeholder: "Select Cinema Hall",
                width: 'resolve'
            });
            $('#movie').select2({
                placeholder: "Select Movie",
                width: 'resolve'
            });
            $('#time-price').select2({
                placeholder: "Select Time and Price",
                width: 'resolve'
            });

            $(document).on('change', '#cinema-hall', function () {
                let hallId = $(this).val();

                let index = halls.findIndex(item => item.id == hallId);
                let hall = halls[index];

                if (index !== -1) {
                    $('#movie').empty();
                    $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
                    $.each(hall.movies, function (key, movie) {
                        if (movie.status == "Active") {
                            $('select[name="movie_id"]').append(`<option value="${movie.id}">${movie.title}</option>`);
                        }
                    });
                } else {
                    $('#movie').empty();
                    $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
                }
            });

            $(document).on('change', '#movie', function () {
                let movieId = $(this).val();

                let movieIndex = movies.findIndex(item => item.id == movieId);
                let movie = movies[movieIndex];

                if (movieIndex !== -1) {
                    $('#time-price').empty();
                    $('#time-price').append('<option value="{{null}}">--Select Time and Price--</option>');
                    $.each(movie.show_times, function (key, showTime) {
                            let data = JSON.parse(showTime.show_details);
                            data.forEach(function (value, index) {
                                $('select[name="time_price"]').append(`<option value="${value.show_date},${value.show_time},${value.ticket_price}"> Date => ${value.show_date}, Time => ${value.show_time}, Price => Rs ${value.ticket_price}</option>`);
                            });
                        }
                    );
                } else {
                    $('#time-price').empty();
                    $('#time-price').append('<option value="{{null}}">--Select Time and Price--</option>');
                }
            });

            $(document).on('change', '#time-price', function () {
                let timePrice = $(this).val();
                if (timePrice) {
                    let splitDate = timePrice.split(",");
                    let date = splitDate[0];
                    let time = splitDate[1];
                    let price = splitDate[2];

                    $('#date').val(date);
                    $('#time').val(time);
                    $('#price').val(price);
                }
            });
        });
    </script>
@endpush
