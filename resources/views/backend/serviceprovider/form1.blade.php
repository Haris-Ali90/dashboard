@section('CSSLibraries')
        <!-- DataTables CSS -->
<link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<!-- <div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
        {{ Form::label('full_name', 'First Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('full_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('full_name') )
                <p class="help-block">{{ $errors->first('full_name') }}</p>
        @endif
</div> -->
<div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
        {{ Form::label('user_name', 'Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('user_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('user_name') )
                <p class="help-block">{{ $errors->first('user_name') }}</p>
        @endif
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {{ Form::label('email', 'Email', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('email', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('email') )
                <p class="help-block">{{ $errors->first('email') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        {{ Form::label('phone', 'Mobile', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('phone', null, ['class' => 'class="date-picker form-control col-md-7 col-xs-12" ','required' => 'required']) }}
        </div>
        @if ( $errors->has('phone') )
                <p class="help-block">{{ $errors->first('phone') }}</p>
        @endif
</div>
<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {{ Form::label('address', 'Address *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('address', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('address') )
                <p class="help-block">{{ $errors->first('address') }}</p>
        @endif
</div>
<!-- <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
        {{ Form::label('city', 'City *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('city', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('city') )
                <p class="help-block">{{ $errors->first('city') }}</p>
        @endif
</div> -->

<!-- <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
        {{ Form::label('new_password', 'Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('new_password', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('new_password') )
                <p class="help-block">{{ $errors->first('new_password') }}</p>
        @endif
</div> -->
<!-- <div class="form-group{{ $errors->has('confirmpwd') ? ' has-error' : '' }}">
        {{ Form::label('confirmpwd', 'Confirm Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('confirmpwd', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('confirmpwd') )
                <p class="help-block">{{ $errors->first('confirmpwd') }}</p>
        @endif
</div> -->
<!-- <div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
    {{ Form::label('profile_picture', 'Profile picture', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {{ Form::file('profile_picture', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
    </div>
    @if ( $errors->has('profile_picture') )
        <p class="help-block">{{ $errors->first('profile_picture') }}</p>
    @endif
</div> -->

<?php
$RCount =0;
if(isset($institute)) {
?>
<div class="form-group">
    <label for="Image" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
    <div class="col-md-6 col-sm-6 col-xs-12">

        <input name="delete_reports" id="delete_reports" value="0" type="hidden">

        <div style="float: left;padding: 10px 15px; border:1px solid #ddd; min-height:75px">

            <img src="{{ URL::to('/') }}/public/images/institute_logo/{{$institute->logo}}" style="width:50px; max-height:50px">
        </div>
    </div>
</div>
<?php
}
?>
<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
        {{ Html::link( backend_url('sub/admin'), 'Cancel', ['class' => 'btn btn-default']) }}
    </div>
</div>


@section('JSLibraries')
        <!-- DataTables JavaScript -->
<script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
<script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')
    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
@endsection
