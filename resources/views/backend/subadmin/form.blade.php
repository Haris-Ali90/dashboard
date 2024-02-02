@section('CSSLibraries')
        <!-- DataTables CSS -->
        <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection

<div class="col-lg-6 col-md-12 responsive-width c-mb ">
        <div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
        {{ Form::label('full_name', 'Full Name *', ['class'=>'']) }}
        <div >
                {{ Form::text('full_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('full_name') )
                <p class="help-block">{{ $errors->first('full_name') }}</p>
        @endif
</div>
</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {{ Form::label('email', 'Email', ['class'=>'']) }}
        <div >
                {{ Form::email('email', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('email') )
                <p class="help-block">{{ $errors->first('email') }}</p>
        @endif
</div>
</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        {{ Form::label('phone', 'Phone', ['class'=>'']) }}
        <div >
                {{ Form::text('phone', null, ['class' => 'class="date-picker form-control col-md-7 col-xs-12" ','required' => 'required']) }}
        </div>
        @if ( $errors->has('phone') )
                <p class="help-block">{{ $errors->first('phone') }}</p>
        @endif
</div>
</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {{ Form::label('address', 'Address(Optional)', ['class'=>'']) }}
        <div >
                {{ Form::text('address', null, ['class' => 'form-control col-md-7 col-xs-12']) }}
        </div>
        @if ( $errors->has('address') )
                <p class="help-block">{{ $errors->first('address') }}</p>
        @endif
</div>
</div>

<!-- <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
        {{ Form::label('city', 'City *', ['class'=>'']) }}
        <div >
                {{ Form::text('city', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('city') )
                <p class="help-block">{{ $errors->first('city') }}</p>
        @endif
</div> -->
<!-- <div class="form-group{{ $errors->has('emergency_contact') ? ' has-error' : '' }}">
        {{ Form::label('emergency_contact', 'Emergency Contact *', ['class'=>'']) }}
        <div >
                {{ Form::text('emergency_contact', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('emergency_contact') )
                <p class="help-block">{{ $errors->first('emergency_contact') }}</p>
        @endif
</div> -->
<!-- <div class="form-group{{ $errors->has('guardian_name') ? ' has-error' : '' }}">
        {{ Form::label('guardian_name', 'Guardian Name *', ['class'=>'']) }}
        <div >
                {{ Form::text('guardian_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('guardian_name') )
                <p class="help-block">{{ $errors->first('guardian_name') }}</p>
        @endif
</div> -->
<!-- <div class="form-group{{ $errors->has('guardian_phone') ? ' has-error' : '' }}">
        {{ Form::label('guardian_phone', 'Guardian Phone *', ['class'=>'']) }}
        <div >
                {{ Form::text('guardian_phone', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('guardian_phone') )
                <p class="help-block">{{ $errors->first('guardian_phone') }}</p>
        @endif
</div> -->
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('rights') ? ' has-error' : '' }}">
        {{ Form::label('rights', 'Dashboard Permission ', ['class'=>'']) }}
      
                <!-- {{ Form::select('institute_id',  array('1' => 'Anees Hussain', '2' => 'Ahmedabad', '3' => 'Aligarh Institute'),null,['class' => 'form-control col-md-7 col-xs-12']) }} -->
                <select class="js-example-basic-multiple form-control  " name="rights[]"  multiple="multiple">
                        <option value="montreal_dashboard">Amazone Montreal Dashboard</option>
                        <option value="ottawa_dashboard">Amazone Ottawa Dashboard</option>
                                <option value="ctc_dashboard">Ctc Dashboard</option>
                                <option value="grocery_dashboard">Grocery Dashboard</option>
                        <!-- <option value="walmart_dashboard" >Walmart Dashboard</option> -->
                        <option value="loblaws_dashboard" >Loblaws Dashboard</option>
                        <option value="loblawscalgary_dashboard" >Loblaws Calgary Dashboard</option>
                        <option value="loblawshomedelivery_dashboard" >Loblaws Home Delivery Dashboard</option>
                        <option value="logx_dashboard" >Logx Dashboard</option>
                        <option value="wildfork_dashboard" >WildFork Dashboard</option>
                        <option value="complain" >Complain</option>
                </select>
   
        @if ( $errors->has('rights') )
                <p class="help-block">{{ $errors->first('rights') }}</p>
        @endif
</div>
</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('statistics') ? ' has-error' : '' }}">
        {{ Form::label('statistics', 'Hub Permission', ['class'=>'']) }}
     

                <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="statistics[]"  multiple="multiple">
                        @foreach($hubs as $hub)
                                <option value="{{$hub->id}}">{{$hub->city_name}}</option>
                        @endforeach
                </select>
       
        @if ( $errors->has('statistics') )
                <p class="help-block">{{ $errors->first('statistics') }}</p>
        @endif
</div>
</div>



{{--<div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
        {{ Form::label('permissions', 'Permissions ', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
   
                <!-- {{ Form::select('institute_id',  array('1' => 'Anees Hussain', '2' => 'Ahmedabad', '3' => 'Aligarh Institute'),null,['class' => 'form-control col-md-7 col-xs-12']) }} -->
                <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="permissions[]" required="" multiple="multiple">

                    <option value="read"> View</option>
                    <option value="add"> Add</option>
                    <option value="edit"> Edit</option>
                    <option value="delete"> Delete </option>
                </select>
     
        @if ( $errors->has('permissions') )
                <p class="help-block">{{ $errors->first('permissions') }}</p>
        @endif
</div>--}}
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('role_type') ? ' has-error' : '' }}">
        {{ Form::label('role_type', 'Role Type ', ['class'=>'']) }}
      
                <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="role_type" required="">
              
                        @foreach( $role as $record )
                        <option value="{{ $record->id }}"> {{ $record->display_name }}</option>
                        @endforeach
                </select>       
       
        @if ( $errors->has('role_type') )
                <p class="help-block">{{ $errors->first('role_type') }}</p>
        @endif
</div>

</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', 'Password', ['class'=>'']) }}
      
                {{ Form::password('password', ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        
        @if ( $errors->has('password') )
                <p class="help-block">{{ $errors->first('password') }}</p>
        @endif
</div>
</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('confirmpwd') ? ' has-error' : '' }}">
        {{ Form::label('confirmpwd', 'Confirm Password', ['class'=>'']) }}
      
                {{ Form::password('confirmpwd', ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
     
        @if ( $errors->has('confirmpwd') )
                <p class="help-block">{{ $errors->first('confirmpwd') }}</p>
        @endif
</div>
</div>
<div class="col-lg-6 col-md-12 responsive-width c-mb ">
<div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
        {{ Form::label('profile_picture', 'Profile picture', ['class'=>'']) }}
       
                {{ Form::file('profile_picture', null, ['class' => 'form-control prof-pic col-md-7 col-xs-12','required' => 'required']) }}
       
        @if ( $errors->has('profile_picture') )
                <p class="help-block">{{ $errors->first('profile_picture') }}</p>
        @endif
</div>
</div>
<div class="col-lg-12">
<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        {{ Form::label('type', 'Manager', ['class'=>'']) }}
      
                <input type="checkbox" style="height: 19px; width: 19px" name="type" maxlength="150"
                value="manager"
                       class="form-control"/>
      
        @if ( $errors->has('type') )
                <p class="help-block">{{ $errors->first('type') }}</p>
        @endif
</div>
</div>


<div class="col-l2">
<div class="form-group">
        <div class="reder">
                {{ Form::submit('Save', ['class' => 'c-btn']) }}
                {{ Html::link( backend_url('subadmins'), 'Cancel', ['class' => 'btn c-btn']) }}
        </div>
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
