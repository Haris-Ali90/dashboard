@extends( 'backend.layouts.app' )

@section('title', 'Edit Right | Right Permissions')
@section('CSSLibraries')
    <style>
        .inline-form-btn-margin
        {
            margin-top: 26px;
        }
        .table-top-form-row {
            border: 2px solid #e6e9ed;
            padding-bottom: 15px;
        }
        .portal-permission-wraper {
            border: 2px solid #eee;
            margin: 5px 0px;
        }
        .main-section-heading {
            background-color: #c6dd38;
            padding: 5px 15px;
            color: #000;
        }
        .rights-permission-main-row .from-input-wraper {
            margin: 10px 0px;
            border: 1px dashed #eee;
        }
        .portal-name-heading-row {
            margin: 10px 0px;
            background-color: #3e3e3e;
            padding: 5px 0px 0px 0px;
        }
        .portal-name-heading h2 {
            font-size: 15px;
            color: #fff;
            margin: 0px;
        }
        .permissions-checkbox-all {
            margin: 0px 0px 0px 0px !important;
        }
        .portal-check-all-wrap label {
            position: relative;
            color: #fff;
            bottom: 2px !important;
            padding: 0px !important;
        }
        .section-heading span {
            display: inline-block;
            background-color: #eee;
            margin-top: 5px;
            padding: 3px 5px;
            font-weight: 700;
            color: black;
        }
		.from-input-col .control-label {
			text-transform: capitalize;
		}
    </style>
