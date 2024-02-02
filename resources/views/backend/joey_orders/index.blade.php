<?php
use App\Joey;
$statusId = array(
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
    "147" => "Scanned at hub",
    "148" => "Scanned at Hub and labelled",
    "149" => "Bundle Pick From Hub",
    "150" => "Bundle Drop To Hub",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow'

);
$statuses = array(
    "136" => "Client requested to cancel the order",
    "135" => "Customer refused delivery",
    "108" => "Customer unavailable-Incorrect address",
    "106" => "Customer unavailable - delivery returned",
    "107" => "Customer unavailable - Left voice mail - order returned",
    "109" => "Customer unavailable - Incorrect phone number",
    "142" => "Damaged at hub (before going OFD)",
    "143" => "Damaged on road - undeliverable",
    "110" => "Delivery to hub for re-delivery",
    "111" => "Delivery to hub for return to merchant",
    "102" => "Joey Incident",
    "104" => "Damaged on road - delivery will be attempted",
    "105" => "Item damaged - returned to merchant",
    "101" => "Joey on the way to pickup",
    "112" => "To be re-attempted",
    "131" => "Office closed - returned to hub",
    "145" => "Returned To Merchant",
    "146" => "Delivery Missorted, Incorrect Address",
    "147" => "Scanned at hub",
    "148" => "Scanned at Hub and labelled",
    "149" => "Bundle Pick From Hub",
    "150" => "Bundle Drop To Hub",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow'
);
?>

@extends( 'backend.layouts.app' )

@section('title', 'Joey Orders')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!--joey-custom-css-->
    <link href="{{ backend_asset('css/joeyco-custom.css') }}" rel="stylesheet">
    <style>


        .search-order {
            background-color: #c6dd38;
        }

        .tracking_id
        {
            margin-top: 26px;
        }
        .input-error {
            color: red;
            padding: 10px 0px;
        }
        .form-submit-btn {
            margin-top: 26px;
            width: 100%;
            background-color: #c6dd38;
        }
        .filter-out-button .filter-out
        {
            margin-top: 26px;
            text-align: center;
            background-color: #c6dd38;
        }
        .show-notes{
            background-color: #C6DD38;
            border-style:none;
            padding: 6px 9px 6px 9px;
        }
        /* dragable div for route count */

        #mydiv {
            position: absolute;
            z-index: 9;
            background-color: #f1f1f1;
            text-align: center;
            border: 1px solid #d3d3d3;
        }

        #mydivheader {
            padding: 10px;
            cursor: move;
            z-index: 10;
            background-color: #c7dd1f;
            color: black;
        }

        .add-inst{
            background-color: #c7dd1f;
            padding: 2px;
        }
        .instruction-text{
            margin-bottom:5px;
            border-radius: 5px;
            margin-right:5px;
            padding: 10px;
            width: 100%;
        }
        .track-instruction{
            padding: 5px;
        }
        .inst-model-btn{
            margin: 10px;
            background-color: #c8dd00;
            border: #c8dd00;
            color: black;
        }
        .close-button{
            color: white;
            padding: 5px;
            margin-top: 4px;
        }
        .heading-joey-orders{
            margin-top: 20px!important;
        }
    </style>


@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&libraries=places" type="text/javascript"></script>
    <!-- Custom JavaScript -->
    <script src="{{ backend_asset('js/joeyco-custom-script.js')}}"></script>
@endsection

