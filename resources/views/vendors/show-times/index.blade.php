@extends('templates.index')

@section('title', 'Show Times')

@section('content_header')
    <h1>Show Times</h1>
@stop

@section('ext_css')
@stop

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
            <tr class="text-left text-capitalize">
                <th>#id</th>
                <th>cinema hall</th>
                <th>movie</th>
                <th>action</th>
            </tr>
            </thead>

        </table>
    </div>
@stop

@push('scripts')
    <script>
        $(function () {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('show-times.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'cinema_hall_id', name: 'cinema_hall_id'},
                    {data: 'movie_id', name: 'movie_id'},
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endpush
