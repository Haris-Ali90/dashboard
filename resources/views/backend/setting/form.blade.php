@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<div class="form-group{{ $errors->has('hub_id') ? ' has-error' : '' }}">
    {{ Form::label('hub_id', 'Hub  ', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="hub_id" required="">
            @foreach( $hubs as $record )
                @if($record->id==old('hub_id'))
                {
                    <option selected value="{{ $record->id }}"> {{ $record->city_name }}</option>
                }
              
                @else
                <option value="{{ $record->id }}"> {{ $record->city_name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    @if ( $errors->has('hub_id') )
        <p class="help-block">{{ $errors->first('hub_id') }}</p>
    @endif
</div>
<div class="form-group{{ $errors->has('sorting_time') ? ' has-error' : '' }}">
    {{ Form::label('sorting_time', 'Sorting Time *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('sorting_time', old('sorting_time'), ['class' => 'form-control col-md-7 col-xs-12','min'=>'1','required' => 'required']) }}
    </div>
    @if ( $errors->has('sorting_time') )
        <p class="help-block">{{ $errors->first('sorting_time') }}</p>
    @endif
</div>

<div class="form-group{{ $errors->has('pickup_time') ? ' has-error' : '' }}">
    {{ Form::label('pickup_time', 'Pickup Time *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('pickup_time', old('pickup_time'), ['class' => 'form-control col-md-7 col-xs-12','min'=>'1','required' => 'required']) }}
    </div>
    @if ( $errors->has('pickup_time') )
        <p class="help-block">{{ $errors->first('pickup_time') }}</p>
    @endif
</div>

{{--
<div class="form-group{{ $errors->has('delivery_time') ? ' has-error' : '' }}">
    {{ Form::label('delivery_time', 'Delivery Time *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('delivery_time', old('delivery_time'), ['class' => 'form-control col-md-7 col-xs-12','min'=>'1','required' => 'required']) }}
    </div>
    @if ( $errors->has('delivery_time') )
        <p class="help-block">{{ $errors->first('delivery_time') }}</p>
    @endif
</div>
--}}








<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('setting'), 'Cancel', ['class' => 'btn btn-default']) }}
    </div>
</div>
@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
    <script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')
    <script>
        $(document).ready(function () {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                calender_style: "picker_4"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
@endsection
