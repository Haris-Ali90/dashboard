{{--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    //3D Pie Chart
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],

            ['Sorted Orders',     {{ isset($ottawa_count)?$ottawa_count->sorter_order_count:0 }}],
            ['Pickup From Hub',      {{ isset($ottawa_count)?$ottawa_count->hub_deliver_order_count:0 }}],
            ['Not Scan',     {{ isset($ottawa_count)?$ottawa_count->failedOrders:0 }}],
            ['Delivered Orders',     {{ isset($ottawa_count)?$ottawa_count->deliver_order_count:0 }}],


        ]);

        var options = {
            title: 'Ottawa Orders',

        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_ottawa'));

        chart.draw(data, options);
    }

</script>
<div class="col-sm-6 dashboard-statistics-box" id="piechart_ottawa"></div>--}}


@section('ottawa-script')
    <script src="{{ backend_asset('js/echarts.min.js') }}"></script>
    <script type="text/javascript">
        if ($('#ottawa_pie').length) {

            var echartPie_ottawa = echarts.init(document.getElementById('ottawa_pie'));

            echartPie_ottawa.setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x: 'center',
                    y: 'bottom',
                    data: ['Sorted Orders', 'Picked Up From Hub', 'Not Scan Orders', 'Delivered Orders', 'Failed Orders', 'Return Orders', 'Yesterday Return Orders', 'Custom Orders', 'Mainfest Orders']
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
                    name: 'Ottawa Dashboard',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '48%'],
                    data: [{
                        value: $('#ottawa_sorted_orders_pie').val(),
                        name: 'Sorted Orders'
                    }, {
                        value: $('#ottawa_picked_orders_pie').val(),
                        name: 'Picked Up From Hub'
                    }, {
                        value: $('#ottawa_notscan_orders_pie').val(),
                        name: 'Not Scan Orders'
                    }, {
                        value: $('#ottawa_delivered_orders_pie').val(),
                        name: 'Delivered Orders'
                    }, {
                        value: $('#ottawa_failed_orders_pie').val(),
                        name: 'Failed Orders'
                    }, {
                        value: $('#ottawa_return_orders_pie').val(),
                        name: 'Return Orders'
                    }, {
                        value: $('#ottawa_yesterday_orders_pie').val(),
                        name: 'Yesterday Return Orders'
                    }, {
                        value: $('#ottawa_custom_orders_pie').val(),
                        name: 'Custom Orders'
                    }, {
                        value: $('#ottawa_mainfest_orders_pie').val(),
                        name: 'Mainfest Orders'
                    }]
                }]
            });

        }


        function updatePieChartValuesOttawa() {
            console.log('yes');
            echartPie_ottawa.setOption({
                series: [{
                    data: [{
                        value: $('#ottawa_sorted_orders_pie').val(),
                        name: 'Sorted Orders'
                    }, {
                        value: $('#ottawa_picked_orders_pie').val(),
                        name: 'Picked Up From Hub'
                    }, {
                        value: $('#ottawa_notscan_orders_pie').val(),
                        name: 'Not Scan Orders'
                    }, {
                        value: $('#ottawa_delivered_orders_pie').val(),
                        name: 'Delivered Orders'
                    }, {
                        value: $('#ottawa_failed_orders_pie').val(),
                        name: 'Failed Orders'
                    }, {
                        value: $('#ottawa_return_orders_pie').val(),
                        name: 'Return Orders'
                    }, {
                        value: $('#ottawa_yesterday_orders_pie').val(),
                        name: 'Yesterday Return Orders'
                    }, {
                        value: $('#ottawa_custom_orders_pie').val(),
                        name: 'Custom Orders'
                    }, {
                        value: $('#ottawa_mainfest_orders_pie').val(),
                        name: 'Mainfest Orders'
                    }]
                }]
            });

        }

        /* $(document).ready(function(){
             setInterval(function(){
                 $("#ottawaCards").load(window.location.href + " #ottawa-dashboard-tiles-id" );
                 var ref = $('#yajra-reload').DataTable();
                 ref.ajax.reload();
             }, 30000);
         });*/

    </script>

    <script>


        function getTotalOrderDataOttawa() {
            let selected_date = $('.data-selector').val();
            let type = 'all';
            // show loader
            $('.total-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newottawa/totalcards/" + selected_date + "/" + type,
                data: {},
                success: function (data) {
                    $('#total_orders').text(data['amazon_count']['total']);
                    $('#return_orders').text(data['amazon_count']['return_orders']);
                    $('#sorted_orders').text(data['amazon_count']['sorted']);
                    $('#picked_orders').text(data['amazon_count']['pickup']);
                    $('#delivered_orders').text(data['amazon_count']['delivered_order']);
                    $('#notscan_orders').text(data['amazon_count']['notscan']);

                    $('#ottawa_return_orders_pie').val(data['amazon_count']['return_orders']);
                    $('#ottawa_sorted_orders_pie').val(data['amazon_count']['sorted']);
                    $('#ottawa_picked_orders_pie').val(data['amazon_count']['pickup']);
                    $('#ottawa_delivered_orders_pie').val(data['amazon_count']['delivered_order']);
                    $('#ottawa_notscan_orders_pie').val(data['amazon_count']['notscan']);


                    // hide loader
                    $('.total-order').removeClass('show');
                    updatePieChartValuesOttawa();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.total-order').removeClass('show');
                }
            });
        }

        function getMainfestOrderDataOttawa() {
            let selected_date = $('.data-selector').val();

            // show loader
            $('.mainfest-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newottawa/mainfestcards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#mainfest_orders').text(data['mainfest_orders']);
                    $('#ottawa_mainfest_orders_pie').val(data['mainfest_orders']);
                    // hide loader
                    $('.mainfest-order').removeClass('show');
                    updatePieChartValuesOttawa();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.mainfest-order').removeClass('show');
                }
            });
        }

        function getFailedOrderDataOttawa() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.failed-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newottawa/failedcards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#failed_orders').text(data['failed_orders']);
                    $('#ottawa_failed_orders_pie').val(data['failed_orders']);
                    // hide loader
                    $('.failed-order').removeClass('show');
                    updatePieChartValuesOttawa();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.failed-order').removeClass('show');
                }
            });
        }

        function getCustomRouteDataOttawa() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.custom-route').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newottawa/customroutecards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#custom_orders').text(data['custom_route']);
                    $('#ottawa_custom_orders_pie').val(data['custom_route']);
                    // hide loader
                    $('.custom-route').removeClass('show');
                    updatePieChartValuesOttawa();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.custom-route').removeClass('show');
                }
            });
        }

        function getYesterdayOrderDataOttawa() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.yesterday-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/newottawa/yesterdaycards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#yesterday_orders').text(data['yesterday_return_orders']);
                    $('#ottawa_yesterday_orders_pie').val(data['yesterday_return_orders']);
                    // hide loader
                    $('.yesterday-order').removeClass('show');
                    updatePieChartValuesOttawa();
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.yesterday-order').removeClass('show');
                }
            });
        }


        setTimeout(function () {
            getTotalOrderDataOttawa();
            getMainfestOrderDataOttawa();
            getFailedOrderDataOttawa();
            getCustomRouteDataOttawa();
            getYesterdayOrderDataOttawa();

        }, 1000);


    </script>
