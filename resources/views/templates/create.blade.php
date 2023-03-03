@extends('adminlte::page')

@section('title', 'Add '.$title)

@section('content_header')
    <h1> Add {{$title}}</h1>
@stop

@section('css')
    @stack('styles')
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col">
                    <!-- general form elements -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" name="myForm" action="{{route($route.'store')}}"
                              method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
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
                            @if(!isset($hideCardFooter))
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <div class="col-6 d-flex align-items-center">
                                        <button type="submit"
                                                id="button_submit" class="button_submit btn btn-primary"
                                                name="action" value="submit">Submit
                                        </button>

                                        @if(isset($addMoreButton))
                                            <button type="submit" id="button_submit_add"
                                                    class="button_submit btn btn-primary"
                                                    name="action" value="add">
                                                Submit & Add new
                                            </button>
                                        @endif
                                    </div>
                                    @if(!isset($hideCancel))
                                        <div class="col-6 d-flex justify-content-end align-items-center">
                                            <a href="javascript:history.back();"
                                               class="btn btn-default btn-cancel ">Cancel</a>
                                        </div>
                                    @endif
                                </div>
                            @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/localization/messages_ar.min.js"
            integrity="sha512-nb2K94mYysmXkqlnVuBdOagDjQ2brfrCFIbfDIwFPosVjrIisaeYDxPvvr7fsuPuDpqII0fwA51IiEO6GulyHQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('#button_submit').click(
            function (e) {
                var form = $('#form');
                if (!form.valid()) {
                    return;
                }
                form.submit();
            }
        );
    </script>

    @stack('scripts')
@endsection
