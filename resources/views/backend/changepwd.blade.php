@extends( 'backend.layouts.app' )

@section('title', 'Add User')

@section('content')



        <!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
 
            </div>


            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">

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
                        <h2>Admin Change Password <small></small></h2>


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

                    {!! Form::open( ['url' => ['changepwd/create/'], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}
                        <div class="form-group c-mb{{ $errors->has('old_pwd') ? ' has-error' : '' }}">
                            {{ Form::label('old_pwd', 'Old Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ Form::text('old_pwd', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
                            </div>
                            @if ( $errors->has('old_pwd') )
                                <p class="help-block">{{ $errors->first('old_pwd') }}</p>
                            @endif
                             </div>
                        <div class="form-group c-mb{{ $errors->has('new_pwd') ? ' has-error' : '' }}">
                            {{ Form::label('new_pwd', 'New Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ Form::text('new_pwd', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
                            </div>
                            @if ( $errors->has('new_pwd') )
                                <p class="help-block">{{ $errors->first('new_pwd') }}</p>
                            @endif
                        </div>
                        <div class="form-group c-mb{{ $errors->has('confirm_pwd') ? ' has-error' : '' }}">
                            {{ Form::label('confirm_pwd', 'Confirm Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ Form::text('confirm_pwd', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
                            </div>
                            @if ( $errors->has('confirm_pwd') )
                                <p class="help-block">{{ $errors->first('confirm_pwd') }}</p>
                            @endif
                        </div>
                        <div class="ln_solid"></div>
                        <div class="row d-flex justify-content-end">
                            <div class="d-flex">
                                {{ Form::submit('Save', ['class' => 'btn c-btn btn-primary']) }}
                                {{ Html::link( backend_url('dashboard'), 'Cancel', ['class' => ' c-btn btn btn-default']) }}
                            </div>
                        </div>

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
