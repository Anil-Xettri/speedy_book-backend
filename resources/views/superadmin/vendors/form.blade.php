<div class="form-group row">
    <div class="col-6">
        <label for="">Name <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" name="name" value="{{ old('name',$item->name) }}"
               placeholder="Enter Name">
    </div>
    <div class="col-6">
        <label for="">Email <span class="text-danger">*</span></label>
        <input type="email" autocomplete="off" required class="form-control" name="email" value="{{ old('email',$item->email) }}"
               placeholder="Enter Email">
    </div>
    <div class="col-6 my-2">
        <label for="password">Password @if($routeName == "Create") <span class="text-danger">*</span> @endif</label>
        <div style="position: relative">
            <input type="password" name="password" class="form-control pr-5" placeholder="Enter Password"
                   autocomplete="off" @if($routeName == "Create") required @endif id="password" minlength="8">
            <span class="far fa-eye" id="togglePassword"
                  style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>
        </div>
        @if($routeName == "Edit")
            <span class="text-muted">Leave Blank To Remain Unchanged</span>
        @endif
    </div>
    <div class="col-6 my-2">
        <label for="">Phone</label>
        <input type="text" class="form-control" name="phone" value="{{ old('phone',$item->phone) }}"
               placeholder="Enter Phone">
    </div>
    <div class="col-6 my-2">
        <label for="">Address</label>
        <input type="text" class="form-control" name="address" value="{{ old('address',$item->address) }}"
               placeholder="Enter Address">
    </div>
    <div class="col-6 my-2">
        <label for="">Registration Number</label>
        <input type="text" class="form-control" name="registration_number"
               value="{{ old('registration_number',$item->registration_number) }}"
               placeholder="Enter Registration Number">
    </div>
    <div class="col-6 my-2">
        <label for="">Legal Number</label>
        <input type="text" class="form-control" name="legal_number"
               value="{{ old('legal_number',$item->legal_number) }}"
               placeholder="Enter Legal Number">
    </div>
    <div class="col-6 my-2">
        <label for="">Legal Type</label>
        <select name="legal_type" class="form-control">
            <option value="PAN" {{old('legal_type', $item->legal_type) == "PAN" ? 'selected' : ''}}>PAN</option>
            <option value="VAT" {{old('legal_type', $item->legal_type) == "VAT" ? 'selected' : ''}}>VAT</option>
        </select>
    </div>
    <div class="col-6 my-2">
        <label for="">Status</label>
        <select name="status" class="form-control">
            <option value="Active" {{old('status', $item->status) === "Active" ? 'selected' : ''}}>Active</option>
            <option value="Inactive" {{old('status', $item->status) === "Inactive" ? 'selected' : ''}}>Inactive</option>
        </select>
    </div>
    <div class="col-6 my-2">
        <label for="image_url">Image</label><br>
        <input type="file" name="image" class="form-control" id="image" onchange="loadFile(event)"><br>
        <img src="" style="display: none" id="outputCreate" class="w-50 h-50"><br>
        @if($item->getImage())
            <img src="{{$item->getImage()}}" id="output" class="w-50 h-50"><br>
        @endif
    </div>

        <div class="col-6 my-2">
            <label for="">Banner Image</label><br>
            <input type="file" name="banner_image" class="form-control" id="banner-image" onchange="file(event)"><br>
            <img src="" style="display: none" id="bannerOutputCreate" class="w-50 h-50"><br>
            @if($item->getImage('banner-image'))
                <img src="{{$item->getImage('banner-image')}}" id="bannerOutput" class="w-50 h-50"><br>
            @endif
        </div>
</div>

<div class="row">
    <div class="col-md-12 my-2">
        <label for="description">Description</label>
        <textarea id="description" class="form-control" name="description"
                  rows="4">{{$item->description}}</textarea>
    </div>
</div>
@push('scripts')
    <script>
        jQuery(document).ready(function () {
            $('#togglePassword').click(function (e) {
                const type = $('#password').attr('type') === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                $(this).toggleClass('fa-eye-slash');
            });
        });
    </script>
@endpush
