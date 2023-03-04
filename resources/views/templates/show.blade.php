@extends('adminlte::page')
@section('css')
    <style>
        body::-webkit-scrollbar {
            display: none; /* Safari and Chrome */
        }

        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .content-header {
            padding-top: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;

            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        .content {
            padding-top: 40px !important;
        }

        #head {
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .1);
        }
    </style>
    @stack('styles')
@stop
@section('title', 'Show '.$title)
@section('content_header')
    <div class="navbar bg-white p-2" id="head" style="border-radius: 2px">
        <a href="javascript:history.back();"
           class="btn btn-default btn-sm btn-cancel"><i class="fas fa-arrow-left"></i> Back</a>

        @if(!isset($hideEdit))
            <a href="{{route($route.'edit', $item->id)}}" class="btn btn-primary btn-sm" style="margin-right: 18%!important;color: white!important;">
                <i class="fa fa-edit"></i> Edit
            </a>
        @endif
    </div>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col">
                    <!-- general form elements -->
                    <div class="card mx-4 mb-4">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}} | Show</h3>
                        </div>

                        <div class="card-body px-5">
                            @yield('form_content')

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('js')
    @stack('scripts')
    <script>
        jQuery(document).ready(function () {
            $('#form input').attr('readonly', true);
            $('#form select').attr('disabled', true);
        });
    </script>
@stop
