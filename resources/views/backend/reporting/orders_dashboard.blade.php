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

@section('title', 'Vendor Reporting')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />

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
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>

    {{--    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />--}}
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

            $('#datatable').dataTable({
                "lengthMenu": [50, 100, 150 ,200],
                "processing": true,
                "serverSide": true
            });
            $(".group1").colorbox({height: "50%", width: "50%"});

            $(document).on('click', '.status_change', function (e) {
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
                                    url: "<?php echo URL::to('/'); ?>/api/changeUserStatus/" + Uid,
                                    data: {},
                                    success: function (data) {
                                        if (data == '0' || data == 0) {
                                            var DataToset = '<button type="button" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="' + Uid + '" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv' + Uid).html(DataToset);
                                        } else {
                                            var DataToset = '<button type="button" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="' + Uid + '" data-target=".bs-example-modal-sm">Active</button>'
                                            $('#CurerntStatusDiv' + Uid).html(DataToset);
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

            $(document).on('click', '.form-delete', function (e) {

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
        //        $(function() {
        //
        //            $('input[name="datefilter"]').daterangepicker({
        //                autoUpdateInput: false,
        //                locale: {
        //                    cancelLabel: 'Clear'
        //                }
        //            });
        //
        //            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        //                $(this).val(picker.startDate.format('Y-m-d') + ' - ' + picker.endDate.format('Y-m-d'));
        //            });
        //
        //            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        //                $(this).val('');
        //            });
        //
        //        });


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
            appConfig.set('yajrabox.ajax', '{{ route('reporting.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.fromdatepicker = jQuery('[name=fromdatepicker]').val();
                data.todatepicker = jQuery('[name=todatepicker]').val();
                data.vendors = jQuery('select[name=vendors]').val();

            });

            appConfig.set('yajrabox.columns', [
                {data: 'id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'tracking_id',orderable: false, searchable: false, className: 'text-center'},
                {data: 'created_at' ,orderable: true, searchable: true, className: 'text-center'},
                {data: 'updated_at',orderable: true, searchable: true, className: 'text-center'},
                {data :'status_id',  orderable: true, searchable: false, className: 'text-center'},
                {data :'sprintTasks', name: 'sprintTasks.status_id',orderable: false, searchable: false, className: 'text-center'},
//                {data: 'type', orderable: true, searchable: true,className: 'text-center'},
                {data: 'route_id', orderable: false, searchable: false, className: 'text-center'},

            ]);
        })
        //$.fn.dataTable.ext.errMode = 'throw';
        //pageRefresh();
        //        $(document).ready(function () {
        //            setInterval(function () {
        //                var ref = $('#yajra-reload').DataTable();
        //                ref.ajax.reload();
        //            }, 30000);
        //        });


        $('.buttons-excel').on('click', function (event) {
            event.preventDefault();
            let href = $(this).attr('href');
            let vendor = jQuery('[name=vendors]').val();
            // alert(vendor);
            let from_date = $('#fromdatepicker').val();
            let to_date = $('#todatepicker').val();
            window.location.href = href + '/' + vendor + '/' + from_date + '/' + to_date;
        });


        $("#fromdatepicker").datetimepicker
        ({
            format :'YYYY-MM-DD',

        });

        $("#todatepicker").datetimepicker({
            format :'YYYY-MM-DD',

        });

        $("#fromdatepicker").on("dp.change", function (e) {
            $('#todatepicker').data("DateTimePicker").minDate(e.date);
        });
        $("#todatepicker").on("dp.change", function (e) {
            $('#fromdatepicker').data("DateTimePicker").maxDate(e.date);
        });
    </script>
@endsection

@section('content')


    <style type="text/css">
        .sel {
            margin-left: 15px;
            margin-right: 61px;
            width: 170px;
            height: 24px;
        }
    </style>
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Vendor Reporting
                        <small></small>
                    </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        {{--@include('backend.montrealdashboard.montreal_cards')--}}
        <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Reporting</h2>
                            @if(can_access_route('export_reporting.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right">
                                    <a href="{{ route('export_reporting.excel') }}"
                                       class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                        Export to Excel
                                    </a>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                        </div>


                        <div class="x_title">
                          {{-- <form  method="get" action="reporting">
                                <label >From Date:</label>
                                <input style="width: 170px;" id="fromdatepicker" type="text"
                                       name="fromdatepicker" class="data-selector form-control" required=""
                                       value="{{ isset($_GET['fromdatepicker'])?$_GET['fromdatepicker']: date('Y-m-d') }}"
                                       placeholder="Search">
                                <label style="margin-left: 50px">To Date:</label>
                                <input style="width: 170px;" type="text" id="todatepicker"
                                       name="todatepicker" class="data-selector form-control" required=""
                                       value="{{ isset($_GET['todatepicker'])?$_GET['todatepicker']: date('Y-m-d') }}"
                                       placeholder="Search">
                                <label style="margin-left: 50px">Select Vendor</label>
                                {!! Form::select('vendors', $vendors, null, ['class' => 'js-example-basic-multiple col-md-3 col-xs-6']) !!}
                                <button class="btn btn-primary" type="submit">
                                    Go
                                </button>
                            </form>--}}
							  <form style="margin-left: 50px" method="get" action="reporting">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label >From Date:</label>
                                        <input id="select_data_type" type="hidden" name="select_data_type">
                                        <input style="height: 38px;" id="fromdatepicker" type="text" name="fromdatepicker"
                                               class="data-selector form-control" required=""
                                               value="{{ isset($_GET['fromdatepicker'])?$_GET['fromdatepicker']: date('Y-m-d') }}"
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <label>To Date:</label>
                                        <input style="height: 38px;"
                                                type="text" id="todatepicker" name="todatepicker"
                                                class="data-selector form-control" required=""
                                                value="{{ isset($_GET['todatepicker'])?$_GET['todatepicker']: date('Y-m-d') }}"
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <label>Select Vendor:</label>
                                        {!! Form::select('vendors', $vendors, null, ['class' => 'js-example-basic-multiple form-control']) !!}
                                    </div>
                                    {{--<label style="    margin-left: 223px;">Select Vendor</label>
                                    {!! Form::select('vendors', $vendors, null, ['class' => 'sel']) !!}--}}
                                    <div class="col-md-2">
                                        <button class="btn btn-primary" type="submit" style="       margin-top: 24px;
    padding-bottom: 9px;">
                                            Go
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered yajrabox" >
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <th>Sprint id</th>

                                        <th>Tracking id</th>

                                        <th>Sprints created</th>
                                        <th>Sprint updated</th>
                                        <th>Sprint Status</th>
                                        <th>Task Status</th>
                                        {{--<th>Type</th>--}}
                                        <th>Route</th>
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
    </div>
    <!-- /#page-wrapper -->

@endsection