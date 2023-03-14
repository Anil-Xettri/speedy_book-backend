@extends('templates.create')
@push('styles')
@endpush
@section('form_content')
    @include('vendors.movies.form')
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
            var trailer = document.getElementById('video-create');
            trailer.src = URL.createObjectURL(event.target.files[0]);
            $('#video-create').css('display', '');
        };
    </script>
@endpush
