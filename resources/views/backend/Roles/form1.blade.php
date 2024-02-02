@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
    <input type="hidden" name="id" value="{{$role->id}}" />
    {{ Form::label('display_name', 'Name*', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('display_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
    </div>
    @if ( $errors->has('display_name') )
        <p class="help-block">{{ $errors->first('display_name') }}</p>
    @endif
</div>
<div class="ln_solid"></div>
<div class="form-group">
    <div class="d-flex justify-content-center">
        {{ Form::submit('Save', ['class' => 'btn sub-ad btn-primary']) }}
        {{ Html::link( backend_url('role'), 'Cancel', ['class' => 'btn sub-ad c-close btn-default']) }}
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
