<div class="form-group row">
    <div class="col-md-6">
        <label for="">Title <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="title" value="{{ old('title',$item->title) }}"
               placeholder="Enter Title">
    </div>
    <div class="col-md-6">
        <label for="">Cinema Hall <span class="text-danger">*</span></label>
        <select name="cinema_hall_id" required class="form-control">
            <option value="">Select Cinema Hall</option>
            @foreach($cinemaHalls as $cinemaHall)
                <option
                    value="{{$cinemaHall->id}}" {{old('cinema_hall_id', $item->cinema_hall_id) === $cinemaHall->id ? 'selected' : ''}}>{{$cinemaHall->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 my-2">
        <label for="password">Duration(In Minutes) <span class="text-danger">*</span></label>
        <input type="number" name="duration" class="form-control" placeholder="Enter Movie Duration in Minutes"
               value="{{old('duration', $item->duration)}}">
    </div>
{{--    <div class="col-md-6 my-2">--}}
{{--        <label for="">Ticket Price <span class="text-danger">*</span></label>--}}
{{--        <input type="number" step="0.01" name="ticket_price" class="form-control" placeholder="Enter Ticket Price"--}}
{{--               value="{{old('ticket_price', $item->ticket_price)}}">--}}
{{--    </div>--}}
{{--    <div class="col-md-6 my-2">--}}
{{--        <label for="">Show Date</label>--}}
{{--        <input type="text" class="form-control" onfocus="(this.type='date')" name="show_date"--}}
{{--               value="{{ old('show_date',$item->show_date) }}"--}}
{{--               placeholder="Enter Show Date">--}}
{{--    </div>--}}
{{--    <div class="col-md-6 my-2">--}}
{{--        <label for="">Show Time</label>--}}
{{--        <input type="text" class="form-control" onfocus="(this.type='time')" name="show_time"--}}
{{--               value="{{ old('show_time',$item->show_time) }}"--}}
{{--               placeholder="Enter Show Time">--}}
{{--    </div>--}}
    <div class="col-md-6 my-2">
        <label for="">Status</label>
        <select name="status" class="form-control">
            <option value="Active" {{old('status', $item->status) === "Active" ? 'selected' : ''}}>Active</option>
            <option value="Inactive" {{old('status', $item->status) === "Inactive" ? 'selected' : ''}}>Inactive</option>
        </select>
    </div>
    <div class="col-md-6 my-2">
        <label for="image_url">Image <span class="text-danger">*</span></label><br>
        <input type="file" name="image" class="form-control" id="image" onchange="loadFile(event)"><br>
        <img src="" style="display: none" id="outputCreate" class="w-50 h-50"><br>
        @if($item->image)
            <img src="{{$item->image_url}}" id="output" class="w-50 h-50"><br>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12 my-1">
        <label for="description">Description</label>
        <textarea id="description" class="form-control" name="description"
                  rows="4">{{$item->description}}</textarea>
    </div>
</div>
@push('scripts')
    <script>

    </script>
@endpush
