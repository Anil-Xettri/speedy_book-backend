@extends('adminlte::page')


@section('title', 'Edit '.$title)

@section('content_header')
    <div class="navbar p-2" id="head" style="border-radius: 2px">
        @if(!isset($hideCancel))
            <a href="javascript:history.back();"
               class="btn btn-default btn-sm btn-cancel"><i class="fas fa-arrow-left"></i> Back</a>
        @endif

        <button type="submit"
                id="button_submit" class="button_submit btn btn-sm btn-primary" style="margin-right: 18%!important;"
                name="action" value="submit"><i class="fas fa-pen"></i> Update
        </button>
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
                            <h3 class="card-title">{{$title}} | Edit</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" action="{{route($route.'update',$item->id)}}"
                              method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @method('PUT')
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                                @yield('form_content')

                            </div>
{{--                            <div class="card-footer d-flex justify-content-between align-items-center">--}}
{{--                                <div class="col-6 align-items-center">--}}
{{--                                    <button type="submit" id="button_submit" class="btn btn-primary button_submit">--}}
{{--                                        Submit--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                                <div class="col-6 d-flex justify-content-end align-items-center">--}}
{{--                                    <a href="javascript:history.back();" class="btn btn-default btn-cancel float-right">Cancel</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('css')
    <style>
        label.error {
            color: rgba(234, 52, 52, 0.84);
        }
        body::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
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
            padding-top: 40px!important;
        }
        #head {
            box-shadow: 0 2px 4px 0 rgba(0,0,0,.1);
            background-color: rgb(255, 255, 255);
        }
    </style>
    @stack('styles')
@stop
@section('js')
    @yield('ext_js')
    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/localization/messages_ar.min.js"
            integrity="sha512-nb2K94mYysmXkqlnVuBdOagDjQ2brfrCFIbfDIwFPosVjrIisaeYDxPvvr7fsuPuDpqII0fwA51IiEO6GulyHQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js"></script>
    <script>
        let seatDetails = [];
        $('#button_submit').click(
            function (e) {
                var form = $('#form');
                if (!form.valid()) {
                    return;
                }

                let seats = [...$('.seats')];
                let seatIds = [...$('.seat-ids')];
                let rows = [...$('.rows')];
                let columns = [...$('.columns')];

                rows.forEach(function(row, obj){
                    let keyValue = {
                        seat : seats[obj].value,
                        seatIds: seatIds[obj].value,
                        row: row.value,
                        column: columns[obj].value,
                    }

                    seatDetails.push(keyValue)

                });

                form.append(`
                        <input name="seats" type="hidden" value='${ JSON.stringify(seatDetails)}'>
                    `);

                form.submit();
            }
        );
    </script>
@endsection
