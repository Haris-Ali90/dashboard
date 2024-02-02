{{--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    //3D Pie Chart
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Sorted Orders',     {{ isset($montreal_count)?$montreal_count->sorter_order_count:0 }}],
            ['Pickup From Hub',      {{ isset($montreal_count)?$montreal_count->hub_deliver_order_count:0 }}],
            ['Not Scan',     {{ isset($montreal_count)?$montreal_count->failedOrders:0 }}],
            ['Delivered Orders',     {{ isset($montreal_count)?$montreal_count->deliver_order_count:0 }}],


        ]);

        var options = {
            title: 'Montreal Orders',

        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_monteral'));
        chart.draw(data, options);
    }

</script>
<div class="col-sm-6 dashboard-statistics-box" id="piechart_monteral"></div>--}}
<!--End Of Chart-->
@section('montreal-script')
    <script src="{{ backend_asset('js/echarts.min.js') }}"></script>
    <script type="text/javascript">
        if ($('#montreal_pie').length) {

            var echartPie_montreal = echarts.init(document.getElementById('montreal_pie'));

            echartPie_montreal.setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x: 'center',
                    y: 'bottom',
                    data: ['Sorted Orders', 'Picked Up From Hub', 'Not Scan Orders', 'Delivered Orders', 'Failed Orders', 'Return Orders', 'Mainfest Orders']
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
                    name: 'Montreal Dashboard',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '48%'],
                    data: [{
                        value: $('#montreal_sorted_orders_pie').val(),
                        name: 'Sorted Orders'
                    }, {
                        value: $('#montreal_picked_orders_pie').val(),
                        name: 'Picked Up From Hub'
                    }, {
                        value: $('#montreal_notscan_orders_pie').val(),
                        name: 'Not Scan Orders'
                    }, {
                        value: $('#montreal_delivered_orders_pie').val(),
                        name: 'Delivered Orders'
                    }, {
                        value: $('#montreal_failed_orders_pie').val(),
                        name: 'Failed Orders'
                    }, {
                        value: $('#montreal_return_orders_pie').val(),
                        name: 'Return Orders'
                    }, {
                        value: $('#montreal_yesterday_orders_pie').val(),
                        name: 'Yesterday Return Orders'
                    }, {
                        value: $('#montreal_custom_orders_pie').val(),
                        name: 'Custom Orders'
                    }, {
                        value: $('#montreal_mainfest_orders_pie').val(),
                        name: 'Mainfest Orders'
                    }]
                }]
            });


        }

        function updatePieChartValuesMontreal() {
            console.log('yes');
            echartPie_montreal.setOption({
                series: [{
                    data: [{
                        value: $('#montreal_sorted_orders_pie').val(),
                        name: 'Sorted Orders'
                    }, {
                        value: $('#montreal_picked_orders_pie').val(),
                        name: 'Picked Up From Hub'
                    }, {
                        value: $('#montreal_notscan_orders_pie').val(),
                        name: 'Not Scan Orders'
                    }, {
                        value: $('#montreal_delivered_orders_pie').val(),
                        name: 'Delivered Orders'
                    }, {
                        value: $('#montreal_failed_orders_pie').val(),
                        name: 'Failed Orders'
                    }, {
                        value: $('#montreal_return_orders_pie').val(),
                        name: 'Return Orders'
                    }, {
                        value: $('#montreal_yesterday_orders_pie').val(),
                        name: 'Yesterday Return Orders'
                    }, {
                        value: $('#montreal_custom_orders_pie').val(),
                        name: 'Custom Orders'
                    }, {
                        value: $('#montreal_mainfest_orders_pie').val(),
                        name: 'Mainfest Orders'
                    }]
                }]
            });

        }

        /*
            $(document).ready(function () {
                setInterval(function () {
                    $("#montrealCards").load(window.location.href + " #montreal-dashbord-tiles-id");
                    var ref = $('#yajra-reload').DataTable();
                    ref.ajax.reload();
                }, 30000);
            });
        */

    </script>

    <script>

        function getTotalOrderDataMontreal() {
            let selected_date = $('.data-selector').val();
            let type = 'all';
            // show loader
            $('.total-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newmontreal/totalcards/" + selected_date + "/" + type,
                data: {},
                success: function (data) {
                    $('#total_orders').text(data['amazon_count']['total']);
                    $('#return_orders').text(data['amazon_count']['return_orders']);
                    $('#sorted_orders').text(data['amazon_count']['sorted']);
                    $('#picked_orders').text(data['amazon_count']['pickup']);
                    $('#delivered_orders').text(data['amazon_count']['delivered_order']);
                    $('#notscan_orders').text(data['amazon_count']['notscan']);

                    $('#montreal_return_orders_pie').val(data['amazon_count']['return_orders']);
                    $('#montreal_sorted_orders_pie').val(data['amazon_count']['sorted']);
                    $('#montreal_picked_orders_pie').val(data['amazon_count']['pickup']);
                    $('#montreal_delivered_orders_pie').val(data['amazon_count']['delivered_order']);
                    $('#montreal_notscan_orders_pie').val(data['amazon_count']['notscan']);
                    // hide loader
                    $('.total-order').removeClass('show');
                    updatePieChartValuesMontreal();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.total-order').removeClass('show');
                }
            });
        }

        function getMainfestOrderDataMontreal() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.mainfest-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newmontreal/mainfestcards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#mainfest_orders').text(data['mainfest_orders']);
                    $('#montreal_mainfest_orders_pie').val(data['mainfest_orders']);
                    // hide loader
                    $('.mainfest-order').removeClass('show');
                    updatePieChartValuesMontreal();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.mainfest-order').removeClass('show');
                }
            });
        }

        function getFailedOrderDataMontreal() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.failed-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newmontreal/failedcards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#failed_orders').text(data['failed_orders']);
                    $('#montreal_failed_orders_pie').val(data['failed_orders']);
                    // hide loader
                    $('.failed-order').removeClass('show');
                    updatePieChartValuesMontreal();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.failed-order').removeClass('show');
                }
            });
        }

        function getCustomRouteDataMontreal() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.custom-route').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newmontreal/customroutecards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#custom_orders').text(data['custom_route']);
                    $('#montreal_custom_orders_pie').val(data['custom_route']);
                    // hide loader
                    $('.custom-route').removeClass('show');
                    updatePieChartValuesMontreal();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.custom-route').removeClass('show');
                }
            });
        }

        function getYesterdayOrderDataMontreal() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.yesterday-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newmontreal/yesterdaycards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#yesterday_orders').text(data['yesterday_return_orders']);
                    $('#montreal_yesterday_orders_pie').val(data['yesterday_return_orders']);
                    // hide loader
                    $('.yesterday-order').removeClass('show');
                    updatePieChartValuesMontreal();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.yesterday-order').removeClass('show');
                }
            });
        }


        setTimeout(function () {
            getTotalOrderDataMontreal();
            getMainfestOrderDataMontreal();
            getFailedOrderDataMontreal();
            getCustomRouteDataMontreal();
            getYesterdayOrderDataMontreal();
        }, 1000);

    </script>


