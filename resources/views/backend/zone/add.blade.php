@extends( 'backend.layouts.app' )

@section('title', 'Add Zone')

@section('content')

<!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Add Zone</h3>
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
                            <h2>Add Zone</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                        </div>

                        {!! Form::open( ['url' => ['backend/zone/create'], 'method' => 'POST', 'files' => true , 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}
                        @include( 'backend.zone.form' )
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /page content -->


    {{--<div class="row">
        <div class="col-lg-6">
            {!! Form::model($zone, ['url' => ['backend/zone', $zone->id], 'method' => 'PUT', 'class' => '', 'role' => 'form']) !!}
                @include( 'backend.zone.form' )
            {!! Form::close() !!}
        </div>
    </div>--}}


    <!-- /#page-wrapper -->


@endsection
