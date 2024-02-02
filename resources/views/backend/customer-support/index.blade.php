<?php
$status = array(
    "136" => "Client requested to cancel the order",
    "137" => "Delay in delivery due to weather or natural disaster",
    "118" => "left at back door",
    "117" => "left with concierge",
    "135" => "Customer refused delivery",
    "108" => "Customer unavailable-Incorrect address",
    "106" => "Customer unavailable - delivery returned",
    "107" => "Customer unavailable - Left voice mail - order returned",
    "109" => "Customer unavailable - Incorrect phone number",
    "142" => "Damaged at hub (before going OFD)",
    "143" => "Damaged on road - undeliverable",
    "144" => "Delivery to mailroom",
    "103" => "Delay at pickup",
    "139" => "Delivery left on front porch",
    "138" => "Delivery left in the garage",
    "114" => "Successful delivery at door",
    "113" => "Successfully hand delivered",
    "120" => "Delivery at Hub",
    "110" => "Returned to hub for re-delivery",
    "111" => "Delivery to hub for return to merchant",
    "121" => "Pickup from Hub",
    "102" => "Joey Incident",
    "104" => "Damaged on road - delivery will be attempted",
    "105" => "Item damaged - returned to merchant",
    "129" => "Joey at hub",
    "128" => "Package on the way to hub",
    "140" => "Delivery missorted, may cause delay",
    "116" => "Successful delivery to neighbour",
    "132" => "Office closed - safe dropped",
    "101" => "Joey on the way to pickup",
    "32" => "Order accepted by Joey",
    "14" => "Merchant accepted",
    "36" => "Cancelled by JoeyCo",
    "124" => "At hub - processing",
    "38" => "Draft",
    "18" => "Delivery failed",
    "56" => "Partially delivered",
    "17" => "Delivery success",
    "68" => "Joey is at dropoff location",
    "67" => "Joey is at pickup location",
    "13" => "At hub - processing",
    "16" => "Joey failed to pickup order",
    "57" => "Not all orders were picked up",
    "15" => "Order is with Joey",
    "112" => "To be re-attempted",
    "131" => "Office closed - returned to hub",
    "125" => "Pickup at store - confirmed",
    "61" => "Scheduled order",
    "37" => "Customer cancelled the order",
    "34" => "Customer is editting the order",
    "35" => "Merchant cancelled the order",
    "42" => "Merchant completed the order",
    "54" => "Merchant declined the order",
    "33" => "Merchant is editting the order",
    "29" => "Merchant is unavailable",
    "24" => "Looking for a Joey",
    "23" => "Waiting for merchant(s) to accept",
    "28" => "Order is with Joey",
    "133" => "Packages sorted",
    "55" => "ONLINE PAYMENT EXPIRED",
    "12" => "ONLINE PAYMENT FAILED",
    "53" => "Waiting for customer to pay",
    "141" => "Lost package",
    "60" => "Task failure",
	"145" => "Returned To Merchant",
    "146" => "Delivery Missorted, Incorrect Address",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow'
);

$user = Auth::user();
if ($user->email != "admin@gmail.com") {

    $data = explode(',', $user['rights']);
    $dataPermission = explode(',', $user['permissions']);
} else {
    $data = [];
    $dataPermission = [];
}
?>
@extends( 'backend.layouts.app' )

@section('title', 'Orders Under Review')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
<style>
    .btn{
        background-color: #C6DD38;
    }

    .note{
        resize: vertical;
    }

     /*Open Css For Button Group*/
    .table-input-with-btn {
        width: 100%;
        display: block;
        margin: 0 auto;
        box-sizing: border-box;
        position: relative;
    }
    .table-input-with-btn input {
        padding-right: 25px;
        width: 100% !important;
        display: block !important;
        margin: 0px;
    }
    .table-input-with-btn button {
        position: absolute;
        right: 0px;
        top: 10px;
        background: none;
        border: none;
        margin: 0px;
        color: #327ab7;
    }
    /*Close Css For Button Group*/
    .input-error
    {
        border-color: red !important;
    }
    .form-submit-btn {
        margin-top: 26px;
        width: 100%;
        background-color: #c6dd38;
    }

    .show-notes{
        border-style:none;
        padding: 8px 13px 8px 13px;
    }

</style>
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!--  <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&libraries=places" type="text/javascript"></script>
    <!-- Custom JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{ backend_asset('js/joeyco-custom-script.js')}}"></script>
@endsection

