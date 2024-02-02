@extends( 'backend.layouts.app' )

@section('title', 'Change Password')

@section('content')



        <!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Change Password</h3>
            </div>


            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    {{--<div class="input-group">--}}
                        {{--<input type="text" class="form-control" placeholder="Search for...">--}}
                            {{--<span class="input-group-btn">--}}
                      {{--<button class="btn btn-default" type="button">Go!</button>--}}
                    {{--</span>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                    @if ( $errors->count() )
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            There was an error while saving your form, please review below.
                        </div>
                    @endif

                    @include( 'backend.layouts.notification_message' )

                    <div class="x_title">
                        <h2>Change Password<small>Admin change Password</small></h2>


                        {{--<ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>--}}
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />

                    </div>

                    {!! Form::open( ['url' => ['backend/update/password'], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}
                    @include( 'backend.users.formchange' )
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /page content -->


{{--<div class="row">
    <div class="col-lg-6">
        {!! Form::model($user, ['url' => ['backend/user', $user->id], 'method' => 'PUT', 'class' => '', 'role' => 'form']) !!}
            @include( 'backend.users.form' )
        {!! Form::close() !!}
    </div>
</div>--}}


        <!-- /#page-wrapper -->


@endsection
