@extends('templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Name:</span></label> {{ $item->name }}<br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Total Rows:</span></label> {{ $item->rows ?: '---'}}<br>
        </div>

        <div class="col-md-6 my-2">
            <label for=""><span class="show-text">Total Column:</span></label> {{ $item->columns ?: '---'}}<br>
        </div>

        <div class="col-md-6 my-3">
            <label for=""><span class="show-text">Status:</span></label>
            @if($item->status == 'Active')
                <span class="badge badge-success">Active</span>
            @elseif($item->status == 'Inactive')
                <span class="badge badge-secondary">Inactive</span>
            @endif
            <br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Seat Details:</span></label>
            <hr>
            <div class="table-responsive">
                <table>
                    @for ($r=0; $r<=$item->rows; $r++)
                        <tr>
                            @for ($c=0; $c<=$item->columns; $c++)
                                @foreach($item->seats as $seat)
                                    @if($seat->row_no == $r && $seat->column_no == $c)
                                        <td>
                                            <input type="text" readonly class="form-control w-100 mt-1"
                                                   value="{{$seat->seat_name}}">
                                        </td>
                                    @endif
                                @endforeach
                            @endfor
                        </tr>
                    @endfor
                </table>
            </div>
        </div>
    </div>
@endsection
