<?php    

                      
                       // dd($education_types);
                    ?>
@section('CSSLibraries')
        <!-- DataTables CSS -->
<link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection

<!-- <div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
        {{ Form::label('full_name', 'Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('full_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('full_name') )
                <p class="help-block">{{ $errors->first('full_name') }}</p>
        @endif
</div> -->
<div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
        {{ Form::label('user_name', 'Donor Name *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
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
<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', 'Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
               <input class="form-control col-md-7 col-xs-12" required="required" name="password" type="password" id="password">
        </div>
        @if ( $errors->has('password') )
                <p class="help-block">{{ $errors->first('password') }}</p>
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
<!--<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">-->
<!--        {{ Form::label('city', 'City *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}-->
<!--        <div class="col-md-6 col-sm-6 col-xs-12">-->
<!--                {{ Form::text('city', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}-->
<!--        </div>-->
<!--        @if ( $errors->has('city') )-->
<!--                <p class="help-block">{{ $errors->first('city') }}</p>-->
<!--        @endif-->
<!--</div>-->
<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {{ Form::label('address', 'Address *', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::text('address', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('address') )
                <p class="help-block">{{ $errors->first('address') }}</p>
        @endif
</div>
<!-- <div class="form-group{{ $errors->has('confirmpwd') ? ' has-error' : '' }}">
        {{ Form::label('confirmpwd', 'Confirm Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
        <div class="col-md-6 col-sm-6 col-xs-12">
               <input class="form-control col-md-7 col-xs-12" required="required" name="confirmpwd" type="password" id="confirmpwd">
        </div>
        @if ( $errors->has('confirmpwd') )
                <p class="help-block">{{ $errors->first('confirmpwd') }}</p>
        @endif
</div> -->
<!--<div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">-->
<!--    {{ Form::label('profile_picture', 'Profile picture', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}-->
<!--    <div class="col-md-6 col-sm-6 col-xs-12">-->
<!--        {{ Form::file('profile_picture', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}-->
<!--    </div>-->
<!--    @if ( $errors->has('profile_picture') )-->
<!--        <p class="help-block">{{ $errors->first('profile_picture') }}</p>-->
<!--    @endif-->
<!--</div>-->

<?php
$RCount =0;
if(isset($user)) {
?>
<div class="form-group">
        <label for="Image" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
        <div class="col-md-6 col-sm-6 col-xs-12">

                <input name="delete_reports" id="delete_reports" value="0" type="hidden">

                <div style="float: left;padding: 10px 15px; border:1px solid #ddd; min-height:75px">

                        <img src="{{ URL::to('/') }}/public/images/profile_images/{{$user->profile_picture}}" style="width:50px; max-height:50px">
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
                {{ Html::link( backend_url('donor'), 'Cancel', ['class' => 'btn btn-default']) }}
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
                                locale: {
                                format: 'YYYY-MM-DD',
                                },
                                singleDatePicker: true,

                                calender_style: "picker_4"

                        }, function(start, end, label) {
                                console.log(start.toISOString(), end.toISOString(), label);
                        });
                });
        </script>
@endsection
