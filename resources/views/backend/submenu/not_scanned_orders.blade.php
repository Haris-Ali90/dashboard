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
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');

$user = Auth::user();
if($user->email!="admin@gmail.com")
{

    $data = explode(',', $user['rights']);
    $dataPermission = explode(',', $user['permissions']);
}

else{
    $data = [];
    $dataPermission=[];
}

?>
@extends( 'backend.layouts.app' )

@section('title', 'Montreal Dashboard')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
@endsection

@section('inlineJS')
    <!-- <script type="text/javascript">
   $( function() {
    $( "#datepicker" ).datepicker({changeMonth: true,
      changeYear: true, showOtherMonths: true,
      selectOtherMonths: true}).attr('autocomplete','off');
  } );
  </script> -->

    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').DataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ]
            });

            $(".group1").colorbox({height:"50%",width:"50%"});

            $(document).on('click', '.status_change', function(e){
                var Uid = $(this).data('id');

                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to change user status??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {

                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo URL::to('/'); ?>/api/changeUserStatus/"+Uid,
                                    data: {},
                                    success: function(data)
                                    {
                                        if(data== '0' || data== 0 )
                                        {
                                            var DataToset = '<button type="button" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                        else
                                        {
                                            var DataToset = '<button type="button" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Active</button>'
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                    }
                                });

                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete user ??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                $form.submit();
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

        });

    </script>
    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>

@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">{{ $title_name }} Not Scanned Orders<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
            <div class="row">

                <!--top_tiles Div Open-->
                <div class="top_tiles montreal-dashbord-tiles">

                    <!--Animated-a Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-cubes"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->total:0 }}</div>
                            <h3>Total Orders</h3>
                        </div>
                    </div>
                    <!--Animated-a Div Close-->

                    <!--Animated-b Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-truck"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->mainfestOrders:0 }}</div>
                            <h3> Order From Mainfest</h3>
                        </div>
                    </div>
                    <!--Animated-b Div Close-->

                    <!--Animated-c Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-rotate-left"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->returnOrder:0 }}</div>
                            <h3> Returns Orders</h3>
                        </div>
                    </div>
                    <!--Animated-c Div Close-->

                    <!--Animated-d Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-sort-amount-asc"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->sorter_order_count:0 }}</div>
                            <h3> Total Sorted Orders</h3>
                        </div>
                    </div>
                    <!--Animated-d Div Close-->

                    <!--Animated-e Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-car"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->hub_deliver_order_count:0 }}</div>
                            <h3> Picked up from Hub Orders</h3>
                        </div>
                    </div>
                    <!--Animated-e Div Close-->

                    <!--Animated-f Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-cube"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->deliver_order_count:0 }}</div>
                            <h3> Total Delivered Orders</h3>
                        </div>
                    </div>
                    <!--Animated-f Div Close-->

                    <!--Animated-g Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div class="count">{{ isset($amazon_count)?$amazon_count->failedOrders:0 }}</div>
                            <h3> Not Scan</h3>
                        </div>
                    </div>
                    <!--Animated-g Div Close-->

                </div>
                <!--top_tiles Div Close-->

            </div>
            <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ $title_name }} Not Scanned Orders</small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title">
                            <form method="get" action="scan">
                                <label>Search By Date</label>
                                <input type="date" name="datepicker" required="" placeholder="Search">
                                <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">Go</a> </button>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                    <thead stylesheet="color:black;">
                    <tr>
                        <th>JoeyCo Order #</th>
                        <th>Route Number</th>
                        <th>Joey</th>
                        <th>Customer Address</th>
                        <th>Pickup From Hub</th>
                        <th>Sorter Time</th>
                        <th>Estimated Delivery ETA</th>
                        <th>Actual Arrival @ CX</th>
                        <th>Image</th>
                        <th>Amazon tracking #</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                      </thead>
                      <tbody>
                    @foreach( $scan_order as $record )
                      <tr>
                          <td>{{ $record->order_id }}</td>
                          <td>{{ $record->route }}</td>
                          <td>{{ $record->joey }}</td>
                          <td>{{ $record->address }}</td>
                          <td>{{ $record->picked_hub_time }}</td>
                          <td>{{ $record->sorter_time }}</td>
                          <td>{{ $record->dropoff_eta }}</td>
                          <td>{{ $record->delivery_time }}</td>
                          @if(isset($record->image))
                              <td><a class="group1" href="{{$record->image}}" ><img style="width:50px;height:50px" src="{{$record->image}}" /></a>
                              </td>
                          @else
                              <td></td>
                          @endif
                          <td>{{ $record->tracking_id }}</td>
                          <td>{{ $status[$record->sprint_status] }}</td>
                        <td><a href="{{backend_url('profile/montreal/'.$record->id)}}" class="btn btn-primary btn-xs" style="float: left;"><i class="fa fa-folder"></i></a>
                        </td>


                      </tr>
                     @endforeach
                      </tbody>
  </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection