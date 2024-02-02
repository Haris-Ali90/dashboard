
<?php
$zones = App\Zone::all();
?>
<div class="form-group{{ $errors->has('area_name') ? ' has-error' : '' }}">
        {{ Form::label('area_name', 'Area Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('area_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('area_name') )
                <p class="help-block">{{ $errors->first('area_name') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('zone_id') ? ' has-error' : '' }}">
        {{ Form::label('zone_id', 'Zone Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
               <!--  {{ Form::text('zone_id', null, ['class' => 'form-control col-md-7 col-xs-12']) }} -->
                <select class="form-control" id="zone-select" required="required" name="zone_id">
                     @foreach ($zones as $value)
                        <option value="{{ $value->id}}">{{ $value->zone_name }}</option>
                     @endforeach
                </select>
        </div>
        @if ( $errors->has('zone_id') )
                <p class="help-block">{{ $errors->first('zone_id') }}</p>
        @endif
</div>

<!-- <div class="form-group{{ $errors->has('product_image') ? ' has-error' : '' }}">
    {{ Form::label('product_image', 'Product Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::file('product_image', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
    </div>
    @if ( $errors->has('product_image') )
        <p class="help-block">{{ $errors->first('product_image') }}</p>
    @endif
</div> --> 
<input type="hidden" name="entity_type" value="shopping">
<div class="ln_solid"></div>
<div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                {{ Html::link( backend_url('area'), 'Cancel', ['class' => 'btn btn-default']) }}
        </div>
</div>

