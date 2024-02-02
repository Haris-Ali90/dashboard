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

@section('title', 'Returned Order')

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

    .notes-td {
        min-width: 224px;
    }
    .show-notes{
        border-style:none;
        padding: 6px 9px 6px 9px;
    }
    /*Close Css For Button Group*/
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
    <script src="{{ backend_asset('js/joeyco-custom-script.js')}}"></script>
@endsection

@section('inlineJS')

    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {
          let datatable =  $('.return-order-datatable').DataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ],
              scrollX: true,   // enables horizontal scrolling,
              scrollCollapse: true,
             /* columnDefs: [
                  { width: '20%', targets: 0 }
              ],*/
              fixedColumns: true,
            });

        });

    </script>
@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3 class="text-center">Returned Order<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Returned Order</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <!-- <div class="table-responsive"> -->
                                <!--Open Table Tracking Order List-->
                                <table class="table table-striped table-bordered return-order-datatable" data-form="deleteForm">
                                    <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Tracking Id</th>
                                        <th>Route Number</th>
                                        <th style="width: 29%">Customer Address</th>
                                        <th>Customer Phone</th>
                                        <th>Status</th>
                                        <th>Scan At</th>
                                        <th>Process At</th>
                                        <th>Scan By</th>
                                        <th>Verified By</th>
                                        <th>Verified At</th>
                                        <th>Count Of Reattempts Left</th>
                                        <th>Verify Note</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($returned_orders as $returned_order)
                                        <tr>
                                            <td>{{$returned_order->sprint_id}}</td>
                                            <td>{{$returned_order->tracking_id}}</td>
                                            <td>{{$returned_order->route_id}}</td>
                                            <td>{{$returned_order->customer_address.' '.$returned_order->postal_code}}</td>
                                            <td>{{$returned_order->customer_phone}}</td>
                                            <td>@if (isset($status[$returned_order->status_id])) {{ $status[$returned_order->status_id]}} @endif</td>
                                            <td>{{ConvertTimeZone($returned_order->created_at,'UTC','America/Toronto')}}</td>
                                            <td>
                                                @if($returned_order->proceed_at == null || is_null($returned_order->proceed_at) || $returned_order->proceed_at == 'null' )
                                                    <span class="label label-warning">Waiting for completion by routing support</span>
                                                @elseif($returned_order->proceed_at != null || !is_null($returned_order->proceed_at) || $returned_order->proceed_at != 'null' )
                                                    {{ConvertTimeZone($returned_order->proceed_at,'UTC','America/Toronto')}}
                                                @endif
                                            </td>
                                            <td>@if (isset($returned_order->user)) {{$returned_order->user->full_name}}@endif</td>
                                            <td>@if (isset($returned_order->VerifiedByUser)) {{$returned_order->VerifiedByUser->full_name}}@endif</td>
                                            <td>
                                                @if($returned_order->verified_at == null || is_null($returned_order->verified_at) || $returned_order->verified_at == 'null' )
                                                    <span class="label label-warning"></span>
                                                @elseif($returned_order->verified_at != null || !is_null($returned_order->verified_at) || $returned_order->verified_at != 'null' )
                                                    {{ConvertTimeZone($returned_order->verified_at,'UTC','America/Toronto')}}
                                                @endif
                                            </td>
                                            <td>{{$returned_order->reattempt_left}}</td>
                                            <td class="notes-td">
                                                {{$returned_order->varify_note}}
                                                <br>
                                                @if(can_access_route('add-notes',$userPermissoins))
                                                    <a href="{{backend_url('notes/'.$returned_order->id)}}" target="_blank" title="Detail" class="btn btn-primary btn-xs show-notes"><i class="fa fa-tags notes-icon"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!--Close Table Tracking Order List-->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->
@endsection