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
    <div class="col-md-6 my-2">
        <label for="">Address <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="customer_address"
               value="{{ old('customer_address',$item->customer_address) }}"
               placeholder="Enter Address">
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
        <label for="">Movie <span class="text-danger">*</span></label>
        <select name="movie_id" id="movie" required class="form-control" style="width: 100%">
            <option value="">--Select Movie--</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 my-1">
        <label for="description">Notes</label>
        <textarea id="description" class="form-control" name="notes"
                  rows="4">{{$item->notes}}</textarea>
    </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            let halls = JSON.parse($("#halls").val());

            $('#cinema-hall').select2({
                placeholder: "Select Cinema Hall",
                width: 'resolve'
            });
            $('#movie').select2({
                placeholder: "Select Movie",
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
                        $('select[name="movie_id"]').append('<option value="' + movie.id + '">' + movie.title + '</option>');
                    });
                } else {
                    $('#movie').empty();
                    $('#movie').append('<option value="{{null}}">--Select Movie--</option>');
                }
            });
        });
    </script>
@endpush
