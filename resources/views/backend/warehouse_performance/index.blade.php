<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>WareHouse Performance</title>
    {{--  <link rel="icon" href="{!! asset('public/images/joeyco_icon.png') !!}"/>--}}
    <link rel="icon" href="{!! app_asset('images/joeyco_icon.png') !!}"/>

    <!-- Bootstrap -->
    <link href="{{ backend_asset('libraries/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ backend_asset('libraries/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ backend_asset('css/custom.min.css') }}" rel="stylesheet">
    <!-- Confirm Alert CSS -->
    <link href="{{ backend_asset('css/jquery-confirm.css') }}" rel="stylesheet">
    <link rel="icon" href="demo_icon.gif" type="image/gif" sizes="16x16">

    <!--joey-custom-css-->
    <link href="{{ backend_asset('css/joey_custom.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />


</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">

        <!-- <div id="wrapper">-->
    <?php /*?>@if ( Auth::check() )<?php */?>
    <!-- Navigation -->
    @if(Auth::check())
        @include('backend.layouts.sidebar')
    @endif
    <!-- Navigation [END] -->
    <?php /*?> @endif<?php */?>
    @include('backend.layouts.loader')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">WareHouse Performance
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
                            <h2>WareHouse Performance
                                <small></small>
                            </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title">
                            <form method="get" action="{{ route('warehouse-performance.data') }}">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label> Select Hub</label>
                                        <select class="form-control hub-id" name="hub-id"  required>
                                            <option value=""> Select Hub</option>
                                            @foreach( $hubs as $hub )
                                                <option value="{{ $hub->id }}"> {{ $hub->city_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Select Month</label>
                                        <select class="form-control month-id" name="month-id"  required>
                                            <option value=""> Select Month</option>
                                            <option value="01">Janaury</option>
                                            <option value='02'>February</option>
                                            <option value='03'>March</option>
                                            <option value='04'>April</option>
                                            <option value='05'>May</option>
                                            <option value='06'>June</option>
                                            <option value='07'>July</option>
                                            <option value='08'>August</option>
                                            <option value='09'>September</option>
                                            <option value='10'>October</option>
                                            <option value='11'>November</option>
                                            <option value='12'>December</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 25px;">
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
                                <table class="table table-striped table-bordered" id="joey-report-payout">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <td style="background-color:#C6DD38;color: black"></td>
                                        <td colspan="8" style="text-align: center;background-color: #E36D28;color: black;font-size: large;font-weight: bold;">InBound Team</td>
                                        <td colspan="5" style="text-align: center;background-color: #C6DD38;color: black;font-size: large;font-weight: bold;">OutBound Team</td>
                                        <td colspan="5" style="text-align: center;background-color: #E36D28;color: black;font-size: large;font-weight: bold;">Closing Team</td>
                                        <td style="background-color:#C6DD38;color: black"></td>
                                        <td style="background-color:#C6DD38;color: black"></td>
                                        <td style="background-color:#C6DD38;color: black"></td>
                                        <td style="background-color:#C6DD38;color: black"></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;background-color:#C6DD38;color: black;">Date</th>
                                        <th style="text-align: center;background-color: #E36D28; color: black;">Total Packages</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Number of Sorters</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total System Routes</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Manual Routes</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Damaged Packages</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Not Received (Order Under Scheduled)</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Mis Sort-s</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Mis Ratio (%)</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Total Orders (picked)</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Total Dispensed Routes</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Total Manual Routes</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Missing Stolen Packages</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Lost Packages (%)</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Same Day Returns</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Return Scan</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Not Return Scan</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Completed Deliveries Before 9PM</th>
                                        <th style="text-align: center;background-color: #E36D28;color: black;">Total Completed Deliveries After 9PM</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">TotalManual Routes (Inbound/Outbound)</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Dispencing Accuracy</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">Dispencing Accuracy</th>
                                        <th style="text-align: center;background-color: #C6DD38;color: black;">OTD</th>
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
    </div>
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>
<!-- <script src="{{ backend_asset('libraries/jquery/dist/jquery-ui.min.js') }}"></script> -->
<!-- Bootstrap -->
<script src="{{ backend_asset('libraries/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.js')}}"></script>


<!-- Custom Theme JavaScript -->
<script src="{{ backend_asset('js/custom.min.js')}}"></script>
<script src="{{ backend_asset('js/jquery-confirm.js') }}"></script>
<!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
<link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
    var app_url = "{{url('/')}}";
</script>
<script src="{{ backend_asset('js/custom-dashbaord.js')}}"></script>




<script src="{{ backend_asset('js/customyajra.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>

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
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        $('#birthday').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });


    var filter_date = '{{date('Y-m-d')}}';
    var filter_warehouse = 'not set'
    // dwonload btn clcik genete file name
    function getExportFileName() {
        // setting up filter date
        filter_warehouse = ($('.hub-id').val() == "") ? filter_warehouse : $( ".hub-id" ).find('option:selected').text();
        filter_date = ($('.month-id').val() == "") ? filter_warehouse : $( ".month-id").find('option:selected').text();
        console.log([$('.month-id').val(),$('.hub-id').val()]);
        return filter_date+' - '+filter_warehouse+' Warehouse Performance';

    };

    // datatable init
    let datatable = $('#joey-report-payout').DataTable({
        scrollX: true,   // enables horizontal scrolling,
        scrollCollapse: true,
        searching: false, paging: false, ordering: false,
        columns: [
            {"data": "date", "className": "text-center"},
            {"data": "total_packages", "className": "text-center"},
            {"data": "number_of_sorters", "className": "text-center"},
            {"data": "total_system_routes", "className": "text-center"},
            {"data": "inbound_total_manual_routes", "className": "text-center"},
            {"data": "total_damaged_packages", "className": "text-center"},
            {"data": "not_received", "className": "text-center"},
            {"data": "total_mis_sort", "className": "text-center"},
            {"data": "total_mis_ratio", "className": "text-center"},
            {"data": "total_picked_order", "className": "text-center"},
            {"data": "total_dispensed_routes", "className": "text-center"},
            {"data": "outbound_total_manual_routes", "className": "text-center"},
            {"data": "missing_stolen_packages", "className": "text-center"},
            {"data": "lost_packages", "className": "text-center"},
            {"data": "total_same_day_returns", "className": "text-center"},
            {"data": "total_return_scan", "className": "text-center"},
            {"data": "total_not_return_scan", "className": "text-center"},
            {"data": "total_completed_deliveries_before_9pm", "className": "text-center"},
            {"data": "total_completed_deliveries_after_9pm", "className": "text-center"},
            {"data": "overall_total_manual_routes", "className": "text-center"},
            {"data": "dispencing_accuracy", "className": "text-center"},
            {"data": "dispencing_accuracy_2", "className": "text-center"},
            {"data": "otd", "className": "text-center "}
        ],
        autoWidth: false,
        fixedColumns: true,
        dom: 'Bflrtip',
        buttons: ['excelHtml5'],

        "lengthMenu": [[50, 100, 150, 200], [50, 100, 150, 200]],
    });

    // show loader on submit
    $('form').submit(function (event) {
        //ShowSessionAlert('info','The downloading will start in a moment please be patient !');
        // stoping form submit
        event.preventDefault()

        // get from inputs data
        let method = $(this).attr('method');
        let url = $(this).attr('action');
        let all_inputs = $(this).serializeArray();
        let request_data = {};
        all_inputs.forEach(function (data, index) {
            request_data[data.name] = data.value;
        });

        // overdide ta date variable
        // setting limimt
        request_data['limit'] = 1;
        // setting page
        request_data['current_page'] = 1;

        // clear datatable for new request data
        datatable.clear().draw(false);

        // calling ajax
        getDataByjax(url, method, request_data, true, 0);

    });


    //function for call ajax for download file
    function getDataByjax(url ='', method = '', request_data = {}, progress_bar_create = false, pregress_per = 0) {
        //create progress bar
        if (progress_bar_create) {
            showProgressBar('Page data is loading please be patient . .');
        }
        else // update progress bar
        {
            updateProgressBar(pregress_per);
        }

        // sending ajax
        $.ajax({
            type: method,
            url: url,
            data: request_data,
            success: function (response) {

                // hide error connection alert
                progressBarErrorHide();

                // setting data to variables
                let request_data = response.metaData;
                let totalRecords = parseInt(request_data.total_pages);
                let completed_records = parseInt(request_data.current_page);
                let Percentage_Completed = 100;
                let datatable_data = response.body;//Object.values(response.body);
                // checking  the total records is not zero
                if (totalRecords > 0) {
                    Percentage_Completed = (completed_records / totalRecords ) * 100;
                }
                // checking the record is grather then 0
                if (completed_records >= totalRecords) {
                    // update progress bar
                    updateProgressBar(100);

                    // add final data to datatable and re-draw table
                    datatable.row.add(datatable_data).draw(true);

                    //calculate_totals();

                    // remove progress bar
                    setTimeout(function () {
                        hideProgressBar()
                    }, 1000);
                }
                else if (completed_records < totalRecords) {
                    // add final data to datatable and re-draw table
                    datatable.row.add(datatable_data).draw(false);
                    // sending next page request
                    request_data['current_page'] = parseInt(request_data.current_page) + 1;
                    // calling ajax
                    getDataByjax(url, method, request_data, false, Percentage_Completed.toFixed(2));

                }

            },
            error: function (error) {

                console.log(error);

                // checking the date validaion
                // checking key exist
                if ('errors' in error.responseJSON) {

                    let errors = error.responseJSON.errors;
                    // looping the errors
                    for (const index in  errors) {
                        var single_error = errors[index];
                        // checking the type of error
                        if (typeof single_error == 'object') {
                            // showing errors by loop
                            single_error.forEach(function (value) {
                                ShowSessionAlert('danger', value);
                            });

                        }
                        else {
                            ShowSessionAlert('danger', single_error);
                        }
                    }

                    // removeing header
                    hideProgressBar();
                    // return with error show and stop the ajax
                    return false;

                }
                else if ('error' in error.responseJSON) // session end error handling
                {
                    if (error.responseJSON.error == 'Unauthenticated.') {
                        alert("Your Session is expired");
                        location.reload();
                        return false;
                    }
                }

                // show error connection alert
                progressBarErrorShow();

                request_data['error'] = 'block';
                let metaData = request_data.metaData;
                // checking metaData is exsit or not
                if (typeof metaData !== 'undefined') {
                    let totalRecords = parseInt(metaData.total_pages);
                    let completed_records = parseInt(metaData.current_page) * parseInt(metaData.limit);
                    let Percentage_Completed = 100;
                    // checking  the total records is not zero
                    if (totalRecords > 0) {
                        Percentage_Completed = (completed_records / totalRecords ) * 100;
                    }

                    getDataByjax(url, method, request_data, false, Percentage_Completed.toFixed(2));
                }
                else {
                    var current_persent = parseFloat($('.progress-main-wrap').find('.progress-bar').text());
                    getDataByjax(url, method, request_data, false, current_persent);
                }

            }
        });
    }

</script>

</body>

</html>

