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
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
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

@section('title', 'CTC Route Info')

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
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"
            type="text/javascript"></script>

    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->

@endsection

@section('inlineJS')

    <script>
        $('#datatable').DataTable({
            "lengthMenu": [250, 500, 750, 1000],
            "pageLength": 250
        });

        /*  $('.buttons-excel').on('click', function (event) {

              event.preventDefault();

              alert('Processing for Download csv !')

              //showloader()


              //window.open(encodedUri);

              console.log('yes');
              let selected_date = $('.data-selector').val();
              $.ajax({
                  type: "get",
                  url: '{{ URL::to('ctc/route-info/list/') }}/' + selected_date,
                data: {},
                success: function (data) {
                    //hideloader()
                    // checking the rows of csv
                    /!*if(data.length <= 0)
                    {
                        alert('There is no data to download !');
                        return;
                    }*!/

                    let csvContent = "data:text/xls;charset=utf-8,";
                    data.forEach(function (rowArray) {
                        let row = rowArray.join(",");
                        csvContent += row + "\r\n";
                    });
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "Ctc-route-info-" + selected_date + ".csv");
                    document.body.appendChild(link); // Required for FF

                    link.click(); // This will download the data file named "my_data.csv".

                },
                error: function (error) {
                    //hideloader()
                    console.log(error);
                    alert('something went wrong !');
                }
            });

        });
*/

        //javascript function for excel download
        $(document).ready(function () {
            var table = $('#datatable').DataTable();
            $('#btnExport').unbind().on('click', function () {
                $('<table>')
                    .append($(table.table().header()).clone())
                    .append(table.$('tr').clone())
                    .table2excel({
                        exclude: "#actiontab",
                        filename: "CTC-route-info",
                        fileext: ".csv",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true
                    });
            });
        })


        /*-$(function () {
            appConfig.set('yajrabox.ajax', '{{-- route('montreal.route-info.data') --}}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.ajax.data', function (data) {
            //data.datepicker = jQuery('[name=datepicker]').val();
        });

        appConfig.set('yajrabox.columns', [
            {data: 'id',   orderable: true,   searchable: true },
            {data: 'total_no_drops', orderable: true,   searchable: true},
            {data: 'total_no_orders_picked',   orderable: true,   searchable: true},
            {data: 'total_no_drops_completed',   orderable: true,   searchable: true},
            {data: 'total_no_return',   orderable: true,   searchable: true},
            {data: 'total_no_unattempted',   orderable: true,   searchable: true},
        ]);
    });*/

    </script>

@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Canadian Tire Route Info
                        <small></small>
                    </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
            <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>CTC
                                <small>Route Info</small>
                            </h2>
                            @if(can_access_route('export_CTCRouteInfo.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right">
                                    {{--  <a href="{{ route('export_CTCRouteInfo.excel') }}"
                                         class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass" download>
                                          Export to Excel
                                      </a>--}}
                                    <button id="btnExport"
                                            class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                        Export to Excel
                                    </button>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                            <form method="get" action="">
                                <label>Search By Date</label>
                                <input type="date" name="datepicker" class="data-selector" required=""
                                       value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                                       placeholder="Search">
                                <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                                    Go</a> </button>
                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead stylesheet="color:black;">
                                    <tr>

                                        <th class="text-center ">JoeyCo Order #</th>
                                        <th class="text-center ">Route #</th>
                                        <th class="text-center ">Joey</th>
                                        <th class="text-center ">Shipment tracking #</th>
                                        <th class="text-center ">Estimated Delivery ETA</th>
                                        <th class="text-center ">Shipment Delivery Status</th>
                                        <th class="text-center ">Store Name</th>
                                        <th class="text-center ">Customer Name</th>
                                        <th class="text-center ">Weight</th>
                                        <th class="text-center ">Customer Address</th>
                                        <th id="actiontab">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $ctc_dashboard as $record )
                                        <tr>
                                            <td>{{ $record->id }}</td>

                                            <td>
                                                @if($record->sprintCtcTasks)
                                                    @if($record->sprintCtcTasks->taskRouteLocation)
                                                        R-{{$record->sprintCtcTasks->taskRouteLocation->route_id}}-
                                                        {{$record->sprintCtcTasks->taskRouteLocation->ordinal}}
                                                    @else

                                                        {{" "}}
                                                    @endif
                                                @else
                                                    {{" "}}
                                                @endif

                                            </td>
                                            <td>
                                                @if($record->sprintCtcTasks)
                                                    @if($record->sprintCtcTasks->taskRouteLocation)
                                                        @if($record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey)
                                                            {{$record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->first_name.' '.$record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey->last_name
                                                            .' ('
                                                                    .$record->sprintCtcTasks->taskRouteLocation->joeyRoute->joey_id.')'
                                                            }}
                                                        @else
                                                            {{" "}}
                                                        @endif
                                                    @else
                                                        {{" "}}
                                                    @endif
                                                @else
                                                    {{" "}}
                                                @endif
                                            </td>

                                            <td>
                                                @if($record->sprintCtcTasks)
                                                    @if($record->sprintCtcTasks->taskMerchants)
                                                        {{substr($record->sprintCtcTasks->taskMerchants->tracking_id, strrpos($record->sprintCtcTasks->taskMerchants->tracking_id, '_' )+0)}}
                                                    @endif
                                                @endif</td>

                                            <td>  @if($record->sprintCtcTasks)
                                                    {{date('Y-m-d H:i:s', strtotime("+1 day", strtotime($record->sprintCtcTasks->eta_time)))}}
                                                @endif</td>
                                         <?php

                                            $current_status = $record->status_id;
                                            if ($record->status_id == 17) {
                                            $preStatus = \App\SprintTaskHistory
                                                ::where('sprint_id','=' , $record->id )
                                                ->where('status_id', '!=','17')
                                                ->orderBy('id','desc')->first();
                                            if (!empty($preStatus)){
                                                $current_status = $preStatus->status_id;
                                                }
                                                }
                                            ?>

                                            <td>
											@if($current_status==13)
												At hub Processing
													
@else													
													{{$status[$current_status]}}
												@endif
                                                </td>
                                            <td>{{$record->sprintVendor->name}}</td>

                                            <td>  @if($record->sprintCtcTasks)
                                                    {{$record->sprintCtcTasks->taskContact->name}}
                                                @endif</td>

                                            <td>
                                                @if($record->sprintCtcTasks)
                                                    @if($record->sprintCtcTasks->taskMerchants)
                                                        {{$record->sprintCtcTasks->taskMerchants->weight.$record->sprintCtcTasks->taskMerchants->weight_unit}}
                                                    @endif
                                                @endif</td>
                                            <td>
                                                @if($record->sprintCtcTasks)
                                                    @if($record->sprintCtcTasks->taskMerchants)
                                                        {{$record->sprintCtcTasks->taskMerchants->address_line2}}
                                                    @endif
                                                @endif</td>

                                            <td id="actiontab">
                                                
                                                <a href="{{backend_url('ctc/new/detail/'.$record->id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> Details
                                                </a>
                                                
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

    <!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

    <!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

    <!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->



@endsection