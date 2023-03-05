@push('styles')
@endpush

<div class="form-group row">
    <div class="col-md-6">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" required class="form-control" name="name" placeholder="Enter name of cinema hall"
               value="{{old('name', $item->name)}}">
    </div>

    <div class="col-md-6">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="Active" {{old('status', $item->status) === "Active" ? 'selected' : ''}}>Active</option>
            <option value="Inactive" {{old('status', $item->status) === "Inactive" ? 'selected' : ''}}>Inactive</option>
        </select>
    </div>

    <div class="col-6 my-2">
        <label for="image_url">Image</label><br>
        <input type="file" name="image" class="form-control" id="image" onchange="loadFile(event)"><br>
        <img src="" style="display: none" id="outputCreate" class="w-50 h-50"><br>
        @if($item->image)
            <img src="{{$item->image_url}}" id="output" class="w-50 h-50"><br>
        @endif
    </div>
</div>

<h4>Seat Details</h4>
<hr>
<div class="row">
    <div class="col-md-3">
        <label>Seat Calculation</label>
        <select name="seat_calculation" id="seat-selection-type" class="form-control">
            <option
                value="Number_of_Seats" {{old('seat_calculation', $item->seat_calculation) === "Number_of_Seats" ? 'selected' : ''}}>
                Using
                Number of Seats
            </option>
            <option
                value="Rows_Columns" {{old('seat_calculation', $item->seat_calculation) === "Rows_Columns" ? 'selected' : ''}}>
                Using
                Rows and Columns
            </option>
        </select>
    </div>
    <div class="col-md-3" id="total-seats">
        <label>Total Seats</label>
        <input type="number" class="form-control" name="total_seats" placeholder="Enter number of seats">
    </div>
    <div class="col-md-3 rows-columns" style="display: none">
        <label>Total Rows</label>
        <input type="number" class="form-control" name="rows" placeholder="Enter number of rows">
    </div>

    <div class="col-md-3 rows-columns" style="display: none">
        <label>Total Columns</label>
        <input type="number" class="form-control" name="columns" placeholder="Enter number of columns">
    </div>

    <div class="col-md-3">
        <button id="get-seats" class="btn btn-primary" style="margin-top: 31px">Get Seats</button>
    </div>
</div>

<div class="row" style="margin-top: 60px; margin-bottom: 40px">
    <div class="col-md-12">
        <div id="seat-details"></div>
    </div>
</div>


@push('scripts')
    <script>
        let oldSelectionType = $('#seat-selection-type').val();

        if (oldSelectionType === "Rows_Columns") {
            $('#total-seats').css('display', 'none');
            $('.rows-columns').css('display', '');
        } else {
            $('#total-seats').css('display', '');
            $('.rows-columns').css('display', 'none');
        }

        $(document).on('change', '#seat-selection-type', function () {
            let selectionType = $(this).val();

            if (selectionType === "Rows_Columns") {
                $('#total-seats').css('display', 'none');
                $('.rows-columns').css('display', '');
            } else {
                $('#total-seats').css('display', '');
                $('.rows-columns').css('display', 'none');
            }
        });
    </script>
{{--    <script>--}}
{{--        $('#seat-details').append(`<input type="text" class="form-control" style="width: 80px;margin-bottom: 5px;margin-right: 5px;">--}}
{{--<input type="text" class="form-control" style="width: 80px;margin-bottom: 5px;margin-right: 5px;">`);--}}
{{--    </script>--}}
@endpush