@section('inlineJS')

    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('.update-phone-on-change').mask('(000) 000-0000', {placeholder: "(__) __-____"});

            $('.return-order-datatable').dataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ],
                "autoWidth": false,
                scrollX: true,   // enables horizontal scrolling,
                scrollCollapse: true,
                fixedColumns: true,
            });
            $(document).on('click', '.edit', function (e) {

                let id = $(this).attr('data-id');

                //setting id in modal
                $('#model_id').val(id);
                $('#myModaledit').modal();

            });

            // ajax function to update column
            $(document).on('click', '.update-phone-on-change', function () {
                $(this).siblings(".datatable-input-update-btn").show();

            });

            // ajax function to update column
            $(document).on('click', '.datatable-input-update-btn', function () {
                let input_el = $(this).siblings('input');
                ColumnValueChangeByAjax(input_el);

            });

            // ajax function to show google map address suggestion
            $(document).on('focus', '.update-address-on-change', function () {

				let triggerAjax = true;

               // var acInputs = document.getElementsByClassName("google-address");
                var acInputs = this;
                var element = $(this);

                // remove error class if exist
                element.removeClass('input-error');

                const map = new google.maps.Map(document.getElementById("map"), {
                    center: {lat: 40.749933, lng: -73.98633},
                    zoom: 13,
                });

                const options = {
                    componentRestrictions: {country: "ca"},
                    fields: ["formatted_address", "geometry", "name","address_components"],
                    origin: map.getCenter(),
                    strictBounds: false,
                    //types: ["establishment"],
                };
                var autocomplete = new google.maps.places.Autocomplete(acInputs, options);

                var address_sorted_object = {};
                google.maps.event.addListener(autocomplete, 'place_changed', function () {

                    // removing alert
                    $(".session-wrapper").find('.alert').remove();

                    var place = autocomplete.getPlace();
                    var address_components = place.address_components;

                    address_components.forEach(function (currentValue) {
                        address_sorted_object[currentValue.types[0]] = currentValue;
                    });

                    //var last_element = hh[hh.length - 1];
                    // add lat lng
                    $(element).attr('data-lat',place.geometry.location.lat());
                    $(element).attr('data-lng',place.geometry.location.lng());
                    // checking data is completed
                    if(!("postal_code" in address_sorted_object))
                    {
                        // show session alert
                        ShowSessionAlert('danger', 'Your selected address does not contain a Postal Code. Kindly select a nearby address! ');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }
                    else if(!("locality" in address_sorted_object))
                    {
                        // show session alert
                        ShowSessionAlert('danger', 'Your Selected address does not contain city kindly select near by address !');
                        element.val(element.attr('data-old-val'));
                        element.siblings(".datatable-input-update-btn").hide();
                        element.addClass('input-error');
                        console.log(address_sorted_object);
                        return;
                    }

                    element.attr('data-postal-code',address_sorted_object.postal_code.long_name);
                    element.attr('data-city',address_sorted_object.locality.long_name);
console.log(address_sorted_object.postal_code.long_name);
					// checking the ajax is already not trigger
                    // if(triggerAjax){
                        // now making trigger ajax false for multiple trigger
                        triggerAjax = false;
                        ColumnValueChangeByAjax(element);
                    // }

                });

            });

            // checking the address changed by google select or not
            $(document).on('change','.update-address-on-change',function () {
                let el = $(this);
                let status = el.attr('data-select-from-google-status');
                let old_val = el.attr('data-old-val');
                console.log(status);
                // checking the address is updated by the google address suggestion
                if(status == 'false')
                {
                    ShowSessionAlert('danger','Kindly select address from google suggestion');
                    el.val(old_val);

                }
                else
                {
                    // updating status address updated by google suggestion
                    el.attr('data-select-from-google-status','false');
                }
            });

            // function column value change by ajax
            function ColumnValueChangeByAjax(element) {


                let el = $(element);
                //Getting Value From Ajax Request
                let type = el.attr('data-type');
                let ids = el.attr('data-match');
                let id = el.attr('data-id');
                let val = el.val();
                let lat = el.attr('data-lat');
                let lng = el.attr('data-lng');
                let postalcode = el.attr('data-postal-code');
                let city_val = el.attr('data-city');
                let old_val = el.attr('data-old-val');

                $.confirm({
                    title: 'Confirmation',
                    content: 'Are you sure you want to update ?',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {

                                // show loader
                                showLoader();

                                // convert data type according to type
                                if (type == 'customer_address') {
                                    ids = parseInt(ids);
                                    // checking the id is not 0 else return for hire not send  any ajax request
                                    if (ids < 1) {
                                        hideLoader();
                                        return;
                                    }
                                }
                                else if (type == 'customer_phone') {
                                    ids = parseInt(ids);
                                    // checking the id is not 0 else return for hire not send  any ajax request
                                    if (ids < 1) {
                                        hideLoader();
                                        return;
                                    }
                                }
                                else {
                                    ids = JSON.parse(ids);
                                }

                                // sending ajax request
                                $.ajax({
                                    type: "get",

                                    url: "{{URL::to('/')}}/reattempt/order/column/update",
                                    data: {
                                        type: type,
                                        ids: ids,
                                        id: id,
                                        val: val,
                                        lat: lat,
                                        lng: lng,
                                        postalcode: postalcode,
                                        city_val: city_val
                                    },
                                    success: function (response) {
                                        hideLoader();
                                        // update event status
                                        el.attr('data-event-status', 'false');
                                        $('.datatable-input-update-btn').hide();
                                        // Hide Remove Button On Edit Column Action
                                        $('.remove-reattempt-order').hide();
                                        // checking responce status
                                        if (response.status == true) // notifying user  the update is completed
                                        {
                                            // show session alert
                                            ShowSessionAlert('success', response.message);

                                            // updating old value to new  value
                                            el.attr('data-old-val', val);

                                            // updating status address updated by google suggestion
                                            el.attr('data-select-from-google-status','true');

                                        }
                                        else if (response.status == false) // update  failed by server
                                        {
                                            // show session alert
                                            ShowSessionAlert('danger', response.message);

                                            // setting previous value
                                            el.val(old_val);
                                        }
                                        else // some thing went wrong
                                        {
                                            alert('some error occurred please see the console');
                                            console.log(response);
                                        }

                                        //hide loader
                                        hideLoader();

                                    },
                                    error: function (error) {
                                        hideLoader();
                                        alert('some error occurred please see the console');
                                        console.log(error);
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            el.val(old_val);
                        }
                    }
                });

            }

            //call modal for notes
            $(document).on('click', '.add-note', function (e) {

                let id = $(this).attr('data-id');

                //setting id in modal
                $('#note_id').val(id);
                $('#myModalAddNote').modal();

            });

        });

    </script>
    <script>
        $(document).ready(function () {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
        setInterval(ajaxCall, 5000); //300000 MS == 5 minutes
        var customer_count = $('#count_orders_now').val();

        function ajaxCall() {
            $.ajax({
            url: '{{ URL::to('order/under-review/count')}}',
            type: 'GET',
            success: function (response) {
                if(customer_count != response.count){
                    var buzzer_valid = $('#valid_sound')[0];
                    $('#count_modal').css('display','block');
                    buzzer_valid.play();
                    customer_count = response.count;
                    // location.reload();
                }
            },
            error:function(err){
                console.log(err);
            }
            })
        }

        $('#refresh_order').on('click',function(){
            location.reload();
        })

        $('#count_modal_close').on('click',function(){
            $('#count_modal').hide();
        })
    </script>

@endsection

@section('content')

<div id="map"></div>
    <div class="right_col" role="main">
        <div class="">

            @if ($message = Session::pull('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::pull('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
        @endif

        <!-- Edit Modal -->
        <div class="modal fade" id="myModaledit" role="dialog">
            <form action="{{ URL::to('order/approval')}}" method="post" class="Editorder">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Note For Approval</h4>
                        </div>
                        <div class="modal-body">
                            <!-- <label>Vendor:</label> -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" id="model_id" class="form-control"  required/>

                            <label>Note:</label>
                            <textarea name="note"  placeholder='Note' class="form-control note"  required></textarea>
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style='background-color: #c6dd38;' class="btn btn">Approved</button>
                        </div>
                    </div>

                </div>

        </form>
    </div>
    <!-- Modal end-->
                <!--MOdal for count-->
                <div class="alert alert-primary" id="count_modal" style="display:none;color: #004085;
                background-color: #cce5ff;
                border-color: #b8daff;" 
                role="alert">
                    Order has been placed for customer support, Please refresh to see the new order
                    <button type="button" class="ml-2 mb-1 close" id="count_modal_close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <input type="hidden" name="count_orders_now" id="count_orders_now" value="{{$return_reattempt_history_count}}">
                    <audio id="valid_sound" >
                        <source src="{{app_asset('/media/beep-07.mp3')}} "  type="audio/mpeg">
                    </audio>
                    <button class="btn" id="refresh_order" style="margin-left: 1000px;"> Refresh</button>
                </div>
                <!--MOdal for count-->
                <!-- Add Notes Open -->
                <div class="modal fade" id="myModalAddNote" role="dialog">
                    <form action="{{ URL::to('add/notes')}}" method="post" class="Editorder">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Notes</h4>
                                </div>
                                <div class="modal-body">
                                    <!-- <label>Vendor:</label> -->
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" id="note_id" class="form-control"  required/>

                                    <label>Note:</label>
                                    <textarea name="note"  placeholder='Note' class="form-control note"  required></textarea>
                                    <br />
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" style='background-color: #c6dd38;' class="btn btn">Add Note</button>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
                <!-- Add Note End-->

            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3 class="text-center">Orders Under Review<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Orders Under Review List </h2>
                            <div class="clearfix"></div>
                        </div>
                        {{--<form class="form-horizontal table-top-form-from">
                            <!--table-top-form-row-open-->
                            <div class="row table-top-form-row">
                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-4 table-top-form-col-warp">
                                    <label class="control-label">Select Date</label>
                                    <input name="search_date" max="{{date('Y-m-d')}}" value="{{ isset($old_request_data['search_date']) ? trim($old_request_data['search_date']) : date('Y-m-d') }}" type="date" class="form-control">
                                    --}}{{--<input name="search_date" value="@if($old_request_data){{trim($old_request_data['search_date'])}} @endif"  type="date" class="form-control">--}}{{--
                                </div>
                                <!--table-top-form-col-warp-close-->
                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-2 table-top-form-col-warp">
                                    <button class="btn orange form-submit-btn"  type="submit"> Filter </button>
                                </div>
                                <!--table-top-form-col-warp-close-->

                            </div>
                            <!--table-top-form-row-close-->
                        </form>--}}
                        <div class="x_content">

                            {{--<div class="table-responsive">--}}
                            @include( 'backend.layouts.notification_message' )


                                 <!--Open Table Tracking Order List-->
                                <table class="table table-striped table-bordered return-order-datatable" data-form="deleteForm">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 60px">Order Id</th>
                                        <th style="min-width: 90px">Tracking Id</th>
                                        <th style="min-width: 95px">Route Number</th>
                                        <th style="min-width: 450px">Customer Address</th>
                                        <th style="min-width: 120px">Customer Phone</th>
                                        <th style="min-width: 120px">Status</th>
                                        <th style="min-width: 80px">Re-Attempts Left</th>
                                        <th style="min-width: 71px">Add Notes</th>
                                        <th style="min-width: 110px">Created At</th>
                                        <th style="min-width: 120px">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($return_reattempt_history as $history)

                                        <tr>
                                            <td>{{$history->sprint_id}}</td>
                                            <td>{{$history->tracking_id}}</td>
                                            <td>{{$history->route_id}}</td>
                                            <!--Open Table Input For Address-->
                                            <td>

                                                    <div class="table-input-with-btn">
                                                        <input type="text"
                                                               data-select-from-google-status="false"
                                                               data-event-status="false"
                                                               data-type="customer_address"
                                                               data-match="{{$history->location_id}}"
                                                               data-id="{{$history->id}}"
                                                               data-lat=""
                                                               data-lng=""
                                                               data-city=""
                                                               data-postal-code=""
                                                               data-old-val="{{$history->customer_address.' '.$history->postal_code}}"
                                                               class="form-control update-address-on-change google-address"
                                                               value="{{$history->customer_address.', '.$history->postal_code}}"/>
                                                        <button class="datatable-input-update-btn fa fa-pencil"
                                                                style="display: none"></button>
                                                    </div>
                                            </td>
                                            <!--Close Table Input For Address-->
                                            <!--Open Table Input For Phone-->
                                            <td>
                                                <div class="table-input-with-btn">
                                                    @if(strlen($history->customer_phone) < 11)
                                                        <?php $phoneNo =  '+1'.$history->customer_phone ?>
                                                    @else
                                                        <?php $phoneNo =  $history->customer_phone ?>
                                                    @endif
                                                        <input type="text" data-event-status="false" data-type="customer_phone"
                                                               data-match="{{$history->sprint_contact_id}}"
                                                               data-id="{{$history->id}}"
                                                               data-old-val="{{convert_ca_number_formatted($history->customer_phone)}}"
                                                               class="form-control update-phone-on-change"
                                                               value="{{convert_ca_number_formatted($phoneNo)}}" name="phone"/>
                                                        <button class="datatable-input-update-btn fa fa-pencil"
                                                                style="display: none"></button>
                                                    </div>
                                            </td>
                                            <!--Close Table Input For Phone-->
                                            @if (isset($status[$history->status_id]))
                                                <td>{{ $status[$history->status_id]}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{$history->reattempt_left}}</td>
                                            <td style="width: 8%">
                                                @if(can_access_route('add-notes',$userPermissoins))
                                                <button class="btn orange-gradient add-note" type="submit" data-id='{{$history->id}}'><i class="fa fa-plus"></i> </button>
                                                <a href="{{backend_url('notes/'.$history->id)}}" target="_blank" title="Detail" class="btn  btn-sm show-notes"><i class="fa fa-tags notes-icon"></i></a>
                                                @endif
                                            </td>
                                            <td>{{ConvertTimeZone($history->created_at,'UTC','America/Toronto')}}</td>
                                            <!--Open Action Button td-->
                                            <td>
                                                @if(can_access_route('orderConfirmation.transfer',$userPermissoins))
                                                    <button class="btn orange-gradient edit" type="submit" data-id='<?php echo $history->id; ?>'>Order Approval </button>
                                                @endif
                                            </td>
                                            <!--Close Action Button td-->
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                <!--Close Table Tracking Order List-->
                           {{-- </div>--}}
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection