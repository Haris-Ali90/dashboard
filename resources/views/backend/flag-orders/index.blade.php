@extends( 'backend.layouts.app' )

@section('title', 'Flag Order List')

@section('CSSLibraries')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-submit-btn{
            margin-top: 26px;
        }
        .select2-container--default .select2-selection--multiple , .select2-container--default .select2-selection--single
        {
            min-height: 34px;
            padding: 1px 10px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 0px !important;
            -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
            box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }
        .label-success[href]:hover,
        .label-success[href]:focus {
            background-color: #449d44;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px !important;
        }


        /*pie chart box css*/
        .row.charts-box-main-wrap {
            position: relative;
        }
        .charts-box-ajax-data-loader-wrap {
            position: absolute;
            top: 0px;
            left: 0px;
            z-index: 1;
            width: 100%;
            height: 100%;
            text-align: center;
            padding: 0px 10px;
            display: none;
        }

        .charts-box-ajax-data-loader-wra.show
        {
            display: block;
        }
        .charts-box-ajax-data-loader-inner-wrap {
            position: relative;
            background-color: #0000006e;
            height: 100%;
        }
        .charts-box-ajax-data-loader-inner-wrap .lds-facebook {
            top: 47%;
        }

        .charts-box-ajax-data-loader-inner-wrap p
        {
            position: relative;
            top: 45%;
            color: #fff;
        }
        .dashboard-statistics-box {
            min-height: 600px;
            margin: 15px 0px;
            padding: 20px 0px;
            position: relative;
            box-sizing: border-box;
        }

        .lds-facebook {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        .lds-facebook div {
            display: inline-block;
            position: absolute;
            left: 8px;
            width: 16px;
            background: #fff;
            animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }
        .lds-facebook div:nth-child(1) {
            left: 8px;
            animation-delay: -0.24s;
        }
        .lds-facebook div:nth-child(2) {
            left: 32px;
            animation-delay: -0.12s;
        }
        .lds-facebook div:nth-child(3) {
            left: 56px;
            animation-delay: 0;
        }
        @keyframes lds-facebook {
            0% {
                top: 8px;
                height: 64px;
            }
            50%, 100% {
                top: 24px;
                height: 32px;
            }
        }

        .charts-box-ajax-data-loader-wrap
        {

        }
        button.dt-button.buttons-csv.buttons-html5 {
            visibility: hidden;
        }

    </style>
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('nprogress/nprogress.js') }}"></script>
    <script src="{{ backend_asset('libraries/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/skycons/skycons.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('js/echarts.min.js') }}"></script>
    <script src="{{ backend_asset('js/joeyco-custom-script.js')}}"></script>
@endsection

@section('inlineJS')

    <script type="text/javascript">
        <!-- Datatable -->

        $(document).ready(function () {

            $('#datatable').dataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ]
            });
            $(".group1").colorbox({height:"50%",width:"50%"});

            $("#start_date").datetimepicker
            ({
                format :'YYYY-MM-DD',
                maxDate:'{{(isset($_GET['start_date']))? $_GET['start_date'] : date('Y-m-d')}}'
            });

            $("#end_date").datetimepicker({
                format :'YYYY-MM-DD',
                //useCurrent:'{{--(isset($_GET['end_date']))? $_GET['end_date'] : date('Y-m-d')--}}',
                maxDate:'{{date('Y-m-d')}}'
                //defaultDate: '{{--(isset($_GET['end_date']))? $_GET['end_date'] : date('Y-m-d')--}}',
                //maxDate:jQuery('#end_date').val()?jQuery('#end_date').val():date('Y-m-d')

            });

