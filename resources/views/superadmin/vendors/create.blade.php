@extends('templates.create')
@push('styles')
@endpush
@section('form_content')
    @include('superadmin.vendors.form')
@endsection
@push('scripts')
    <script>
        var loadFile = function (event) {
            var image = document.getElementById('outputCreate');
            image.src = URL.createObjectURL(event.target.files[0]);
            $('#outputCreate').css('display', '');
        };
    </script>

    <script>
        var file = function (event) {
            var bannerImage = document.getElementById('bannerOutputCreate');
            bannerImage.src = URL.createObjectURL(event.target.files[0]);
            $('#bannerOutputCreate').css('display', '');
        };
    </script>
@endpush
