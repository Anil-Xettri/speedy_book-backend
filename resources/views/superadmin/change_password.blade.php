@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
    <section class="content ">
        <div class="container-fluid ">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12 col-offset-6 centered">
                    <!-- general form elements -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" action="{{ route('superadmin-password.store') }}"
                              method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $error }}</strong>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif

                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="">Old Password *</label>
                                        <input type="password" class="form-control" required name="old_password" value="{{ old('old_password') }}" placeholder="Enter old Password">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">New Password *</label>
                                        <input type="password" class="form-control" maxlength="8" required name="new_password" value="{{ old('new_password') }}" placeholder="Enter new Password">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Confirm Password *</label>
                                        <input type="password" class="form-control" required name="confirm_password" value="{{ old('confirm_password') }}" placeholder="Confirm Password">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-default float-right">Cancel</button>
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
@stop

@section('css')
{{--    <link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')

@stop
