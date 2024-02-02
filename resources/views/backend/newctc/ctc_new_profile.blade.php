<?php

$status = array("136" => "Client requested to cancel the order",
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
    "110" => "Delivery to hub for re-delivery",
    "111" => "Delivery to hub for return to merchant",
    "121" => "Out for delivery",
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
    "255" => 'Order Delay',
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
?>

@extends( 'backend.layouts.app' )


@section('title', 'Client Dashboard Detail')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box JS -->

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>

    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".group1").colorbox({height: "75%"});
        });
    </script>

@endsection



@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>{{$ctc_data->joey}}</h3>
                </div>


            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>CTC Order Detail
                                <small></small>
                            </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-2 col-sm-2 col-xs-12 profile_left">
                                 <div class="profile_img">
                                     <div id="crop-avatar">

                                         <ul class="main-image" style="list-style: none;">
                                             <li class="col-md-12">
                                                 @if($ctc_data->sprintCtcTasks)
                                                     @if($ctc_data->sprintCtcTasks->sprintConfirmations)
                                                         @if($ctc_data->sprintCtcTasks->sprintConfirmations->attachment_path)
                                                 <a>
                                                     <img onClick="ShowLightBox(this);" src="{{ $ctc_data->sprintCtcTasks->sprintConfirmations->attachment_path}}" style=" width: 100%;
    height: 100%;"  alt="Avatar"/>
                                                 </a>
                                                     @endif
                                                     @endif
                                                 @endif
                                             </li>
                                         </ul>


                                     </div>
                                 </div>
                                 <h3></h3>

                                {{--<ul class="list-unstyled user_data">
                                     <li><label>Full Name :</label> {{$ctc_dash['order_id'] or "N/A"}}</li>
                                     <li><label>Email Address : </label> {{$ctc_dash['route'] or "N/A"}}</li>
                                     <li><label>Phone / Mobile no :</label>{{$ctc_dash['joey'] or "N/A"}}</li>

                                </ul>--}}

                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab"
                                                                                  role="tab" data-toggle="tab"
                                                                                  aria-expanded="true">CTC Detail</a>
                                        </li>
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                             aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th colspan="2">Ctc Order Detail</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $hubreturned3 = "";
                                                $hubpickup3 = "";
                                                $deliver3 = "";
                                                $hubreturned2 = "";
                                                $hubpickup2 = "";
                                                $deliver2 = "";
                                                $hubreturned = "";
                                                $hubpickup = "";
                                                $deliver = "";
                                                $notes = '';
                                                $pickup = $ctc_data->pickupFromStore()->pickup;
                                                $notes1 = \App\Notes::where('object_id', $ctc_data->id)->pluck('note');
                                                $i = 0;
                                                foreach ($notes1 as $note) {
                                                    if ($i == 0)
                                                        $notes = $notes . $note;
                                                    else
                                                        $notes = $notes . ', ' . $note;
                                                }
                                                if ($ctc_data->sprintReattempts) {
                                                    if ($ctc_data->sprintReattempts->reattempts_left == 1) {

                                                        $hubreturned3 = $ctc_data->atHubProcessing()->athub;
                                                        $hubpickup3 = $ctc_data->outForDelivery()->outdeliver;
                                                        $deliver3 = $ctc_data->deliveryTime()->delivery_time;

                                                        $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc_data->sprintReattempts->reattempt_of)->orderBy('created_at','ASC')
                                                            ->get(['status_id',\DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                                                        if (!empty($secondAttempt)) {

                                                            foreach ($secondAttempt as $secAttempt) {

                                                                if ($secAttempt->status_id == 125) {
                                                                    $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                                                }
                                                                if (in_array($secAttempt->status_id, [124, 13])) {
                                                                    $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at) );
                                                                }
                                                                if ($secAttempt->status_id == 121) {
                                                                    $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at) );
                                                                }
                                                                if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136])) {
                                                                    $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                                                }
                                                            }
                                                        }

                                                        $firstSprint = \App\SprintReattempt::where('reattempt_of', '=', $ctc_data->sprintReattempts->reattempt_of)->first();
                                                        if (!empty($firstSprint)) {
                                                            $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->sprint_id)->orderBy('created_at','ASC')->
                                                            get(['status_id',\DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                                                            if (!empty($firstAttempt)) {

                                                                foreach ($firstAttempt as $firstAttempt) {

                                                                    if (in_array($firstAttempt->status_id, [124, 13])) {
                                                                        $hubreturned2 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at) );
                                                                    }
                                                                    if ($firstAttempt->status_id == 121) {
                                                                        $hubpickup2 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at) );
                                                                    }
                                                                    if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136])) {
                                                                        $deliver2 = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                                                    }
                                                                }

                                                            }
                                                        }
                                                    }
                                                    if ($ctc_data->sprintReattempts->reattempts_left == 2) {

                                                        $hubreturned2 = $ctc_data->atHubProcessing()->athub;
                                                        $hubpickup2 = $ctc_data->outForDelivery()->outdeliver;
                                                        $deliver2 = $ctc_data->deliveryTime()->delivery_time;

                                                        $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $ctc_data->sprintReattempts->reattempt_of)->orderBy('created_at','ASC')->
                                                            get(['status_id',\DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                                                        if (!empty($secondAttempt)) {
                                                            date_default_timezone_set('America/Toronto');
                                                            foreach ($secondAttempt as $secAttempt) {
                                                                if ($secAttempt->status_id == 125) {
                                                                    $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at) );
                                                                }
                                                                if (in_array($secAttempt->status_id, [124, 13])) {
                                                                    $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at) );
                                                                }
                                                                if ($secAttempt->status_id == 121) {
                                                                    $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at) );
                                                                }
                                                                if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136])) {
                                                                    $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at) );
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>


                                                <tr>
                                                    <td style="width: 30%;"><label>JoeyCo Order #</label></td>
                                                    <td>{{$ctc_data->id or " "}}</td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 30%;"><label>Route #</label></td>
                                                    <td> @if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->taskRouteLocation)
                                                                R-{{$ctc_data->sprintCtcTasks->taskRouteLocation->route_id}}
                                                                -
                                                                {{$ctc_data->sprintCtcTasks->taskRouteLocation->ordinal}}
                                                            @endif
                                                            @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <lable>Joey</lable>
                                                    </td>
                                                    <td>@if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->taskRouteLocation)
                                                                @if($ctc_data->sprintCtcTasks->taskRouteLocation->joeyRoute->joey)
                                                                    {{$ctc_data->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name.' '
                                                                    .$ctc_data->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name.' ('
                                                                    .$ctc_data->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id.')'}}
                                                                @else
                                                                    {{" "}}
                                                                @endif
                                                            @else
                                                                {{" "}}
                                                            @endif
                                                        @else
                                                            {{" "}}
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Store Name</label></td>
                                                    <td>{{ isset($ctc_data->sprintVendor->name) ? $ctc_data->sprintVendor:'' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Name</label></td>
                                                    <td>@if( isset($ctc_data->sprintCtcTasks) ? $ctc_data->sprintCtcTasks:'' )
                                                            {{$ctc_data->sprintCtcTasks->taskContact->name }}
                                                        @endif</td>
                                                </tr>



                                                <tr>
                                                    <td><label>Customer Address</label></td>
                                                    <td>@if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->taskMerchants)
                                                                {{$ctc_data->sprintCtcTasks->taskMerchants->address_line2 }}
                                                            @endif
                                                        @endif</td>
                                                </tr>
                                                {{--<tr>
                                                    <td><label>Address</label></td>

                                                    <td>  @if($ctc_data->sprintCtcTasks)

                                                            @if($ctc_data->sprintCtcTasks->task_Location)
                                                                {{$ctc_data->sprintCtcTasks->task_Location->address }}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>--}}
                                                <tr>
                                                    <td><label>Postal Code</label></td>
                                                    <td>  @if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->task_Location)
                                                                {{$ctc_data->sprintCtcTasks->task_Location->postal_code}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label>City</label></td>
                                                    <td>  @if( isset($ctc_data->sprintCtcTasks) ? $ctc_data->sprintCtcTasks:'' )
                                                            @if($ctc_data->sprintCtcTasks->task_Location)
                                                                @if($ctc_data->sprintCtcTasks->task_Location->city)
                                                                    {{$ctc_data->sprintCtcTasks->task_Location->city->name }}
                                                                @endif
                                                            @endif
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Weight</label></td>

                                                    <td>  @if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->taskMerchants)
                                                                {{$ctc_data->sprintCtcTasks->taskMerchants->weight}}
                                                                {{$ctc_data->sprintCtcTasks->taskMerchants->weight_unit}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><label>Pickup From Store</label></td>
                                                    <td>{{$pickup }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>At Hub Processing</label></td>
                                                    <td>@if(!empty($hubreturned))
                                                            {{$hubreturned}}
                                                        @else
                                                            {{$ctc_data->atHubProcessing()->athub }}
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Out For Delivery</label></td>
                                                    <td>@if(!empty($hubpickup))
                                                            {{$hubpickup}}
                                                        @else

                                                            {{$ctc_data->outForDelivery()->outdeliver}}
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Estimated Customer Delivery Time</label></td>
                                                    <td> @if($ctc_data->sprintCtcTasks)
                                                            {{date('Y-m-d H:i:s', strtotime("+1 day", strtotime($ctc_data->sprintCtcTasks->eta_time)))}}

                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Actual Customer delivery time</label></td>
                                                    <td>@if(!empty($deliver))
                                                            {{$deliver}}
                                                        @else
                                                            {{$ctc_data->deliveryTime()->delivery_time }}
                                                        @endif</td>
                                                </tr>
                                              {{--  <tr>
                                                    <td><label>Safe Drop image</label></td>
                                                    <td>
                                                        @if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->sprintConfirmations)
                                                                @if($ctc_data->sprintCtcTasks->sprintConfirmations->attachment_path)
                                                                    <img onClick="ShowLightBox(this);"
                                                                         src="{{$ctc_data->sprintCtcTasks->sprintConfirmations->attachment_path}}"
                                                                         class="avatar" alt="Avatar"/>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>--}}


                                                <tr>
                                                    <td><label>Shipment Tracking #</label></td>
                                                    <td>@if($ctc_data->sprintCtcTasks)
                                                            @if($ctc_data->sprintCtcTasks->taskMerchants)
                                                                {{substr($ctc_data->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc_data->sprintCtcTasks->taskMerchants->tracking_id, '_' )+0) }}
                                                            @endif
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Shipment Tracking Link</label></td>
                                                <td>
                                                    @if($ctc_data->sprintCtcTasks)
                                                        @if($ctc_data->sprintCtcTasks->taskMerchants)
                                                            <a style="font-weight: bold;" href='https://www.joeyco.com/track-order/{{substr($ctc_data->sprintCtcTasks->taskMerchants->tracking_id, strrpos($ctc_data->sprintCtcTasks->taskMerchants->tracking_id, '_' )+0)}}' target="_blank" >
                                                                Please click here to view tracking Detail</a>
                                                        @endif
                                                    @endif
                                                <td>
                                                </td>


                                                <?php
                                                $current_status = $ctc_data->status_id;
                                                if ($ctc_data->status_id == 17) {
                                                    $preStatus = \App\SprintTaskHistory
                                                        ::where('sprint_id', '=', $ctc_data->id)
                                                        ->where('status_id', '!=', '17')
                                                        ->orderBy('id', 'desc')->first();
                                                    if (!empty($preStatus)) {
                                                        $current_status = $preStatus->status_id;
                                                    }
                                                }
                                                ?>

                                                <tr>
                                                    <td><label>Shipment Delivery Status</label></td>
                                                    <td>
                                                        @if ($current_status==13)
                                                            At hub Processing

                                                        @else
                                                            {{$status[$current_status]  }}
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>JoyeCo Notes / Comments</label></td>
                                                    <td>{{$notes }}</td>
                                                </tr>
                                                {{--<tr>--}}
                                                    {{--<td><label>Task Status</label></td>--}}
                                                    {{--<td>@if($ctc_data->sprintCtcTasks)--}}
                                                            {{--@if ($ctc_data->sprintCtcTasks->status_id == 13)--}}
                                                                {{--At hub Processing--}}

                                                            {{--@else--}}
                                                                {{--{{$status[$ctc_data->sprintCtcTasks->status_id]  }}--}}
                                                            {{--@endif--}}
                                                        {{--@endif</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td><label>Old Sprint</label></td>--}}
                                                    {{--<td>@if($ctc_data->sprintReattempts)--}}
                                                            {{--{{  $ctc_data->sprintReattempts->reattempt_of }}--}}
                                                        {{--@endif--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}

                                               {{-- <tr>
                                                    <td><label>Reattempt Left</label></td>
                                                    <td>@if($ctc_data->sprintReattempts)
                                                            {{  $ctc_data->sprintReattempts->reattempts_left }}
                                                        @endif</td>
                                                </tr>--}}
                                                <tr>
                                                    <td><label>Returned to HUB 2</label></td>
                                                    <td>{{$hubreturned2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>2nd Attempt Pick up</label></td>
                                                    <td>{{$hubpickup2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>2nd Attempt Delivery</label></td>
                                                    <td>{{$deliver2 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Returned to HUB 3</label></td>
                                                    <td>{{$hubreturned3 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>3rd Attempt Pick up</label></td>
                                                    <td>{{$hubpickup3 }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>3rd Attempt Delivery</label></td>
                                                    <td>{{$deliver3 }}</td>
                                                </tr>


                                               {{-- <tr>
                                                    <td><label>Created At</label></td>
                                                    <td>{{$ctc_data->created_at }}</td>
                                                </tr>
--}}

                                                </tbody>
                                            </table>
                                            <!-- end user projects -->

                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                             aria-labelledby="profile-tab">

                                            <!-- start user projects -->
                                            <!--  -->
                                            <!-- end user projects -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection