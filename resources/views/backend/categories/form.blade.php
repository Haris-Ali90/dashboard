
<div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
        {{ Form::label('product_name', 'Category Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('product_name', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('product_name') )
                <p class="help-block">{{ $errors->first('product_name') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('product_image') ? ' has-error' : '' }}">
    {{ Form::label('product_image', 'Product Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::file('product_image', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
    </div>
    @if ( $errors->has('product_image') )
        <p class="help-block">{{ $errors->first('product_image') }}</p>
    @endif
</div> 
<input type="hidden" name="entity_type" value="shopping">
<div class="ln_solid"></div>
<div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                {{ Html::link( backend_url('categories'), 'Cancel', ['class' => 'btn btn-default']) }}
        </div>
</div>

