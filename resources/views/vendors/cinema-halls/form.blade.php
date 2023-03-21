@push('styles')
@endpush

<div class="form-group row">
    <div class="col-md-6">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" required class="form-control" name="name" placeholder="Enter name of cinema hall"
               value="{{old('name', $item->name)}}">
    </div>

    <div class="col-md-6">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" name="email" placeholder="Enter email"
               value="{{old('email', $item->email)}}">
    </div>

    <div class="col-md-6 my-2">
        <label for="phone">Phone</label>
        <input type="text" id="phone" class="form-control" name="phone" placeholder="Enter phone"
               value="{{old('phone', $item->phone)}}">
    </div>

    <div class="col-md-6 my-2">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="Active" {{old('status', $item->status) === "Active" ? 'selected' : ''}}>Active</option>
            <option value="Inactive" {{old('status', $item->status) === "Inactive" ? 'selected' : ''}}>Inactive</option>
        </select>
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
        <input type="number" class="form-control" id="rows" name="total_rows" value="{{$item->rows}}"
               placeholder="Enter number of rows">
        <span class="text-danger" id="rows-error" style="display: none">Rows should not be empty or less than 1.</span>
    </div>

    <div class="col-md-3 rows-columns" style="display: none">
        <label>Total Columns</label>
        <input type="number" class="form-control" id="columns" name="total_columns" value="{{$item->columns}}"
               placeholder="Enter number of columns">
        <span class="text-danger" id="columns-error"
              style="display: none">Columns should not be empty or less than 1.</span>
    </div>

    <div class="col-md-3">
        <button id="get-seats" class="btn btn-primary" style="margin-top: 31px">Get Seats</button>
    </div>
</div>

<div id="seat-section" style="margin-top: 60px; margin-bottom: 40px;{{$routeName == "Create" ? 'display:none' : ''}}">
    <h4>Seats</h4>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div id="seat-details" class="table-responsive">
                <table id="seats">
                    @if($routeName == "Edit")
                        @for ($r=0; $r<=$item->rows; $r++)
                            <tr>
                                @for ($c=0; $c<=$item->columns; $c++)
                                    @foreach($item->seats as $seat)
                                        @if($seat->row_no == $r && $seat->column_no == $c)
                                            <td>
                                                <div>
                                                    <input type="text" class="form-control w-100" name="seats[]"
                                                           value="{{$seat->seat_name}}">
                                                    <input type="hidden" name="seat_ids[]" value="{{$seat->id}}">
                                                    <input type="hidden" name="rows[]" value="{{$r}}">
                                                    <input type="hidden" name="columns[]" value="{{$c}}">
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                @endfor
                            </tr>
                        @endfor
                    @endif
                </table>
            </div>
        </div>
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
    <script>
        $(document).ready(function () {
            $('#rows').on('keyup', function () {
                $('#rows-error').css('display', 'none');
            });
            $('#columns').on('keyup', function () {
                $('#columns-error').css('display', 'none');
            });
        });

        $(document).on('click', '#get-seats', function (e) {
            e.preventDefault();
            let rn = $('#rows').val();
            let cn = $('#columns').val();
            if (!rn || rn < 1) {
                $('#rows-error').css('display', '');
                $('#seats').empty();
                $('#seat-section').slideUp();
                return;
            } else {
                $('#rows-error').css('display', 'none');
            }
            if (!cn || cn < 1) {
                $('#columns-error').css('display', '');
                $('#seats').empty();
                $('#seat-section').slideUp();
                return;
            } else {
                $('#columns-error').css('display', 'none');
            }

            $('#seat-section').slideDown();
            $('#seats').empty();
            let seatNo = 1;
            for (var r = 0; r < parseInt(rn, 10); r++) {
                var x = document.getElementById('seats').insertRow(r);
                for (var c = 0; c < parseInt(cn, 10); c++) {
                    var y = x.insertCell(c);
                    y.innerHTML = `<input type="text" class="form-control w-100 " name="seats[]" value="${seatNo}">
                                    <input type="hidden" name="rows[]" value="${r}">
                                    <input type="hidden" name="columns[]" value="${c}">`;
                    seatNo++;
                }
            }
        });
    </script>
@endpush
