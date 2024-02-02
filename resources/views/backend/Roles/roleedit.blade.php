@extends( 'backend.layouts.app' )

@section('title', 'Edit Role')
@section('content')

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <!-- <h3>Edit Role</h3> -->
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="x_panel">

                        <div class="x_title">
                            <h2>Edit Role</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                        </div>
                        @include( 'backend.layouts.notification_message' )
                        {!! Form::model($role, ['url' => ['role',$role->id], 'method' => 'PUT','files' => true , 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}
                        @include( 'backend.Roles.form1' )
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /page content -->
@endsection
