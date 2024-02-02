@extends( 'backend.layouts.app' )

@section('title', 'summary')

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
    <script src="{{ backend_asset('libraries/moment/moment.js')}}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>

@endsection

@section('inlineJS')
    <style>
        .form-group.dteon{
            position: relative;
        }
        .form-group.dteon h6 {
            position: absolute;
            top: -24px;
            font-size: 14px;
            left: 0;
            color: #443404;
        }
        button.btn.btn-primary.btn-lg.chng_add {
            background: #e46d29;
            color: #fff;
            border: none;
            border-radius: 10px;
            margin: 0 auto;
            display: table;
            margin-right: 0;
        }
        @media (max-width: 991px){
            .form-group.dteon h6{
                position: unset;
            }
            button.btn.btn-primary.btn-lg.chng_add {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="right_col" role="main">
        <div class="dashboard_pg">
            <!-- Header - [start] -->
            <div class="dash_header_wrap">
                <div class="row">
                    <div class="col-md-4">
                        <div class="dash_heading with_filters">
                            <h1>Summary</h1>
                            {{--<button id="expandAllBtn" class="expandBtn btn btn-white nomargin btn-xs">Expand all</button>--}}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <form method="get" action="" id="form1">
                            <div class="row">
                                <div class="filter-col col-md-10">
                                    <div class="row">
                                       {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="filter" id="dateFilter" class="form-control">
                                                    <option value="today" {{ ($date_filter == "today")?'selected': '' }}>Today</option>
                                                    <option value="yesterday" {{ ($date_filter == "yesterday")?'selected': '' }}>Yesterday</option>
                                                    <option value="last-week" {{ ($date_filter == "last-week")?'selected': '' }}>Last Week</option>
                                                    <option value="last-15-days" {{ ($date_filter == "last-15-days")?'selected': '' }}>Last 15 days</option>
                                                </select>
                                            </div>
                                        </div>--}}
                                        <div class="col-md-4">
                                            {{-- <div class="form-group">
                                                 <select name="filter" id="dateFilter" class="form-control">
                                                     <option value="today" {{ ($date_filter == "today")?'selected': '' }}>Today</option>
                                                     <option value="yesterday" {{ ($date_filter == "yesterday")?'selected': '' }}>Yesterday</option>
                                                     <option value="last-week" {{ ($date_filter == "last-week")?'selected': '' }}>Last Week</option>
                                                     <option value="last-15-days" {{ ($date_filter == "last-15-days")?'selected': '' }}>Last 15 days</option>
                                                 </select>
                                             </div>--}}
                                            <div class="form-group dteon">
                                                <h6>Start Date</h6>
                                                <input type="date" id="datepicker1" name="datepicker1" class="data-selector form-control" value="{{ isset($_GET['datepicker1'])?$_GET['datepicker1']: date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {{-- <div class="form-group">
                                                 <select name="filter" id="dateFilter" class="form-control">
                                                     <option value="today" {{ ($date_filter == "today")?'selected': '' }}>Today</option>
                                                     <option value="yesterday" {{ ($date_filter == "yesterday")?'selected': '' }}>Yesterday</option>
                                                     <option value="last-week" {{ ($date_filter == "last-week")?'selected': '' }}>Last Week</option>
                                                     <option value="last-15-days" {{ ($date_filter == "last-15-days")?'selected': '' }}>Last 15 days</option>
                                                 </select>
                                             </div>--}}
                                            <div class="form-group dteon">
                                                <h6>End Date</h6>
                                                <input type="date" id="datepicker2" name="datepicker2" class="data-selector form-control" value="{{ isset($_GET['datepicker2'])?$_GET['datepicker2']: date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group dteon">
                                                <h6>Hub</h6>
                                                <select name="hub_id" id="hub-id"  class="form-control">
                                                    <option value="">Select Hub</option>
                                                    @foreach($hubs as $hub)
                                                        <option value="<?php echo $hub->id?>"{{ ($hub_id == $hub->id)?'selected': '' }} >{{$hub->city_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="sort-col col-md-2">
                                    <button type="button" onclick="submitform()" class="btn btn-primary btn-lg" style="width: 100%">Search</button>
                                </div>
                                {{--<div class="sort-col col-md-3">
                                    <div class="form-group">
                                        <select name="" id="" class="form-control">
                                            <option value="">Sort by</option>
                                            <option value="">Sort by date</option>
                                            <option value="">Sort by setup time</option>
                                            <option value="">Sort by total packages</option>
                                            <option value="">Sort by hub</option>
                                        </select>
                                    </div>
                                </div>--}}
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-8 col-md-12 col-xs-12">
                        <div class="sort-col col-lg-3 col-md-12 col-xs-12" style="justify-content: end;float: left;width: 100%;padding: 0;">
                            <button type="submit" onclick="exportTableToCSV('Summary Report-<?php echo $start_date.' To '.$end_date ?>.csv')" class="btn btn-primary btn-lg chng_add">Export to Excel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header - [/end] -->

            <div id="summary_grid" class="cs_table_wrap style2">
                <table width="100%" class="cs_table tbl_layout_auto" id="export-csv" style="border: solid 1px #eae5e1;">
                    <thead>
                        <tr class="highlight">
                            <th class="align-left" colspan="8">
                                <h2>Inbound</h2>
                            </th>
                            <th class="align-left bc1-lightest" colspan="8">
                                <h2 class="basecolor2">Outbound</h2>
                            </th>
                            <th class="align-left" colspan="5">
                                <h2 class="bodyColor">Closing Team</h2>
                            </th>
                            <th class="align-left bc1-lightest" colspan="6">
                                <h2>&nbsp;</h2>
                            </th>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th style="width: 90px;">Setup time</th>
                            <th style="width: 90px;">Sorting time</th>
                            <th style="width: 90px;">No. Of Sorters</th>
                            <th >Sorted Packages</th>
                            <th>Damaged Packages</th>
                            <th style="width: 100px;">Not Received Orders</th>
                            <th>Routes</th>
                            {{--<th>Mis-sorts</th>
                            <th style="width: 90px;">Mis-sorts Ratio</th>--}}

                            <th width="120px">Dispensing time</th>
                            <th>Picked Order</th>
                            <th>Mis-sorts</th>
                            <th>dispensed routes</th>
                            <th>Routes</th>

                            <th style="width: 90px;">Mis-sorts Ratio</th>
                            <th>Missing Stolen Packages</th>
                            <th>Lost Packages Ratio</th>
                            
                            <th>Return</th>
                            <th>Return Scan</th>
                            <th>Not Return Scan</th>
                            <th>Completed Deliveries Before 9PM</th>
                            <th>Completed Deliveries After 9PM</th>

                            <th>Manual Routes (I/O)</th>
                            <th>Dispencing Accuracy</th>
                            {{--<th>Dispencing Accuracy</th>--}}
                            <th>OTD</th>
                            <th>Hub</th>
                            <th>Manager on duty</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

    <div class="progress-main-wrap">
        <div class="progress">
            <p class="progress-label">Please wait, we are fetching records . . .</p>
            <p class="error-report" style="display: none;">Connection lost, trying to reconnect . . .</p>
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 4%;">0%</div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(function(){
            var loadedRecords = 0

            var days = "{{implode(',',$all_dates)}}";
            days = days.split(',');

            var inboundData = {
                collapsible: {
                    expandAll: function(){
                        $('#expandAllBtn').on('click', function(){
                            if($(this).hasClass('expendedAll')){
                                $(this).removeClass('expendedAll').text('Expand all');
                                $('.detail_arrrow').each(function(){
                                    var thisArrowBtn = $(this);
                                    inboundData.collapsible.collapse(thisArrowBtn);
                                });
                            } else{
                                $(this).addClass('expendedAll').text('Collapse all');
                                $('.detail_arrrow').each(function(){
                                    var thisArrowBtn = $(this);
                                    inboundData.collapsible.expend(thisArrowBtn);
                                });
                            }
                        })
                    },
                    expend: function(thisArrowBtn){
                        var thisBox = thisArrowBtn.closest('.csgrid_data_row');
                        var thisBoxInner = thisArrowBtn.closest('.csgrid_data_row').find('.grid_inner');
                        var thisBoxActualHeight = thisBoxInner.children('.row').innerHeight();
                        thisBox.addClass('expended');
                        thisBoxInner.css("height", thisBoxActualHeight);
                    },
                    collapse: function(thisArrowBtn){
                        var thisBox = thisArrowBtn.closest('.csgrid_data_row');
                        var thisBoxInner = thisArrowBtn.closest('.csgrid_data_row').find('.grid_inner');
                        var thisBoxActualHeight = thisBoxInner.children('.row').innerHeight();
                        thisBox.removeClass('expended');
                        thisBoxInner.removeAttr('style');
                    },
                    expandCollapse: function(thisArrowBtn) {
                        var thisBox = thisArrowBtn.closest('.csgrid_data_row');
                        if(thisBox.hasClass('expended')){
                            inboundData.collapsible.collapse(thisArrowBtn);
                        } else {
                            inboundData.collapsible.expend(thisArrowBtn);
                        }
                    },
                    init: function(){
                        $('.detail_arrrow').on('click', function(e){
                            e.preventDefault();
                            var thisArrowBtn = $(this)
                            inboundData.collapsible.expandCollapse(thisArrowBtn);
                        });
                    }
                },
                startEndTime: {

                    checkStartButton: function() {

                    },
                    init: function(){

                    }
                },
                loadInboundGrid: function(day){
                    let hubId = $('#hub-id').val();
                    let date = days[loadedRecords-1];
                    let data = {
                        date_filter: date,
                        day: day,
                        hub_id: hubId,
                    }
                    $.ajax({
                        type: "GET",
                        url: "<?php echo URL::to('/'); ?>/warehouse/summary/data",
                        data: data,
                        success: function (data) {   // success callback function
                            inboundData.appendRow(data);
                        },
                        error: function (jqXhr, textStatus, errorMessage) { // error callback
                            console.log('Error: ' + errorMessage);
                        },
                        complete: function(data){
                            inboundData.updateProgress();
                            if(loadedRecords < days.length){
                                inboundData.loadNextInbound();
                            }
                        }
                    });
                },
                loadNextInbound(){
                    loadedRecords = loadedRecords+1
                    inboundData.loadInboundGrid();
                },
                appendRow: function(data){
                    jQuery.each(data, function(index, record) {
                        $('#summary_grid tbody').append(`
                            <tr class="csgrid_data_row inbound_row">
                                <td><span>${record.date}</span></td> 
                                <td class="value">
                                    <span id="start_time_show">${record.setup_start_time ? record.setup_start_time : '00:00:00'}</span>
                                    </br><span class="bc1-light">to</span></br>
                                    <span id="end_time_show">${record.setup_end_time ? record.setup_end_time : '00:00:00'}</span>
                                </td>
                                <td>
                                    <span id="sorting_start_show">${record.start_sorting_time ? record.start_sorting_time : '00:00:00'}</span>
                                    </br><span class="bc1-light">to</span></br>
                                    <span id="sorting_end_show">${record.end_sorting_time ? record.end_sorting_time : '00:00:00'}</span>
                                </td>
                                <td>${record.internal_sorter_count + record.brooker_sorter_count}</td>
                                <td>${record.total_packages}</td>
                                <td>${record.total_damaged_packages}</td>
                                <td>${record.total_not_receive}</td>
                                <td>${record.total_route}</td>


                                <td class="bc1-lightest">
                                    <span id="dispensing_start_time">${record.dispensing_start_time ? record.dispensing_start_time : '00:00:00'}</span>
                                    </br>
                                    <span class="bc1-light">to</span>
                                    </br>
                                    <span id="dispensing_end_time">${record.dispensing_end_time ? record.dispensing_end_time : '00:00:00'}</span>
                                </td>
                                <td class="bc1-lightest">${record.total_picked_order}</td>
                                <td class="bc1-lightest">${record.total_mis_order}</td>
                                <td class="bc1-lightest">${record.dispensed_route}</td>
                                <td class="bc1-lightest">${record.total_route}</td>

                                <td>${record.total_mis_ratio}</td>
                                <td class="bc1-lightest">${record.missing_stolen_packages}</td>
                                <td class="bc1-lightest">${record.lost_packages}</td>

                                <td>${record.total_same_day_returns}</td>
                                <td>${record.total_return_scan}</td>
                                <td>${record.total_not_return_scan}</td>
                                <td>${record.total_completed_deliveries_before_9pm}</td>
                                <td>${record.total_completed_deliveries_after_9pm}</td>

                                <td class="bc1-lightest">${record.overall_total_manual_routes}</td>
                                <td class="bc1-lightest">${record.dispencing_accuracy}</td>
                                <td class="bc1-lightest">${record.otd}</td>
                                <td class="bc1-lightest">${record.hub_name}</td>
                                <td class="bc1-lightest">${record.manager_on_duty}</td>
                            </tr>
                        `)
                    })
                },
                updateProgress: function(){
                    var pregressPerc = (parseInt(loadedRecords) / parseInt(days.length) * 100).toFixed(2)
                    pregressPerc = pregressPerc + '%'
                    $('.progress-main-wrap .progress-bar').css('width', pregressPerc).text(pregressPerc);

                    if(pregressPerc === '100.00%') {
                        console.log('pregressPerc: ', pregressPerc);
                        $('.progress-main-wrap').removeClass('show');
                        inboundData.collapsible.init();
                        inboundData.startEndTime.init();
                    }
                },
                init: function() {
                    inboundData.collapsible.expandAll();
                    inboundData.loadNextInbound();
                    $('.progress-main-wrap').addClass('show');
                },
            }
            inboundData.init();
        })

        function submitform() {
            var startDate = document.getElementById("datepicker1").value;
            var endDate = document.getElementById("datepicker2").value;
            var CurrentDate = new Date();
            var newstartDate = new Date(startDate);
            var newendDate = new Date(endDate);
            var diffDays =    (diffDays = (newendDate.getTime() - newstartDate.getTime()) / (1000 * 3600 * 24))+1;
            console.log((diffDays));
            if(newstartDate > CurrentDate || newendDate > CurrentDate){
                alert('Given date range is greater than today . Please select valid dates.');
                document.getElementById("datepicker1").value = CurrentDate.toISOString().slice(0, 10);
                document.getElementById("datepicker2").value = CurrentDate.toISOString().slice(0, 10);
                return false;
            }
            else if ((Date.parse(startDate) > Date.parse(endDate))) {
                alert("To date should be greater than or equal to From date");
                document.getElementById("datepicker1").value = CurrentDate.toISOString().slice(0, 10);
                document.getElementById("datepicker2").value = CurrentDate.toISOString().slice(0, 10);
                return false;
            }
            else if (diffDays > 15) {
                alert("Maximum date range is 15 days.");
                document.getElementById("datepicker1").value = CurrentDate.toISOString().slice(0, 10);
                document.getElementById("datepicker2").value = CurrentDate.toISOString().slice(0, 10);
                return false;
            }
            else{
                // console.log('submit');
                $('#form1').submit();
            }
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("#export-csv tr");

            for (var i = 1; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length-1; j++)
                    row.push(cols[j].innerText.replace(',',' '));

                csv.push(row.join(",").replace(/(\r\n|\n|\r)/gm, " "));
            }

            // Download CSV file
            downloadCSV(csv.join("\n"), filename);
        }

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV file
            csvFile = new Blob([csv], {type: "text/csv"});

            // Download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = "none";

            // Add the link to DOM
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();



        }
    </script>
@endsection
