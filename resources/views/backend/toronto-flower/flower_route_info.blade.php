<?php

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

@section('title', 'Toronto Flower Route Info')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
        .modal-dialog.map-model {
            width: 94%;
        }
        .btn{
            font-size : 12px;
        }

        .modal.fade {
            opacity: 1
        }

        .modal-header {
            font-size: 16px;
        }

        .modal-body h4 {
            background: #f6762c;
            padding: 8px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #fff;
        }

        .form-control {
            display: block;
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        }

        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
        }

        .form-group {
            margin-bottom: 15px;
        }

        #ex1 form{
            padding: 10px;
        }
        div#delay .modal-content, div#details .modal-content {
            padding: 20px;
        }

        #details .modal-content {
            overflow-y: scroll;
            height: 500px;
        }
    </style>
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


        //javascript function for excel download
        $(document).ready(function(){ var table = $('#datatable').DataTable();
            $('#btnExport').unbind().on('click', function(){
                $('<table>')
                    .append($(table.table().header()).clone())
                    .append(table.$('tr').clone())
                    .table2excel({
                        exclude: "#actiontab",
                        filename: "Toronto-Flower-route-info",
                        fileext: ".csv",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true
                    });  });      })







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

    <script>
        $(document).on('click', '.delay', function(e) {

            e.preventDefault();

            var routeId = this.getAttribute('data-route-id');
            $('#route-id').val(routeId);

            $('#delay').modal();
            $('#delay .order-id').text("Route No. : " + routeId);

            return false;
        });


        function mark_delay() {
            let date = $('#delay-date').val();
            let route_id = $('#route-id').val()

             $.ajax({
                    type: "POST",
                    url: '../route/mark/delay',
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    data:{date : date,route_id:route_id},
                    success: function (data) {

                            alert('Your route has been mark delay')
                        $('#delay').modal('toggle');

                    },
                    error: function (error) {
                    }
                });
        }
    </script>
@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Toronto Flower Route Info<small></small></h3>
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
                            <h2>Toronto Flower <small>Route Info</small></h2>
                          @if(can_access_route('export_toronto_flower_route_info.excel',$userPermissoins))
                            <div class="excel-btn" style="float: right">

                                <button id="btnExport" class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">Export to Excel</button>
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
                                        <th class="text-center ">Route #</th>
                                        <th class="text-center ">Joey Name</th>
                                        <th class="text-center "># of drops</th>
                                        <th class="text-center "># of sorted</th>
                                        <th class="text-center "># of picked</th>
                                        <th class="text-center "># of drops completed</th>
                                        <th class="text-center "># of Returns</th>
                                          <th class="text-center "># of Not Scan</th>
                                        <th class="text-center "># of unattempted</th>
												<th class="text-center ">Total Durations</th>
                                        <th id="actiontab">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $flower_info as $record )
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>
                                                @if($record->joey)
                                                       {{$record->Joey->first_name.' '.$record->Joey->last_name}}
                                                @else
                                                    {{" "}}
                                                @endif
                                            </td>
                                            <td>{{$record->TotalOrderDropsCount()}}</td>
                                            <td>{{$record->TotalSortedOrdersCount()}}</td>
                                            <td>{{$record->TotalOrderPickedCount()}}</td>
                                            <td>{{$record->TotalOrderDropsCompletedCount()}}</td>
                                            <td>{{$record->TotalOrderReturnCount()}}</td>
                                            <td>{{$record->TotalOrderNotScanCount()}}</td>
                                            <td>{{$record->TotalOrderUnattemptedCount()}}</td>
												<td> {{$record->EstimatedTime()}}</td>
                                            <td id="actiontab">
                                                @if(can_access_route('toronto_flower_route.detail',$userPermissoins))
                                                <a href="{{backend_url('toronto/flower/route/'.$record->id.'/edit/hub/20')}}" title="Route Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;">Route Details
                                                </a>
                                                @endif

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
    <div id="delay" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-body">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <p><strong class="order-id green"></strong></p>
                    <form method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <label>Please Select Date</label>
                        <input type="date" name="delay_date" id="delay-date" class="data-selector" required=""
                               value="{{ date('Y-m-d') }}"
                        >
                        <input type="hidden" name="route_id" id="route-id">
                        <br>
                        <br>
                        <a type="submit" data-selected-row="false"  onclick="mark_delay()" class="btn btn-success transfer-model-btn">Submit</a>
                        <a class="btn btn-warning " data-dismiss="modal" aria-hidden="true">Close</a>
                    </form>
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