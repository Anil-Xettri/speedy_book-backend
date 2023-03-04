@extends('adminlte::page')


@section('title', $title)

@section('content_header')
    <h1>{{$title}} List</h1>
@stop

@section('css')
    <style>
        body::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
        }
    </style>
    @stack('styles')
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-block p-3">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <section class="col-12 content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class=" card-header">
                                <h3 class="card-title">{{$title}} | Index</h3>
                                @if(!isset($hideCreate))
                                    <div class="float-right">

                                        <a href="{{route($route.'create')}}"
                                           class="btn btn-primary btn-sm float-right">
                                            <i class="fa fa-plus"></i>
                                            <span class="kt-hidden-mobile"> Add</span>
                                        </a>

                                    </div>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @yield('index_content')
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            {{-- </div> --}}
        </section>
    </div>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(document).find('#delete-form').submit();
                    } else if (result.isDismissed) {
                        return false;
                    }
                });
            });
        });
    </script>

    @stack('scripts')
@stop
