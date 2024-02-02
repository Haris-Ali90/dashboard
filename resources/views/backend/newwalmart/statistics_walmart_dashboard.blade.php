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

@section('title', 'Walmart Dashboard')

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
        .wm-statistics-show-box-main-wrap
        {
            /*display: none;*/
            position: relative;
        }
        .wm-statistics-checkbox-wrap {
            min-height: 71px;
            border: 1px solid #eee;
            position: relative;
            padding-right: 30px;
            background: #c6dd38;
        }

        .wm-statistics-dashboard-btn-warp .wm-statistics-checkbox-wrap label
        {
            font-size: 12px;
            margin: 0px;
            color: #000;
        }

        .wm-statistics-dashboard-btn-warp .wm-statistics-checkbox
        {
            width: 15px !important;
            margin-top: 0px;
        }
        .wm-statistics-show-box-inner-wrap {
            background-color: #fff;
            margin: 15px 0px;
        }
        .wm-statistics-show-box-heading-wrap {
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
        .wm-statistics-checkbox-wrap .icon {
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
                    <h3 class="text-center">Walmart Dashboard<small></small></h3>
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
                            <form method="get" action="">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Search By Date</label>
                                        <input type="date" name="date" class="form-data form-control" value='{{$date}}' required="" placeholder="Search">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mb1 bg-green"  type="submit" style="    margin-top: 24px;">Go</a> </button>
                                    </div>


                                </div>


                            </form>
                             <h2>Walmart Dashboard</h2>
                            <div  class=" text-right"><a  target="_blank" href={{ URL::to("report/export")}}?date=<?php echo $date ?> class="btn btn-success pl-md-3">Export Data</a></div>
                            <div class="clearfix"></div>
                        </div>
                      
                        <!-- wm-statistics-dashboard-btn-warp-open-->
                        <div class="col-sm-12 wm-statistics-dashboard-btn-warp">
                            <!--<div class="col-sm-12">
                                <label>Date :</label>
                                <input class="form-control" type="date"/>
                            </div>-->

                            <div class="col-sm-2 wm-statistics-checkbox-wrap">
                                <label><b>OTD Graph  </b>:</label>
                                <input class="form-control wm-statistics-checkbox" type="checkbox" data-status="false" data-for="otd" data-targeted-div="otd-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-pie-chart" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 wm-statistics-checkbox-wrap">
                                <label><b>OTA / OTD % Table </b>:</label>
                                <input class="form-control wm-statistics-checkbox" type="checkbox" data-status="false" data-for="short-summary" data-targeted-div="short-summary-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-file" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 wm-statistics-checkbox-wrap">
                                <label><b>Total Delivery / Late Delivery Graph</b>:</label>
                                <input class="form-control wm-statistics-checkbox" type="checkbox" data-status="false" data-for="walmart-orders" data-targeted-div="walmart-orders-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-line-chart" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 wm-statistics-checkbox-wrap">
                                <label><b>On Time Graph </b> :</label>
                                <input class="form-control wm-statistics-checkbox" type="checkbox" data-status="false" data-for="walmart-on-time-orders" data-targeted-div="walmart-on-time-orders-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-bar-chart" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 wm-statistics-checkbox-wrap">
                                <label><b>Store  Summary</b> :</label>
                                <input class="form-control wm-statistics-checkbox" type="checkbox" data-status="false" data-for="walmart-stores-data" data-targeted-div="walmart-stores-data-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-tasks" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>
                            <div class="col-sm-2 wm-statistics-checkbox-wrap">
                                <label><b>Overall Orders</b> :</label>
                                <input class="form-control wm-statistics-checkbox" type="checkbox" data-status="false" data-for="total-orders-summary" data-targeted-div="total-orders-summary-main-wrap" />
                                <div class="icon">
                                    <i class="fa fa-cubes" style=" font-size: x-large;color: #e36d28;"></i>
                                </div>
                            </div>

                        </div>
                        <!-- wm-statistics-dashboard-btn-warp-close-->


                    </div>

                </div>

                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-6 wm-statistics-show-box-main-wrap otd-main-wrap otd-joeyco-exprince-main-wrap">

                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>OTD JoeyCo Experience </b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap otd otd-joeyco-exprince">
                            <div id="cExp"></div>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->


                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-6 wm-statistics-show-box-main-wrap otd-main-wrap otd-overall-exprince-main-wrap">

                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->


                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>Overall OTD</b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap otd-overall-exprince">
                            <div id="jExp"></div>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->



                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 wm-statistics-show-box-main-wrap short-summary-main-wrap">


                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>OTA / OTD Percentage Table</b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap short-summary">

                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->


                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 wm-statistics-show-box-main-wrap walmart-orders-main-wrap">


                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>Total Delivery / Late Delivery <Graph></Graph></b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap walmart-orders">
                            <div id="wm-orders"></div>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->


                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 wm-statistics-show-box-main-wrap walmart-on-time-orders-main-wrap">


                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>On Time Graph</b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap walmart-on-time-orders">
                            <div id="on_time_orders"></div>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->


                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 wm-statistics-show-box-main-wrap walmart-stores-data-main-wrap">


                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>Store  Summary</b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap walmart-stores-data">

                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->



                <!--wm-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 wm-statistics-show-box-main-wrap total-orders-summary-main-wrap">

                    <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                    <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--wm-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 wm-statistics-show-box-inner-wrap">
                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-heading-wrap">
                            <h2><b>Overall Orders</b></h2>
                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                        <!--wm-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 wm-statistics-show-box-data-wrap total-orders-summary">

                        </div>
                        <!--wm-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--wm-statistics-show-box-inner-wrap-close-->

                </div>
                <!--wm-statistics-show-box-main-wrap-close-->


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
        $('.wm-statistics-checkbox').click(function () {

            let el = $(this);
            let url_for =  el.attr('data-for');
            let status =  el.attr('data-status');
            let check_type = el.prop("checked");
            let targeted_div =  el.attr('data-targeted-div');


            // checking send ajax request or not
            if(status == 'false' && check_type == true ){

                //show loader
                $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').addClass('show');

                $("."+url_for+'-main-wrap').find('.wm-statistics-show-box-data-wrap').show();

                // calling ajax
                walmart_ajax_statistics_call(el,url_for,targeted_div);
            }
            else // toggle the div hide and show
            {
                $("."+url_for+'-main-wrap').find('.wm-statistics-show-box-data-wrap').toggle();
            }
            if(check_type)
            {

                $('html, body').animate({
                    scrollTop: $("."+url_for).offset().top
                }, 2000);
            }

        });


        function walmart_ajax_statistics_call(el,url_for,targeted_div) {

            let date = $('.form-data').val();

            $.ajax({
                type: "get",
                url: '{{ URL::to('new/dashboard/statistics/ajax/')}}/'+url_for,
                data:{'date':date},
                success: function (data) {

                    // hide loader
                    $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');

                    if(data.status = true)
                    {
                        console.log(data.status);
                        /*updating check box status*/
                        el.attr('data-status','true');

                        if(data['for'] == 'pie_chart')
                        {
                            show_odt_pichart(data.data);
                        }
                        else if(data['for']=='walmart-orders')
                        {
                            show_wm_otd_bar_chart_one(data.data)
                        }
                        else if(data['for'] == 'walmart-on-time-orders')
                        {
                            show_wm_otd_bar_chart_two(data.data)
                        }
                        else
                        {
                            $('.'+data['for']).html(data.html);
                        }

                    }
                    else
                    {
                        // hide loader
                        $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');

                        alert('Some error occurred');
                        console.log(error);
                    }

                },
                error:function (error) {

                    // hide loader
                    $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');

                    alert('Some error occurred');
                    console.log(error);
                }
            });

        }



        function show_odt_pichart(data) {

            Highcharts.chart('jExp', {
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
                        y:data[0]['y1'],
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Off Time Deliveries',
                        y: data[0]['y2']
                    }, ]
                }],
                exporting: {
                    enabled: false
                }
            });


            Highcharts.chart('cExp', {
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
                        y: data[1]['y1'],
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Off Time Deliveries',
                        y: data[1]['y2']
                    }, ]
                }],
                exporting: {
                    enabled: false
                }
            });
        }


        function show_wm_otd_bar_chart_one(data) {

            Highcharts.chart('wm-orders',{
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: data.categories
                },
                yAxis: [{
                    min: 0,
                    title: {
                        text: 'Total Deliveries'
                    }
                }, {
                    title: {
                        text: 'Total Late Deliveries'
                    },
                    opposite: true
                }],
                legend: {
                    shadow: false
                },
                tooltip: {
                    shared: true
                },
                series: [{
                    name: 'Total Deliveries',
                    color: 'rgb(183, 212, 9)',
                    data: data.data_set_one,
                    yAxis: 1
                }, {
                    name: 'Total Late Deliveries',
                    color: 'rgb(221, 105, 39)',
                    data: data.data_set_two,
                    yAxis: 1
                }],exporting: {
                    enabled: false
                }
            });
        }

        function show_wm_otd_bar_chart_two(data) {

            Highcharts.chart('on_time_orders',{
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: data.categories
                },
                yAxis: [{
                    min: 0,
                    title: {
                        text: 'On Time Arrival'
                    }
                }, {
                    title: {
                        text: 'On Time Delivery'
                    },
                    opposite: true
                }],
                legend: {
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                series: [{
                    name: 'OTA',
                    color: 'rgb(59, 59, 59)',
                    data: data.data_set_one,
                    yAxis: 1
                }, {
                    name: 'OTD',
                    color: 'rgb(183, 212, 9)',
                    data: data.data_set_two,
                    yAxis: 1
                }],
                exporting: {
                    enabled: false
                }
            });
        }

        // pagination function
        $(document).on('click','.paginationbt',function(){

            $(".total-orders-summary-main-wrap").find('.statistics-ajax-data-loader-wrap').addClass('show');
            let page_no =  $(this).attr('data-id');
            let date=document.getElementsByName('date')[0].value;
            //   let total_count=$('.total_order_count').val();

            $.ajax({
                type: "get",
                url: "{{ URL::to('new/dashboard/statistics/ajax/total-orders-summary')}}",
                data:{page:page_no,date:date,total_count:total_count},
                success: function (data) {
                    // hide loader
                    $(".total-orders-summary-main-wrap").find('.statistics-ajax-data-loader-wrap').removeClass('show');


                    if(data.status = true)
                    {
                        $('.'+data.for).html('');
                        $('.'+data.for).html(data.html);
                    }
                    else
                    {

                    }

                },
                error:function (error) {
                    // hide loader
                    $(".total-orders-summary-main-wrap").find('.statistics-ajax-data-loader-wrap').removeClass('show');

                    alert('some error');
                }
            });
        });

    </script>

@endsection