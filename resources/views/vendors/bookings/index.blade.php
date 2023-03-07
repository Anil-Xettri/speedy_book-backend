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
                <th>showtime</th>
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
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'movie_id', name: 'movie_id'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'show_date_time', name: 'show_date_time'},
                    {
                        data: 'status', name: 'status', render: function (data, type, full, meta) {
                            switch (data) {
                                case ('Pending'):
                                    return `<span class="badge badge-warning">Pending</span>`;
                                    break;
                                case ('Inactive'):
                                    return `<span class="badge badge-secondary">Inactive</span>`;
                                    break;
                                case ('Confirmed'):
                                    return `<span class="badge badge-success">Confirmed</span>`;
                                    break;
                                case ('Cancelled'):
                                    return `<span class="badge badge-danger">Cancelled</span>`;
                                    break;
                                default:
                                    return `<span class="badge badge-secondary">Inactive</span>`;
                            }
                        }
                    },
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endpush
