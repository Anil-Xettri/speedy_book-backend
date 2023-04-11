@extends('templates.index')

@section('title', 'Bookings')

@section('content_header')
    <h1>Bookings</h1>
@stop

@section('ext_css')
@stop

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
            <tr class="text-left text-capitalize">
                <th>#id</th>
                <th>customer name</th>
                <th>theater</th>
                <th>movie</th>
                <th>show time</th>
                <th>tickets</th>
                <th>price</th>
                <th>total</th>
                <th>status</th>
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
                ajax: "{{ route('bookings.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'theater_id', name: 'theater_id'},
                    {data: 'movie_id', name: 'movie_id'},
                    {data: 'show_time', name: 'show_time'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'price', name: 'price'},
                    {data: 'total', name: 'total'},
                    {
                        data: 'status', name: 'status', render: function (data, type, full, meta) {
                            switch (data) {
                                case ('Paid'):
                                    return `<span class="badge badge-success">Paid</span>`;
                                    break;
                                case ('Unpaid'):
                                    return `<span class="badge badge-danger">Unpaid</span>`;
                                    break;
                                default:
                                    return `<span class="badge badge-danger">Unpaid</span>`;
                            }
                        }
                    },
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endpush
