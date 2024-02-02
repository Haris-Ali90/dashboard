<?php
$category = App\Category::all();


?>

<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
        {{ Form::label('category_id', 'Category *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="category_id" name="category_id" class ="form-control col-md-7 col-xs-12">
                    <?php foreach($category as $data ){ ?>
                    <option value="<?php echo $data->id; ?>">
                     <?php echo $data->product_name; ?>   
                    </option>
                     <?php } ?>
                </select>
        </div>
        @if ( $errors->has('category_id') )
                <p class="help-block">{{ $errors->first('category_id') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('service_name') ? ' has-error' : '' }}">
        {{ Form::label('service_name', 'Item Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('service_name', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('service_name') )
                <p class="help-block">{{ $errors->first('service_name') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        {{ Form::label('description', 'Description *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('description', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('description') )
                <p class="help-block">{{ $errors->first('description') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('mandi_price') ? ' has-error' : '' }}">
        {{ Form::label('mandi_price', 'Mandi Price *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('mandi_price', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('mandi_price') )
                <p class="help-block">{{ $errors->first('mandi_price') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('buying_price') ? ' has-error' : '' }}">
        {{ Form::label('buying_price', 'Buying Price *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('buying_price', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('buying_price') )
                <p class="help-block">{{ $errors->first('buying_price') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('selling_price') ? ' has-error' : '' }}">
        {{ Form::label('selling_price', 'Selling Price *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('selling_price', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('selling_price') )
                <p class="help-block">{{ $errors->first('selling_price') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('service_image') ? ' has-error' : '' }}">
    {{ Form::label('service_image', 'Item Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::file('service_image', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
    </div>
    @if ( $errors->has('service_image') )
        <p class="help-block">{{ $errors->first('service_image') }}</p>
    @endif
</div>
<!--<div class="form-group{{ $errors->has('header_image') ? ' has-error' : '' }}">-->
<!--    {{ Form::label('header_image', 'Header Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}-->
<!--    <div class="col-md-6 col-sm-6 col-xs-12">-->
<!--        {{ Form::file('header_image', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}-->
<!--    </div>-->
<!--    @if ( $errors->has('header_image') )-->
<!--        <p class="help-block">{{ $errors->first('header_image') }}</p>-->
<!--    @endif-->
<!--</div>-->
<input type="hidden" name="entity_type" value="shopping">
<div class="ln_solid"></div>
<div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                {{ Html::link( backend_url('services'), 'Cancel', ['class' => 'btn btn-default']) }}
        </div>
</div>

