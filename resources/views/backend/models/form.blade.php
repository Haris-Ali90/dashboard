<?php
$vehicleManufactureModel     = '';
if(isset($vehicleModel))
{
    $vehicleManufactureModel =   $vehicleModel->manufacture_id;
}
?>
<div class="form-group{{ $errors->has('manufacture_id') ? ' has-error' : '' }}">
    {{ Form::label('manufacture_id', 'Vehicle Manufacture', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div id="model_id" class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control col-md-7 col-xs-12"  name="manufacture_id">
            <option value="">Select</option>
            <?php foreach($manufactures as $manufacture){ ?>
            <option <?php if($manufacture->id == $vehicleManufactureModel){ echo "selected";}?> value="<?php echo $manufacture->id;?>"><?php echo $manufacture->name;?></option>
            <?php }?>
        </select>
    </div>
    @if ( $errors->has('manufacture_id') )
        <p class="help-block">{{ $errors->first('manufacture_id') }}</p>
    @endif
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', 'Model Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('name', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('name') )
        <p class="help-block">{{ $errors->first('name') }}</p>
    @endif
</div>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('manufactures'), 'Cancel', ['class' => 'btn btn-default']) }}
    </div>
</div>

