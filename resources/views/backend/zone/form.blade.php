
<div class="form-group{{ $errors->has('location_latitude') ? ' has-error' : '' }}">
        {{ Form::label('location_latitude', 'Latitude *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('location_latitude', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('location_latitude') )
                <p class="help-block">{{ $errors->first('location_latitude') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('location_longitude') ? ' has-error' : '' }}">
        {{ Form::label('location_longitude', 'Longitude *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('location_longitude', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('location_longitude') )
                <p class="help-block">{{ $errors->first('location_longitude') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('radius') ? ' has-error' : '' }}">
        {{ Form::label('radius', 'Radius *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('radius', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('radius') )
                <p class="help-block">{{ $errors->first('radius') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('zone_name') ? ' has-error' : '' }}">
        {{ Form::label('zone_name', 'Zone Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('zone_name', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('zone_name') )
                <p class="help-block">{{ $errors->first('zone_name') }}</p>
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
                {{ Html::link( backend_url('zones'), 'Cancel', ['class' => 'btn btn-default']) }}
        </div>
</div>
<style>
#map {
  height: 400px;
  width: 100%;
 }
</style>
<div id="map"></div>
<script>
      function initMap() {
  // The location of Uluru
  var uluru = {lat: 24.8607, lng: 67.0011};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 4, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
}

</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKHrwoZ6ST8RDWM_ZEnzizExDzBLnH8As&callback=initMap">
</script>