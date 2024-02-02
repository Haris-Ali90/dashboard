@extends( 'backend.layouts.app' )

@section('title', 'Statistics')

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
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>
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
            <!-- Header - [start] -->
            @if(empty($hub_id))

            @else
            <div class="dash_header_wrap">
                <div class="row">
                    <div class="col-md-6">
                        <div class="dash_heading">
                            <h1>{{$hub_name && $hub_name->city_name ? $hub_name->city_name : ''}}</h1>

                            {{--<div class="dropdown_btn">--}}
                                {{--<i class="icofont-rounded-down"></i>--}}
                                {{--<div class="dropdown_wrap">--}}
                                    {{--<ul>--}}
                                        {{--<li><a href="#">Montreal</a></li>--}}
                                        {{--<li><a href="#">Ottawa</a></li>--}}
                                        {{--<li><a href="#">CTC</a></li>--}}
                                        {{--<li><a href="#">Toronto</a></li>--}}
                                        {{--<li><a href="#">Walmart</a></li>--}}
                                        {{--<li><a href="#">Loblaws</a></li>--}}
                                    {{--</ul>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <!-- Header - [/end] -->



            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6 stats_box_wrap">
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
                                <canvas id="doughnutChart1" height="180"></canvas>
                            </div>
                            <div class="row">
                                <div class="attr col-md-6">
									<div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl" >On Time Delivery</div>
									<div class="value" id="day-on-value">0</div>
								</div>
								<div class="attr col-md-6">
									<div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl" >Off Time Delivery</div>
									<div class="value" id="day-off-value">0</div>
								</div>
							</div>
                            <div class="desc">
                                <p>OTD For Selected Date.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 stats_box_wrap">
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
                        <div class="stats_box pop_desc" >
                            <h4>OTD By Week</h4>
                            <div class="circle_chart">
                                <div class="doughnut_percentage" id="doughnutChart2_percentage">00.00%</div>
                                <canvas id="doughnutChart2" height="180"></canvas>
                            </div>
                            <div class="row">
                                <div class="attr col-md-6">
									<div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">On Time Delivery</div>
									<div class="value" id="week-on-value">0</div>
								</div>
								<div class="attr col-md-6">
									<div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Off Time Delivery</div>
									<div class="value" id="week-off-value">0</div>
								</div>
							</div>
                            <div class="desc">
                                <p>OTD For Last Week.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 stats_box_wrap">
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
                                <canvas id="doughnutChart3" height="180"></canvas>
                            </div>
                            <div class="row">
                                <div class="attr col-md-6">
									<div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">On Time Delivery</div>
									<div class="value" id="month-on-value">0</div>
								</div>
								<div class="attr col-md-6">
									<div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Off Time Delivery</div>
									<div class="value" id="month-off-value">0</div>
								</div>
							</div>
                            <div class="desc">
                                <p>OTD For Last Month.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 stats_box_wrap">
                        <div class="dashbords-conts-tiles-loader-main-wrap  otd-year show">
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
                            <h4>OTD By 6 Month</h4>
                            <div class="circle_chart">
                                <div class="doughnut_percentage" id="doughnutChart4_percentage">00.00%</div>
                                <canvas id="doughnutChart4" height="180"></canvas>
                            </div>
                            <div class="row">
								<div class="attr col-md-6">
									<div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">On Time Delivery</div>
									<div class="value" id="year-on-value">0</div>
								</div>
								<div class="attr col-md-6">
									<div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Off Time Delivery</div>
									<div class="value" id="year-off-value">0</div>
								</div>
							</div>
                            <div class="desc">
                                <p>OTD For Last 6 Months.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- stats section 1 - [/end] -->

            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <!-- Featured numbers - [start] -->
                <div class="featured_numbers statistics">
                    <div class="number_box">
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
                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Total Orders</h3>
                            <p class="numbers" id="total_orders">0</p>
                            {{-- <p class="perc">38%</p>--}}
                            
                            <div class="desc">
                                <p>Total number of orders.</p>
                            </div>
                        </div>
                    </div>


                    <div class="number_box">
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
                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Sorted Orders</h3>
                            <p class="numbers" id="sorted_orders">0</p>
                            {{--<p class="perc">{{round(($counts['total'] != 0) ? ($counts['sorted']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of sorted orders.</p>
                            </div>
                        </div>
                    </div>
                    <div class="number_box">
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
                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Out For Delivery Orders</h3>
                            <p class="numbers" id="picked_orders">0</p>
                            {{-- <p class="perc">{{round(($counts['total'] != 0) ? ($counts['pickup']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of picked up from hub orders.</p>
                            </div>
                        </div>
                    </div>
                    <div class="number_box">
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

                            <div class="inner pop_desc">
                            <h3 class="basecolor1">Return Orders</h3>
                            <p class="numbers" id="return_orders">0</p>
                            {{--          <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['return_orders']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of return orders.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
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

                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Hub Return Scan</h3>
                            <p class="numbers" id="hub_return_scan">0</p>
                            {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['failed']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of return orders scanned at hub.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
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

                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Hub Not Return Scan</h3>
                            <p class="numbers" id="hub_not_return_scan">0</p>
                            {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of return orders not scanned at hub.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
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
                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Delivered Orders</h3>
                            <p class="numbers" id="delivered_orders">0</p>
                            {{--  <p class="perc ">{{round(($counts['total'] != 0) ? ($counts['delivered_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of delivered orders.</p>
                            </div>
                        </div>
                    </div>
                    <div class="number_box">
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

                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Scheduled At Hub </h3>
                            <p class="numbers" id="notscan_orders">0</p>
                            {{--            <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['notscan']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of order which are not scan at hub.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
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

                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Reattempted At Hub</h3>
                                <p class="numbers" id="reattempted_orders">0</p>
                                {{--            <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['notscan']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of order which are reattempted at hub.</p>
                                </div>
                            </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap  failed-order show">
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

                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Total Failed Orders</h3>
                            <p class="numbers" id="failed_orders">0</p>
                            {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of failed orders which are not created due to any reason.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap  failed-order show">
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


                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Failed Orders Created</h3>
                            <p class="numbers" id="system_failed_orders">0</p>
                            {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of created failed orders which are created from failed orders.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap  failed-order show">
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

                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Failed Orders Not Created</h3>
                            <p class="numbers" id="not_system_failed_orders">0</p>
                            {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of failed orders which are not created due to any reason.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap  custom-order show">
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

                        <div class="inner pop_desc">
                            <h3 class="basecolor1">Custom Orders</h3>
                            <p class="numbers" id="custom_orders">0</p>
                            {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            <div class="desc">
                                <p>Total number of orders which are created from custom routing.</p>
                            </div>
                        </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap  route-count show">
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

                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Total Routes</h3>
                                <p class="numbers" id="total_route">0</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of routes.</p>
                                </div>
                            </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap  route-count show">
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

                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Normal Routes</h3>
                                <p class="numbers" id="normal_route">0</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of routes except custom and big-box routes.</p>
                                </div>
                            </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap route-count show">
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

                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Custom Routes</h3>
                                <p class="numbers" id="custom_route">0</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of routes created by custom routing.</p>
                                </div>
                            </div>

                    </div>
                    <div class="number_box">
                        <div class="dashbords-conts-tiles-loader-main-wrap route-count show">
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

                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Big Box Routes</h3>
                                <p class="numbers" id="big_box_route">0</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of routes created by big box custom routing.</p>
                                </div>
                            </div>

                    </div>
                </div>
                <!-- Featured numbers - [/end] -->
            </div>
                <hr>

                <!-- stats section 1 - [/end] -->
                <div class="stats section">
                    <!-- Featured numbers - [start] -->
                    <h2>Current Stats</h2>
                    <div class="featured_numbers statistics">


                        <div class="number_box">
                            <div class="dashbords-conts-tiles-loader-main-wrap  total-summary show">
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
                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Remaining To Be Picked Up</h3>
                                <p class="numbers" id="sorted_remain">0</p>
                                {{-- <p class="perc">38%</p>--}}

                                <div class="desc">
                                    <p>Total number of packages which are sorted and ready to pickup.</p>
                                </div>
                            </div>
                        </div>
                        <div class="number_box">
                            <div class="dashbords-conts-tiles-loader-main-wrap  total-summary show">
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
                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Remaining Packages OFD</h3>
                                <p class="numbers" id="picked_remain">0</p>
                                {{--<p class="perc">{{round(($counts['total'] != 0) ? ($counts['sorted']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of packages which are picked up and ready to deliver.</p>
                                </div>
                            </div>
                        </div>
                        <div class="number_box">
                            <div class="dashbords-conts-tiles-loader-main-wrap  total-summary show">
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
                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Remaining Routes OFD</h3>
                                <p class="numbers" id="route_picked_remain">0</p>
                                {{-- <p class="perc">{{round(($counts['total'] != 0) ? ($counts['pickup']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total routes of packages which are picked up and ready to deliver.</p>
                                </div>
                            </div>
                        </div>
                        <div class="number_box">
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

                                <div class="inner pop_desc">
                                    <h3 class="basecolor1">Order Completion Ratio</h3>
                                    <p class="numbers" id="completion_order">0.00%</p>
                                    {{--          <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['return_orders']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                    <div class="desc">
                                        <p>Percentage of delivered packages.</p>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>


            <div class="chart_stats section">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-xs-12">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>On Time Delivery And Off Time Delivery</h4>
                            </div>
                                            {{--   <div class="col-md-4">--}}
                                            {{--    <div class="form-group chart_filter_control">--}}
                                            {{--    <select name="select_graph" id="select_graph" class="form-control form-control-xs tb_padding">--}}
                                            {{--        <option value="week" selected >By Week</option>--}}
                                            {{--        <option value="month">By Month</option>--}}
                                            {{--               </select>--}}
                                            {{--                  </div>--}}
                                            {{--                  </div>--}}
                        </div>
                        <div style="position: relative;">
                            <div class="dashbords-conts-tiles-loader-main-wrap  graph-loader show " style="padding: 0px 0px 0px 0px; !important;" >
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
                            <div class="stats_box">
                                <canvas id="myChart" width="760" height="455"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-top: 58px;">
                        <div class="stats_box mb-20 center pop_desc">
                            <div class="dashbords-conts-tiles-loader-main-wrap  manual-order show nopadding">
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
                            <div class="numbers">Manual Status Mark</div>
                            <div class="title" id="manual_orders">0</div>
                            {{-- <div class="title2">Out of 1,568 orders</div>--}}
                            
                            <div class="desc">
                                <p>Total number of orders which status has marked manually. </p>
                            </div>
                        </div>
                        <div class="stats_box mb-20 center pop_desc">
                            <div class="dashbords-conts-tiles-loader-main-wrap  time-order show nopadding">
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
                            <div class="numbers">Sorting Time</div>
                            <div class="title" id="sorting_time">00:00 Hours</div>
                           {{-- <div class="title2">Out of 1,568 orders</div>--}}
                           
                           <div class="desc">
                                <p>Total sorting time of orders</p>
                            </div>
                        </div>
                        <div class="stats_box center pop_desc">
                            <div class="dashbords-conts-tiles-loader-main-wrap  time-order show nopadding">
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
                            <div class="numbers">Picked Up Time</div>
                            <div class="title" id="picked_time">00:00 Hours</div>
                            {{--<div class="title2">Out of 1,568 orders</div>--}}
                            <div class="desc">
                                <p>Total picked up time of orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <div class="row">
                   
                    <div class="col-lg-4 col-md-6 col-xs-12 pop_desc" >
                        <div class="dashbords-conts-tiles-loader-main-wrap  broker-joey show">
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
                        <div class="data_box pb-0">
                            <h4>Top 10 Brokers </h4>
                            <div class="order_list" id="order_row_list"></div>
                        </div>
                        <div class="desc">
                            <p>The name of brokers their joeys have highest number of on time deliveries.</p>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6 col-xs-12">
                        <div class="stats_box mb-20 pop_desc">
                            <div class="dashbords-conts-tiles-loader-main-wrap  top-ten show nopadding">
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
                            <h4 class="color-green">Top 10 Drivers Performers</h4>
                            <div class="joeys_list">
                                <div id="topPerformers"> </div>
                            </div>
                            <div class="desc">
                                <p>The name of Drivers they have highest number of on time deliveries.</p>
                            </div>
                        </div>

                        <div class="stats_box pop_desc">
                            <div class="dashbords-conts-tiles-loader-main-wrap  least-ten show nopadding">
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
                            <h4 class="color-red">10 Least Drivers Performers</h4>
                            <div class="joeys_list">
                            <div id="leastPerformers"> </div>
                            </div>
                            <div class="desc">
                                <p>The name of Drivers they have lowest number of off time deliveries.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- stats section 1 - [/end] -->
                @endif
        </div>
    </div>
    <!-- /#page-wrapper -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>


        $(function()
        {
			// Chart 1
            var onTime = 100;
            var offTime = 0;

            const doughnutChart1Data = {
                labels: [
                    `On Time Deliveries ${onTime}%`,
                    `Off Time Deliveries ${offTime}%`,
                    //`Running Late ${52}%`,
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
                

            function getOTDDay()
            {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.otd-day').addClass('show');

                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/day/otd",
                    data: {
                        'datepicker': selected_date, 
                        'hub_id': hubId
                    },
                   /* beforeSend: function (xhr) {
                        xhr.overrideMimeType("text/plain; charset=x-user-defined");
                    },*/
                    success: function (data)
                    {
                        doughnutChart1Init.data.labels[0] = 'On Time Deliveries '+data['y2']+'%';
                        doughnutChart1Init.data.labels[1] = 'Off Time Deliveries '+data['y1']+'%';
                        doughnutChart1Init.data.datasets[0].data[0] = data['ontime'];
                        doughnutChart1Init.data.datasets[0].data[1] = data['offtime'];
                        doughnutChart1Init.update();
                        $('#day-on-value').text(data['ontime']);
                        $('#day-off-value').text(data['offtime']);
                        $('#doughnutChart1_percentage').text(data['y2']+'%');
                        $('.otd-day').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.otd-day').removeClass('show');
                    }
                })
            }

			// Chart 2
            var onTime = 100;
            var offTime = 0;
            const doughnutChart2Data = {
                labels: [
                    `On Time Deliveries ${onTime}%`,
                    `Off Time Deliveries ${offTime}%`,
                    //`Running Late ${52}%`,
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
                data: doughnutChart2Data,
                options: {
                    aspectRatio: 1,
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    plugins: {
                        title: {
                            display: false,
                            text: 'Chart.js Doughnut Chart'
                        }
                    }
                },
            })

            function getOTDWeek() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.otd-week').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/week/otd",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    /* beforeSend: function (xhr) {
                         xhr.overrideMimeType("text/plain; charset=x-user-defined");
                     },*/
                    success: function (data) {
                        doughnutChart2Init.data.labels[0] = 'On Time Deliveries '+data['y2']+'%';
                        doughnutChart2Init.data.labels[1] = 'Off Time Deliveries '+data['y1']+'%';
                        doughnutChart2Init.data.datasets[0].data[0] = data['ontime'];
                        doughnutChart2Init.data.datasets[0].data[1] = data['offtime'];
                        doughnutChart2Init.update();
                        $('#week-on-value').text(data['ontime']);
                        $('#week-off-value').text(data['offtime']);
                        $('#doughnutChart2_percentage').text(data['y2']+'%');
                        $('.otd-week').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.otd-week').removeClass('show');
                    }
                })
            }

			// Chart 3
            var onTime = 100;
            var offTime = 0;
            const doughnutChart3Data = {
                labels: [
                    `On Time Deliveries ${onTime}%`,
                    `Off Time Deliveries ${offTime}%`,
                    //`Running Late ${52}%`,
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
                data: doughnutChart3Data,
                options: {
                    aspectRatio: 1,
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    plugins: {
                        title: {
                            display: false,
                            text: 'Chart.js Doughnut Chart'
                        }
                    }
                },
            })

            function getOTDMonth() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.otd-month').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/month/otd",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    /* beforeSend: function (xhr) {
                         xhr.overrideMimeType("text/plain; charset=x-user-defined");
                     },*/
                    success: function (data) {
                        doughnutChart3Init.data.labels[0] = 'On Time Deliveries '+data['y2']+'%';
                        doughnutChart3Init.data.labels[1] = 'Off Time Deliveries '+data['y1']+'%';
                        doughnutChart3Init.data.datasets[0].data[0] = data['ontime'];
                        doughnutChart3Init.data.datasets[0].data[1] = data['offtime'];
                        doughnutChart3Init.update();
                        $('#month-on-value').text(data['ontime']);
                        $('#month-off-value').text(data['offtime']);
                        $('#doughnutChart3_percentage').text(data['y2']+'%');
                        $('.otd-month').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.otd-month').removeClass('show');
                    }
                })
            }

			// Chart 1
            var onTime = 100;
            var offTime = 0;
            const doughnutChart4Data = {
                labels: [
                    `On Time Deliveries ${onTime}%`,
                    `Off Time Deliveries ${offTime}%`,
                    //`Running Late ${52}%`,
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [onTime, offTime],
                    backgroundColor: ['#0fda8b', '#ff6384',],
                    hoverOffset: 30
                }]
            };
            var doughnutChart4 = document.getElementById('doughnutChart4');
            var doughnutChart4Init = new Chart(doughnutChart4, {
                type: 'doughnut',
                data: doughnutChart4Data,
                options: {
                    aspectRatio: 1,
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    plugins: {
                        title: {
                            display: false,
                            text: 'Chart.js Doughnut Chart'
                        }
                    }
                },
            })

            function getOTDYear() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.otd-year').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/year/otd",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    /* beforeSend: function (xhr) {
                         xhr.overrideMimeType("text/plain; charset=x-user-defined");
                     },*/
                    success: function (data) {
                        // console.log(data);
                        doughnutChart4Init.data.labels[0] = 'On Time Deliveries '+data['y2']+'%';
                        doughnutChart4Init.data.labels[1] = 'Off Time Deliveries '+data['y1']+'%';
                        doughnutChart4Init.data.datasets[0].data[0] = data['ontime'];
                        doughnutChart4Init.data.datasets[0].data[1] = data['offtime'];
                        doughnutChart4Init.update();
                        $('#year-on-value').text(data['ontime']);
                        $('#year-off-value').text(data['offtime']);
                        $('#doughnutChart4_percentage').text(data['y2']+'%');
                        $('.otd-year').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.otd-year').removeClass('show');
                    }
                })
            }

            function getTotalOrderDataCount() 
            {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.total-order').addClass('show');
                
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/all/counts",
                    data: {
                        'datepicker': selected_date, 
                        'hub_id': hubId
                    },
                    success: function (data) {
                        $('#total_orders').text(data['total']);
                        $('#return_orders').text(data['return_orders']);
                        $('#hub_return_scan').text(data['hub_return_scan']);
                        $('#hub_not_return_scan').text(data['hub_not_return_scan']);
                        $('#sorted_orders').text(data['sorted']);
                        $('#picked_orders').text(data['pickup']);
                        $('#delivered_orders').text(data['delivered_order']);
                        $('#notscan_orders').text(data['notscan']);
                        $('#reattempted_orders').text(data['reattempted']);
                        $('#completion_order').text(data['completion_ratio']);
                        // hide loader
                        $('.total-order').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.total-order').removeClass('show');
                    }
                });
            }

            function getInprogressOrderDataCount() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.total-summary').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/inprogress",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function (data) {
                        $('#sorted_remain').text(data['remaining_sorted']);
                        $('#picked_remain').text(data['remaining_pickup']);
                        $('#route_picked_remain').text(data['remaining_route']);
                        // hide loader
                        $('.total-summary').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.total-summary').removeClass('show');
                    }
                });
            }

            function getFailedOrderDataCount() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.failed-order').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/failed/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function (data) {
                        $('#failed_orders').text(data['failed']);
                        $('#system_failed_orders').text(data['system_failed_order']);
                        $('#not_system_failed_orders').text(data['not_in_system_failed_order']);
                        // hide loader
                        $('.failed-order').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.failed-order').removeClass('show');
                    }
                });
            }

            function getCustomOrderDataCount() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.custom-order').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/custom/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function (data) {
                        $('#custom_orders').text(data['custom_order']);
                        $('.custom-order').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.custom-order').removeClass('show');
                    }
                });
            }
            function getManualOrderDataCount() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.manual-order').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/manual/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function (data) {
                        $('#manual_orders').text(data['manual']);
                        $('.manual-order').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.manual-order').removeClass('show');
                    }
                });
            }

            function getRouteDataCounts() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.route-count').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/route/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function (data) {
                        $('#total_route').text(data['total_route']);
                        $('#normal_route').text(data['normal_route']);
                        $('#custom_route').text(data['custom_route']);
                        $('#big_box_route').text(data['big_box_route']);
                        $('.route-count').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.route-count').removeClass('show');
                    }
                });
            }

            function getOnTimeCounts() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                // show loader
                $('.time-order').addClass('show');
                
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/on-time/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function (data) {
                        $('#sorting_time').text(data['sorting']+' Hours');
                        $('#picked_time').text(data['pickup']+' Hours');
                        $('.time-order').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.time-order').removeClass('show');
                    }
                });
            }

            var ctx = document.getElementById('myChart').getContext('2d');
            const data = {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [
                    {
                        label: ['On Time Delivery'],
                        data: [90,80,85,95,80,85,90,95],
                        borderColor: '#0fda8b',
                        fill: false,
                        lineTension: 0,
                        
                    },
                    {
                        label: ['Off Time Delivery'],
                        data: [10,20,15,5,20,15,10, 5],
                        borderColor: '#ff6384',
                        fill: false,
                        lineTension: 0,
                    }
                ]
            };
            var myChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    bezierCurve: false,
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart'
                    }
                    },
                    hover: {
                    mode: 'index',
                    intersec: false
                    },
                    scales: {
                    x: {
                        title: {
                        display: true,
                        text: 'Month'
                        }
                    },
                        yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                steps: 11,
                                stepValue: 5,
                                max: 100
                            }
                        }]
                    }
                },
            });

            function getgraph(type) {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();

                $('.graph-loader').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/graph",
                    data: {'datepicker': selected_date, 'hub_id': hubId,'type':type},

                    success: function (data) {
                        var i = 0;
                        jQuery.each(data, function(index, record) {
                            myChart.data.datasets[0].data[i] = record['y2'];
                            myChart.data.datasets[1].data[i] = record['y1'];
                            myChart.data.labels[i] = index;
                            i++
                        })
                        myChart.update();
                        $('.graph-loader').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.graph-loader').removeClass('show');
                    }
                })
            }

            var sliderConfig = {
                loop:false,
                margin:10,
                nav:true,
                dots: false,
                responsive:{
                    0:{
                        items:2
                    },
                    600:{
                        items:4
                    },
                    1000:{
                        items:6
                    }
                }
            };

            function topbrookers(){
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.broker-joey').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                    success: function(data) {


                        $('#order_row_list').html('<div id="order_row_list_brooker"></div>');
                        jQuery.each(data, function(index, record) {
                            $('#order_row_list_brooker').append(`
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">${record.name}<span class="broker_count color-green">${record.count} orders</span></h4>
                                    </div>
                                    <div class="actions">
                                        <a href="#" target="_blank" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                            `)
                        })




                     /*   $('#order_row_list_brooker').owlCarousel(
                            sliderConfig
                        );*/
                        // $('#topPerformers').trigger('resize.owl.carousell');
                        $('.broker-joey').removeClass('show');
                    },
                    error: function (error) {
                        console.log(error);
                        // hide loader
                        $('.broker-joey').removeClass('show');
                    }
                })
            };

            function topPerformers(){
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.top-ten').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/top-ten/joeys",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                        success: function(data) {
                            $('#topPerformers').html('<div id="topPerformersSlider" class="owl-carousel"></div>');
                            /*jQuery.each(data, function(index, record) {

                                $('#topPerformersSlider').append(`
                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="${record.image}" alt="">
                                        <h5 class="title">${record.name} (${record.joey_id})</h5>
                                        <p class="count color-green">${record.count} orders</p>
                                    </div>
                                    </a>
                                `)
                            })*/

                            $('#topPerformersSlider').append(`
                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Pankaj Thakur (43819)</h5>
                                        <p class="count color-green">6541 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Hyun Mi Kim (43817)</h5>
                                        <p class="count color-green">5424 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Anmol Deol (43815)</h5>
                                        <p class="count color-green">5241 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Erina Mesfin (43813)</h5>
                                        <p class="count color-green">4512 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Savita Bery (43811)</h5>
                                        <p class="count color-green">4212 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Stalin Amirthanathar (43809)</h5>
                                        <p class="count color-green">3541 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Hamid Brahim (43821)</h5>
                                        <p class="count color-green">2452 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Chinni Subbarao chigurupati (43823)</h5>
                                        <p class="count color-green">2211 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Sear Noor (43825)</h5>
                                        <p class="count color-green">1520 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Sawan Patel (43827)</h5>
                                        <p class="count color-green">1300 orders</p>
                                    </div>
                                    </a>
                                `),

                            console.log(data);

                            $('#topPerformersSlider').owlCarousel(
                                sliderConfig
                            );
                            // $('#topPerformers').trigger('resize.owl.carousell');
                            $('.top-ten').removeClass('show');
                        },
                        error: function (error) {
                            console.log(error);
                            // hide loader
                            $('.top-ten').removeClass('show');
                    }
                    })
            };// topPerformers();


            function leastPerformers(){
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                $('.least-ten').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/least-ten/joeys",
                    data: {'datepicker': selected_date, 'hub_id': hubId},
                        success: function(data){                            
                            $('#leastPerformers').html('<div id="leastPerformersSlider" class="owl-carousel"></div>');
                            $('#leastPerformersSlider').append(`
                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Pankaj Thakur (43819)</h5>
                                        <p class="count color-green">2340 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Hyun Mi Kim (43817)</h5>
                                        <p class="count color-green">1754 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Anmol Deol (43815)</h5>
                                        <p class="count color-green">4512 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Erina Mesfin (43813)</h5>
                                        <p class="count color-green">1334 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Savita Bery (43811)</h5>
                                        <p class="count color-green">1844 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Stalin Amirthanathar (43809)</h5>
                                        <p class="count color-green">6451 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Hamid Brahim (43821)</h5>
                                        <p class="count color-green">3421 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Chinni Subbarao chigurupati (43823)</h5>
                                        <p class="count color-green">2241 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Sear Noor (43825)</h5>
                                        <p class="count color-green">3251 orders</p>
                                    </div>
                                    </a>

                                    <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="http://localhost/joeyco-dashboards-5-2-live/public/images/profile_images/default.png" alt="">
                                        <h5 class="title">Sawan Patel (43827)</h5>
                                        <p class="count color-green">4541 orders</p>
                                    </div>
                                    </a>
                                `),
                            /*jQuery.each(data, function(index, record) {

                                $('#leastPerformersSlider').append(`
                                   <a href="#" target="_blank">
                                    <div class="item">
                                        <img src="${record.image}" alt="">
                                        <h5 class="title">${record.name} (${record.joey_id})</h5>
                                        <p class="count color-green">${record.count} orders</p>
                                    </div>
                                    </a>
                                `)
                            })*/
                        
                            $('#leastPerformersSlider').owlCarousel(
                                sliderConfig
                            );
                            $('.least-ten').removeClass('show');
                        },
                        error: function (error) {
                            console.log(error);
                            // hide loader
                            $('.least-ten').removeClass('show');
                        }
                    })
            };// leastPerformers();


            $('#select_graph').on('change', function() {
                var type  = this.value;
                getgraph(type);
            });
            setTimeout(function () {
                getOTDDay();
                getOTDWeek();
                getOTDMonth();
                getOTDYear();
                getTotalOrderDataCount();
                getInprogressOrderDataCount();
                getFailedOrderDataCount();
                getCustomOrderDataCount();
                getManualOrderDataCount();
                getOnTimeCounts();
                topbrookers();
                topPerformers();
                leastPerformers();
                getgraph('week');
                getRouteDataCounts();
            }, 1000);
        })


    </script>
@endsection