@stop
<!--E-chart Pie-->

<!-- ottawa Graph -->
@if($graph == 'ottawa')
    @if (count($data)== 0)
        <div class="x_panel">
            <div class="x_title">
                <form method="get" action="ottawa">
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
        @include('backend.newottawadashboard.ottawa_cards')

    @elseif (count($data)>0 &&in_array("statistics", $data))

        @if(in_array("ottawa_dashboard", $data))
            <div class="x_panel">
                <div class="x_title">
                    <form method="get" action="ottawa">
                        <label>Search By Dateablabel></label>
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
            @include('backend.newottawadashboard.ottawa_cards')
        @endif
    @else
        <input type="hidden" class="data-selector" name="datepicker"
               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
    @endif
@else
    <input type="hidden" class="data-selector" name="datepicker"
           value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
@endif
<!-- End ottawa Graph -->

<input type="hidden" id="ottawa_mainfest_orders_pie" value="0">
<input type="hidden" id="ottawa_return_orders_pie" value="0">
<input type="hidden" id="ottawa_yesterday_orders_pie" value="0">
<input type="hidden" id="ottawa_sorted_orders_pie" value="0">
<input type="hidden" id="ottawa_picked_orders_pie" value="0">
<input type="hidden" id="ottawa_delivered_orders_pie" value="0">
<input type="hidden" id="ottawa_notscan_orders_pie" value="0">
<input type="hidden" id="ottawa_failed_orders_pie" value="0">
<input type="hidden" id="ottawa_custom_orders_pie" value="0">


<div class="col-sm-6 dashboard-statistics-box">
    <div class="x_panel">
        <div class="x_title">
            <h2>Amazon Ottawa</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="dashboard-statistics-box" id="ottawa_pie"></div>

        </div>
    </div>
</div>

<!--E-chart Pie-->


