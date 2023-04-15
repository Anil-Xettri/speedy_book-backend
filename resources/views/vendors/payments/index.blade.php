@extends('templates.index')

@section('title', 'Payments')

@section('content_header')
    <h1>Payments</h1>
@stop

@section('ext_css')
@stop

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
            <tr class="text-left text-capitalize">
                <th>#id</th>
                <th>paid by</th>
                <th>customer email</th>
                <th>Booking Id</th>
                <th>payment method</th>
                <th>paid at</th>
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
                ajax: "{{ route('payments.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'email', name: 'email'},
                    {data: 'booking_id', name: 'booking_id'},
                    {
                        data: 'payment_method', name: 'payment_method', render: function (data, type, full, meta) {
                            switch (data) {
                                case ('Cash'):
                                    return `<span class="badge badge-success">Cash</span>`;
                                    break;
                                case ('Khalti'):
                                    return `<span class="badge badge-primary">Khalti</span>`;
                                    break;
                                default:
                                    return `<span class="badge badge-success">Cash</span>`;
                            }
                        }
                    },
                    {data: 'created_at', name: 'created_at'},
                ],
            });
        });
    </script>
@endpush
