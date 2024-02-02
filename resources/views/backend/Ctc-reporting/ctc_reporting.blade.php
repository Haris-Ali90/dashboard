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

@section('title', 'CTC Summary')

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
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').DataTable({
                "lengthMenu": [250, 500, 750, 1000]
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

    <script type="text/javascript">
        /*$('#total-order').on('click', function (e) {
            $("#ctc-reporting-detail").show();
            var table = $('.yajrabox').DataTable({
                processing: true,
                ajax: "{{ route('ctc_reporting_data.data') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });*/

        $('.cards-details-datatable-show').on('click', function (e) {

            // show loader
            showLoader();
            $("#excel").show();
            $(".total-orders").show();
            $(".head-text").show();
            let data_for = $(this).attr('data-type');
            let date_date = $(this).attr('date-date');
            $("#select_data_type").val($(this).attr('data-type'));
            var title_page = '';
            switch (data_for) {
                case "total_orders":
                    title_page = "Total Orders";
                    break;
                case "picked-up":
                    title_page = " Picked Up From Store";
                    break;
                case "at-hub":
                    title_page = " At Hub";
                    break;
                case "at-store":
                    title_page = "At Store Orders";
                    break;
                case "sorted-order":
                    title_page = "Sorted Orders";
                    break;

                case "out-for-delivery":
                    title_page = "Out For Delivery ";
                    break;
                case "delivered-order":
                    title_page = "Delivered Orders";
                    break;
                case "returned":
                    title_page = "Returned Orders";
                    break;
                case "returned-to-merchant":
                    title_page = "Returned To Merchant ";
            }
            title_page = title_page+' ('+date_date+')';
            $(".title-page").text(title_page);


            //let targeted_table = 'ctc-total-orders';
            var table = $('.ctc-total-orders').DataTable({
                lengthMenu: [[250, 500, 750, 1000], [250, 500, 750, 1000]],
				searching: false,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('ctc_reporting_data.data') }}",
                    data: function (req_data) {
                        req_data.from_date = date_date;
                        req_data.to_date = date_date;
                        req_data.data_for = data_for;
                        console.log(req_data);
                    },
                    dataSrc: function ( json ) {
                        // hide loader
                        hideLoader();
                        return json.data;
                    }
                },
                columns: [
                    {data: 'id', orderable: false, searchable: false},
                    {data: 'store_name', orderable: false, searchable: false},
                    {data: 'tracking_id', orderable: false, searchable: false},
                    {data: 'status_id', orderable: false, searchable: false},
                    {data: 'created_at', orderable: false, searchable: false},
                    {data: 'updated_at', orderable: false, searchable: false},
                    {data: 'action', orderable: false, searchable: false}
                ],
            });
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
        $('.buttons-excel').on('click', function (event) {
            event.preventDefault();
            let href = $(this).attr('href');
            let data_for = $('#select_data_type').val();
            // alert(vendor);
            let from_date = $('#fromdatepicker').val();
            let to_date = $('#todatepicker').val();
            //alert(href);
            window.location.href = href + '/' + data_for + '/' + from_date + '/' + to_date;
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
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">CTC Summary
                        <small></small>
                    </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>CTC Summary
                                <small></small>
                            </h2>
                           {{-- @if(can_access_route('export_ctc_reporting.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right; display: none" id="excel">
                                    <a href="{{ route('export_ctc_reporting.excel') }}"
                                       class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                        Export to Excel
                                    </a>
                                </div>
                            @endif--}}

                            <div class="clearfix"></div>

                        </div>
                        @include( 'backend.layouts.notification_message' )
                        <div class="x_title">
                            <form style="margin-left: 115px" method="get" action="ctcreporting">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>From Date:</label>
                                        <input id="select_data_type" type="hidden" name="select_data_type">
                                        <input id="fromdatepicker" type="text" name="fromdatepicker"
                                               class="data-selector form-control" required=""
                                               value="{{ isset($_GET['fromdatepicker'])?$_GET['fromdatepicker']: date('Y-m-d') }}"
                                             >
                                    </div>
                                    <div class="col-md-3">
                                        <label>To Date:</label>
                                        <input
                                                type="text" id="todatepicker" name="todatepicker"
                                                class="data-selector form-control" required=""
                                                value="{{ isset($_GET['todatepicker'])?$_GET['todatepicker']: date('Y-m-d') }}"
                                                >
                                    </div>
                                    {{--<label style="    margin-left: 223px;">Select Vendor</label>
                                    {!! Form::select('vendors', $vendors, null, ['class' => 'sel']) !!}--}}
                                    <div class="col-md-2">
                                        <button class="btn btn-primary" type="submit" style="    margin-top: 23px;">
                                            Go
                                        </button>
                                    </div>
                            </div>
                        </form>

                        <div class="clearfix"></div>
                    </div>
                    <!--Count Div Row Open-->
                @include('backend.Ctc-reporting.ctc_reporting_cards')
                <!--Count Div Row Close-->


                    <div class="x_content">
                        <div class="clearfix"></div>



                    <!--total-orders-table-open-->

                        <div class="table-responsive " >
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 20%">Date</th>
                                <th style="width: 10%">Total Orders</th>
                                <th style="width: 10%"> Picked Up From Store</th>
                                <th style="width: 10%"> At Hub</th>
                                <th style="width: 10%">At Store Orders</th>
                                <th style="width: 10%">Sorted Orders</th>
                                <th style="width: 10%">Out For Delivery</th>
                                <th style="width: 10%">Delivered Orders</th>
                                <th style="width: 10%">Returned Orders</th>
                                <th style="width: 10%">Returned To Merchant</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ctc_range_count as $key=> $value)
                                <tr>
                                    <td >{{ $key}}</td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="total_orders" data-target="total-orders" data-ajax-status="false" id="total-order"><button style="font-weight: bold;" class="btn btn-link">{{ $value['total']  }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="picked-up" data-target="picked-up" data-ajax-status="false" id="picked-up"><button style="font-weight: bold;" class="btn btn-link">{{$value['picked-up'] }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="at-hub" data-target="at-hub" data-ajax-status="false" id="at-hub"><button style="font-weight: bold;" class="btn btn-link">{{$value['at-hub'] }}</button></td>

                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="at-store" data-target="at-store" data-ajax-status="false" id="at-store"><button style="font-weight: bold;" class="btn btn-link">{{$value['at-store'] }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="sorted-order" data-target="sorted-order" data-ajax-status="false" id="sorted-order"><button style="font-weight: bold;" class="btn btn-link">{{$value['sorted-order'] }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="out-for-delivery" data-target="out-for-delivery" data-ajax-status="false" id="out-for-delivery"><button style="font-weight: bold;" class="btn btn-link">{{$value['out-for-delivery'] }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="delivered-order" data-target="delivered-order" data-ajax-status="false" id="delivered-order"><button style="font-weight: bold;" class="btn btn-link">{{$value['delivered-order'] }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="returned" data-target="returned" data-ajax-status="false" id="returned"><button style="font-weight: bold;" class="btn btn-link">{{$value['returned'] }}</button></td>
                                    <td class="cards-details-datatable-show" date-date="{{$key}}" data-type="returned-to-merchant" data-target="returned-to-merchant" data-ajax-status="false" id="returned-to-merchant"><button style="font-weight: bold;" class="btn btn-link">{{$value['returned-to-merchant'] }}</button></td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <br>

                        <div class="clearfix"></div>
                        <div style="background: #3E3E3E;display: none;
    color: white;
    height: 36px;" class="head-text"><h3 style="margin-left: 12px;padding-top: 5px;"><label class="title-page"></label>
                            </h3></div>
                        <br>
                        <div class="table-responsive  total-orders" style="display: none">
                            <table class="table table table-striped table-bordered ctc-total-orders">
                                <thead>
                                <tr>
                                    <th style="width: 10%">Sprint ID</th>
                                    <th style="width: 20%">Store Name</th>
                                    <th style="width: 12%">Tracking ID</th>
                                    <th style="width: 25%">Status</th>
                                    <th style="width: 12%">Created At</th>
                                    <th style="width: 12%">Updated At</th>
                                    <th style="width: 5%">Action</th>
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