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

@section('title', 'Client Dashboard')

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
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
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
                "lengthMenu": [250, 500, 750, 1000]
            });

            $(".group1").colorbox({height: "50%", width: "50%"});

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
    </script>

    <script>

        $(function () {
            appConfig.set('yajrabox.ajax', '{{ route('newctc-dashboard.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            //appConfig.set('yajrabox.scrollx_responsive',true);
			appConfig.set('dt.searching',false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.datepicker = jQuery('[name=datepicker]').val();
                data.tracking_id = jQuery('[name=tracking_id]').val();
				data.route_id = jQuery('[name=route_id]').val();
                data.status = jQuery('select[name=status]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'id', orderable: false, searchable: false},
                {data: 'route_id', orderable: false, searchable: false},
                {data: 'joey', orderable: false, searchable: false},
                {data: 'tracking_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'eta_time', orderable: false, searchable: false, className: 'text-center'},
                {data: 'status_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'store_name', orderable: false, searchable: false},
                {data: 'customer_name', orderable: false, searchable: false},
                {data: 'weight', orderable: false, searchable: false, className: 'text-center'},
                {data: 'address', orderable: false, searchable: false, className: 'text-center'},
                {data: 'action', orderable: false, searchable: false, className: 'text-center'}
            ]);
        })

        //pageRefresh();
       /* $(document).ready(function () {
            setInterval(function () {
                $("#ctcCards").load(window.location.href + " #ctc-dashbord-tiles-id");
                var ref = $('#yajra-reload').DataTable();
                ref.ajax.reload();
            }, 50000);
        });*/

        $('.buttons-excel').on('click', function (event) {
            event.preventDefault();
            let href = $(this).attr('href');
            let selected_date = $('.data-selector').val();
            window.location.href = href + '/' + selected_date;
        });
    </script>
@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Client Dashboard<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
       {{-- @include('backend.ctc.ctc_new_cards')--}}
        <!--Count Div Row Close-->
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Client Dashboard<small></small></h2>

                                <div class="excel-btn" style="float: right">
                                    <a  href="{{ route('export_ctc_new_dashboard.excel') }}"
                                       class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass" target="_blank">
                                        Export to Excel
                                    </a>
                                </div>

                            <div class="clearfix"></div>
                        </div>

                         <div class="x_title">
                            <form method="get" action="ctc-dashboard">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Search By Date :</label>
                                        <input type="date" name="datepicker" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                                               >
                                    </div>
                                    <div class="col-md-2">
                                        <label>Search By Tracking ID</label>
                                        <input type="text" name="tracking_id" class="form-control"
                                               value="{{ isset($_GET['tracking_id'])?$_GET['tracking_id']: "" }}"
                                               placeholder="Tracking Id">
                                    </div>
									<div class="col-md-2">
                                        <label>Search By Route ID</label>
                                        <input type="text" name="route_id" class="form-control"
                                               value="{{ isset($_GET['route_id'])?$_GET['route_id']: "" }}"
                                               placeholder="Route Id">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Select Status:</label>
                                        {!! Form::select('status', [null=>'Please Select'] + $status_code, null, ['class' => 'js-example-basic-multiple form-control']) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary" type="submit" style="       margin-top: 24px;
    padding-bottom: 9px;">
                                            Go</a> </button>
                                    </div>
                                </div>



                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )


                            <table class="table table-striped table-bordered yajrabox" id="yajra-reload">
                                <thead stylesheet="color:black;">
                                <tr>
                                    <th class="text-center ">JoeyCo Order #</th>
                                    <th class="text-center ">Route #</th>
                                    <th class="text-center ">Joey</th>
                                    <th class="text-center ">Shipment Tracking #</th>
                                    <th class="text-center ">Estimated Customer Delivery Time</th>
                                    <th class="text-center ">Shipment Delivery Status</th>
                                    <th class="text-center ">Store Name</th>
                                    <th class="text-center ">Customer Name</th>
                                    <th class="text-center ">Weight</th>
                                    <th class="text-center ">Customer Address</th>
                                    <th class="text-center ">Action</th>


                                </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>



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