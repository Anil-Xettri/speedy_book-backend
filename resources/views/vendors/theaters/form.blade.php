@push('styles')
@endpush

<div class="form-group row">
    <div class="col-md-6">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" required class="form-control" name="name" placeholder="Enter name of theater"
               value="{{old('name', $item->name)}}">
    </div>

    <div class="col-md-6">
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
    <div class="col-md-4 rows-columns">
        <label>Total Rows <span class="text-danger">*</span></label>
        <input type="number" class="form-control" required id="rows" name="total_rows" value="{{$item->rows}}"
               placeholder="Enter number of rows">
        <span class="text-danger" id="rows-error" style="display: none">Rows should not be empty or less than 1.</span>
    </div>

    <div class="col-md-4 rows-columns">
        <label>Total Columns <span class="text-danger">*</span></label>
        <input type="number" class="form-control" required id="columns" name="total_columns" value="{{$item->columns}}"
               placeholder="Enter number of columns">
        <span class="text-danger" id="columns-error"
              style="display: none">Columns should not be empty or less than 1.</span>
    </div>

    <div class="col-md-4">
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
                                                    <input type="text" class="form-control w-100 seats"
                                                           value="{{$seat->seat_name}}">
                                                    <input type="hidden" class="seat-ids" name="seat_ids[]" value="{{$seat->id}}">
                                                    <input type="hidden" class="rows" value="{{$r}}">
                                                    <input type="hidden" class="columns" value="{{$c}}">
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
                    y.innerHTML = `<input type="text" class="form-control w-100 seats" value="${seatNo}">
                                   <input type="hidden" class="seat-ids" name="seat_ids[]" value="null">
                                    <input type="hidden" class="rows" value="${r}">
                                    <input type="hidden" class="columns" value="${c}">`;
                    seatNo++;
                }
            }
        });
    </script>
@endpush
