@extends('templates.index')

@section('title', 'Theaters')

@section('content_header')
    <h1>Theaters</h1>


@stop

@section('ext_css')
@stop

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
            <tr class="text-left text-capitalize">
                <th>#id</th>
                <th>name</th>
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
                ajax: "{{ route('theaters.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {
                        data: 'status', name: 'status', render: function (data, type, full, meta) {
                            switch (data) {
                                case ('Active'):
                                    return `<span class="badge badge-success">Active</span>`;
                                    break;
                                case ('Inactive'):
                                    return `<span class="badge badge-secondary">Inactive</span>`;
                                    break;
                                default:
                                    return `<span class="badge badge-success">Active</span>`;
                            }
                        }
                    },
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endpush
