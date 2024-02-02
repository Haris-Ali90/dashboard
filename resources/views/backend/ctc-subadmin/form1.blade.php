@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection

<div class="col-md-6 col-sm-6 from-input-col">
    <div class="form-group">
        <label class="control-label">Full Name *</label>
        {{ Form::text('full_name', null, ['class' => 'form-control','required' => 'required']) }}
        @if ($errors->has('full_name'))
            <span class="help-block">
                                        <strong>{{ $errors->first('full_name') }}</strong>
                                        </span>
        @endif
    </div>
</div>


<div class="col-md-6 col-sm-6 from-input-col">
    <div class="form-group">
        <label class="control-label">Email</label>
        {{ Form::email('email', null, ['class' => 'form-control ','readonly' ]) }}
        @if ($errors->has('email'))
            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                        </span>
        @endif
    </div>
</div>

<div class="col-md-6 col-sm-6 from-input-col">
    <div class="form-group">
        <label class="control-label">Phone *</label>
        {{ Form::text('phone', null, ['class' => "form-control" ,'required' => 'required']) }}
        @if ($errors->has('phone'))
            <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
        @endif
    </div>
</div>

<div class="col-md-6 col-sm-6 from-input-col">
    <div class="form-group">
        <label class="control-label">Address(Optional)</label>
        {{ Form::text('address', null, ['class' => 'form-control ']) }}
        @if ($errors->has('address'))
            <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                        </span>
        @endif
    </div>
</div>

<div class="col-md-6 col-sm-6 from-input-col">
    <div class="form-group">
        <label class="control-label">Password</label>
        {{ Form::password('password', ['class' => 'form-control ']) }}
        @if ($errors->has('password'))
            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                        </span>
        @endif
    </div>
</div>



<div class="col-md-6 col-sm-6 from-input-col">
    <div class="form-group">
        <label class="control-label">Profile picture</label>
        {{ Form::file('profile_picture', null, ['class' => 'form-control ','required' => 'required']) }}
        @if ($errors->has('profile_picture'))
            <span class="help-block">
                                        <strong>{{ $errors->first('profile_picture') }}</strong>
                                        </span>
        @endif
    </div>

        <div class="col-md-6 col-sm-6 from-input-col">
            <img  onClick="ShowLightBox(this);" src="{{$user->profile_picture}}" style = "width:50px;height:50px; " class="avatar" alt="Avatar"/>
        </div>

    </div>


<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12 text-right">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('ctc/subadmins'), 'Cancel', ['class' => 'btn btn-default']) }}
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