@section('inlineJS')

    <script>

        // $(document).ready(function () {
        //     $('.return-order-datatable').dataTable({
        //         "lengthMenu": [ 250, 500, 750, 1000 ],
        //         scrollX: true,   // enables horizontal scrolling,
        //         scrollCollapse: true,
        //         /*columnDefs: [
        //             { width: '20%', targets: 0 }
        //         ],*/
        //         fixedColumns: true,
        //     });
        // });

        $(document).on('click', '.instruction', function(e) {
            e.preventDefault();
            var trackingId = this.getAttribute('data-tracking-id');
            var taskId = this.getAttribute('data-task-id');

            $('.track-instruction').text('Add Instruction ('+ trackingId + ')');
            $('#tracking_id').val(trackingId);
            $('#task_id').val(taskId);
            $('#instruction').modal();
            return false;
        });


        function instruction() {
            let instruction = $('#joey-instruction').val();
            let trackingId = $('#tracking_id').val();
            let taskId = $('#task_id').val()



            // $('#transfer .order-id').text("R-" + routeId);

            if(instruction  == 'undefined' || instruction == ''){
                $('.alert-message-model').html('<div class="alert alert-danger alert-red"><button style="color:#f5f5f5"; type="button" class="close" data-dismiss="alert"><strong><b><i  class="fa fa-close"></i><b></strong></button><strong>Please Insert Instruction</strong>');
                return false;
            }
            else{

                $.ajax({
                    type: "POST",
                    url: '../joey/order/instruction',
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    data:{instruction : instruction, tracking_id:trackingId, task_id: taskId},
                    success: function (data) {
                        var res = JSON.parse(data)
                        if(res.status === 200){
                            $('.alert-message').html('<div class="alert alert-success alert-green"><button style="color:#f5f5f5"; type="button" class="close" data-dismiss="alert"><strong><b><i  class="fa fa-close"></i><b></strong></button><strong>' + res.message + '</strong>');
                            $('#instruction').modal('hide');
                        }else{
                            $('.alert-message').html('<div class="alert alert-danger alert-red"><button style="color:#f5f5f5"; type="button" class="close" data-dismiss="alert"><strong><b><i  class="fa fa-close"></i><b></strong></button><strong>' + res.message + '</strong>');
                            $('#instruction').modal('hide');
                        }

                        document.getElementById("joey-instruction").value = "";
                    },
                    error: function (error) {
                    }
                });
            }



        }

    </script>
@endsection

@section('content')
{{--    @include( 'backend.layouts.loader' )--}}
    <div id="map"></div>
    <div class="right_col" role="main">
        <div class="page-title">
            <div class="title_left">
                <h3> Search by joey
                    <small></small>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix"></div>
                        <form class="search-tracking-id" action="" method="get">
                            <div class="row customColBox">
                                <div class="col-sm-4 col-md-4 colBox">
{{--                                    <input class="form-control joey_id" name="joey_id" type="text" placeholder="Joey Id" required="required"/>--}}
                                    <select  id="joey_id"  name="joey_id" class="form-control js-example-basic-multiple" required>
                                        <option value="">Please Select Joey</option>
                                        @foreach($joeys as $joey)
                                            <option value="{{ $joey->id }}">{{ $joey->first_name . " " . $joey->last_name .'(' . $joey->id . ')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-md-1 colBox">
                                    <button class="form-control search-order" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                        <h2 class="heading-joey-orders">Joey Orders List</h2>
                        <div class="clearfix"></div>
                    </div>
{{--                    @include( 'backend.layouts.notification_message' )--}}
                    <div class="alert-message"></div>
                    <div class="x_content">
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        @if(session()->has('error'))
                            <div class="alert alert-error">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <table class="table table-striped table-bordered joey-order-datatable" data-form="deleteForm">
                            <thead>
                            <tr>
                                <th style="width: 8%">Order Id</th>
                                <th style="width: 10%">Tracking Id</th>
                                <th style="width: 10%">Route Id</th>
                                <th style="width: 10%">Joey Id</th>
                                <th style="width: 7%">Status</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($routeData != null)
                                @foreach($routeData as $data)
                                    <tr class="tr-no">

                                        <td>{{$data->sprint_id}}</td>
                                        <td>{{$data->tracking_id}}</td>
                                        <td>{{$data->route_id}}</td>
                                        <td>{{ $data->joey_name . '(' . $data->joey_id . ')' }}</td>
                                        <td>{{$statusId[$data->task_status_id]}}</td>
                                        <td>
                                            <button class="btn btn-primary instruction" id="add-instruction" data-tracking-id="{{ $data->tracking_id }}" data-task-id="{{ $data->task_id }}">Add Instruction</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>No Record Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


<div id="instruction" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="add-inst">
                    <h2 class="order-id green text-center" style="color: black">Add Instruction</h2>
                </div>
                <div>
                    <strong class="alert-message-model col-md-12" style="margin-top: 10px;"></strong>
                </div>
                <form action='' method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12">
                        <label class="col-md-12 track-instruction"></label>
                        <textarea required="" class="instruction-text" rows="5" name="joey-instruction" id="joey-instruction"></textarea>
                        <input type="hidden" name="tracking_id" id="tracking_id">
                        <input type="hidden" name="task_id" id="task_id">
                    </div>
                    <br>
                    <a type="submit" data-selected-row="false"  onclick="instruction()"
                       class="btn green-gradient inst-model-btn" style="">Submit</a>
                    <a class="btn black-gradient btn-danger close-button" data-dismiss="modal" aria-hidden="true">Close</a>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection
