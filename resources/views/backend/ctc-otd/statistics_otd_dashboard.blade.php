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

@section('title', 'CTC Graph')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
        .bg-green {
            background: #c6dd38 !important;
            border: 1px solid #34495e  !important;
            color: #fff;
        }
        .ctc-statistics-show-box-main-wrap
        {
            /*display: none;*/
            position: relative;
        }
        .ctc-statistics-checkbox-wrap {
            min-height: 71px;
            border: 1px solid #eee;
            position: relative;
            padding-right: 30px;
            background: #c6dd38;
        }

        .ctc-statistics-dashboard-btn-warp .ctc-statistics-checkbox-wrap label
        {
            font-size: 12px;
            margin: 0px;
            color: #000;
        }

        .ctc-statistics-dashboard-btn-warp .ctc-statistics-checkbox
        {
            width: 15px !important;
            margin-top: 0px;
        }
        .ctc-statistics-show-box-inner-wrap {
            background-color: #fff;
            margin: 15px 0px;
        }
        .ctc-statistics-show-box-heading-wrap {
            text-align: center;
            margin: 0px;
            padding: 10px 0px 20px;
            color: #3e3e3e;
        }


        /*loader box css*/
        .statistics-ajax-data-loader-wrap {
            position: absolute;
            top: 0px;
            left: 0px;
            z-index: 9999;
            background-color: #0000005e;
            width: 100%;
            height: 95%;
            text-align: center;
            display: none;
        }
        .statistics-ajax-data-loader-wrap.show {
            display: block !important;
        }
        .statistics-ajax-data-loader-inner-wrap {
            position: relative;
            top: 0%;
        }
        .statistics-ajax-data-loader-inner-wrap p {
            color: #fff;
            font-size: 17px;
            position: relative;
            bottom: 15px;
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
        .ctc-statistics-checkbox-wrap .icon {
            position: absolute;
            top: 40%;
            right: 3%;
        }




        html {
            scroll-behavior: smooth;
        }


        @media only screen and (max-width: 1689px) {
            .otd-joeyco-exprince-main-wrap {
                height: 100% !important;
                width: 100% !important;
            }
            .otd-overall-exprince-main-wrap {
                height: 100% !important;
                width: 100% !important;
            }
        }
    </style>
@endsection


@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">CTC Graph<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        {{--@include('backend.ctc.ctc_cards')--}}
        <!--Count Div Row Close-->
        {{--@include('backend.layouts.modal')
        @include( 'backend.layouts.popups')--}}

        <!--row-open-->
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title col-md-12">
                            <h2>CTC Graph<small></small></h2>
                            <div class="clearfix"></div>
                        </div>
                        <!-- ctc-statistics-dashboard-btn-warp-open-->
                        <div class="col-sm-12 ctc-statistics-dashboard-btn-warp">
                            <!--<div class="col-sm-12">
                                <label>Date :</label>
                                <input class="form-control" type="date"/>
                            </div>-->

                            <div class="col-sm-2 ctc-statistics-checkbox-wrap">
                                <label><b>OTD Day </b>:</label>
                                <input class="form-control ctc-statistics-checkbox" type="checkbox" data-status="false" data-for="otd-day" data-targeted-div="otd-day-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-pie-chart" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 ctc-statistics-checkbox-wrap">
                                <label><b>OTD Week </b>:</label>
                                <input class="form-control ctc-statistics-checkbox" type="checkbox" data-status="false" data-for="otd-week" data-targeted-div="otd-week-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-pie-chart" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 ctc-statistics-checkbox-wrap">
                                <label><b>OTD Month </b>:</label>
                                <input class="form-control ctc-statistics-checkbox" type="checkbox" data-status="false" data-for="otd-month" data-targeted-div="otd-month-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-pie-chart" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>

                        </div>
                        <!-- ctc-statistics-dashboard-btn-warp-close-->


                    </div>

                </div>

                <!--ctc-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 ctc-statistics-show-box-main-wrap otd-day-main-wrap otd-joeyco-exprince-main-wrap">

                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--ctc-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 ctc-statistics-show-box-inner-wrap">
                        <!--ctc-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 ctc-statistics-show-box-heading-wrap">
                            <h2><b>OTD Day</b></h2>
                        </div>
                        <!--ctc-statistics-show-box-heading-wrap-close-->

                        <!--ctc-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 ctc-statistics-show-box-data-wrap otd-joeyco-exprince">
                            <div id="dayExp"></div>
                        </div>
                        <!--ctc-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--ctc-statistics-show-box-inner-wrap-close-->

                </div>
                <!--ctc-statistics-show-box-main-wrap-close-->
                <!--ctc-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 ctc-statistics-show-box-main-wrap otd-week-main-wrap otd-joeyco-exprince-main-wrap">

                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--ctc-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 ctc-statistics-show-box-inner-wrap">
                        <!--ctc-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 ctc-statistics-show-box-heading-wrap">
                            <h2><b>OTD Week</b></h2>
                        </div>
                        <!--ctc-statistics-show-box-heading-wrap-close-->

                        <!--ctc-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 ctc-statistics-show-box-data-wrap otd-joeyco-exprince">
                            <div id="weekExp"></div>
                        </div>
                        <!--ctc-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--ctc-statistics-show-box-inner-wrap-close-->

                </div>
                <!--ctc-statistics-show-box-main-wrap-close-->

                <!--ctc-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 ctc-statistics-show-box-main-wrap otd-month-main-wrap otd-joeyco-exprince-main-wrap">

                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--ctc-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 ctc-statistics-show-box-inner-wrap">
                        <!--ctc-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 ctc-statistics-show-box-heading-wrap">
                            <h2><b>OTD Month</b></h2>
                        </div>
                        <!--ctc-statistics-show-box-heading-wrap-close-->

                        <!--ctc-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 ctc-statistics-show-box-data-wrap otd-joeyco-exprince">
                            <div id="monthExp"></div>
                        </div>
                        <!--ctc-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--ctc-statistics-show-box-inner-wrap-close-->

                </div>
                <!--ctc-statistics-show-box-main-wrap-close-->

            </div>
            <!--row-open-->


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

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection


@section('inlineJS')
    <script>

        // check box functions
        $('.ctc-statistics-checkbox').click(function () {

            let el = $(this);
            let url_for =  el.attr('data-for');
            let status =  el.attr('data-status');
            let check_type = el.prop("checked");
            let targeted_div =  el.attr('data-targeted-div');


            // checking send ajax request or not
            if(status == 'false' && check_type == true ){

                //show loader
                $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').addClass('show');

                $("."+url_for+'-main-wrap').find('.ctc-statistics-show-box-data-wrap').show();

                // calling ajax
                walmart_ajax_statistics_call(el,url_for,targeted_div);
            }
            else // toggle the div hide and show
            {
                $("."+url_for+'-main-wrap').find('.ctc-statistics-show-box-data-wrap').toggle();
            }
            if(check_type)
            {
                $('html, body').animate({
                    scrollTop: $("."+url_for+'-main-wrap').offset().top
                }, 2000);
            }

        });


        function walmart_ajax_statistics_call(el,url_for,targeted_div) {

            let date = $('.form-data').val();

            $.ajax({
                type: "get",
                url: '{{ URL::to('dashboard/statistics/ajax/')}}/'+url_for,
                data:{'date':date},
                success: function (data) {

                    // hide loader
                    $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');

                    if(data.status = true)
                    {

                        /*updating check box status*/
                        el.attr('data-status','true');

                        if(data['for'] == 'pie_chart1')
                        {
                            show_odt_pichart1(data.data);
                        }
                        else if(data['for'] == 'pie_chart2')
                        {
                            show_odt_pichart2(data.data);
                        }
                        else
                        {
                            show_odt_pichart3(data.data);
                        }

                    }
                    else
                    {
                        // hide loader
                        $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');

                        alert('Some error occurred');

                    }

                },
                error:function (error) {

                    // hide loader
                    $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');

                    alert('Some error occurred');

                }
            });

        }



        function show_odt_pichart1(data) {

            Highcharts.chart('dayExp', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        colors: [
                            '#b6d309',
                            '#f1732a'
                        ],
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Deliveries',
                    colorByPoint: true,
                    data: [{
                        name: 'On Time Deliveries',
                        y:data[0]['y2'],
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Off Time Deliveries',
                        y: data[0]['y1']
                    }, ]
                }],
                exporting: {
                    enabled: false
                }
            });
        }

        function show_odt_pichart2(data) {

            Highcharts.chart('weekExp', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        colors: [
                            '#b6d309',
                            '#f1732a'
                        ],
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Deliveries',
                    colorByPoint: true,
                    data: [{
                        name: 'On Time Deliveries',
                        y: data[0]['y2'],
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Off Time Deliveries',
                        y: data[0]['y1']
                    }, ]
                }],
                exporting: {
                    enabled: false
                }
            });


        }
        function show_odt_pichart3(data) {

            Highcharts.chart('monthExp', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        colors: [
                            '#b6d309',
                            '#f1732a'
                        ],
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Deliveries',
                    colorByPoint: true,
                    data: [{
                        name: 'On Time Deliveries',
                        y:data[0]['y2'],
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Off Time Deliveries',
                        y: data[0]['y1']
                    }, ]
                }],
                exporting: {
                    enabled: false
                }
            });
        }


    </script>

@endsection