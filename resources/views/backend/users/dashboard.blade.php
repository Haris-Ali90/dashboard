@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

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

@endsection




@section('content')

             <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-child"></i> Total Users</span>
              <div class="count" id="totalUser">{{$totalUsers}}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-list-alt"></i> Total Categories</span>
              <div class="count" id="totalAndroidUser">{{$totalCategories}}</div>
             </div>
             <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-list-alt"></i> Total Services</span>
              <div class="count" id="totalActiveUser">{{$totalServices}}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-android"></i> Total Android Users</span>
              <div class="count green" id="totalIosUser">{{$totalAndroidUser}}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-apple"></i> Total IOS User</span>
              <div class="count" id="totalWebUser">{{$totalIosUser}}</div>
            </div>   
          </div>
          <!-- /top tiles -->



          <div class="row" style="display: none;">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h3>Network Activities <small>Graph title sub-title</small></h3>
                  </div>
                  <div class="col-md-6">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                      <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                    </div>
                  </div>
                </div>

                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                  <div style="width: 100%;">
                    <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2>Top Campaign Performance</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                      <p>Facebook Campaign</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Twitter Campaign</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                      <p>Conventional Media</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="40"></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Bill boards</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 76%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />

          <div class="row">


            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Sales</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>

                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <h4>Today Sales</h4>
                  <br>
                  <div class="widget_summary">
                    <div class="w_left w_25">
                      <span>Sales $</span>
                    </div>
                    <div class="w_center w_55">
                      <div class="progress">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"  style="width: 50%;">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </div>
                    <div class="w_right w_20">
                      <span></span>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <br><br>
                  {{--<div class="widget_summary">--}}
                    {{--<div class="w_left w_25">--}}
                      {{--<span>Staff Post</span>--}}
                    {{--</div>--}}
                    {{--<div class="w_center w_55">--}}
                      {{--<div class="progress">--}}
                        {{--<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" >--}}
                          {{--<span class="sr-only">60% Complete</span>--}}
                        {{--</div>--}}
                      {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="w_right w_20">--}}
                      {{--<span></span>--}}
                    {{--</div>--}}
                    {{--<div class="clearfix"></div>--}}
                  {{--</div>--}}
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                  <h2>Registered Platform</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">

                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table class="" style="width:100%">

                    <tr>
                      <td>
                        <canvas id="canvas1" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                      </td>
                      <td>
                        <table class="tile_info">
                          <tr>
                            <td>
                              <p><i class="fa fa-square green"></i>Android</p>
                            </td>

                          </tr>
                          {{--<tr>--}}
                            {{--<td>--}}
                              {{--<p><i class="fa fa-square aero"></i></p>--}}
                            {{--</td>--}}

                          {{--</tr>--}}
                          <tr>
                            <td>
                              <p><i class="fa fa-square blue"></i>IOS </p>
                            </td>

                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>


          <!--   <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Courses Request Acceptance Ratio</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    {{--<ul class="quick-list">--}}
                      {{--<li><i class="fa fa-calendar-o"></i><a href="#">Settings</a>--}}
                      {{--</li>--}}
                      {{--<li><i class="fa fa-bars"></i><a href="#">Subscription</a>--}}
                      {{--</li>--}}
                      {{--<li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a> </li>--}}
                      {{--<li><i class="fa fa-line-chart"></i><a href="#">Achievements</a>--}}
                      {{--</li>--}}
                      {{--<li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a> </li>--}}
                      {{--<li><i class="fa fa-line-chart"></i><a href="#">Achievements</a>--}}
                      {{--</li>--}}
                      {{--<li><i class="fa fa-area-chart"></i><a href="#">Logout</a>--}}
                      {{--</li>--}}
                    {{--</ul>--}}
                     <div class="row">
                       <div class="col-md-3 col-sm-4 col-xs-12"></div>
                       <div class="col-md-6 col-sm-4 col-xs-12">
                    <div class="">
                      <h4 style="text-align: center">Courses Request</h4>
                      <canvas width="150" height="80" id="foo" class="" style="width: 160px; height: 100px;"></canvas>
                      <div class="goal-wrapper">
                        <span class="gauge-value "></span>
                        <span id="gauge-text" class="gauge-value pull-left"></span>
                        <span id="goal-text" class="goal-value pull-right"></span>
                      </div>
                    </div>
                       </div>
                       <div class="col-md-3 col-sm-4 col-xs-12"></div>
                     </div>

                  </div>
                </div>
              </div>
            </div> -->

          </div>



        </div>

	 
	            <!-- footer content -->
        <footer>
          <div class="pull-right">

          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
        <!-- /#page-wrapper -->

             @section('inlineJS')
                     <!-- Flot -->
             <script>
               $(document).ready(function() {
                 var data1 = [
                   [gd(2012, 1, 1), 17],
                   [gd(2012, 1, 2), 74],
                   [gd(2012, 1, 3), 6],
                   [gd(2012, 1, 4), 39],
                   [gd(2012, 1, 5), 20],
                   [gd(2012, 1, 6), 85],
                   [gd(2012, 1, 7), 7]
                 ];

                 var data2 = [
                   [gd(2012, 1, 1), 82],
                   [gd(2012, 1, 2), 23],
                   [gd(2012, 1, 3), 66],
                   [gd(2012, 1, 4), 9],
                   [gd(2012, 1, 5), 119],
                   [gd(2012, 1, 6), 6],
                   [gd(2012, 1, 7), 9]
                 ];
                 $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
                   data1, data2
                 ], {
                   series: {
                     lines: {
                       show: false,
                       fill: true
                     },
                     splines: {
                       show: true,
                       tension: 0.4,
                       lineWidth: 1,
                       fill: 0.4
                     },
                     points: {
                       radius: 0,
                       show: true
                     },
                     shadowSize: 2
                   },
                   grid: {
                     verticalLines: true,
                     hoverable: true,
                     clickable: true,
                     tickColor: "#d5d5d5",
                     borderWidth: 1,
                     color: '#fff'
                   },
                   colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
                   xaxis: {
                     tickColor: "rgba(51, 51, 51, 0.06)",
                     mode: "time",
                     tickSize: [1, "day"],
                     //tickLength: 10,
                     axisLabel: "Date",
                     axisLabelUseCanvas: true,
                     axisLabelFontSizePixels: 12,
                     axisLabelFontFamily: 'Verdana, Arial',
                     axisLabelPadding: 10
                   },
                   yaxis: {
                     ticks: 8,
                     tickColor: "rgba(51, 51, 51, 0.06)",
                   },
                   tooltip: false
                 });

                 function gd(year, month, day) {
                   return new Date(year, month - 1, day).getTime();
                 }
               });
             </script>
             <!-- /Flot -->

             <!-- JQVMap -->
             {{--<script>--}}
             {{--$(document).ready(function(){--}}
             {{--$('#world-map-gdp').vectorMap({--}}
             {{--map: 'world_en',--}}
             {{--backgroundColor: null,--}}
             {{--color: '#ffffff',--}}
             {{--hoverOpacity: 0.7,--}}
             {{--selectedColor: '#666666',--}}
             {{--enableZoom: true,--}}
             {{--showTooltip: true,--}}
             {{--values: sample_data,--}}
             {{--scaleColors: ['#E6F2F0', '#149B7E'],--}}
             {{--normalizeFunction: 'polynomial'--}}
             {{--});--}}
             {{--});--}}
             {{--</script>--}}
                     <!-- /JQVMap -->

             <!-- Skycons -->
             <script>
               $(document).ready(function() {
                 var icons = new Skycons({
                           "color": "#73879C"
                         }),
                         list = [
                           "clear-day", "clear-night", "partly-cloudy-day",
                           "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                           "fog"
                         ],
                         i;

                 for (i = list.length; i--;)
                   icons.set(list[i], list[i]);

                 icons.play();
               });
             </script>
             <!-- /Skycons -->

             <!-- Doughnut Chart -->
             <script>
               $(document).ready(function(){
                 var options = {
                   legend: false,
                   responsive: false
                 };

                 new Chart(document.getElementById("canvas1"), {
                   type: 'doughnut',
                   tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                   data: {
                     labels: [
                       "Android",
                       "IOS"
                     ],
                     datasets: [{
                       data: [{{$totalAndroidUser}}, {{$totalIosUser}}],
                       backgroundColor: [
                         "#26B99A",
                         "#3498DB"
                       ],
                       hoverBackgroundColor: [
                         "#36CAAB",
                         "#49A9EA"
                       ]
                     }]
                   },
                   options: options
                 });
               });
             </script>
             <!-- /Doughnut Chart -->

             <!-- bootstrap-daterangepicker -->
             <script>
               $(document).ready(function() {

                 var cb = function(start, end, label) {
                   console.log(start.toISOString(), end.toISOString(), label);
                   $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                 };

                 var optionSet1 = {
                   startDate: moment().subtract(29, 'days'),
                   endDate: moment(),
                   minDate: '01/01/2012',
                   maxDate: '12/31/2015',
                   dateLimit: {
                     days: 60
                   },
                   showDropdowns: true,
                   showWeekNumbers: true,
                   timePicker: false,
                   timePickerIncrement: 1,
                   timePicker12Hour: true,
                   ranges: {
                     'Today': [moment(), moment()],
                     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                   },
                   opens: 'left',
                   buttonClasses: ['btn btn-default'],
                   applyClass: 'btn-small btn-primary',
                   cancelClass: 'btn-small',
                   format: 'MM/DD/YYYY',
                   separator: ' to ',
                   locale: {
                     applyLabel: 'Submit',
                     cancelLabel: 'Clear',
                     fromLabel: 'From',
                     toLabel: 'To',
                     customRangeLabel: 'Custom',
                     daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                     monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                     firstDay: 1
                   }
                 };
                 $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
                 $('#reportrange').daterangepicker(optionSet1, cb);
                 $('#reportrange').on('show.daterangepicker', function() {
                   console.log("show event fired");
                 });
                 $('#reportrange').on('hide.daterangepicker', function() {
                   console.log("hide event fired");
                 });
                 $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                   console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
                 });
                 $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
                   console.log("cancel event fired");
                 });
                 $('#options1').click(function() {
                   $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
                 });
                 $('#options2').click(function() {
                   $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
                 });
                 $('#destroy').click(function() {
                   $('#reportrange').data('daterangepicker').remove();
                 });
               });
             </script>
             <!-- /bootstrap-daterangepicker -->

             <!-- gauge.js -->
             <script>
               var opts = {
                 lines: 12,
                 angle: 0,
                 lineWidth: 0.4,
                 pointer: {
                   length: 0.75,
                   strokeWidth: 0.042,
                   color: '#1D212A'
                 },
                 limitMax: 'false',
                 colorStart: '#1ABC9C',
                 colorStop: '#1ABC9C',
                 strokeColor: '#F0F3F3',
                 generateGradient: true
               };
               var target = document.getElementById('foo'),
                       gauge = new Gauge(target).setOptions(opts);

               gauge.maxValue ={{$totalInquiries}};
               gauge.animationSpeed = 32;
               gauge.set({{$totalSuccessInquiries}});
               gauge.setTextField(document.getElementById("gauge-text"));
             </script>
             <!-- /gauge.js -->
@endsection
@endsection