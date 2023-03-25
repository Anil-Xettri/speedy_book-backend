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
                <th >#id</th>
                <th>theater</th>
                <th>movie</th>
                <th>show details</th>
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
                    {data: 'theater_id', name: 'theater_id'},
                    {data: 'movie_id', name: 'movie_id'},
                    {
                        data: 'show_details', name: 'show_details', render: function (data, type, full, meta) {
                            var details = "";
                            data.forEach(function (value, index) {
                                let colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
                                let randomColor = Math.floor(Math.random() * colors.length);
                                details +=`<span class="badge badge-${colors[randomColor]} mr-2">${value.show_date} ${value.show_time}, Rs. ${value.ticket_price}</span>`;
                            });

                            return details;
                        }
                    },
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endpush
