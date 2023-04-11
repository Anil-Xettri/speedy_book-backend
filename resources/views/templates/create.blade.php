@extends('adminlte::page')

@section('title', 'Add '.$title)

@if(!isset($hideCardFooter))
@section('content_header')
    <div class="navbar bg-white p-2" id="head" style="border-radius: 2px">
        @if(!isset($hideCancel))
            <a href="javascript:history.back();"
               class="btn btn-default btn-sm btn-cancel"><i class="fas fa-arrow-left"></i> Back</a>
        @endif

        <button type="submit"
                id="button_submit" class="button_submit btn btn-sm btn-primary" style="margin-right: 18%!important;"
                name="action" value="submit"><i class="fas fa-plus"></i> Submit
        </button>

        @if(isset($addMoreButton))
            <button type="submit" id="button_submit_add"
                    class="button_submit btn btn-primary"
                    name="action" value="add"><i class="fas fa-plus-circle"></i>
                Submit & Add new
            </button>
        @endif
    </div>
@stop
@endif

@section('css')
    <style>
        label.error {
            color: rgba(234, 52, 52, 0.84);
        }

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

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col">
                    <!-- general form elements -->
                    <div class="card mx-4 mb-4">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}} | Create</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" name="myForm" action="{{route($route.'store')}}"
                              method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                                <input name="add_more" type="hidden" id="add-more" value="{{false}}">
                                @yield('form_content')

                            </div>
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


@section('js')
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js"></script>
    <script>
        let seatDetails = [];
        $('#button_submit').click(
            function (e) {
                var form = $('#form');
                let valid = true;

                $(document).find('.required-field').each(function () {
                    if (!$(this).val()) {
                        $(this).parent().append(`<span style="color: rgba(234, 52, 52, 0.84);;font-weight: bold">This field is required.</span>`);
                        valid = false;
                    }
                });

                if (!form.valid()) {
                    valid = false;
                }

                if (!valid) {
                    return;
                }

                let seats = [...$('.seats')];
                let seatIds = [...$('.seat-ids')];
                let rows = [...$('.rows')];
                let columns = [...$('.columns')];

                rows.forEach(function (row, obj) {
                    let keyValue = {
                        seat: seats[obj].value,
                        seatIds: seatIds[obj].value,
                        row: row.value,
                        column: columns[obj].value,
                    }

                    seatDetails.push(keyValue)

                });

                form.append(`
                        <input name="seats" type="hidden" value='${JSON.stringify(seatDetails)}'>
                    `);

                form.submit();
            }
        );
    </script>

    @stack('scripts')
@endsection
