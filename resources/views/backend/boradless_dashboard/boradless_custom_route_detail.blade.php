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
    '147' => 'Scanned at Hub',
    '148' => 'Scanned at Hub and labelled',
    '149' => 'pick from hub',
    '150' => 'drop to other hub',
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
    '155' => 'To be re-attempted tomorrow');
?>
@extends( 'backend.layouts.app' )



@section('title', 'Toronto Custom Route Order')

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
                    <h3> {{"CR-".$sprintId}} </h3>
                </div>


            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Toronto Customer Route Order Details
                                <small></small>
                            </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-2 col-sm-2 col-xs-12 profile_left">

                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab"
                                                                                  role="tab" data-toggle="tab"
                                                                                  aria-expanded="true">Order Info </a>
                                        </li>
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                             aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <table class="table table-bordered">
                                                <?php foreach($data as $response){
                                                //dd($response->joey_lastname)
                                                ?>
                                                <thead>
                                                <tr>
                                                    {{-- <th colspan="2" >Task Info </th>--}}
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><label>Tracking Id</label></td>
                                                    <td>@if(str_contains($response->tracking_id, 'old_'))
                                                            {{substr($response->tracking_id, strrpos($response->tracking_id, '_') + 1).' (Reattempted Order)'}}
                                                        @else
                                                            {{$response->tracking_id}}
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Merchant Order Num</label></td>
                                                    <td>{{$response->merchant_order_num}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Route No</label></td>
                                                    <td>
                                                        @if($response->route_id)
                                                            {{$response->route_id ."-".$response->stop_number}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <lable>Customer Name</lable>
                                                    </td>
                                                    <td>{{$response->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Phone</label></td>
                                                    <td>{{$response->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Email</label></td>
                                                    <td>{{$response->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Address</label></td>
                                                    <td>@if($response->creator_id !=477260 && $response->creator_id!=477282)
                                                            {{$response->address_line2 }}
                                                        @else
                                                            {{$response->address }}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><label>Joey</label></td>
                                                    <td>@if($response->joey_id)
                                                            {{$response->joey_firstname." ".$response->joey_lastname." (".$response->joey_id.")" }}
                                                        @endif</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Joey contact</label></td>
                                                    <td>{{$response->joey_contact }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Merchant</label></td>
                                                    <td>{{$response->merchant_name }}</td>
                                                </tr>


                                                <?php
                                                $statuses = array_merge($response['status'], $response['status1'], $response['status2']
                                                );

                                                $sort_key = array_column($statuses, 'created_at');
                                                $sort_id_key = array_column($statuses, 'id');
                                                array_multisort($sort_key, SORT_ASC, $statuses);

                                                $status_array = array_merge(getStatusCodes('competed'), getStatusCodes('return'));
                                                if (array_intersect($status_array, $sort_id_key))
                                                {
                                                ?>
                                                <tr>

                                                    <?php
                                                    $image = \App\SprintConfirmation::where('task_id', '=', $response['id'])->whereNotNull('attachment_path')->orderBy('id', 'desc')->first();
                                                    if(!empty($image))
                                                    {
                                                    ?>
                                                        <td><label>Image</label></td>
                                                    <td>
                                                        <img onclick="ShowLightBox(this);"
                                                             src="{{$image->attachment_path}}" width='300' height='200'
                                                             alt={{"CR-".$response['sprint_id']}}/>
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php }?>

                                                <tr>
                                                    <?php
                                                    if(str_contains($response->tracking_id, 'old_')){
                                                        $tracking_id = substr($response->tracking_id, strrpos($response->tracking_id, '_') + 1);
                                                    }
                                                    else{
                                                        $tracking_id = $response->tracking_id;
                                                    }
                                                    $order_image = \App\OrderImage::where('tracking_id', $tracking_id)->whereNull('deleted_at')->orderBy('id','asc')->get();
                                                    if(!empty($order_image))
                                                    {
                                                    ?>
                                                    <td><label>Additional Image</label></td>
                                                    <td>
                                                        @foreach($order_image as $img)
                                                            <img onclick="ShowLightBox(this);"
                                                                 src="{{$img->image}}" width='300' height='200' style="margin: 4px;"
                                                                 alt={{"CR-".$response['sprint_id']}}/>
                                                        @endforeach
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- end user projects -->

                                            <h5 style="clear:both;text-align:left" class="accordion">
                                                <button class="btn btn-xs orange-gradient color:#000 !important;">Status
                                                    History
                                                    <i class="fa fa-angle-down"></i></button>
                                            </h5>
                                            <table id="main" class="table table-striped table-bordered panel"
                                                   style="display: none">

                                                <thead>
                                                <tr>
                                                    <th id="main">Code</th>
                                                    <th id="main">Description</th>
                                                    <th id="main">Date</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php
                                                // dd($response);


                                               /* foreach ($statuses as $status) {
                                                    echo "<tr>";
                                                    echo "<td>" . $status['id'] . "</td>";
                                                    echo "<td>" . $status['description'] . "</td>";
                                                    echo "<td>" . date("Y-m-d H:i:s", strtotime($status['created_at'])) . "</td>";
                                                    //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
                                                    echo "</tr>";
                                                }*/
                                                $firstattempt=[];
                                                $secondattempt=[];
                                                $thirdattempt=[];

                                                if(!empty($response['status2'])){

                                                    $firstattempt = $response['status2'];
                                                    $secondattempt = $response['status'];
                                                    $thirdattempt = $response['status1'];

                                                }
                                                else if(!empty($response['status'])){
                                                    $firstattempt = $response['status'];
                                                    $secondattempt = $response['status1'];
                                                }
                                                else{
                                                    $firstattempt = $response['status1'];
                                                }

                                                echo "<tr class='reattemptrow'>";
                                                echo "<td colspan='3'><strong>First Attempt</strong></td>";
                                                echo "</tr>";
                                                foreach ($firstattempt as $status){
                                                    echo "<tr>";
                                                    echo "<td>".$status['id']."</td>";
                                                    echo "<td>".$status['description']."</td>";
                                                    echo "<td>".$status['created_at']."</td>";
                                                    //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
                                                    echo "</tr>";
                                                }
                                                if( $secondattempt)
                                                {
                                                    echo "<tr class='reattemptrow'>";
                                                    echo "<td colspan='3'><strong>Second Attempt</strong></td>";
                                                    echo "</tr>";
                                                }

                                                foreach ( $secondattempt as $status){
                                                    if($status['id']==125 || $status['id']==61){
                                                        continue;
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>".$status['id']."</td>";
                                                    echo "<td>".$status['description']."</td>";
                                                    echo "<td>".$status['created_at']."</td>";
                                                    echo "</tr>";
                                                }
                                                if($thirdattempt)
                                                {
                                                    echo "<tr class='reattemptrow'>";

                                                    echo "<td colspan='3'><strong>Third Attempt</strong></td>";

                                                    echo "</tr>";
                                                }
                                                foreach ( $thirdattempt  as $status){
                                                    if($status['id']==125 || $status['id']==61){
                                                        continue;
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>".$status['id']."</td>";
                                                    echo "<td>".$status['description']."</td>";
                                                    echo "<td>".$status['created_at']."</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                            <?php }?>
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
    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "inline-table") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "inline-table";
                }
            });
        }

    </script>

@endsection