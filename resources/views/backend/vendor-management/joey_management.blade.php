@extends( 'backend.layouts.app' )

@section('title', 'Joey Management')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('css/icofont.min.css')}}" rel="stylesheet"/>
    <link href="{{ backend_asset('css/dashboard.css')}}" rel="stylesheet"/>
    <link href="{{ backend_asset('css/owl.carousel.min.css')}}" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        .link-wrap {
            position: relative;
        }

        .link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
        }

        @media only screen and (min-width: 768px) {
            .filter_bar {
                display: flex;
                align-items: center;
                padding: 15px;
            }
        }

        .filter_bar .total_joeys_signup {
        }

        .filter_bar .total_joeys_signup h3 {
            color: #e36d29;
            font-size: 45px;
            margin: 0;
            padding: 0;
            font-weight: bold !important;
        }

        .filter_bar .total_joeys_signup p {
            margin: 0;
            padding: 0;
            font-size: 16px;
        }

        .filter_bar .filter_form {
            margin-left: 20px;
        }

        .filter_bar .filter_form:after {
            content: "";
            display: block;
            clear: both;
        }

        .filter_bar .filter_form > * {
            float: left;
        }

        .filter_bar .filter_form .form-control {
            border: solid 1px #ddd;
            height: 44px;
            border-radius: 100px !important;
            width: 220px;
            padding: 0 15px;
            font-size: 16px;
        }

        .filter_bar .btn {
            cursor: pointer;
            height: 44px;
            margin-left: 8px;
            background: #e36d29;
            color: #fff;
            border: none;
            padding: 0 20px;
            font-size: 16px;
            border-radius: 100px !important;
            font-weight: bold;
        }

        .stages_list {
        }

        .stages_list:after {
            content: "";
            display: block;
            clear: both;
        }

        .stages_list .col {
            padding: 8px 12px;
            width: 20%;
            float: left;
        }

        .stages_list .stage_box {
            padding: 20px 5px 12px;
            box-shadow: 0 4px 16px #e6e6e6;
            border: solid 3px #fff;
            background: #fff;
            text-align: center;
            transition: all 0.2s ease-in-out;
            border-radius: 10px !important;
        }

        .stages_list .stage_box .number {
            color: #e36d29;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 0px;
            line-height: 1em;
        }

        .stages_list .stage_box .label {
            font-size: 15px;
            line-height: 1.4em;
            color: #666666;
            white-space: normal;
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stages_list .col .stage_box .fa {
            display: none;
        }

        .stages_list .col.active .stage_box {
            border-color: #e36d29 !important;
        }

        .stages_list .col.active .stage_box .fa {
            display: block;
            color: #e36d29;
            position: absolute;
            bottom: -26px;
            left: 50%;
            margin-left: -10px;
            width: 25px;
            height: 25px;
            font-size: 40px;
        }

        .graph_n_data_wrap {
            margin-top: 40px;
        }

        .graph_n_data_wrap .total_number {
            color: #e36d29;
            font-size: 32px;
            font-weight: bold;
            margin-top: 12px;
        }

        .graph_n_data_wrap .number_txt {
            margin-top: 15px;
            font-size: 16px;
        }

        .graph_n_data_wrap .number_txt strong {
            color: #e36d29;
        }

        .graph_n_data {
        }

        .loading_wrap {
            display: none;
        }

        .flex-wrapper {
            display: flex;
            flex-flow: row nowrap;
        }

        .single-chart {
            width: 230px;
            margin: 0 auto;
            justify-content: space-around;
        }

        .circular-chart {
            display: block;
            margin: 10px auto;
            max-width: 80%;
            max-height: 250px;
        }

        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 3.8;
        }

        .circle {
            fill: none;
            stroke-width: 2.8;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
        }

        @keyframes progress {
            0% {
                stroke-dasharray: 0 100;
            }
        }

        .circular-chart.orange .circle {
            stroke: #e36d29;
        }

        .percentage {
            fill: #e36d29;
            font-family: sans-serif;
            font-size: 0.5em;
            text-anchor: middle;
        }
        
        .m_l_dspace {
            margin-left: 50px;
        }
        
        .m_l_dspace:first-child {
            margin-left: 130px;
        }

        @media only screen and (max-width: 1160px) {
        }

        @media only screen and (max-width: 767px) {
            .stages_list .col {
                width: 50%;
            }

            .filter_bar .total_joeys_signup {
                text-align: center;
                margin-bottom: 12px;
            }

            .stages_list .stage_box {
            }
            .m_l_dspace {
                margin-left: 0;
                margin-bottom: 15px;
            }
        
            .m_l_dspace:first-child {
                margin-left: 0;
            }
        }

        @media only screen and (max-width: 480px) {
            .stages_list .col {
                width: 100%;
            }
        }

    </style>
    @if($data==false)
        <style>
            .stage_box.link-wrap {
                pointer-events: none;
            }
        </style>
    @endif
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/owl.carousel.min.js')}}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>