@endsection

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Manage Rights & Permissions<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="">
                            <div class="row">
                                <h2 class="main-section-heading">Edit Right</h2>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        @include( 'backend.layouts.notification_message' )
                        <?php
                        // static variables
                        $old_selected_options = $rights->GetAttachedPlans->pluck('role_name')->first();
                        $old_selected_options = str_replace(strtolower($rights->display_name).'_',"",$old_selected_options);
                        //dd($portals,$rights->GetAttachedPlans);
                        ?>
                        <form class="form-horizontal table-top-form-from"  method="POST" action="{{route('right.update',$rights->id)}}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            <input type="hidden" name="id" value="{{old('id',$rights->id)}}">
                            {{--looping on every edit rights--}}
                            @foreach($rights->GetAttachedPlans as $key => $data)
                                <input type="hidden" name="{{strtolower($portals[$data->type])}}_edit_id" value="{{old('id',$data->id)}}">
                            @endforeach

                            <input type="hidden" class="slug-name" name="slug_name" value="{{old('slug_name',$rights->GetAttachedPlans)}}" />
                            <input type="hidden" name="old_seletec_options" value="{{old('old_seletec_options',$old_selected_options)}}" />
                            <input type="hidden" class="seletec_options" name="seletec_options" value="{{old('seletec_options',$old_selected_options)}}" />

                            <!--table-top-form-row-open-->
                            <div class="row table-top-form-row">
                                <!--table-top-form-col-warp-open-->
                                {{--<div class="col-sm-3 col-md-3 table-top-form-col-warp">--}}
                                    {{--<label class="control-label">Portal</label>--}}
                                    {{--<input name="portal_name_view" type="text" value="{{ old('portal_name_view',$portals)}}" class="form-control"  readonly>--}}
                                {{--</div>--}}

                            <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">Portal*</label>
                                    <select class="form-control portal_name js-example-basic-multiple"  name="portal_name[]" multiple required >
                                        {{--<option value="">Select an option</option>--}}
                                        @foreach($portals as $key => $portal)
                                            <option  value="{{$key}}">{{$portal}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">Right Name*</label>
                                    <input name="right_name"  type="text" value="{{ old('right_name',$rights->display_name)}}" class="form-control right_name" required>
                                </div>

                            </div>
                            <!--table-top-form-row-close-->

                            <!--rights-permission-mian-row-open-->
                            <div class="row rights-permission-main-row">
                                <div class="col-sm-12">
                                    <div class="row">
                                    <h2 class="main-section-heading" >Permissions</h2>
                                    </div>
                                </div>

                                @foreach($permissions_static_data as $portal_name => $permissions_static)
                                    <?php
                                        $selected_permission_array = (isset($selected_permissions[$portal_name]))? $selected_permissions[$portal_name] :[];

                                    ?>
                                    <!--portal-permission-wraper-open-->
                                        <div class="col-sm-12 portal-permission-wraper  {{strtolower($portal_name)}}">

                                            <!--portal-name-heading-row-open-->
                                            <div class="row  portal-name-heading-row">
                                                <div class="col-xs-7  portal-name-heading">
                                                    <h2>{{ucwords($portal_name)}}</h2>
                                                </div>
                                                <div class="col-xs-5 portal-check-all-wrap text-right">
                                                    <input type="checkbox"  class="permissions-checkbox-all" value="{{$portal_name}}" />
                                                    <label class="control-label">Check All</label>
                                                </div>
                                            </div>
                                            <!--portal-name-heading-row-close-->

                                        @foreach($permissions_static as $permission_label => $permissions)
                                            <!--from-input-wraper-open-->
                                                <div class="row from-input-wraper">
                                                    <div class="col-md-12">
                                                        <p class="section-heading"><span>{{$permission_label}}</span></p>
                                                    </div>
                                                @foreach($permissions as  $name =>  $permission)
                                                    <!--from-input-col-open-->
                                                        <div class="col-md-3 col-sm-3 from-input-col">
                                                            <div class="form-group">
                                                                <input type="checkbox" name="{{$portal_name}}_permissions[]" class="permissions-checkbox {{$portal_name}}-permissions-checkbox"  value="{{ $permission }}" data-portal-name="{{$portal_name}}" @if(check_permission_exist($permission,$selected_permission_array)) checked @endif />
                                                                <label class="control-label">{{$name}}</label>
                                                            </div>
                                                        </div>
                                                        <!--from-input-col-close-->
                                                    @endforeach
                                                </div>
                                                <!--from-input-wraper-close-->
                                            @endforeach

                                        </div>
                                        <!--portal-permission-wraper-open-->
                                @endforeach

                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-2 table-top-form-col-warp inline-form-btn-margin">
                                    <button class="btn orange btn-primary form-submit-btn"  > Save </button>
                                    <a href="{{backend_url('right')}}"class="btn orange btn-default"  > Cancel </a>
                                </div>
                                <!--table-top-form-col-warp-close-->

                            </div>
                            <!--rights-permission-main-row-close-->

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
    <script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')
    <script>
        $(document).ready(function() {

            let right_form_handler =
                {
                    "postfixes":{
                        "permission_checkbox_name":"-permissions-checkbox"
                    },
                    "static_data":{
                        "Selected_portals_names":[],
                        "Portal_names":{!! json_encode(array_keys($permissions_static_data)) !!},
                    },
                    "methods":{
                        "resetCheckBox":function (class_name = 'all' , condition = true) {
                            if(class_name == 'all')
                            {
                                $(".permissions-checkbox").prop("checked", false);
                            }
                            else if(condition == false)
                            {
                                $('.permissions-checkbox').not(class_name).prop("checked", false);
                            }
                            else
                            {
                                $(class_name).prop("checked", false);
                            }
                        },
                        "createAndUpdateSlugName":function (values) {

                        },
                        "hideForm":function () {
                            $('.portal-permission-wraper').addClass('hide');
                        },
                        "showFormSection":function (el) {
                            $(el).removeClass('hide');
                        },
                        "getSelectSelectedData":function (el) {
                            var returnObj = {};
                            returnObj.val = el.val();
                            returnObj.text = el.find('option:selected').text();
                            return  returnObj;

                        },
                        "getSelect2multiSelectedData":function (el) {
                            var returnObj = [];
                            var select2_data = el.select2('data');
                            select2_data.forEach(function (value) {
                                returnObj.push({"value":value.id,"text":value.text});

                            });
                            return  returnObj;

                        },
                        "makeOptionSelectedAndTrige":function (el,val) {
                            $(el).val(val).trigger('change');
                        },
                        "formSubmitHndller":function () {
                            let return_responce = true;
                            // validations
                            let right_name  = $('.right_name').val();
                            if(right_name == '')
                            {
                                alert('Please enter right name !');
                                return false;
                            }

                            // seleceted portals names @array
                            var seleceted_portals  = right_form_handler.static_data.Selected_portals_names

                            // checking every selected portals  have any permission  or not
                            let alert_message = '';
                            seleceted_portals.forEach(function (portal_name) {
                                let checked_permissions  = $('.'+portal_name+'-permissions-checkbox').is(':checked');
                                if(!checked_permissions)
                                {
                                    alert_message+= 'Please select at least one permission for this portal "'+portal_name+'" \n';
                                    return_responce  = false;
                                }
                            });

                            if(alert_message != '')
                            {
                                alert(alert_message);
                            }


                            // adding slug name
                            $('.slug-name').val(right_name+'_'+right_form_handler.static_data.Selected_portals_names.join('_'));
                            // adding seleted options
                            $('.seletec_options').val(right_form_handler.static_data.Selected_portals_names.join('_'));
                            return return_responce;
                        },
                        "setContentHeight": function () {
                            // reset height
                            $RIGHT_COL.css('min-height', $(window).height());

                            var bodyHeight = $BODY.outerHeight(),
                                footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
                                leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
                                contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

                            // normalize content
                            contentHeight -= $NAV_MENU.height() + footerHeight;

                            $RIGHT_COL.css('min-height', contentHeight);
                        },
                        "portalCheckedAllHandler":function (portals = [] , handle_type = true)
                        {
                            // checking data type of portals
                            if(typeof portals == "string")
                            {
                                $('.'+portals).find('.permissions-checkbox').prop("checked", handle_type);
                            }
//                            else if(typeof portals == "object")
//                            {
//
//                            }
                        },
                        "portalCheckedAllHandlerForChild":function (portals_name) {
                            console.log(portals_name);
                            // checking data type of portals
                            if(typeof portals_name == "string")
                            {
                                checked_length =  $('.'+portals_name+'-permissions-checkbox:not(:checked)').length;
                                // checking if one check box is not checked then remove check all
                                if(checked_length > 0)
                                {
                                    $('.'+portals_name).find('.permissions-checkbox-all').prop('checked',false);
                                }
                                else
                                {
                                    $('.'+portals_name).find('.permissions-checkbox-all').prop('checked',true);
                                }
                            }
                            else if(typeof portals_name == "object")
                            {
                                for(var key in portals_name)
                                {
                                    checked_length = 0;
                                    var loop_value = portals_name[key];
                                    checked_length =  $('.'+loop_value+'-permissions-checkbox:not(:checked)').length;
                                    // checking if one check box is not checked then remove check all
                                    if(checked_length > 0)
                                    {
                                        $('.'+loop_value).find('.permissions-checkbox-all').prop('checked',false);
                                    }
                                    else
                                    {
                                        $('.'+loop_value).find('.permissions-checkbox-all').prop('checked',true);
                                    }

                                }
                            }
                        }

                    },
                    "element_instances": {
                        "form":$('form').submit(function (e) {
                            return right_form_handler.methods.formSubmitHndller(e)
                        }),
                        "permissions_checkbox_all":$('.permissions-checkbox-all').click(function () {
                            // gatting values
                            var permissions_checkbox_all_el  = $(this);
                            // calling fn
                            right_form_handler.methods.portalCheckedAllHandler(permissions_checkbox_all_el.val(),permissions_checkbox_all_el.is(':checked'));

                        }),
                        "permissions_checkbox":$('.permissions-checkbox').click(function () {
                            // gatting values
                            var permissions_checkbox_el  = $(this);
                            // calling fn
                            right_form_handler.methods.portalCheckedAllHandlerForChild(permissions_checkbox_el.attr('data-portal-name'));

                        }),
                        "portal_name":$('.portal_name').change(function () {
                            // getting current element
                            let portal_name_el = $(this);
                            // getting selection option data
                            var selection_data =  right_form_handler.methods.getSelect2multiSelectedData(portal_name_el);
                            // hide all forms
                            right_form_handler.methods.hideForm();
                            // resting values
                            right_form_handler.static_data.Selected_portals_names = [];
                            // looping on every option selected
                            selection_data.forEach(function (value) {
                                // updating selected portals
                                right_form_handler.static_data.Selected_portals_names.push(value.text.toLowerCase());
                                // now showing selected forms
                                right_form_handler.methods.showFormSection("."+value.text.toLowerCase());
                            });

                            // setting page height
                            right_form_handler.methods.setContentHeight();

                        }),

                    },
                    "init":function () {

                        // update selecet 2 with seleceted values
                        setTimeout(function () {
                            $('.portal_name').val({!! json_encode($selected_options) !!}).trigger("change");
                            right_form_handler.methods.portalCheckedAllHandlerForChild(right_form_handler.static_data.Portal_names);
                        }, 1000);



                    },

                };
            right_form_handler.init();

        });
    </script>
@endsection
