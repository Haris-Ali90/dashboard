@extends( 'backend.layouts.app' )

@section('title', 'Show Right | Right Permissions')
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
        .portal-name-heading h2 {
            background-color: #3e3e3e;
            font-size: 15px;
            padding: 5px 10px;
            color: #fff;
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
    <style>
        .custm_labl input[type="checkbox"]:after {
            content: "\2713";
            position: absolute;
            left: 0;
            font-size: 14px;
            font-weight: 900;
            height: 19px;
            text-indent: 3px;
            width: 19px;
            color: #000000;
            background: #f1f1f1;
            border: 1px solid #9e9b9b;
            border-radius: 0px;
        }
        .custm_labl input[type="checkbox"] {
            font-size: 20px;
            height: 19px;
            width: 20px;
            margin-right: 5px;
            position: relative;
        }
        .custm_labl{
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>View Rights & Permissions<small></small></h3>
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
                                <h2 class="main-section-heading">Right</h2>
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
                        <form class="form-horizontal table-top-form-from"  method="get" action="{{ backend_url('right/'.base64_encode($rights->id)) }}">

                            <!--table-top-form-row-open-->
                            <div class="row table-top-form-row">
                                <!--table-top-form-col-warp-open-->
                            {{--<div class="col-sm-3 col-md-3 table-top-form-col-warp">--}}
                            {{--<label class="control-label">Portal</label>--}}
                            {{--<input name="portal_name_view" type="text" value="{{ old('portal_name_view',$portals)}}" class="form-control"  readonly>--}}
                            {{--</div>--}}

                            <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">Portal</label>
                                    <select class="form-control portal_name js-example-basic-multiple"  name="portal_name[]" multiple required disabled >
                                        {{--<option value="">Select an option</option>--}}

                                        @foreach($portals as $key => $portal)
                                            <option  value="{{$key}}">{{$portal}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">Right Name</label>
                                    <input name="right_name"  type="text" value="{{ old('right_name',$rights->display_name)}}" class="form-control right_name" required disabled>
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
                                            <div class="col-sm-12 portal-name-heading">
                                                <h2>{{ucwords($portal_name)}}</h2>
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
                                                        <div class="form-group custm_labl">
                                                            <input type="checkbox" name="{{$portal_name}}_permissions[]" class="permissions-checkbox {{$portal_name}}-permissions-checkbox"  value="{{ $permission }}" @if(check_permission_exist($permission,$selected_permission_array)) checked @endif disabled />
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

                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-2 table-top-form-col-warp inline-form-btn-margin">
                                    <a href="{{backend_url('right')}}"class="btn orange btn-default"  > Back </a>
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
                    {{--let right_form_handler =--}}
                    {{--{--}}
                    {{--"init":function () {--}}
                    {{--//right_form_handler.methods.hideForm();--}}
                    {{--//right_form_handler.methods.resetCheckBox();--}}
                    {{--//right_form_handler.methods.makeOptionSelectedAndTrige('.portal_name','{{old('portal_name')}}');--}}
                    {{--},--}}
                    {{--"methods":{--}}
                    {{--"resetCheckBox":function () {--}}
                    {{--$(".permissions-checkbox").prop("checked", false);--}}
                    {{--},--}}
                    {{--"hideForm":function () {--}}
                    {{--//$('.portal-permission-wraper').addClass('hide');--}}
                    {{--},--}}
                    {{--"showFormSection":function (el) {--}}
                    {{--$(el).removeClass('hide');--}}
                    {{--},--}}
                    {{--"getSelectSelectedData":function (el) {--}}
                    {{--var returnObj = {};--}}
                    {{--returnObj.val = el.val();--}}
                    {{--returnObj.text = el.find('option:selected').text();--}}
                    {{--return  returnObj;--}}

                    {{--},--}}
                    {{--"makeOptionSelectedAndTrige":function (el,val) {--}}
                    {{--$(el).val(val).triggerHandler('change');--}}
                    {{--}--}}

                    {{--},--}}
                    {{--"element_instances": {--}}
                    {{--//                    "portal_name":$('.portal_name').change(function () {--}}
                    {{--//                        let portal_name_el = $(this);--}}
                    {{--//                        // getting selection option data--}}
                    {{--//                        var selection_data =  right_form_handler.methods.getSelectSelectedData(portal_name_el);--}}
                    {{--//                        // reseting from--}}
                    {{--//                        right_form_handler.methods.resetCheckBox();--}}
                    {{--//                        // hide old from--}}
                    {{--//                        right_form_handler.methods.hideForm();--}}
                    {{--//                        // show from section--}}
                    {{--//                        right_form_handler.methods.showFormSection('.'+selection_data.text.toLowerCase());--}}
                    {{--//                    }),--}}
                    {{--}--}}

                    {{--};--}}
                    {{--right_form_handler.init();--}}

            let right_form_handler =
                    {
                        "postfixes":{
                            "permission_checkbox_name":"-permissions-checkbox"
                        },
                        "static_data":{
                            "Selected_portals_names":[],
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
                                // validations
                                let right_name  = $('.right_name').val();
                                if(right_name == '')
                                {
                                    alert('Please enter right name !');
                                    return false;
                                }
                                // adding slug name
                                $('.slug-name').val(right_name+'_'+right_form_handler.static_data.Selected_portals_names.join('_'));
                                // adding seleted options
                                $('.seletec_options').val(right_form_handler.static_data.Selected_portals_names.join('_'));
                                return true;
                            },

                        },
                        "element_instances": {
                            "form":$('form').submit(function (e) {
                                return right_form_handler.methods.formSubmitHndller(e)
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

                            }),

                        },
                        "init":function () {

                            // update selecet 2 with seleceted values
                            setTimeout(function () {
                                $('.portal_name').val({!! json_encode($selected_options) !!}).trigger("change");
                            }, 1000);

                        },

                    };
            right_form_handler.init();

        });
    </script>
@endsection
