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
                <th>movie</th>
                <th>tickets</th>
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
                    {data: 'movie_id', name: 'movie_id'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                    {
                        data: 'status', name: 'status', render: function (data, type, full, meta) {
                            switch (data) {
                                case ('Available'):
                                    return `<span class="badge badge-success">Available</span>`;
                                    break;
                                case ('Reserve'):
                                    return `<span class="badge badge-warning">Reserve</span>`;
                                    break;
                                case ('Sold Out'):
                                    return `<span class="badge badge-danger">Sold Out</span>`;
                                    break;
                                case ('Unavailable'):
                                    return `<span class="badge badge-secondary">Unavailable</span>`;
                                    break;
                                default:
                                    return `<span class="badge badge-success">Available</span>`;
                            }
                        }
                    },
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endpush
