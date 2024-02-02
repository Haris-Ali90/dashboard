@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    {{ Form::label('title', 'Reason *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12 control-label-left']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::text('title', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required','maxlength' => 255]) }}
    </div>
    @if ( $errors->has('title') )
        <p class="help-block">{{ $errors->first('title') }}</p>
    @endif
</div>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 d-flex justify-content-end">
    {{ Html::link( backend_url('reason'), 'Cancel', ['class' => 'btn sub-ad c-close btn-default']) }}
        {{ Form::submit('Save', ['class' => 'btn sub-ad  btn-primary']) }}
      
    </div>
</div>
@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
    <script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')

@endsection
