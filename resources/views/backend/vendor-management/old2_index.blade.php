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
            <div class="dash_header_wrap">
                <div class="row">
                    <div class="col-md-6">
                        <div class="dash_heading">
                            <h1>{{$hub_name && $hub_name->city_name ? $hub_name->city_name : 'Amazon Montreal'}}</h1>

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
                        <form method="get" action="">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="date" name="datepicker" class="data-selector form-control" value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select name="hub_id"  class="form-control">
                                            @foreach($hubs as $hub)
                                                <option value="{{$hub->id}}"{{ ($hub_id == $hub->id)?'selected': '' }} >{{$hub->city_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-lg">Search</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Header - [/end] -->

            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats_box">
                            <h4>Active Shipments</h4>
                            <canvas id="doughnutChart1" width="102px"></canvas>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats_box">
                            <h4>Active Shipments</h4>
                            <canvas id="doughnutChart2" width="102px"></canvas>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats_box">
                            <h4>Active Shipments</h4>
                            <canvas id="doughnutChart3" width="102px"></canvas>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats_box ">
                            <h4>Active Shipments</h4>
                            <canvas id="doughnutChart4" width="102px"></canvas>
                            <div class="row">
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #0fda8b;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                                <div class="attr col-md-6">
                                    <div class="swatch" style="background: #ff6384;"></div>
                                    <div class="lbl">Label 1</div>
                                    <div class="value">2450</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- stats section 1 - [/end] -->

            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <!-- Featured numbers - [start] -->
                <div class="featured_numbers">
                    <div class="row">
                        <div class="number_box col-md-2">
                            <div class="inner" >
                                <h3 class="basecolor1">Total Orders</h3>
                                <p class="numbers">{{$counts['total']}}</p>
                                {{-- <p class="perc">38%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Sorted Orders</h3>
                                <p class="numbers">{{$counts['sorted']}}</p>
                                {{--<p class="perc">{{round(($counts['total'] != 0) ? ($counts['sorted']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">PickedUp from Hub </h3>
                                <p class="numbers">{{$counts['pickup']}}</p>
                                {{-- <p class="perc">{{round(($counts['total'] != 0) ? ($counts['pickup']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Delivered Orders</h3>
                                <p class="numbers">{{$counts['delivered_order']}}</p>
                                {{--  <p class="perc ">{{round(($counts['total'] != 0) ? ($counts['delivered_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Return Orders</h3>
                                <p class="numbers">{{$counts['return_orders']}}</p>
                                {{--          <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['return_orders']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>

                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Not Scanned </h3>
                                <p class="numbers">{{$counts['notscan'] }}</p>
                                {{--            <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['notscan']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Failed Orders</h3>
                                <p class="numbers">{{$counts['failed']}}</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['failed']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Custom Orders</h3>
                                <p class="numbers">{{$counts['custom_order']}}</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Custom Orders</h3>
                                <p class="numbers">{{$counts['custom_order']}}</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Custom Orders</h3>
                                <p class="numbers">{{$counts['custom_order']}}</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Custom Orders</h3>
                                <p class="numbers">{{$counts['custom_order']}}</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                        <div class="number_box col-md-2">
                            <div class="inner">
                                <h3 class="basecolor1">Custom Orders</h3>
                                <p class="numbers">{{$counts['custom_order']}}</p>
                                {{--       <p class="perc color-red">{{round(($counts['total'] != 0) ? ($counts['custom_order']/$counts['total'])*100  : 0, 2)}}%</p>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Featured numbers - [/end] -->
            </div>
            <!-- stats section 1 - [/end] -->


            <div class="chart_stats section">
                <div class="row">
                    <div class="col-md-7">
                        <h4>On-time delivery and off-time delivery</h4>
                        <div class="stats_box">
                            <canvas id="myChart" width="760" height="380"></canvas>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="stats_box mb-20 center">
                            <div class="numbers">685</div>
                            <div class="title">Pending </div>
                            <div class="title2">Out of 1,568 orders</div>
                        </div>
                        <div class="stats_box center">
                            <div class="numbers">368</div>
                            <div class="title">Order</div>
                            <div class="title2">Out of 1,568 orders</div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- stats section 1 - [start] -->
            <div class="stats section">
                <div class="row">

                    <div class="col-md-4">
                        <div class="data_box pb-0">
                            <h4>Latest 6 Failed Orders</h4>
                            <div class="order_list">
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">CR-455595</h4>
                                        <p class="desc">Due to wrong address</p>
                                    </div>
                                    <div class="actions">
                                        <a href="#" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">CR-455595</h4>
                                        <p class="desc">Due to wrong address</p>
                                    </div>
                                    <div class="actions">
                                        <a href="#" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">CR-455595</h4>
                                        <p class="desc">Due to wrong address</p>
                                    </div>
                                    <div class="actions">
                                        <a href="#" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">CR-455595</h4>
                                        <p class="desc">Due to wrong address</p>
                                    </div>
                                    <div class="actions">
                                        <a href="#" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">CR-455595</h4>
                                        <p class="desc">Due to wrong address</p>
                                    </div>
                                    <div class="actions">
                                        <a href="#" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                                <div class="order_row">
                                    <div class="info">
                                        <h4 class="title">CR-455595</h4>
                                        <p class="desc">Due to wrong address</p>
                                    </div>
                                    <div class="actions">
                                        <a href="#" class="ico_btn"><i class="icofont-eye-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="data_detail_btn">View all</a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="stats_box mb-20">
                            <h4 class="color-green">Top 10 Performers</h4>
                            <div class="joeys_list">
                                <div id="topPerformers"> </div>
                            </div>
                        </div>

                        <div class="stats_box">
                            <h4 class="color-red">Least 10 Performers</h4>
                            <div class="joeys_list">
                                <div id="leastPerformers"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- stats section 1 - [/end] -->
        </div>
    </div>
    <!-- /#page-wrapper -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(function(){

            // Chart 1
            var onTime = '<?php echo $odt_data_1['y2']?>';
            var offTime = '<?php echo $odt_data_1['y1']?>';
            console.log(onTime)
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
                    plugins: {
                        title: {
                            display: false,
                            text: 'Chart.js Doughnut Chart'
                        }
                    }
                },
            })
            $.ajax({
                url: "https://jsonplaceholder.typicode.com/todos/1",
                beforeSend: function( xhr ) {
                    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                },
                success: function(data){
                    console.log('success', data);
                    console.log('dataset: ', doughnutChart1Init.data.datasets[0])
                    doughnutChart1Init.data.datasets[0].data[0] = 100;
                    doughnutChart1Init.data.datasets[0].data[1] = 50;
                    console.log('dataset: ', doughnutChart1Init.data.datasets[0])

                    doughnutChart1Init.update();
                }
            })

            // Chart 2
            var onTime = '<?php echo $odt_data_1['y2']?>';
            var offTime = '<?php echo $odt_data_1['y1']?>';
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

            // Chart 3
            var onTime = '<?php echo $odt_data_1['y2']?>';
            var offTime = '<?php echo $odt_data_1['y1']?>';
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

            // Chart 1
            var onTime = '<?php echo $odt_data_1['y2']?>';
            var offTime = '<?php echo $odt_data_1['y1']?>';
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


            var ctx = document.getElementById('myChart').getContext('2d');
            const data = {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [
                    {
                        label: 'On time',
                        data: [0,60,65,85,68,90,50,80],
                        borderColor: '#ff6384',
                        fill: false,

                    },
                    {
                        label: 'Off time',
                        data: [40,60,85,10,68,90,75, 80],
                        borderColor: '#0fda8b',
                        fill: false,

                    }
                ]
            };
            var myChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
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
                        y: {
                            title: {
                                display: true,
                                text: 'Value'
                            },
                            min: 0,
                            max: 100,
                            ticks: {
                                // forces step size to be 50 units
                                stepSize: 50
                            }
                        }
                    }
                },
            });



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
                $.ajax({
                    url: "https://jsonplaceholder.typicode.com/todos/1",
                    beforeSend: function( xhr ) {
                        xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                    },
                    success: function(data){
                        console.log('slider: ', data)

                        $('#topPerformers').html('<div id="topPerformersSlider" class="owl-carousel"></div>');
                        var topPerformersArr = [
                            {profile: 'images/profile_1.jpg', name: 'Morgan', orders: 304},
                            {profile: 'images/profile_2.jpg', name: 'Morgan', orders: 250},
                            {profile: 'images/profile_3.jpg', name: 'Morgan', orders: 120},
                            {profile: 'images/profile_4.jpg', name: 'Morgan', orders: 60},
                            {profile: 'images/profile_1.jpg', name: 'Morgan', orders: 304},
                            {profile: 'images/profile_2.jpg', name: 'Morgan', orders: 250},
                            {profile: 'images/profile_3.jpg', name: 'Morgan', orders: 120},
                            {profile: 'images/profile_4.jpg', name: 'Morgan', orders: 60},
                            {profile: 'images/profile_1.jpg', name: 'Morgan', orders: 304},
                            {profile: 'images/profile_2.jpg', name: 'Morgan', orders: 250},
                        ];
                        for (let item = 0; item < topPerformersArr.length; item++) {
                            $('#topPerformersSlider').append(`
                                    <div class="item">
                                        <img src="{{ asset('${item.profile}') }}" alt="">
                                        <h5 class="title">Morgan</h5>
                                        <p class="count color-green">304 orders</p>
                                    </div>
                                `)
                        }

                        $('#topPerformersSlider').owlCarousel(
                            sliderConfig
                        );
                        // $('#topPerformers').trigger('resize.owl.carousell');
                    }
                })
            }; topPerformers();


            function leastPerformers(){
                $.ajax({
                    url: "https://jsonplaceholder.typicode.com/todos/1",
                    beforeSend: function( xhr ) {
                        xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                    },
                    success: function(data){
                        $('#leastPerformers').html('<div id="leastPerformersSlider" class="owl-carousel"></div>');
                        var leastPerformersArr = [
                            {profile: 'images/profile_1.jpg', name: 'Morgan', orders: 304},
                            {profile: 'images/profile_2.jpg', name: 'Morgan', orders: 250},
                            {profile: 'images/profile_3.jpg', name: 'Morgan', orders: 120},
                            {profile: 'images/profile_4.jpg', name: 'Morgan', orders: 60},
                            {profile: 'images/profile_1.jpg', name: 'Morgan', orders: 304},
                            {profile: 'images/profile_2.jpg', name: 'Morgan', orders: 250},
                            {profile: 'images/profile_3.jpg', name: 'Morgan', orders: 120},
                            {profile: 'images/profile_4.jpg', name: 'Morgan', orders: 60},
                            {profile: 'images/profile_1.jpg', name: 'Morgan', orders: 304},
                            {profile: 'images/profile_2.jpg', name: 'Morgan', orders: 250},
                        ];
                        for (let item = 0; item < leastPerformersArr.length; item++) {
                            $('#leastPerformersSlider').append(`
                                    <div class="item">
                                        <img src="{{ asset('${item.profile}') }}" alt="">
                                        <h5 class="title">Morgan</h5>
                                        <p class="count color-green">304 orders</p>
                                    </div>
                                `)
                        }

                        $('#leastPerformersSlider').owlCarousel(
                            sliderConfig
                        );
                    }
                })
            }; leastPerformers();



        })
    </script>
@endsection