@stop
<!--E-chart Pie-->

<!-- Montreal Graph -->

@if($graph == 'montreal')
    @if (count($data)== 0)
        <div class="x_panel">
            <div class="x_title">
                <form method="get" action="montreal">
                    <label>Search By Date</label>
                    <input type="date" name="datepicker" class="data-selector" required=""
                           value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                           placeholder="Search">
                    <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                        Go
                    </button>
                </form>

                <div class="clearfix"></div>
            </div>
        </div>
        @include('backend.newmontrealdashboard.montreal_cards')


    @elseif (count($data)>0 &&in_array("statistics", $data))

        @if(in_array("montreal_dashboard", $data))
            <div class="x_panel">
                <div class="x_title">
                    <form method="get" action="montreal">
                        <label>Search By Date</label>
                        <input type="date" name="datepicker" class="data-selector" required=""
                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                               placeholder="Search">
                        <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                            Go
                        </button>
                    </form>

                    <div class="clearfix"></div>
                </div>
            </div>
            @include('backend.newmontrealdashboard.montreal_cards')
        @endif

    @else
        <input type="hidden" class="data-selector" name="datepicker"
               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
    @endif
@else
    <input type="hidden" class="data-selector" name="datepicker"
           value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
@endif

<!-- End Montreal Graph -->
<input type="hidden" id="montreal_mainfest_orders_pie" value="0">
<input type="hidden" id="montreal_return_orders_pie" value="0">
<input type="hidden" id="montreal_yesterday_orders_pie" value="0">
<input type="hidden" id="montreal_sorted_orders_pie" value="0">
<input type="hidden" id="montreal_picked_orders_pie" value="0">
<input type="hidden" id="montreal_delivered_orders_pie" value="0">
<input type="hidden" id="montreal_notscan_orders_pie" value="0">
<input type="hidden" id="montreal_failed_orders_pie" value="0">
<input type="hidden" id="montreal_custom_orders_pie" value="0">

<div class="col-sm-6 dashboard-statistics-box">
    <div class="x_panel">
        <div class="x_title">
            <h2>Amazon Montreal</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="dashboard-statistics-box" id="montreal_pie"></div>

        </div>
    </div>
</div>
<!--E-chart Pie-->

