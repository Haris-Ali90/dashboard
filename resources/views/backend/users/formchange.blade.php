<div class="form-group{{ $errors->has('old_pwd') ? ' has-error' : '' }}">
    {{ Form::label('old_pwd', 'Old Password *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('old_pwd', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('old_pwd') )
        <p class="help-block">{{ $errors->first('old_pwd') }}</p>
    @endif
</div>
<div class="form-group{{ $errors->has('new_pwd') ? ' has-error' : '' }}">
    {{ Form::label('new_pwd', 'New Password *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('new_pwd', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('new_pwd') )
        <p class="help-block">{{ $errors->first('new_pwd') }}</p>
    @endif
</div>
<div class="form-group{{ $errors->has('confirm_pwd') ? ' has-error' : '' }}">
    {{ Form::label('confirm_pwd', 'password_confirmation *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('confirm_pwd', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('confirm_pwd') )
        <p class="help-block">{{ $errors->first('confirm_pwd') }}</p>
    @endif
</div>


<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('dashboard'), 'Cancel', ['class' => 'btn btn-default']) }}
    </div>
</div>

