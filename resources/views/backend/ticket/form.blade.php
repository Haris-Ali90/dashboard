
<?php
$zones = App\Zone::all();
$categories = App\Category::all();
$area = App\Area::all();
?>
<div class="form-group{{ $errors->has('area_id') ? ' has-error' : '' }}">
        {{ Form::label('area_id', 'Area *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
               <!--  {{ Form::text('zone_id', null, ['class' => 'form-control col-md-7 col-xs-12']) }} -->
                <select class="form-control" id="zone-select" required="required" name="area_id">
                     @foreach ($area as $value)
                        <option value="{{ $value->id}}">{{ $value->area_name }}</option>
                     @endforeach
                </select>
        </div>
        @if ( $errors->has('area_id') )
                <p class="help-block">{{ $errors->first('area_id') }}</p>
        @endif
</div>

<div class="form-group{{ $errors->has('zone_id') ? ' has-error' : '' }}">
        {{ Form::label('zone_id', 'Zone *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
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

<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
        {{ Form::label('category_id', 'Category *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
               <!--  {{ Form::text('zone_id', null, ['class' => 'form-control col-md-7 col-xs-12']) }} -->
                <select class="form-control" id="zone-select" required="required" name="category_id">
                     @foreach ($categories as $value)
                        <option value="{{ $value->id}}">{{ $value->product_name }}</option>
                     @endforeach
                </select>
        </div>
        @if ( $errors->has('category_id') )
                <p class="help-block">{{ $errors->first('category_id') }}</p>
        @endif
</div>

<div class="form-group{{ $errors->has('NIC_number') ? ' has-error' : '' }}">
        {{ Form::label('NIC_number', 'NIC No *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('NIC_number', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('NIC_number') )
                <p class="help-block">{{ $errors->first('NIC_number') }}</p>
        @endif
</div>

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        {{ Form::label('description', 'Description *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('description', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('description') )
                <p class="help-block">{{ $errors->first('description') }}</p>
        @endif
</div>

<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {{ Form::label('address', 'Address *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('address', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('address') )
                <p class="help-block">{{ $errors->first('address') }}</p>
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
                {{ Html::link( backend_url('ticket'), 'Cancel', ['class' => 'btn btn-default']) }}
        </div>
</div>

