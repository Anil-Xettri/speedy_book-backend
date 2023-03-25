@extends('templates.edit')
@push('styles')
@endpush
@section('form_content')
    @include('vendors.theaters.form')
@endsection
@push('scripts')
    <script>
        var loadFile = function (event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endpush
