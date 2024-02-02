<?php
$user = Auth::user();
if($user->email!="admin@gmail.com")
{

    $data = explode(',', $user['rights']);
    $permissions = explode(',', $user['permissions']);
}

else{
    $data = [];
    $permissions=[];
}
?>

@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('CSSLibraries')
    <style>
        .dashboard-statistics-box
        {
            min-height: 400px;
            margin: 15px 0px;
            position: relative;
            box-sizing: border-box;
        }
        .dashboard-statistics-box.dashboard-statistics-tbl-show td {
            padding-top: 52px;
            padding-bottom: 52px;
        }
    </style>
@endsection
@section('JSLibraries')
    {{--<script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>--}}
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>

    {{--<script src="{{ backend_asset('libraries/bootstrap/dist/js/bootstrap.min.js') }}"></script>--}}
    {{--<script src="{{ backend_asset('libraries/fastclick/lib/fastclick.js') }}"></script>--}}
    <script src="{{ backend_asset('nprogress/nprogress.js') }}"></script>
    <script src="{{ backend_asset('libraries/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/iCheck/icheck.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/skycons/skycons.js') }}"></script>
    <script src="{{ backend_asset('libraries/flot/jquery.flot.js') }}"></script>
    <script src="{{ backend_asset('libraries/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ backend_asset('libraries/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ backend_asset('libraries/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ backend_asset('libraries/flot/jquery.flot.resize.js') }}"></script>
    {{--<script src="{{ backend_asset('libraries/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>--}}
    {{--<script src="{{ backend_asset('libraries/flot-spline/js/jquery.flot.spline.min.js') }}"></script>--}}
    {{--<script src="{{ backend_asset('libraries/flot.curvedlines/curvedLines.js') }}"></script>--}}
    {{--<script src="{{ backend_asset('libraries/DateJS/build/date.js') }}"></script>--}}
    <script src="{{ backend_asset('libraries/jqvmap/dist/jquery.vmap.js') }}"></script>
    <script src="{{ backend_asset('libraries/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ backend_asset('libraries/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    {{--<script src="{{ backend_asset('js/custom.min.js') }}"></script>--}}
    {{--<script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>--}}
    {{--<script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>--}}
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection
@section('inlineJS')
    <script type="text/javascript">
        //3D Pie Chart
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Total', {{ isset($montreal_count)?$montreal_count->total:0 }}],
                ['Sorted Orders',     {{ isset($montreal_count)?$montreal_count->sorter_order_count:0 }}],
                ['Pickup From Hub',      {{ isset($montreal_count)?$montreal_count->hub_deliver_order_count:0 }}],
                ['Delivered Orders',  {{ isset($montreal_count)?$montreal_count->deliver_order_count:0 }}]


            ]);

            var options = {
                title: 'Montreal Orders',
                //is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);



            //Pie Chart


            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Total', {{ isset($ottawa_count)?$ottawa_count->total:0 }}],
                ['Sorted Orders',     {{ isset($ottawa_count)?$ottawa_count->sorter_order_count:0 }}],
                ['Pickup From Hub',      {{ isset($ottawa_count)?$ottawa_count->hub_deliver_order_count:0 }}],
                ['Delivered Orders',  {{ isset($ottawa_count)?$ottawa_count->deliver_order_count:0 }}]

            ]);

            var options = {
                title: 'Ottawa Orders'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }

    </script>
    <script type="text/javascript">
        //Table Chart
        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('number', 'Orders');
            data.addRows([
                ['Montreal',  {f: '{{ isset($montreal_count)?$montreal_count->total:0 }}'}, ],
                ['Ottawa',   {f: '{{ isset($ottawa_count)?$ottawa_count->total:0 }}'},  ],
                ['Canadian Tire', {f: '{{ isset($ctc_count)?$ctc_count->total:0 }}'}, ]
            ]);

            var table = new google.visualization.Table(document.getElementById('table_div'));

            table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        }


        //Bar Chart
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        // making labels
        var dataArray = [
            ['Month', 'Amazon Montreal Orders', 'Amazon Ottawa Orders','Canadian Tire Orders'],
        ];
        // geting month data
        let bar_chart_data =  JSON.parse( '{!!$bar_chart_data !!}');
        // converting json into array
        for(index in bar_chart_data)
        {
            dataArray.push([bar_chart_data[index].month,bar_chart_data[index].montreal_total,bar_chart_data[index].ottawa_total,bar_chart_data[index].ctc_total]);
        }

        function drawChart() {
            var data = google.visualization.arrayToDataTable(dataArray);
            var options = {
                chart: {
                    title: 'Joeyco Activities',
                }
            };
            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

    </script>

@endsection

@section('content')
    <!--right_col open-->
    <div class="right_col" role="main">


        @if (count($data)== 0)
        <div class="row">
            <div class="col-sm-6 dashboard-statistics-box" id="piechart_3d"></div>
            <div class="col-sm-6 dashboard-statistics-box" id="piechart"></div>
            <div class="col-sm-6 dashboard-statistics-box dashboard-statistics-tbl-show" id="table_div"></div>
            <div class="col-sm-6 dashboard-statistics-box" id="columnchart_material"></div>
        </div>
        @endif

        @if (count($data)>0 &&in_array("statistics", $data))
                <div class="row">

                </div>
            @endif
    </div>


    <!-- footer content -->
    <footer>
        <div class="pull-right">

        </div>
        <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
    <!-- /#page-wrapper -->
@endsection