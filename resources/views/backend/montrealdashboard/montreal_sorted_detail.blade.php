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
    "32"  => "Order accepted by Joey",
    "14"  => "Merchant accepted",
    "36"  => "Cancelled by JoeyCo",
    "124" => "At hub - processing",
    "38"  => "Draft",
    "18"  => "Delivery failed",
    "56"  => "Partially delivered",
    "17"  => "Delivery success",
    "68"  => "Joey is at dropoff location",
    "67"  => "Joey is at pickup location",
    "13"  => "At hub - processing",
    "16"  => "Joey failed to pickup order",
    "57"  => "Not all orders were picked up",
    "15"  => "Order is with Joey",
    "112" => "To be re-attempted",
    "131" => "Office closed - returned to hub",
    "125" => "Pickup at store - confirmed",
    "61"  => "Scheduled order",
    "37"  => "Customer cancelled the order",
    "34"  => "Customer is editting the order",
    "35"  => "Merchant cancelled the order",
    "42"  => "Merchant completed the order",
    "54"  => "Merchant declined the order",
    "33"  => "Merchant is editting the order",
    "29"  => "Merchant is unavailable",
    "24"  => "Looking for a Joey",
    "23"  => "Waiting for merchant(s) to accept",
    "28"  => "Order is with Joey",
    "133" => "Packages sorted",
    "55"  => "ONLINE PAYMENT EXPIRED",
    "12"  => "ONLINE PAYMENT FAILED",
    "53"  => "Waiting for customer to pay",
    "141" => "Lost package",
    "60"  => "Task failure",
    "145" => 'Returned To Merchant',
    "146" => "Delivery Missorted, Incorrect Address",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
?>
@extends( 'backend.layouts.app' )



@section('title', 'Montreal Sorted Order')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- Custom Light Box JS -->
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
                    <h3>{{$amazon_montreal->joey}}</h3>
                </div>


            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Montreal Sorted Order <small></small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-2 col-sm-2 col-xs-12 profile_left">
                                {{-- <div class="profile_img">
                                     <div id="crop-avatar">

                                         <ul class="main-image" style="list-style: none;">
                                             <li class="col-md-12">
                                                 <a class="group1">
                                                     <img class="img-responsive avatar-view" src="{{ URL::to('/') }}/public/images/profile_images/{{$amazon_montreal->image}}" style="    margin-left: -46px;" class="avatar" alt="Avatar"/>
                                                 </a>
                                             </li>
                                         </ul>

                                     </div>
                                 </div>
                                 <h3></h3>--}}

                                {{--<ul class="list-unstyled user_data">
                                     <li><label>Full Name :</label> {{$amazon_montreal['order_id'] or "N/A"}}</li>
                                     <li><label>Email Address : </label> {{$amazon_montreal['route'] or "N/A"}}</li>
                                     <li><label>Phone / Mobile no :</label>{{$amazon_montreal['joey'] or "N/A"}}</li>

                                </ul>--}}

                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Amazon Montreal Sorted Order</a>
                                        </li>
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th colspan="2" >Montreal Sorted Order</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><label>JoeyCo Order #</label></td>
                                                    <td>{{$amazon_montreal->order_id or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Amazon tracking #</label></td>
                                                    <td>{{$amazon_montreal->tracking_id or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><lable>Route Number</lable></td>
                                                    <td>{{$amazon_montreal->route  or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Joey</label></td>
                                                    <td>{{$amazon_montreal->joey or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Address</label></td>
                                                    <td>{{$amazon_montreal->address or "N/A"}}</td>
                                                </tr>

                                                {{--<tr>
                                                    <td><label>Scheduled Time</label></td>
                                                    <td>{{$amazon_montreal->scheduled_duetime or "N/A"}}</td>
                                                </tr>--}}
                                                {{--<tr>
                                                    <td><label>Actual Arrival @ CX</label></td>
                                                    <td>{{$amazon_montreal->arrival_time or "N/A"}}</td>
                                                </tr>--}}
                                                <tr>
                                                    <td><label>Estimated Delivery ETA</label></td>
                                                    <td>{{$amazon_montreal->departure_time or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Pickup From Hub</label></td>
                                                    <td>{{$amazon_montreal->picked_hub_time or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Sorter Time</label></td>
                                                    <td>{{$amazon_montreal->sorter_time or "N/A"}}</td>
                                                </tr>

                                                {{--<tr>
                                                    <td><label>Time Open</label></td>
                                                    <td>{{$amazon_montreal->start_time or "N/A"}}</td>
                                                </tr>--}}
                                                {{--<tr>
                                                    <td><label>Time close</label></td>
                                                    <td>{{$amazon_montreal->end_time or "N/A"}}</td>
                                                </tr>--}}
                                                {{--<tr>
                                                    <td><label>Drop Off</label></td>
                                                    <td>{{$amazon_montreal->dropoff_eta or "N/A"}}</td>
                                                </tr>--}}
                                                {{--<tr>
                                                    <td><label>Delivery Time</label></td>
                                                    <td>{{$amazon_montreal->delivery_time or "N/A"}}</td>
                                                </tr>--}}
                                                <tr>
                                                    <td><label>Signature</label></td>
                                                    <td>{{$amazon_montreal->signature or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Order Status</label></td>
                                                    <td>{{ $status[$amazon_montreal->sprint_status] or "N/A" }}</td>
                                                </tr>
                                                {{--<tr>
                                                    <td><label>Task Status</label></td>
                                                    <td>{{$amazon_montreal->task_status or "N/A"}}</td>
                                                </tr>--}}
                                                {{--<tr>
                                                    <td><label>Merchant #</label></td>
                                                    <td>{{$amazon_montreal->merchant_order_num or "N/A"}}</td>
                                                </tr>--}}
                                                <tr>
                                                    <td><label>Image</label></td>
                                            
                                                    <td>
                                                        @if($amazon_montreal->image)
                                                            <img onClick="ShowLightBox(this);" src="{{$amazon_montreal->image}}" width="200" height="200" alt="{{$amazon_montreal->order_id}}"/>
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                                {{--<tr>
                                                    <td><label>Vender #</label></td>
                                                    <td>{{$amazon_montreal->vendor_id or "N/A"}}</td>
                                                </tr>--}}

                                                </tbody>
                                            </table>
                                            <!-- end user projects -->

                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

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