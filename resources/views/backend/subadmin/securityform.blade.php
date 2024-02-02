@section('CSSLibraries')
        <!-- DataTables CSS -->

@endsection


<div class="form-group{{ $errors->has('is_email') ? ' has-error' : '' }}">
        {{ Form::label('is_email', 'Is Email *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                <input  {{$user->is_email == 1 ? 'checked' : ''}} name="is_email"  type="checkbox"  required/>
        </div>
        @if ( $errors->has('is_email') )
                <p class="help-block">{{ $errors->first('is_email') }}</p>
        @endif
</div>

<div class="form-group{{ $errors->has('is_scan') ? ' has-error' : '' }}">
        {{ Form::label('is_scan', 'Is Google Scan', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                <input {{$user->is_scan == 1 ? 'checked' : ''}} name="is_scan" type="checkbox" >
        </div>
        @if ( $errors->has('is_scan') )
                <p class="help-block">{{ $errors->first('is_scan') }}</p>
        @endif
</div>


<div class="ln_solid"></div>
<div class="row d-flex justify-content-end">
        <div class="d-flex">
                {{ Form::submit('Save', ['class' => 'btn c-btn btn-primary']) }}
                {{ Html::link( backend_url('subadmins'), 'Cancel', ['class' => 'btn c-btn btn-default']) }}
        </div>
</div>


@section('JSLibraries')
        <!-- DataTables JavaScript -->
<script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
<script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')

@endsection