//            $("#start_date").on("dp.change", function (e) {
//                $('#end_date').data("DateTimePicker").minDate(e.date);
//            });
//
            $("#end_date").on("dp.change", function (e) {
                $('#start_date').data("DateTimePicker").maxDate(e.date);
            });

            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete reason ??',
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

            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });


        });

        $(document).on('click', '.performance-status', function (e) {
            let el = $(this);
            var id = el.attr("data-id");

            $.confirm({
                title: 'A secure action',
                content: 'Are you sure you want to mark approved?',
                icon: 'fa fa-question-circle',
                animation: 'scale',
                closeAnimation: 'scale',
                opacity: 0.5,
                buttons: {
                    'confirm': {
                        text: 'Proceed',
                        btnClass: 'btn-info',
                        action: function () {
                            showLoader();
                            $.ajax({
                                type: "GET",
                                url: "{{URL::to('/')}}/joey-flag/performance/" + id,
                                success: function (res) {
                                    hideLoader();
                                    // checking responce
                                    if (res.status == false) {
                                        ShowSessionAlert('danger', res.message);
                                        return false;
                                    }

                                    ShowSessionAlert('success', res.message);
                                    $DataTable.row($(el).parents('tr'))
                                        .remove()
                                        .draw();


                                },
                                error: function (error) {
                                    hideLoader();
                                    console.log(error);
                                    ShowSessionAlert('danger', 'Something critical went wrong');
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
        // dwonload btn clcik genete file name
        function getExportFileName() {
            // setting up filter date
            var filter_start_date = ($('input[name="start_date"]').val() == "")? filter_date : $('input[name="start_date"]').val();
            var filter_start_end = ($('input[name="end_date"]').val() == "")? filter_date : $('input[name="end_date"]').val();
            return 'All Flag Order List '+filter_start_date+" to "+filter_start_end;

        };

        // bind datatable csv download btn to external buton
        $('.datatable-csv-download-btn').click(function () {
            $('.buttons-csv').trigger('click');
        });
        $DataTable = $('.flag-orders-table').DataTable({
            order: [[ 0, 'desc']],
            searching: true,
            select: false,
            pageLength: 250,
            autoWidth: false,
            scrollX: true,   // enables horizontal scrolling,
            scrollCollapse: true,
            fixedColumns: true,
            dom: 'Bflrtip',
            buttons: [/*{
                    extend:     'excel',
                    text:       'Excel',
                    filename:"_excel"

                    },*/
                {
                    extend:     'csv',
                    text:       'CSV Export',
                    filename:function () { return getExportFileName();},
                }],
            //lengthMenu: [250, 500, 750, 1000 ],
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('flag-order-list.data') }}',
                data: function(data) {
                    data.start_date = jQuery('[name=start_date]').val();
                    data.end_date = jQuery('[name=end_date]').val();
                    data.joeys = jQuery(".joeys-list").val();
                },
            },
            columns: [
                {data: 'id',   orderable: true,   searchable: true ,className:'text-center'},
                {data: 'route_id',   orderable: true,   searchable: true, className:'text-center'},
                {data: 'tracking_id',   orderable: true,   searchable: true, className:'text-center'},
                {data: 'sprint_id',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'flag_cat_name',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'portal_type',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'joey_name',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'flag_by',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'created_at',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'flagged_type',   orderable: true,   searchable: true,className:'text-center'},
                //{data: 'current_status',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'joey_performance_status',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'action',   orderable: false,   searchable: false,className:'text-center'},

            ]
        });

       /* $('.form-submit-btn').click(function () {
            $DataTable.ajax.reload();
            return false;
        });*/


        let pieChartHendler = {
            "element_instance": echartPie_montreal = echarts.init(document.getElementById('flag-order-pie-chart-dev')),
            "methods":{
                "loadPieChartData": function () {
                    // getting selected data
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();

                    // sending ajax request
                    $.ajax({
                        type: "get",
                        url: "{{route('flag-order-list-pie-chart-data')}}",
                        data: {
                            start_date: start_date,
                            end_date: end_date,
                        },
                        success: function (response) {
                            pieChartHendler.methods.hideLoader();

                            // updating pie chart options data
                            pieChartHendler.options.legend.data = response.body.legend;
                            pieChartHendler.options.series[0].data = response.body.data;

                            // init pieChart
                            pieChartHendler.element_instance.setOption(pieChartHendler.options);
                        },
                        error: function (error) {
                            pieChartHendler.methods.hideLoader();
                            alert('some error occurred please see the console');
                            console.log(error);
                        }
                    });
                },
                "showLoader":function () {
                    $('.charts-box-ajax-data-loader-wrap').addClass('show');
                },
                "hideLoader":function () {
                    $('.charts-box-ajax-data-loader-wrap').removeClass('show');
                },
            },
            "options":{
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x: 'center',
                    y: 'bottom',
                    data: []
                    //data: ['Sorted Orders', 'Picked Up From Hub', 'Not Scan Orders', 'Delivered Orders', 'Failed Orders', 'Return Orders', 'Mainfest Orders']
                },
                toolbox: {
                    show: true,
                    feature: {
                        magicType: {
                            show: true,
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'left',
                                    max: 1548
                                }
                            }
                        }
                    }
                },
                calculable: true,
                series: [{
                    name: 'Flag orders data ',
                    type: 'pie',
                    radius: '80%',
                    center: ['50%', '50%'],
                    data: []
//                    data: [{
//                    value: 1,
//                    name: 'Sorted Orders'
//                    }, {
//                        value: 5,
//                        name: 'Picked Up From Hub'
//                    }, {
//                        value: 5,
//                        name: 'Not Scan Orders'
//                    }, {
//                        value: 5,
//                        name: 'Delivered Orders'
//                    }, {
//                        value: 5,
//                        name: 'Failed Orders'
//                    }, {
//                        value: 5,
//                        name: 'Return Orders'
//                    }, {
//                        value: 5,
//                        name: 'Yesterday Return Orders'
//                    }, {
//                        value: 7,
//                        name: 'Custom Orders'
//                    }, {
//                        value: 8,
//                        name: 'Mainfest Orders'
//                    }]
                }],
            },
            "init":function () {

                pieChartHendler.methods.showLoader();
                pieChartHendler.methods.loadPieChartData()

            }

        };

        pieChartHendler.init();

//        var echartPie_montreal = echarts.init(document.getElementById('flag-order-pie-chart-dev'));
//        function loadPieChartData() {
//           let start_data = $('#start_date').val();
//           let end_data = $('#end_date').val();
//
//
//        }



//        echartPie_montreal.setOption({
//            tooltip: {
//                trigger: 'item',
//                formatter: "{a} <br/>{b} : {c} ({d}%)"
//            },
//            legend: {
//                x: 'center',
//                y: 'bottom',
//                //data: ['Sorted Orders', 'Picked Up From Hub', 'Not Scan Orders', 'Delivered Orders', 'Failed Orders', 'Return Orders', 'Mainfest Orders']
//            },
//            toolbox: {
//                show: true,
//                feature: {
//                    magicType: {
//                        show: true,
//                        type: ['pie', 'funnel'],
//                        option: {
//                            funnel: {
//                                x: '25%',
//                                width: '50%',
//                                funnelAlign: 'left',
//                                max: 1548
//                            }
//                        }
//                    }
//                }
//            },
//            calculable: true,
//            series: [{
//                name: 'Flag orders data ',
//                type: 'pie',
//                radius: '80%',
//                center: ['50%', '50%'],
//                data: [/*{
//                    value: 1,
//                    name: 'Sorted Orders'
//                }, {
//                    value: 5,
//                    name: 'Picked Up From Hub'
//                }, {
//                    value: 5,
//                    name: 'Not Scan Orders'
//                }, {
//                    value: 5,
//                    name: 'Delivered Orders'
//                }, {
//                    value: 5,
//                    name: 'Failed Orders'
//                }, {
//                    value: 5,
//                    name: 'Return Orders'
//                }, {
//                    value: 5,
//                    name: 'Yesterday Return Orders'
//                }, {
//                    value: 7,
//                    name: 'Custom Orders'
//                }, {
//                    value: 8,
//                    name: 'Mainfest Orders'
//                }*/]
//            }]
//        });
    </script>


@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    @include('backend.flag-orders.flag-order-cards')
                    <h3>{{--Flag Orders--}}<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <!--charts-box-main-wrap-open-->
            <div class="row charts-box-main-wrap">
                <!--charts-box-ajax-data-loader-wrap-loader-open-->
                <div class="charts-box-ajax-data-loader-wrap">
                    <div class="charts-box-ajax-data-loader-inner-wrap">
                        <div class="lds-facebook"><div></div><div></div><div></div></div>
                        <p>Loading...</p>
                    </div>
                </div>
                <!--charts-box-ajax-data-loader-wrap-loader-close-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Flag Orders Pie Chart</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="dashboard-statistics-box" id="flag-order-pie-chart-dev"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--charts-box-main-wrap-close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Flag Orders List</h2>
                            <div class="dt-buttons" style="float: right">
                                <button class="datatable-csv-download-btn" type="button">
                                    Export CSV
                                </button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        {{--<form class="form-horizontal table-top-form-from">
                            <!--table-top-form-row-open-->
                            <div class="row table-top-form-row">
                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">From Date</label>
                                    <input id="start_date" name="start_date" max="{{date('Y-m-d')}}" value="{{ isset($_GET['fromdatepicker'])?$_GET['fromdatepicker']: date('Y-m-d') }}" type="text" class="form-control">
                                </div>
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">To Date</label>
                                    <input id="end_date" name="end_date" max="{{date('Y-m-d')}}" value="{{ isset($_GET['todatepicker'])?$_GET['todatepicker']: date('Y-m-d')  }}" type="text" class="form-control">
                                </div>

                                <div class="col-sm-3 col-md-3 model-input-col">
                                    <label class="control-label">Joeys list</label>
                                    <select class="form-control joeys-list"  name="joeys[]" multiple>
                                        @foreach($all_joeys_accept_selected as $joey)
                                            <option value="{{$joey->id}}">{{$joey->full_name}} {{$joey->id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-2 table-top-form-col-warp">
                                    <button class="btn orange form-submit-btn"  type="button"> Filter </button>
                                </div>
                                <!--table-top-form-col-warp-close-->

                            </div>
                            <!--table-top-form-row-close-->
                        </form>--}}
                        <form method="get" action="">
                            <div class="row">
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">From Date</label>
                                    {{--<input id="fromdatepicker" type="text" name="fromdatepicker"
                                           class="data-selector form-control" required=""
                                           value="{{ isset($_GET['fromdatepicker'])?$_GET['fromdatepicker']: date('Y-m-d',strtotime( '-1 days' )) }}"
                                           max="{{date('Y-m-d',strtotime( '-1 days' ))}}"
                                    >--}}
                                    <input id="start_date"
                                           name="start_date"
                                           max="{{date('Y-m-d',strtotime( '-1 days' ))}}"
                                           value="{{ isset($_GET['start_date'])?$_GET['start_date']: date('Y-m-d') }}"
                                           type="text"
                                           class="form-control">
                                </div>
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">To Date</label>
                                    {{--<input
                                            type="text" id="end_date" name="end_date"
                                            class="data-selector form-control" required=""
                                            value="{{ isset($_GET['end_date'])?$_GET['end_date']: date('Y-m-d',strtotime( '-1 days' ))  }}"
                                            max="{{date('Y-m-d',strtotime( '-1 days' ))}}"
                                    >--}}
                                    <input id="end_date"
                                           name="end_date"
                                           max="{{date('Y-m-d',strtotime( '-1 days' ))}}"
                                           value="{{ isset($_GET['end_date'])?$_GET['end_date']: date('Y-m-d')  }}"
                                           type="text"
                                           class="form-control">
                                </div>
                                <div class="col-sm-3 col-md-3 model-input-col">
                                    <label class="control-label">Joeys list</label>
                                    <select class="form-control joeys-list" name="joey">
                                        <option value="">Select Joeys</option>
                                        @foreach($all_joeys_accept_selected as $joey)
                                            <option value="{{$joey->id}}" {{ ($joey->id ==  $selectjoey)?'selected': '' }}>{{$joey->full_name}} {{$joey->id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=" col-sm-2">
                                    <button class="btn btn-primary" type="submit" style="margin-top: 25px;">
                                        Filter</a> </button>
                                </div>
                            </div>
                        </form>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif


                            <table class="table table-striped table-bordered flag-orders-table" >
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Route ID</th>
                                    <th>Tracking ID</th>
                                    <th>Order NO</th>
                                    <th>Flag Category Names</th>
                                    <th>Flag From</th>
                                    <th>Joey Name</th>
                                    <th>Flag By</th>
                                    <th>Flagged Date</th>
                                    <th>Flagged Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
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

@endsection
@section('multi-script')
<script>
    $(document).ready(function () {
        $('.joeys-list').select2({
            minimumInputLength: 2,
            placeholder: "Search a joey to assign",
            allowClear: true,
            matcher: matchStart,
            sorter: function(data) {

                return data.sort(function(a, b) {
                    return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                });
            }
        });

        // search by start characters
        function matchStart (params, data) {

            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // checking the search is by id or name
            if(isNaN(parseInt(params.term))) // block for string search
            {
                if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                    return $.extend({}, data, true);
                }
            }
            else
            {
                // number search block
                if (data.text.indexOf(params.term) > -1) {
                    return  modifiedData = $.extend({}, data, true);
                }
            }
            // matching start characters


            // return null
            return null;
        }
    });
</script>
@endsection
