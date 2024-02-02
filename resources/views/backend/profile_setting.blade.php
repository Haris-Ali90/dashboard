@extends( 'backend.layouts.app' )

@section('title', 'Update Profile')

@section('content')

        <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Update Profile</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                @if ( $errors->count() )
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    There was an error while saving your form, please review below.
                </div>
                @endif

                @include( 'backend.layouts.notification_message' )

                <div class="row">
                    <div class="col-lg-6">
                        {!! Form::model($user, ['method' => 'POST', 'class' => '', 'role' => 'form']) !!}
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                {{ Form::label('first_name', 'First Name', ['class'=>'control-label']) }}
                                {{ Form::text('first_name', null, ['class' => 'form-control']) }}
                                @if ( $errors->has('first_name') )
                                    <p class="help-block">{{ $errors->first('first_name') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                {{ Form::label('last_name', 'Last Name', ['class'=>'control-label']) }}
                                {{ Form::text('last_name', old('last_name'), ['class' => 'form-control']) }}
                                @if ( $errors->has('last_name') )
                                    <p class="help-block">{{ $errors->first('last_name') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                {{ Form::label('email', 'Email', ['class'=>'control-label']) }}
                                {{ Form::email('email', null, ['class' => 'form-control']) }}
                                @if ( $errors->has('email') )
                                    <p class="help-block">{{ $errors->first('email') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                {{ Form::label('password', 'Password', ['class'=>'control-label']) }}
                                {{ Form::password('password', ['class' => 'form-control']) }}
                                @if ( $errors->has('password') )
                                    <p class="help-block">{{ $errors->first('password') }}</p>
                                @endif
                            </div>

                            {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                            {{ Html::link( backend_url('/dashboard'), 'Cancel', ['class' => 'btn btn-default']) }}
                        {!! Form::close() !!}
                    </div>
                </div>

        </div>
        <!-- /#page-wrapper -->

@endsection