@extends('templates.create')
@push('styles')
@endpush
@section('form_content')
    @include('vendors.theaters.form')
@endsection
@push('scripts')
    <script>
        var loadFile = function (event) {
            var image = document.getElementById('outputCreate');
            image.src = URL.createObjectURL(event.target.files[0]);
            $('#outputCreate').css('display', '');
        };
    </script>
@endpush
