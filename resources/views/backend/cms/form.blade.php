<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    {{ Form::label('title', 'Title *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('title', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('title') )
        <p class="help-block">{{ $errors->first('title') }}</p>
    @endif
</div>
<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
    {{ Form::label('content', 'Body *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::textarea('content', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('content') )
        <p class="help-block">{{ $errors->first('content') }}</p>
    @endif
</div>
<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('cms'), 'Cancel', ['class' => 'btn btn-default']) }}
    </div>
</div>

