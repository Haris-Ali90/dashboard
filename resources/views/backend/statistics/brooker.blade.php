@extends( 'backend.layouts.app' )

@section('title', 'Broker Statistics')

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
    <script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
{{--    <script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>--}}
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
            <div class="dash_header_wrap">
                <div class="row">
                    <div class="col-md-6">
                        <div class="dash_heading with_profileimg">
                            <div class="thumb">
                                <img src="{{ url('/').'/images/profile_images/default.png'}}" alt="">
                            </div>

                            <div class="info">
                                <h1>{{$brooker ? $brooker->name : ''}}</h1>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="hub_id" id="hub-id" value="<?php echo $hub_id?>">
                    <input type="hidden" name="datepicker" class="data-selector form-control" value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
                </div>
            </div>
            <!-- Header - [/end] -->


            <input type="hidden" id="brooker_id" name="brooker_id" value="{{$brooker_id}}">
            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <div class="row">
                    <div class="col-md-3" style="margin-left: 130px;">
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
                           {{-- <div class="circle_chart"><canvas id="doughnutChart1" height="180"></canvas></div>--}}
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
                    <div class="col-md-3" style="margin-left: 50px;">
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
                            <h4>OTD By week</h4>
                            <div class="circle_chart">
                                <div class="doughnut_percentage" id="doughnutChart2_percentage">00.00%</div>
                                <canvas id="doughnutChart2" height="180"></canvas>
                            </div>
                          {{--  <div class="circle_chart"><canvas id="doughnutChart2" height="180"></canvas></div>--}}
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
                                <p>OTD For Previous Week.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-left: 50px;">
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
                          {{--  <div class="circle_chart"><canvas id="doughnutChart3" height="180"></canvas></div>--}}
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
                                <p>OTD For Previous Month.</p>
                                <p>1-On Time Delivery = Orders delivered/returned before 9 PM.</p>
                                <p>2-Off Time Delivery = Orders delivered/returned After 9 PM.</p>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col-md-3">
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
                        <div class="stats_box ">
                            <h4>OTD By 6 Month</h4>
                            <div class="circle_chart"><canvas id="doughnutChart4" height="180"></canvas></div>
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
                        </div>
                    </div>--}}
                </div>
            </div>
            
            <!-- stats section 1 - [/end] -->

            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <!-- Featured numbers - [start] -->
                <div class="featured_numbers">
                    <div class="row">

                        <div class="number_box col-md-2">
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
                        <div class="number_box col-md-2">
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
                        <div class="number_box col-md-2">
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
                                <h3 class="basecolor1">Out For Delivery Orders </h3>
                                <p class="numbers" id="picked_orders">0</p>
                                {{-- <p class="perc">{{round(($counts['total'] != 0) ? ($counts['pickup']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of picked up from hub orders.</p>
                                </div>
                            </div>
                        </div>
                        <div class="number_box col-md-2">
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
                            <a href="<?php echo URL::to('/'); ?>/statistics/brooker-detail/orders?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&hub=<?php echo base64_encode($hub_id);?>&type=<?php echo base64_encode('return');?>&brooker=<?php echo $brooker_id;?>"
                               target="_blank">
                                <div class="inner pop_desc">
                                <h3 class="basecolor1">Return Orders</h3>
                                <p class="numbers" id="return_orders">0</p>
                                {{--          <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['return_orders']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of return orders.</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="number_box col-md-2">
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
                            <a href="<?php echo URL::to('/'); ?>/statistics/brooker-detail/orders?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&hub=<?php echo base64_encode($hub_id);?>&type=<?php echo base64_encode('hub_return');?>&brooker=<?php echo $brooker_id;?>">
                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Hub Return Scan</h3>
                                <p class="numbers" id="hub_return_scan">0</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['failed']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of return orders scanned at hub.</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="number_box col-md-2">
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
                            <a href="<?php echo URL::to('/'); ?>/statistics/brooker-detail/orders?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&hub=<?php echo base64_encode($hub_id);?>&type=<?php echo base64_encode('not_hub_return');?>&brooker=<?php echo $brooker_id;?>">
                            <div class="inner pop_desc">
                                <h3 class="basecolor1">Hub Not Return Scan</h3>
                                <p class="numbers" id="hub_not_return_scan">0</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of return orders not scanned at hub.</p>
                                </div>
                            </div>
                            </a>
                        </div><div class="number_box col-md-2">
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
                            <a href="<?php echo URL::to('/'); ?>/statistics/brooker-detail/orders?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&hub=<?php echo base64_encode($hub_id);?>&type=<?php echo base64_encode('not_scan');?>&brooker=<?php echo $brooker_id;?>">
                                <div class="inner pop_desc">
                                    <h3 class="basecolor1">Not Scanned </h3>
                                    <p class="numbers" id="notscan_orders">0</p>
                                    {{--            <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['notscan']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                    <div class="desc">
                                        <p>Total number of delivered orders.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="number_box col-md-2">
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
                                    <p>Total number of order which are not scan at hub.</p>
                                </div>
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="dashbords-conts-tiles-loader-main-wrap  manual-order show">
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
                                <h3 class="basecolor1">Manual Status Mark</h3>
                                <p class="numbers" id="manual_orders">0</p>
                                {{--            <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['notscan']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                                <div class="desc">
                                    <p>Total number of orders which status has marked manually.</p>
                                </div>
                            </div>

                        </div>

                        <div class="number_box col-md-2">
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
                        <div class="number_box col-md-2">
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
                        <div class="number_box col-md-2">
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
                        <div class="number_box col-md-2">
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
                </div>
                <!-- Featured numbers - [/end] -->
            </div>
            <!-- stats section 1 - [/end] -->


            <div class="chart_stats section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>On Time Delivery And Off Time Delivery</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group chart_filter_control">
                                    <select name="select_graph" id="select_graph" class="form-control form-control-xs">
                                        <option value="week" selected >By Week</option>
                                        <option value="month">By Month</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="position: relative;">
                        <div class="dashbords-conts-tiles-loader-main-wrap  graph-loader show " style="padding: 0px 0px 0px 0px; !important;">
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
               
                </div>
            </div>

            
            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <div class="row">
                   
                
                    <div class="col-md-12 ">
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
                            <h4 class="color-green">Top 10 Joeys Performers</h4>
                            <div class="joeys_list">
                                <div id="topPerformers"> </div>
                            </div>
                            <div class="desc">
                                <p>The name of joeys they have highest number of on time deliveries.</p>
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
                            <h4 class="color-red">Least 10 Joeys Performers</h4>
                            <div class="joeys_list">
                            <div id="leastPerformers"> </div>
                            </div>
                            <div class="desc">
                                <p>The name of joeys they have lowest number of off time deliveries.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- stats section 1 - [/end] -->

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
                    {{--    <th style="width: 10%">Image</th>--}}
                        <th style="width: 20%">Name</th>
                        <th style="width: 20%">Email</th>
                        <th style="width: 20%">Phone</th>
                      {{--  <th style="width: 20%">Address</th>--}}
                        <th style="width: 10%">OTD</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>


        $(function(){

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
            function getOTDDay() {

                console.log(onTime);
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                let brooker_id = $('#brooker_id').val();
                $('.otd-day').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/day/otd",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id },
                   /* beforeSend: function (xhr) {
                        xhr.overrideMimeType("text/plain; charset=x-user-defined");
                    },*/
                    success: function (data) {
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
                let brooker_id = $('#brooker_id').val();
                $('.otd-week').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/week/otd",
                    data: {'datepicker': selected_date, 'hub_id': hubId , 'brooker_id':brooker_id },
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
                let brooker_id = $('#brooker_id').val();
                $('.otd-month').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/month/otd",
                    data: {'datepicker': selected_date, 'hub_id': hubId , 'brooker_id':brooker_id },
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


            function getTotalOrderDataCount() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();  
                let brooker_id = $('#brooker_id').val();
                // show loader
                $('.total-order').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/all/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id },
                    success: function (data) {
                        $('#total_orders').text(data['total']);
                        $('#return_orders').text(data['return_orders']);
                        $('#hub_return_scan').text(data['hub_return_scan']);
                        $('#hub_not_return_scan').text(data['hub_not_return_scan']);
                        $('#sorted_orders').text(data['sorted']);
                        $('#picked_orders').text(data['pickup']);
                        $('#delivered_orders').text(data['delivered_order']);
                        $('#notscan_orders').text(data['notscan']);
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

            function getManualOrderDataCount() {
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                let brooker_id = $('#brooker_id').val();
                // show loader
                $('.manual-order').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/manual/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id },
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
                let brooker_id = $('#brooker_id').val();
                // show loader
                $('.route-count').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/route/counts",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id },
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
                let brooker_id = $('#brooker_id').val();
                $('.graph-loader').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/graph",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id,'type':type},

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

            function topPerformers(){
                let selected_date = $('.data-selector').val();
                let hubId = $('#hub-id').val();
                  let brooker_id = $('#brooker_id').val();
                $('.top-ten').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/top-ten/joeys",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id},
                        success: function(data) {
                            $('#topPerformers').html('<div id="topPerformersSlider" class="owl-carousel"></div>');
                            jQuery.each(data, function(index, record) {

                                $('#topPerformersSlider').append(`
                                    <a href="<?php echo URL::to('/'); ?>/statistics/joey-detail?datepicker=${selected_date}&hub=${hubId}&rec=${record.encode_joey_id}">
                                    <div class="item">
                                        <img src="${record.image}" alt="">
                                        <h5 class="title">${record.name} (${record.joey_id})</h5>
                                        <p class="count color-green">${record.count} orders</p>
                                    </div>
                                    </a>
                                `)
                            })

                            console.log(data);
//




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
                  let brooker_id = $('#brooker_id').val();
                $('.least-ten').addClass('show');
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('/'); ?>/statistics/brooker-detail/least-ten/joeys",
                    data: {'datepicker': selected_date, 'hub_id': hubId, 'brooker_id':brooker_id},
                        success: function(data){                            
                            $('#leastPerformers').html('<div id="leastPerformersSlider" class="owl-carousel"></div>');
                            jQuery.each(data, function(index, record) {

                                $('#leastPerformersSlider').append(`
                                    <a href="<?php echo URL::to('/'); ?>/statistics/joey-detail?datepicker=${selected_date}&hub=${hubId}&rec=${record.encode_joey_id}">
                                    <div class="item">
                                        <img src="${record.image}" alt="">
                                        <h5 class="title">${record.name} (${record.joey_id})</h5>
                                        <p class="count color-green">${record.count} orders</p>
                                    </div>
                                    </a>
                                `)
                            })
                        
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

            function getAllJoeyOTD(type) {
                let brooker_id = $('#brooker_id').val();
                let hubId = $('#hub_id').val();
                $('.all-joey').addClass('show');
                $('.joeysOTDTable').DataTable({
                    ajax: {
                        type: "GET",
                        url: "{{route('statistics-brooker-detail-all-joeys-otd.index')}}",
                        data: {'hub_id': hubId,'type': type ,'brooker_id':brooker_id},
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
                       // {data: 'image'},
                        {data: 'name'},
                        {data: 'email'},
                        {data: 'phone'},
                      //  {data: 'address'},
                        {data: 'count'},
                    ],
                    columnDefs:
                        [{
                            "targets": 1,
                            "data": 'logo',
                            "render": function (data, type, row, meta) {
                                return '<img src="' + data + '" alt="' + data + '"height="35" width="35"/>';
                            }
                        }],
                });
            }



            $('#select_table_graph').on('change', function() {
                var type  = this.value;
                getAllJoeyOTD(type);
            });
            setTimeout(function () {

                    getOTDDay();
                    getOTDWeek();
                    getOTDMonth();
                    getTotalOrderDataCount();
                    getManualOrderDataCount();
                    topPerformers();
                    leastPerformers();
                    getgraph('week');
                getRouteDataCounts();
                getAllJoeyOTD('week')

            }, 1000);
        })


    </script>
@endsection
