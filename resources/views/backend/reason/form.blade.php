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
       <div class="row">
       <div class="col-lg-12 col-sm-6 col-xs-12  d-flex justify-content-center">
                {{ Form::submit('Save', ['class' => 'btn c-btn btn-primary']) }}
                {{ Html::link( backend_url('reason'), 'Cancel', ['class' => 'btn d-table c-btn btn-default']) }}
        </div>
       </div>
</div>


@section('JSLibraries')
        <!-- DataTables JavaScript -->
<script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
<script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')

@endsection
