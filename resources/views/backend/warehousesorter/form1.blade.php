@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<div class="form-group{{ $errors->has('role_type') ? ' has-error' : '' }}">
    {{ Form::label('hub_id', 'Hub  ', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
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
{{-- <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
    {{ Form::label('date', 'Date *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::date('date', old('date'), ['type' => 'date','class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
    </div>
    @if ( $errors->has('date') )
        <p class="help-block">{{ $errors->first('date') }}</p>
    @endif
</div> --}}

{{-- <div class="form-group{{ $errors->has('internal_sorter_count') ? ' has-error' : '' }}">
    {{ Form::label('internal_sorter_count', 'Internal Sorter Counts', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('internal_sorter_count', old('internal_sorter_count'), ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('internal_sorter_count') )
        <p class="help-block">{{ $errors->first('internal_sorter_count') }}</p>
    @endif
</div> --}}

{{-- <div class="form-group{{ $errors->has('brooker_sorter_count') ? ' has-error' : '' }}">
    {{ Form::label('brooker_sorter_count', 'Brooker Sorter Counts', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('brooker_sorter_count', old('brooker_sorter_count'), ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('brooker_sorter_count') )
        <p class="help-block">{{ $errors->first('brooker_sorter_count') }}</p>
    @endif
</div> --}}

{{-- <div class="form-group{{ $errors->has('dispensed_route') ? ' has-error' : '' }}">
    {{ Form::label('dispensed_route', 'Dispensed Route', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('dispensed_route', old('dispensed_route'), ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('dispensed_route') )
        <p class="help-block">{{ $errors->first('dispensed_route') }}</p>
    @endif
</div> --}}

{{-- <div class="form-group{{ $errors->has('manager_on_duty') ? ' has-error' : '' }}">
    {{ Form::label('manager_on_duty', 'Manager On Duty', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('manager_on_duty', old('manager_on_duty'), ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('manager_on_duty') )
        <p class="help-block">{{ $errors->first('manager_on_duty') }}</p>
    @endif
</div> --}}

<div class="form-group{{ $errors->has('sorting_time') ? ' has-error' : '' }}">
    {{ Form::label('sorting_time', 'Sorting Time', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('sorting_time', old('sorting_time'), ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('sorting_time') )
        <p class="help-block">{{ $errors->first('sorting_time') }}</p>
    @endif
</div>

<div class="form-group{{ $errors->has('pickup_time') ? ' has-error' : '' }}">
    {{ Form::label('pickup_time', 'Pickup Time', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('pickup_time', old('pickup_time'), ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('pickup_time') )
        <p class="help-block">{{ $errors->first('pickup_time') }}</p>
    @endif
</div>
<div class="form-group{{ $errors->has('delivery_percentage') ? ' has-error' : '' }}">
    {{ Form::label('delivery_percentage', 'Delivery Percentage ', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::Number('delivery_percentage', old('delivery_percentage'), ['class' => 'form-control col-md-7 col-xs-12' , 'required'=>true]) }}
    </div>
    @if ( $errors->has('delivery_percentage') )
        <p class="help-block">{{ $errors->first('delivery_percentage') }}</p>
    @endif
</div>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('warehouse/sorter'), 'Cancel', ['class' => 'btn btn-default']) }}
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
