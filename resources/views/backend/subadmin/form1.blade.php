@section('CSSLibraries')
<!-- DataTables CSS -->
<link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
<div class="col-lg-6">
    <div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
        {{ Form::label('full_name', 'Full Name *', ['class'=>' ']) }}

        {{ Form::text('full_name', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}

        @if ( $errors->has('full_name') )
        <p class="help-block">{{ $errors->first('full_name') }}</p>
        @endif
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {{ Form::label('email', 'Email', ['class'=>' ']) }}

        {{ Form::email('email', null, ['class' => 'form-control col-md-7 col-xs-12','readonly' ]) }}

        @if ( $errors->has('email') )
        <p class="help-block">{{ $errors->first('email') }}</p>
        @endif
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        {{ Form::label('phone', 'Phone', ['class'=>' ']) }}

        {{ Form::text('phone', null, ['class' => 'class="date-picker form-control col-md-7 col-xs-12" ','required' =>
        'required']) }}

        @if ( $errors->has('phone') )
        <p class="help-block">{{ $errors->first('phone') }}</p>
        @endif
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {{ Form::label('address', 'Address(Optional)', ['class'=>' ']) }}

        {{ Form::text('address', null, ['class' => 'form-control col-md-7 col-xs-12']) }}

        @if ( $errors->has('address') )
        <p class="help-block">{{ $errors->first('address') }}</p>
        @endif
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group{{ $errors->has('rights') ? ' has-error' : '' }}">
        {{ Form::label('rights', 'Dashboard Permission ', ['class'=>' ']) }}

        <!-- {{ Form::select('institute_id',  array('1' => 'Anees Hussain', '2' => 'Ahmedabad', '3' => 'Aligarh Institute'),null,['class' => 'form-control col-md-7 col-xs-12']) }} -->
        <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="rights[]" multiple="multiple">
            <option value="montreal_dashboard" {{(in_array('montreal_dashboard', $rights)) ? 'Selected' : '' }}> Amazone
                Montreal Dashboard
            </option>
            <option value="ottawa_dashboard" {{(in_array('ottawa_dashboard', $rights)) ? 'Selected' : '' }}> Amazone
                Ottawa Dashboard
            </option>
            <option value="ctc_dashboard" {{(in_array('ctc_dashboard', $rights)) ? 'Selected' : '' }}>Ctc Dashboard
            </option>
            <!-- <option value="walmart_dashboard" {{(in_array('walmart_dashboard', $rights)) ? 'Selected' : ''}}>Walmart Dashboard
            </option> -->


            <option value="grocery_dashboard" {{(in_array('grocery_dashboard', $rights)) ? 'Selected' : '' }}>Grocery
                Dashboard</option>



            <option value="loblaws_dashboard" {{(in_array('loblaws_dashboard', $rights)) ? 'Selected' : '' }}>Loblaws
                Dashboard
            </option>
            <option value="loblawscalgary_dashboard" {{(in_array('loblawscalgary_dashboard', $rights)) ? 'Selected' : ''
                }}>Loblaws Calgary Dashboard
            </option>
            <option value="loblawshomedelivery_dashboard" {{(in_array('loblawshomedelivery_dashboard', $rights))
                ? 'Selected' : '' }}>Loblaws Home Delivery Dashboard
            </option>
            <option value="complain" {{(in_array('complain', $rights)) ? 'Selected' : '' }}>Complain</option>
            <option value="logx_dashboard" {{(in_array('logx_dashboard', $rights)) ? 'Selected' : '' }}>Logx Dashboard
            </option>
            <option value="wildfork_dashboard" {{(in_array('wildfork_dashboard', $rights)) ? 'Selected' : '' }}>WildFork
                Dashboard
            </option>
        </select>

        @if ( $errors->has('rights') )
        <p class="help-block">{{ $errors->first('rights') }}</p>
        @endif
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group{{ $errors->has('statistics') ? ' has-error' : '' }}">
        {{ Form::label('statistics', 'Hub Permission', ['class'=>' ']) }}

        <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="statistics[]"
            multiple="multiple">
            @foreach($hubs as $hub)
            <option value="{{$hub->id}}" {{(in_array($hub->id, $statistics)) ? 'Selected' : ''}} >{{$hub->city_name}}
            </option>
            @endforeach
        </select>

        @if ( $errors->has('statistics') )
        <p class="help-block">{{ $errors->first('statistics') }}</p>
        @endif
    </div>
</div>


<div class="col-lg-6">
    <div class="form-group{{ $errors->has('role_type') ? ' has-error' : '' }}">
        {{ Form::label('role_type', 'Role Type ', ['class'=>' ']) }}

        <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="role_type" required="">
            @foreach( $role as $record )
            <option value="{{ $record->id }}" {{ ($record->id == $user->role_type) ? "selected" : "" }}> {{
                $record->display_name }}</option>
            @endforeach
        </select>

        @if ( $errors->has('role_type') )
        <p class="help-block">{{ $errors->first('role_type') }}</p>
        @endif
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', 'Password', ['class'=>' ']) }}

        {{ Form::password('password', ['class' => 'form-control col-md-7 col-xs-12']) }}
    </div>
    @if ( $errors->has('password') )
    <p class="help-block">{{ $errors->first('password') }}</p>
    @endif
</div>
<div class="col-lg-6">
    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        {{ Form::label('type', 'Manager', ['class'=>' ']) }}

        <input type="checkbox" style="height: 19px; width: 19px" name="type" maxlength="150" {{ $user->type == 'manager'
        ? 'checked' : '' }}
        value="manager"
        class="form-control"/>

        @if ( $errors->has('type') )
        <p class="help-block">{{ $errors->first('type') }}</p>
        @endif
    </div>

</div>
 <div class="col-lg-6">
    <div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
        {{ Form::label('profile_picture', 'Profile picture', ['class'=>' ']) }}
        <div>
            {{ Form::file('profile_picture', null, ['class' => 'form-control col-md-7 col-xs-12','required' =>
            'required']) }}
        </div>
        @if ( $errors->has('profile_picture') )
        <p class="help-block">{{ $errors->first('profile_picture') }}</p>
        @endif
    </div>
</div>



<div class="col-lg-6">
    <div class="form-group{{ $errors->has('avatar_view') ? ' has-error' : '' }}">
        <div>
            <img onClick="ShowLightBox(this);" src="{{$user->profile_picture}}"
                style="width:50px;height:50px; margin-left: 51.5%;" class="avatar" alt="Avatar" />
        </div>

    </div>
</div>
    <div class="ln_solid"></div>
  <div class="row d-flex justify-content-end">
    <div class="form-group">
        <div class="d-flex">
            {{ Form::submit('Save', ['class' => 'btn c-btn btn-primary']) }}
            {{ Html::link( backend_url('subadmins'), 'Cancel', ['class' => 'btn c-btn btn-default']) }}
        </div>
    </div>
  </div>
</div> 
</div>
{{--<div class="form-group{{ $errors->has('permissions') ? ' has-error' : '' }}">
    {{ Form::label('permissions', 'Permissions ', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}

    <!-- {{ Form::select('institute_id',  array('1' => 'Anees Hussain', '2' => 'Ahmedabad', '3' => 'Aligarh Institute'),null,['class' => 'form-control col-md-7 col-xs-12']) }} -->
    <select class="js-example-basic-multiple form-control col-md-7 col-xs-12" name="permissions[]" required=""
        multiple="multiple">
        <option value="read" {{(in_array('read', $permissions)) ? 'Selected' : '' }}> View</option>
        <option value="add" {{(in_array('add', $permissions)) ? 'Selected' : '' }}> Add</option>
        <option value="edit" {{(in_array('edit', $permissions)) ? 'Selected' : '' }}> Edit</option>
        <option value="delete" {{(in_array('delete', $permissions)) ? 'Selected' : '' }}> Delete</option>
    </select>

    @if ( $errors->has('permissions') )
    <p class="help-block">{{ $errors->first('permissions') }}</p>
    @endif
</div>--}}



<!-- <div class="form-group{{ $errors->has('confirmpwd') ? ' has-error' : '' }}">
        {{ Form::label('confirmpwd', 'Confirm Password', ['class'=>' ']) }}
        <div >
                {{ Form::text('confirmpwd', null, ['class' => 'form-control col-md-7 col-xs-12','required' => 'required']) }}
        </div>
        @if ( $errors->has('confirmpwd') )
    <p class="help-block">{{ $errors->first('confirmpwd') }}</p>
        @endif
        </div> -->

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