@endsection

@section('inlineJS')

@endsection

@section('content')
    <div class="right_col" role="main">
        <div class="dashboard_pg">
            <div class="dash_header_wrap">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="dash_heading with_filters">
                            <h1>Joey Management</h1>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <form method="get" action="">
                            <div class="row">
                                <div class="total_joeys_signup col-md-4 align-right">
                                    {{-- <h3 class="totalSignupNumber nomargin">
                                        {{0}}
                                        <span class="f16">Total Signups</span>
                                    </h3> --}}
                                </div>
                                <div class="filter_form col-md-4">
                                    <select required name="hub_id"" id="hub_id" class="form-control">
                                    {{-- <option value="">Select Hub</option> --}}
                                    @foreach ($hubs as $hub)
                                        <option <?php if ($hub->id == $hub_id) {
                                            echo "selected";
                                        } ?> value="{{base64_encode($hub->id)}}">{{$hub->city_name}}</option>
                                        @endforeach

                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-lg full-w">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- @if($data==true) --}}
            <div class="stages_list">
                {{-- <div class="col active"> --}}
                <div class="col pop_desc">
                    <div class="dashbords-conts-tiles-loader-main-wrap  joeys show">
                        <div class="dashbords-conts-tiles-loader-inner-wrap">
                            <div class="lds-roller">
                                <div class="dot-1"></div>
                                <div class="dot-2"></div>
                                <div class="dot-3"></div>
                                <div class="dot-4"></div>
                                <div class="dot-5"></div>
                                <div class="dot-6"></div>
                                <div class="dot-7"></div>
                                <div class="dot-8"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stage_box link-wrap basicRegistration totaljoeys pop_desc">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="totaljoeys"></a>
                        <div class="number totalnojoeys">{{0}}</div>
                        <input type="hidden" id="totalJoeyList" name="totalJoeyList[]">
                        <div class="label">Total Joeys</div>
                        <div class="desc">
                            <p>Total number of Joeys.</p>
                        </div>
                    </div>
                </div>
                <div class="col pop_desc">
                    <div class="dashbords-conts-tiles-loader-main-wrap  joeysonduty show">
                        <div class="dashbords-conts-tiles-loader-inner-wrap">
                            <div class="lds-roller">
                                <div class="dot-1"></div>
                                <div class="dot-2"></div>
                                <div class="dot-3"></div>
                                <div class="dot-4"></div>
                                <div class="dot-5"></div>
                                <div class="dot-6"></div>
                                <div class="dot-7"></div>
                                <div class="dot-8"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stage_box link-wrap pop_desc">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="joeyson"></a>
                        <div class="number joeyson">{{0}}</div>
                        <input type="hidden" id="totalJoeyOnList" name="totalJoeyOnList[]">
                        <div class="label">Joeys On Duty</div>
                        <div class="desc">
                            <p>Total number of Joeys on duty.</p>
                        </div>
                    </div>
                </div>
                <div class="col pop_desc">
                    <div class="dashbords-conts-tiles-loader-main-wrap joeysonduty  show">
                        <div class="dashbords-conts-tiles-loader-inner-wrap">
                            <div class="lds-roller">
                                <div class="dot-1"></div>
                                <div class="dot-2"></div>
                                <div class="dot-3"></div>
                                <div class="dot-4"></div>
                                <div class="dot-5"></div>
                                <div class="dot-6"></div>
                                <div class="dot-7"></div>
                                <div class="dot-8"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stage_box link-wrap pop_desc">
                        <i class="fa fa-caret-down"></i>
                        <a href="#" class="link" data-id="joeysoff"></a>
                        <div class="number joeysoff">{{0}}</div>
                        <div class="label">Joeys Not On Duty</div>
                        <div class="desc">
                            <p>Total number of Joeys not on duty.</p>
                        </div>
                    </div>
                </div>
                <div class="col pop_desc">
                    <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                        <div class="dashbords-conts-tiles-loader-inner-wrap">
                            <div class="lds-roller">
                                <div class="dot-1"></div>
                                <div class="dot-2"></div>
                                <div class="dot-3"></div>
                                <div class="dot-4"></div>
                                <div class="dot-5"></div>
                                <div class="dot-6"></div>
                                <div class="dot-7"></div>
                                <div class="dot-8"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stage_box link-wrap pop_desc">
                        <i class="fa fa-caret-down"></i>
                        {{--<a href="#" class="link" data-id="totalorders"></a>--}}
                        <div class="number totalorders">{{0}}</div>
                        <div class="label">Total Orders</div>
                        <div class="desc">
                            <p>Total number of orders.</p>
                        </div>
                    </div>
                </div>
                <div class="col pop_desc">
                    <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                        <div class="dashbords-conts-tiles-loader-inner-wrap">
                            <div class="lds-roller">
                                <div class="dot-1"></div>
                                <div class="dot-2"></div>
                                <div class="dot-3"></div>
                                <div class="dot-4"></div>
                                <div class="dot-5"></div>
                                <div class="dot-6"></div>
                                <div class="dot-7"></div>
                                <div class="dot-8"></div>
                            </div>
                        </div>
                    </div>
                    <div class="stage_box link-wrap pop_desc">
                        <i class="fa fa-caret-down"></i>
                        {{-- <a href="#" class="link" data-id="ordersdelivered"></a>--}}
                        <div class="number ordersdelivered">{{0}}</div>
                        <div class="label">Total Orders Delivered</div>
                        <div class="desc">
                            <p>Total number of delivered orders.</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="graph_n_data_wrap">
                <div class="row graph_n_data">
                    <div class="col-md-3 m_l_dspace">
                        <div class="dashbords-conts-tiles-loader-main-wrap  otd-day show">
                            <div class="dashbords-conts-tiles-loader-inner-wrap">
                                <div class="lds-roller">
                                    <div class="dot-1"></div>
                                    <div class="dot-2"></div>
                                    <div class="dot-3"></div>
                                    <div class="dot-4"></div>
                                    <div class="dot-5"></div>
                                    <div class="dot-6"></div>
                                    <div class="dot-7"></div>
                                    <div class="dot-8"></div>
                                </div>
                            </div>
                        </div>
                        <div class="stats_box pop_desc">
                            <h4>OTD By Day</h4>
                            <div class="circle_chart">
                                <div class="doughnut_percentage" id="doughnutChart1_percentage">00.00%</div>
                                <canvas id="doughnutChart1" height="240"></canvas>
                            </div>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl align-left">On Time Delivery</div>
                                    <div class="value align-left" id="day-on-value">0</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl align-left">Off Time Delivery</div>
                                    <div class="value align-left" id="day-off-value">0</div>
                                </div>
                            </div>
                            <div class="desc">
                                <p>OTD For Day .</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 m_l_dspace">
                        <div class="dashbords-conts-tiles-loader-main-wrap  otd-week show">
                            <div class="dashbords-conts-tiles-loader-inner-wrap">
                                <div class="lds-roller">
                                    <div class="dot-1"></div>
                                    <div class="dot-2"></div>
                                    <div class="dot-3"></div>
                                    <div class="dot-4"></div>
                                    <div class="dot-5"></div>
                                    <div class="dot-6"></div>
                                    <div class="dot-7"></div>
                                    <div class="dot-8"></div>
                                </div>
                            </div>
                        </div>
                        <div class="stats_box pop_desc">
                            <h4>OTD By Week</h4>
                            <div class="circle_chart">
                                <div class="doughnut_percentage" id="doughnutChart2_percentage">00.00%</div>
                                <canvas id="doughnutChart2" height="240"></canvas>
                            </div>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl align-left">On Time Delivery</div>
                                    <div class="value align-left" id="week-on-value">0</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl align-left">Off Time Delivery</div>
                                    <div class="value align-left" id="week-off-value">0</div>
                                </div>
                            </div>
                            <div class="desc">
                                <p>OTD For Week.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 m_l_dspace">
                        <div class="dashbords-conts-tiles-loader-main-wrap  otd-month show">
                            <div class="dashbords-conts-tiles-loader-inner-wrap">
                                <div class="lds-roller">
                                    <div class="dot-1"></div>
                                    <div class="dot-2"></div>
                                    <div class="dot-3"></div>
                                    <div class="dot-4"></div>
                                    <div class="dot-5"></div>
                                    <div class="dot-6"></div>
                                    <div class="dot-7"></div>
                                    <div class="dot-8"></div>
                                </div>
                            </div>
                        </div>
                        <div class="stats_box pop_desc">
                            <h4>OTD By Month</h4>
                            <div class="circle_chart">
                                <div class="doughnut_percentage" id="doughnutChart3_percentage">00.00%</div>
                                <canvas id="doughnutChart3" height="240"></canvas>
                            </div>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl align-left">On Time Delivery</div>
                                    <div class="value align-left" id="month-on-value">0</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl align-left">Off Time Delivery</div>
                                    <div class="value align-left" id="month-off-value">0</div>
                                </div>
                            </div>
                            <div class="desc">
                                <p>OTD For Month.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="graph_n_data_wrap">

                <div class="row graph_n_data section_divider">
                    <div class="col-md-12">
                        <div class="loading_wrap">
                            <img src="<?php echo e(asset('assets/admin/img/giphy.gif')); ?>" alt="">
                        </div>
                        <div class="data_list_wrap">
                            <h2 id="joeysList" style="display: none;">Joeys list</h2>
                            <div class="portlet-body">
                                <table id="totaljoeys"
                                       class="table full-w table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="joeysoff"
                                       class="table full-w table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="joeyson"
                                       class="table full-w table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%" class="text-center ">ID</th>
                                        <th style="width: 30%" class="text-center ">Name</th>
                                        <th style="width: 10%" class="text-center ">Address</th>
                                        <th style="width: 10%" class="text-center ">Email</th>
                                        <th style="width: 20%" class="text-center ">Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="totalorders"
                                       class="table full-w table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%" class="text-center ">Sprint ID</th>
                                        <th style="width: 30%" class="text-center ">Tracking ID</th>
                                        <th style="width: 10%" class="text-center ">Route ID</th>
                                        <th style="width: 20%" class="text-center ">Joey Name</th>
                                        <th style="width: 20%" class="text-center ">Status</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <table id="ordersdelivered"
                                       class="table full-w table-striped table-bordered table-hover hidden data-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%" class="text-center ">Sprint ID</th>
                                        <th style="width: 30%" class="text-center ">Tracking ID</th>
                                        <th style="width: 10%" class="text-center ">Route ID</th>
                                        <th style="width: 20%" class="text-center ">Joey Name</th>
                                        <th style="width: 20%" class="text-center ">Status</th>
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
            {{-- @endif     --}}

            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-6 col-xs-6">
                    <h2>Joeys OTD</h2>
                </div>
                <div class="col-md-4 col-xs-4 pull-right">
                    <select name="select_graph" id="select_graph" class="form-control form-control-xs">
                        <option value="week" selected >By Week</option>
                        <option value="month">By Month</option>
                    </select>
                </div>
            </div>
            <hr/>
            <div id="summary_grid" class="cs_data_table  pop_desc">
                <table 
                    class="joeysOTDTable table full-w table-striped table-bordered table-hover data-table">
                    <thead>
                        <tr>
                            <th style="width: 10%">ID</th>
                           {{-- <th style="width: 10%">Image</th>--}}
                            <th style="width: 20%">Name</th>
                            <th style="width: 20%">Email</th>
                            <th style="width: 20%">Phone</th>
                           {{-- <th style="width: 20%">Address</th>--}}
                            <th style="width: 10%">OTD</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div>
            </div>
        </div>
        <!-- /#page-wrapper -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            var alljoeys = []
            var joeysOnDuty = []


            $(function () {


                // Chart 1
                var onTime = 100;
                var offTime = 0;
                const doughnutChart1Data = {
                    labels: [
                        `On Time Deliveries ${onTime}%`,
                        `Off Time Deliveries ${offTime}%`,
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [onTime, offTime],
                        backgroundColor: ['#0fda8b', '#ff6384',],
                        hoverOffset: 30
                    }]
                };
                var doughnutChart1 = document.getElementById('doughnutChart1');
                var doughnutChart1Init = new Chart(doughnutChart1, {
                    type: 'doughnut',
                    data: doughnutChart1Data,
                    options: {
                        responsive: true,
                        legend: {
                            display: false,
                        },
                        aspectRatio: 1,
                        plugins: {
                            title: {
                                display: false,
                                text: 'Chart.js Doughnut Chart'
                            }
                        }
                    },
                })

                function getOTDDay() {
                    let hubId = $('#hub_id').val();
                    $('.otd-day').addClass('show');
                    $.ajax({
                        type: "GET",
                        url: "{{route('joey-management-otd-day.index')}}",
                        data: {'hub_id': hubId},
                        success: function (data) {
                            doughnutChart1Init.data.labels[0] = 'On Time Deliveries ' + data['y2'] + '%';
                            doughnutChart1Init.data.labels[1] = 'Off Time Deliveries ' + data['y1'] + '%';
                            doughnutChart1Init.data.datasets[0].data[0] = data['ontime'];
                            doughnutChart1Init.data.datasets[0].data[1] = data['offtime'];
                            doughnutChart1Init.update();
                            $('#day-on-value').text(data['ontime']);
                            $('#day-off-value').text(data['offtime']);
                            $('#doughnutChart1_percentage').text(data['y2'] + '%');
                            $('.otd-day').removeClass('show');
                        },
                        error: function (error) {
                            console.log(error);
                            // hide loader
                            $('.otd-day').removeClass('show');
                        }
                    })
                }


                // Chart 1
                var onTime = 100;
                var offTime = 0;
                console.log(onTime)
                const doughnutChart2Data = {
                    labels: [
                        `On Time Deliveries ${onTime}%`,
                        `Off Time Deliveries ${offTime}%`,
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [onTime, offTime],
                        backgroundColor: ['#0fda8b', '#ff6384',],
                        hoverOffset: 30
                    }]
                };
                var doughnutChart2 = document.getElementById('doughnutChart2');
                var doughnutChart2Init = new Chart(doughnutChart2, {
                    type: 'doughnut',
                    data: doughnutChart1Data,
                    options: {
                        responsive: true,
                        legend: {
                            display: false,
                        },
                        aspectRatio: 1,
                        plugins: {
                            title: {
                                display: false,
                                text: 'Chart.js Doughnut Chart'
                            }
                        }
                    },
                })


                function getOTDWeek() {
                    let hubId = $('#hub_id').val();
                    $('.otd-week').addClass('show');
                    $.ajax({
                        type: "GET",
                        url: "{{route('joey-management-otd-week.index')}}",
                        data: {'hub_id': hubId},
                        success: function (data) {
                            doughnutChart2Init.data.labels[0] = 'On Time Deliveries ' + data['y2'] + '%';
                            doughnutChart2Init.data.labels[1] = 'Off Time Deliveries ' + data['y1'] + '%';
                            doughnutChart2Init.data.datasets[0].data[0] = data['ontime'];
                            doughnutChart2Init.data.datasets[0].data[1] = data['offtime'];
                            doughnutChart2Init.update();
                            $('#week-on-value').text(data['ontime']);
                            $('#week-off-value').text(data['offtime']);
                            $('#doughnutChart2_percentage').text(data['y2'] + '%');
                            $('.otd-week').removeClass('show');
                        },
                        error: function (error) {
                            console.log(error);
                            // hide loader
                            $('.otd-week').removeClass('show');
                        }
                    })
                }

                // Chart 1
                var onTime = 100;
                var offTime = 0;
                console.log(onTime)
                const doughnutChart3Data = {
                    labels: [
                        `On Time Deliveries ${onTime}%`,
                        `Off Time Deliveries ${offTime}%`,
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [onTime, offTime],
                        backgroundColor: ['#0fda8b', '#ff6384',],
                        hoverOffset: 30
                    }]
                };
                var doughnutChart3 = document.getElementById('doughnutChart3');
                var doughnutChart3Init = new Chart(doughnutChart3, {
                    type: 'doughnut',
                    data: doughnutChart1Data,
                    options: {
                        responsive: true,
                        legend: {
                            display: false,
                        },
                        aspectRatio: 1,
                        plugins: {
                            title: {
                                display: false,
                                text: 'Chart.js Doughnut Chart'
                            }
                        }
                    },
                })


                function getOTDMonth() {
                    let hubId = $('#hub_id').val();
                    $('.otd-month').addClass('show');
                    $.ajax({
                        type: "GET",
                        url: "{{route('joey-management-otd-month.index')}}",
                        data: {'hub_id': hubId},
                        success: function (data) {
                            doughnutChart3Init.data.labels[0] = 'On Time Deliveries ' + data['y2'] + '%';
                            doughnutChart3Init.data.labels[1] = 'Off Time Deliveries ' + data['y1'] + '%';
                            doughnutChart3Init.data.datasets[0].data[0] = data['ontime'];
                            doughnutChart3Init.data.datasets[0].data[1] = data['offtime'];
                            doughnutChart3Init.update();
                            $('#month-on-value').text(data['ontime']);
                            $('#month-off-value').text(data['offtime']);
                            $('#doughnutChart3_percentage').text(data['y2'] + '%');
                            $('.otd-month').removeClass('show');
                        },
                        error: function (error) {
                            console.log(error);
                            // hide loader
                            $('.otd-month').removeClass('show');
                        }
                    })
                }

                var totalSignups = $('.total_joeys_signup .totalSignupNumber').text();
                console.log('totalSignups: ', totalSignups)
                $('.stage_box a').click(function (e) {
                    e.preventDefault();

                    var thisId = $(this).data('id'),

                        thisNumber = $(this).closest('.stage_box').find('.number').text();
                    calculation = Math.round(parseInt(thisNumber) / parseInt(totalSignups) * 100);

                    percentage = (calculation) ? calculation : 0;

                    $('#percentageCircle').attr('stroke-dasharray', percentage + ', 100');
                    $('.percentage').attr('data-test', 'test').text(percentage + '%');
                    console.log('percentage: ', percentage);

                    $('.portlet-body .dataTables_wrapper').addClass('hidden');
                    $('.portlet-body #' + thisId).removeClass('hidden');

                    $('.total_number').text(thisNumber);

                    $('#' + thisId).attr('data-test', 'test');
                    $('#' + thisId).DataTable().destroy();

                    var filterVal = $('#hub_id').val();
                    $('#' + thisId).dataTable().fnDestroy();

                    if (thisId == 'totaljoeys' || thisId == 'joeyson' || thisId == 'joeysoff') {

                        $('#' + thisId).DataTable({
                            ajax: {
                                url: '{{url("joey-management/list")}}' + '?type=' + thisId + '&hub_id=' + filterVal,
                                type: "GET",
                                //                        error: function (e) {
                                //                        },
                                //                        dataSrc: function (d) {
                                //                            console.log('d: ', d);
                                //                            return d
                                //                        }
                            },
                            pageLength: 10,
                            lengthMenu: [10, 200, 300, 400, 500],
                            "processing": true,
                            "serverSide": true,
                            columns: [
                                {data: 'id'},
                                {data: 'first_name'},
                                {data: 'address'},
                                {data: 'email'},
                                {data: 'phone'},
                            ]
                        });
                    } else {
                        // alert('orders');
                        $('#' + thisId).DataTable({
                            ajax: {
                                url: '{{url("joey-management/order-list")}}' + '?type=' + thisId + '&hub_id=' + filterVal,
                                type: "GET",
                                //                        error: function (e) {
                                //                        },
                                //                        dataSrc: function (d) {
                                //                            console.log('d: ', d);
                                //                            return d
                                //                        }
                            },
                            pageLength: 10,
                            lengthMenu: [10, 200, 300, 400, 500],
                            "processing": true,
                            "serverSide": true,
                            columns: [
                                {data: 'sprint_id'},
                                {data: 'tracking_id'},
                                {data: 'route_id'},
                                {data: 'joey_name'},
                                {data: 'status_id'},
                            ]
                        });
                    }
                });

                $('.stage_box').on('click', function () {

                    // if ($(this).hasClass("joeyson")) {
                    //     $("#chartforactivejoeys").css('display','block');
                    // }else{
                    //     $("#chartforactivejoeys").css('display','none');
                    // }

                    $('.col').removeClass('active');
                    $(this).closest('.col').addClass('active');

                    $('.graph_n_data_wrap .loading_wrap').css('display', 'block');
                    $('.graph_n_data_wrap .data_list_wrap').css('display', 'none');
                    $('.graph_n_data_wrap #joeysList').css('display', 'block');

                    setTimeout(function () {
                        $('.graph_n_data_wrap .loading_wrap').css('display', 'none');
                        $('.graph_n_data_wrap .data_list_wrap').css('display', 'block');
                    }, 1000);
                })


                function totalJoeysCount() {

                    let hubId = $('#hub_id').val();
                    $('.joeys').addClass('show');
                    $('.joeysonduty').addClass('show');
                    $.ajax({
                        type: "GET",
                        url: "{{route('joey-management-joey-count.index')}}",
                        data: {'hub_id': hubId},
                        success: function (data_res) {   // success callback function
                            alljoeys = data_res.joeys_id;
                            $('#totalJoeyList').val(alljoeys);
                            $('.totalnojoeys').html(data_res.joeys_count);
                            $('.joeys').removeClass('show');
                            if (data_res.joeys_count > 0) {
                                getOnDutyJoeys(alljoeys);
                            }
                            else {
                                $('.joeysoff').html(data_res.joeys_count);
                                $('.joeysonduty').removeClass('show');
                            }

                        },
                        error: function (jqXhr, textStatus, errorMessage) { // error callback
                            console.log('Error: ' + errorMessage);
                            $('.joeys').removeClass('show');
                        }
                    });


                }

                function getOnDutyJoeys(alljoeys=[]) {
                    let hubId = $('#hub_id').val();
                    $.ajax({
                        type: "POST",
                        url: "{{route('joey-management-joey-count.onduty')}}",
                        data: {'joeys': alljoeys, 'hub_id': hubId},
                        success: function (res) {   // success callback function
                            joeysOnDuty = res.joeys_id;
                            $('#totalJoeyOnList').val(joeysOnDuty);
                            $('.joeyson').html(res.joeys_count);
                            $('.joeysoff').html(parseInt($('.totalnojoeys').html()) - parseInt(res.joeys_count));
                            $('.joeysonduty').removeClass('show');
                        },
                        error: function (jqXhr, textStatus, errorMessage) { // error callback
                            console.log('Error: ' + errorMessage);
                            $('.joeysonduty').removeClass('show');
                        }
                    });
                }

                function totalOrders() {

                    let hubId = $('#hub_id').val();
                    $('.total-order').addClass('show');
                    $.ajax({
                        type: "GET",
                        url: "{{route('joey-management-orders-count.index')}}",
                        data: {'hub_id': hubId},
                        success: function (data) {   // success callback function

                            $('.totalorders').html(data.total);
                            $('.ordersdelivered').html(data.delivered_order);

                            $('.total-order').removeClass('show');
                        },
                        error: function (jqXhr, textStatus, errorMessage) { // error callback
                            console.log('Error: ' + errorMessage);
                            $('.total-order').removeClass('show');
                        }
                    });


                }

                function getAllJoeyOTD(type) {


                    let hubId = $('#hub_id').val();
                    $('.all-joey').addClass('show');
                    $('.joeysOTDTable').DataTable({
                        ajax: {
                            url: '{{route("joey-management-all-joeys-otd.index")}}',
                            data: {'hub_id': hubId,'type': type},
                            type: "GET",
                            error: function (e) {
                                console.log('e: ', e);
                            },
                            dataSrc: function (d) {
                                console.log('d: ', d);
                                return d
                            }
                        },
                        paging: true,
                        pageLength: 10,
                        lengthMenu: [20, 40, 60, 80, 100, 150, 200],
                        processing: true,
                        // serverSide: true,
                        columns: [
                            {data: 'joey_id'},
                            //{data: 'image'},
                            {data: 'name'},
                            {data: 'email'},
                            {data: 'phone'},
                           // {data: 'address'},
                            {data: 'count'},
                        ],
                       /* columnDefs:
                        [{
                            "targets": 1,
                            "data": 'logo',
                            "render": function (data, type, row, meta) {
                                return '<img src="' + data + '" alt="' + data + '"height="35" width="35"/>';
                            }
                        }],*/
                    });
                }


                $('#select_graph').on('change', function() {
                    var type  = this.value;
                    getAllJoeyOTD(type);
                });
                setTimeout(function () {
                    totalJoeysCount();
                    totalOrders();
                    getOTDDay();
                    getOTDWeek();
                    getOTDMonth();
                    getAllJoeyOTD('week');
                }, 1000);
            })

            $('#days').val('<?php echo (isset($_GET['days'])) ? $_GET['days'] : 'all'; ?>');


        </script>
@endsection
