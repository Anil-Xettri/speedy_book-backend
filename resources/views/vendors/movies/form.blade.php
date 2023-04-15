@push('styles')
    <style>
        .without_ampm::-webkit-datetime-edit-ampm-field {
            display: none;
        }

        input[type=time]::-webkit-clear-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            -o-appearance: none;
            -ms-appearance: none;
            appearance: none;
            margin: -10px;
        }
    </style>
@endpush

<div class="form-group row">
    <div class="col-md-6">
        <label for="">Title <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="title" value="{{ old('title',$item->title) }}"
               placeholder="Enter Title">
    </div>
    <div class="col-md-6">
        <label for="">Theater <span class="text-danger">*</span></label>
        <select name="theater_id" required class="form-control">
            <option value="">Select Theater</option>
            @foreach($theaters as $theater)
                <option
                    value="{{$theater->id}}" {{old('theater_id', $item->theater_id) === $theater->id ? 'selected' : ''}}>{{$theater->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 my-2">
        <label for="">Duration <span class="text-danger">*</span></label>
{{--        <input type="text" required name="duration" onfocus="(this.type='time')" class="form-control without_ampm"--}}
{{--               placeholder="Enter Movie Duration"--}}
{{--               value="{{old('duration', $item->duration)}}">--}}
        <input type="text" required name="duration" id="durationForm" maxlength=8 pattern="^((\d+:)?\d+:)?\d*$"
               placeholder="hh:mm:ss" size=30 class="form-control" value="{{old('duration', $item->duration)}}">

    </div>

    <div class="col-md-6 my-2">
        <label for="">Release Date <span class="text-danger">*</span></label>
        <input type="text" required name="release_date" onfocus="(this.type='date')" class="form-control"
               placeholder="Enter Release Date"
               value="{{old('release_date', $item->release_date)}}">
    </div>

    <div class="col-md-6 my-2">
        <label for="">Status</label>
        <select name="status" class="form-control">
            <option value="Active" {{old('status', $item->status) === "Active" ? 'selected' : ''}}>Active</option>
            <option value="Inactive" {{old('status', $item->status) === "Inactive" ? 'selected' : ''}}>Inactive</option>
        </select>
    </div>
    <div class="col-md-6 my-2">
        <label for="image">Image <span class="text-danger">*</span></label><br>
        <input type="file" name="image" @if(!$item->image_url) required @endif class="form-control" id="image"
               onchange="loadFile(event)"><br>
        <img src="" style="display: none" id="outputCreate" class="w-50 h-50"><br>
        @if($item->image_url)
            <img src="{{$item->image_url}}" id="output" class="w-50 h-50"><br>
        @endif
    </div>

    <div class="col-md-6 my-2">
        <label for="trailer">Trailer <span class="text-danger">*</span></label><br>
        <input type="file" name="trailer" @if(!$item->image_url) required @endif class="form-control" id="trailer"
               onchange="file(event)" accept=".mp4, .mov, .ogg, .qt"><br>
        <video src="" style="display: none" id="video-create" class="w-50 h-50" controls>
        </video>
        @if($item->trailer_url)
            <video src="{{$item->trailer_url}}" id="video-edit" class="w-50 h-50" controls>
            </video>
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
        $(document).ready(function () {
            // two or more digits, to be more precise (might get relevant for durations >= 100h)
            var twoDigits = function (oneTwoDigits) {
                if (oneTwoDigits < 10) {oneTwoDigits = "0" + oneTwoDigits};
                return oneTwoDigits;
            }

            var Time = function (durationFormValue) {
                var hmsString = String(durationFormValue);
                var hms = hmsString.match(/^(?:(?:(\d+)\:)?(\d+)\:)?(\d+)$/);
                if (hms === null) {
                    throw new TypeError("Parameter " + hmsString +
                        " must have the format ((int+:)?int+:)?int+");
                }
                var hoursNumber = +hms[1] || 0;
                var minutesNumber = +hms[2] || 0;
                var secondsNumber = +hms[3] || 0;
                this.seconds = twoDigits(secondsNumber % 60);
                minutesNumber += Math.floor(secondsNumber / 60);
                this.minutes = twoDigits(minutesNumber % 60);
                hoursNumber += Math.floor(minutesNumber / 60);
                this.hours = twoDigits(hoursNumber);
            };

            Time.prototype.equals = function (otherTime) {
                return (this.hours === otherTime.hours) &&
                    (this.minutes === otherTime.minutes) &&
                    (this.seconds === otherTime.seconds);
            };

            Time.prototype.toString = function () {
                return this.hours + ":" + this.minutes + ":" + this.seconds;
            }
        });
    </script>
@endpush
