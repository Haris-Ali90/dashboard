@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
    {{ Form::label('full_name', 'Full Name *', ['class'=>' ']) }}
    <div class="  ">
        {{ Form::text('full_name', null, ['class' => 'form-control ','required' => 'required']) }}
    </div>
    @if ( $errors->has('full_name') )
        <p class="help-block">{{ $errors->first('full_name') }}</p>
    @endif
</div>
</div>
<div class="col-lg-6 responsive-width">
    
<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    {{ Form::label('email', 'Email', ['class'=>' ']) }}
    <div class="  ">
        {{ Form::email('email', null, ['class' => 'form-control ','readonly' ]) }}
    </div>
    @if ( $errors->has('email') )
        <p class="help-block">{{ $errors->first('email') }}</p>
    @endif
</div>
</div>
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
    {{ Form::label('phone', 'Mobile', ['class'=>' ']) }}
    <div class="  ">
        {{ Form::text('phone', null, ['class' => 'class="date-picker form-control " ','required' => 'required']) }}
    </div>
    @if ( $errors->has('phone') )
        <p class="help-block">{{ $errors->first('phone') }}</p>
    @endif
</div>
</div>
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
    {{ Form::label('address', 'Address(Optional)', ['class'=>' ']) }}
    <div class="  ">
        {{ Form::text('address', null, ['class' => 'form-control ']) }}
    </div>
    @if ( $errors->has('address') )
        <p class="help-block">{{ $errors->first('address') }}</p>
    @endif
</div>
</div>
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('rights') ? ' has-error' : '' }}">
    {{ Form::label('rights', 'Rights ', ['class'=>' ']) }}
   
        <select class="js-example-basic-multiple form-control " name="rights[]" required=""
                multiple="multiple">
            <option value="statistics" {{(in_array('statistics', $rights)) ? 'Selected' : ''}}>Statistics </option>
            <option value="subadmins" {{(in_array('subadmins', $rights)) ? 'Selected' : ''}}> Sub Admin</option>
            <option value="montreal_dashboard" {{(in_array('montreal_dashboard', $rights)) ? 'Selected' : ''}}> Amazone
                Montreal
            </option>
            <option value="ottawa_dashboard" {{(in_array('ottawa_dashboard', $rights)) ? 'Selected' : ''}}> Amazone
                Ottawa
            </option>
            <option value="ctc_dashboard" {{(in_array('ctc_dashboard', $rights)) ? 'Selected' : ''}}>Ctc</option>
            <option value="walmart_dashboard" {{(in_array('walmart_dashboard', $rights)) ? 'Selected' : ''}}>Walmart
            </option>
            <option value="other_action" {{(in_array('other_action', $rights)) ? 'Selected' : ''}}>Other Action
            </option>
            <option value="complain" {{(in_array('complain', $rights)) ? 'Selected' : ''}}>Complain</option>
        </select>
 
    @if ( $errors->has('rights') )
        <p class="help-block">{{ $errors->first('rights') }}</p>
    @endif
</div>
</div>
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
    {{ Form::label('permissions', 'Permissions ', ['class'=>' ']) }}
    <div class="  ">
    <!-- {{ Form::select('institute_id',  array('1' => 'Anees Hussain', '2' => 'Ahmedabad', '3' => 'Aligarh Institute'),null,['class' => 'form-control ']) }} -->
        <select class="js-example-basic-multiple form-control " name="permissions[]" required=""
                multiple="multiple">
            <option value="read" {{(in_array('read', $permissions)) ? 'Selected' : ''}}> View</option>
            <option value="add" {{(in_array('add', $permissions)) ? 'Selected' : ''}}> Add</option>
            <option value="edit" {{(in_array('edit', $permissions)) ? 'Selected' : ''}}> Edit</option>
            <option value="delete" {{(in_array('delete', $permissions)) ? 'Selected' : ''}}> Delete</option>
        </select>
    </div>
    @if ( $errors->has('permissions') )
        <p class="help-block">{{ $errors->first('permissions') }}</p>
    @endif
</div>
</div>
<!-- <div class="col-lg-6 responsive-width">
{{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    {{ Form::label('password', 'Password', ['class'=>' ']) }}
    <div class="  ">
        {{ Form::password('password', null, ['class' => 'form-control ','required' => 'required']) }}
    </div>
    @if ( $errors->has('password') )
        <p class="help-block">{{ $errors->first('password') }}</p>
    @endif
</div>--}}
</div> -->
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    {{ Form::label('password', 'Password', ['class'=>' ']) }}
  
        {{ Form::password('password', ['class' => 'form-control ']) }}
   
    @if ( $errors->has('password') )
        <p class="help-block">{{ $errors->first('password') }}</p>
    @endif
</div>
</div>
<div class="col-lg-6 responsive-width">
<div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
    {{ Form::label('profile_picture', 'Profile picture', ['class'=>' ']) }}
    <div class="c-prof">
        {{ Form::file('profile_picture', null, ['class' => 'form-control ','required' => 'required']) }}
    </div>
    @if ( $errors->has('profile_picture') )
        <p class="help-block">{{ $errors->first('profile_picture') }}</p>
    @endif 
</div>
</div>
<div class="col-lg-6 ">
<div class="form-group{{ $errors->has('avatar_view') ? ' has-error' : '' }}">
    <div class="prof-box">
        <img onClick="ShowLightBox(this);" src="{{$user->profile_picture}}"  class="avatar" alt="Avatar"/>
    </div>

</div>
</div>



<!-- <div class="form-group{{ $errors->has('confirmpwd') ? ' has-error' : '' }}">
        {{ Form::label('confirmpwd', 'Confirm Password', ['class'=>' ']) }}
        <div class="  ">
                {{ Form::text('confirmpwd', null, ['class' => 'form-control ','required' => 'required']) }}
        </div>
        @if ( $errors->has('confirmpwd') )
    <p class="help-block">{{ $errors->first('confirmpwd') }}</p>
        @endif
        </div> -->
<div class="ln_solid"></div>
<div class="row d-flex justify-content-end">

<div class="form-group">
    <div class="d-flex">
        {{ Form::submit('Save', ['class' => 'btn btn-primary c-btn']) }}
        {{ Html::link( backend_url('/'), 'Cancel', ['class' => 'btn c-btn  btn-default']) }}
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
