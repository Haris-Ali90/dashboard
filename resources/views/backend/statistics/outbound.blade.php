@extends( 'backend.layouts.app' )

@section('title', 'OutBound')

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
                    <div class="col-lg-4 col-md-12">
                        <div class="dash_heading with_filters">
                            <h1>Outbound </h1>
                            <button id="expandAllBtn" class="expandBtn btn btn-white nomargin btn-xs">Expand all</button>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <form method="get" action="" id="form1">
                            <div class="row">
                                <div class="filter-col col-lg-10 col-md-9 col-xs-12">
{{--                                    <div class="row">--}}
{{--                                       --}}{{-- <div class="col-lg-6 col-md-6 col-xs-12">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <select name="filter" id="dateFilter" class="form-control">--}}
{{--                                                    <option value="today" {{ ($date_filter == "today")?'selected': '' }}>Today</option>--}}
{{--                                                    <option value="yesterday" {{ ($date_filter == "yesterday")?'selected': '' }}>Yesterday</option>--}}
{{--                                                    <option value="last-week" {{ ($date_filter == "last-week")?'selected': '' }}>Last Week</option>--}}
{{--                                                    <option value="last-15-days" {{ ($date_filter == "last-15-days")?'selected': '' }}>Last 15 days</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-4 col-md-4 col-xs-12">--}}
{{--                                            --}}{{-- <div class="form-group">--}}
{{--                                                 <select name="filter" id="dateFilter" class="form-control">--}}
{{--                                                     <option value="today" {{ ($date_filter == "today")?'selected': '' }}>Today</option>--}}
{{--                                                     <option value="yesterday" {{ ($date_filter == "yesterday")?'selected': '' }}>Yesterday</option>--}}
{{--                                                     <option value="last-week" {{ ($date_filter == "last-week")?'selected': '' }}>Last Week</option>--}}
{{--                                                     <option value="last-15-days" {{ ($date_filter == "last-15-days")?'selected': '' }}>Last 15 days</option>--}}
{{--                                                 </select>--}}
{{--                                             </div>--}}
{{--                                            <div class="form-group dteon" >--}}
{{--                                                <h6>Start Date</h6>--}}
{{--                                                <input type="date" id="datepicker1" name="datepicker1" class="data-selector form-control" value="{{ isset($_GET['datepicker1'])?$_GET['datepicker1']: date('Y-m-d') }}">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-4 col-md-4 col-xs-12">--}}
{{--                                            --}}{{-- <div class="form-group">--}}
{{--                                                 <select name="filter" id="dateFilter" class="form-control">--}}
{{--                                                     <option value="today" {{ ($date_filter == "today")?'selected': '' }}>Today</option>--}}
{{--                                                     <option value="yesterday" {{ ($date_filter == "yesterday")?'selected': '' }}>Yesterday</option>--}}
{{--                                                     <option value="last-week" {{ ($date_filter == "last-week")?'selected': '' }}>Last Week</option>--}}
{{--                                                     <option value="last-15-days" {{ ($date_filter == "last-15-days")?'selected': '' }}>Last 15 days</option>--}}
{{--                                                 </select>--}}
{{--                                             </div>--}}
{{--                                            <div class="form-group dteon">--}}
{{--                                                <h6>End Date</h6>--}}
{{--                                                <input type="date" id="datepicker2" name="datepicker2" class="data-selector form-control" value="{{ isset($_GET['datepicker2'])?$_GET['datepicker2']: date('Y-m-d') }}">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        --}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="sort-col col-lg-2 col-md-3 col-xs-12">--}}
{{--                                    <button type="button" class="btn btn-primary btn-lg" onclick="submitform()" style="width: 100%">Search</button>--}}
{{--                                </div>--}}
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
                    <div class="col-lg-12 d-flex justify-content-end col-md-12 col-xs-12">
                        <div class="sort-col col-lg-3 col-md-12 col-xs-12" style="justify-content: end;float: left;width: 100%;padding: 0;">
                            <button type="submit" id="convert" class="btn btn-primary btn-lg chng_add c-btn">Export to Excel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header - [/end] -->

            <div id="inbound_grid" class="csgrid_data_list"></div>
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
            var json_data = [];
            
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
                    dispensingTime: function(date,thisDate){
                        let hubId = $('#hub-id').val();
                        $.ajax({
                            type: "GET",
                            url: "<?php echo URL::to('/'); ?>/outbound/dispensing-time",
                            data: {'dateTime':date,'hubId': hubId},
                            success: function (data) {   // success callback function
                                if(data['state'] == 1){
                                    $('#dispensing_start_time_'+thisDate).text(data['time']);
                                    $('#dispensing_button_hide_'+thisDate).text('End');

                                    var update_val = json_data;
                                    $(update_val).each(function(i,val)
                                    {
                                        $.each(val,function(key,val)
                                        {
                                            if ($.inArray(key, ['date_id']) != -1) {
                                                if (date == val) {
                                                    if ($.inArray(key, ['dispensing_start_time']) == -1) {
                                                        json_data[i]['dispensing_start_time'] = data['time']
                                                    }
                                                }
                                            }
                                        });
                                    });
                                }
                                else{
                                    $('#dispensing_button_hide_'+thisDate).hide();
                                    $('#dispensing_end_time_'+thisDate).text(data['time']);
                                    var update_val = json_data;
                                    $(update_val).each(function(i,val)
                                    {
                                        $.each(val,function(key,val)
                                        {
                                            if ($.inArray(key, ['date_id']) != -1) {
                                                if (date == val) {
                                                    if ($.inArray(key, ['dispensing_end_time']) == -1) {
                                                        json_data[i]['dispensing_end_time'] = data['time']
                                                    }
                                                }
                                            }
                                        });
                                    });
                                }

                            },
                            error: function (jqXhr, textStatus, errorMessage) { // error callback
                                console.log('Error: ' + errorMessage);
                            }
                        });
                    },
                    checkStartButton: function() {

                    },
                    init: function(){
                        $('.dispensing_btn').bind('click', function(e){
                            e.preventDefault();
                            let dateString = $(this).closest('.csgrid_data_row').find('.date').text();
                            let date = new Date(dateString);
                            let formattedDate = moment(date).format('YYYY-MM-DD')
                            let thisDate = $(this).closest('.csgrid_data_row').data('date');
                            inboundData.startEndTime.dispensingTime(formattedDate,thisDate)
                        });
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
                        url: "<?php echo URL::to('/'); ?>/outbound/data",
                        data: data,
                        success: function (data) {   // success callback function
                            json_data.push(data[0]);
                            inboundData.appendRow(data);
                        },
                        error: function (jqXhr, textStatus, errorMessage) { // error callback
                            console.log('Error: ' + errorMessage);
                        },
                        complete: function(data){
                            inboundData.updateProgress();
                            console.log(json_data);
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
                editRowForm: {
                    saveEditForm: function(){
                        $('.updteRowForm').on('click', function(){
                        
        
                            let date = $(this).attr("data-date_id");
                            let hubId = $('#hub-id').val();
                            let dispanced_route=$('#dispancedroutes-'+date).val();
                            let manager_on_duty=$('#manager-'+date).val();
                            let thisDate = $(this).closest('.csgrid_data_row').data('date');
                            $(this).text('Processing');
                            $.ajax({
                                type: "POST",
                                // url: "https://jsonplaceholder.typicode.com/todos/1",
                                url:"{{route('statistics-outbound.wareHouseSorterUpdate')}}",
                                data:{manager_on_duty:manager_on_duty,dispensed_route:dispanced_route,hub_id:hubId,_token: "{{(csrf_token())}}",date:date},
                                success: function (data) {   // success callback function
                                    console.log(data.dispensed_route);
                                    console.log(data.manager);
                                    $('#div-'+date+' .dispensed_route').html(data.dispensed_route);
                                    $('#div-'+date+' .manager_on_duty').html(data.manager);

                                    var update_val = json_data;
                                    $(update_val).each(function(i,val)
                                    {
                                        $.each(val,function(key,val)
                                        {
                                            if ($.inArray(key, ['date_id']) != -1) {
                                                if (date == val) {
                                                    if ($.inArray(key, ['dispensed_route', 'manager_on_duty']) == -1) {
                                                        json_data[i]['dispensed_route'] = data.dispensed_route
                                                        json_data[i]['manager_on_duty'] = data.manager
                                                    }
                                                }
                                            }
                                        });
                                    });

                                    var thisRow = $('.csgrid_data_row[data-date="' + thisDate + '"]');
                                    // console.log('thisDate: ', thisDate);
                                    thisRow.attr('data-test', 'test');
                                    thisRow.find('.edit_row_form .updteRowForm').text('Save');
                                    thisRow.find('.edit_row_form .alert').show();
                                    setTimeout(() => {
                                        thisRow.find('.edit_row_form_wrap').removeClass('opened');
                                        thisRow.find('.edit_row_form .alert').hide();
                                        thisRow.find('.edit_row_btn').text('Edit');
                                    }, 2000);
                                }
                                // ,
                                // error: function (jqXhr, textStatus, errorMessage) { // error callback
                                //     console.log('Error: ' + errorMessage);
                                // }
                            });
                        })
                        
                    },
                    showHideEditForm: function(thisEditBtn){
                        if(thisEditBtn.closest('.edit_row_form_wrap').hasClass('opened')){
                            thisEditBtn.text('Edit');
                            thisEditBtn.closest('.csgrid_data_row').removeClass('active').find('.edit_row_form_wrap').removeClass('opened');
                        } else{
                            thisEditBtn.text('Close');
                            thisEditBtn.closest('.csgrid_data_row').addClass('active').find('.edit_row_form_wrap').addClass('opened');
                        }
                    },
                    init: function(){
                        $('.edit_row_btn').on('click', function(e){
                            e.preventDefault();
                            var thisEditBtn = $(this)
                            inboundData.editRowForm.showHideEditForm(thisEditBtn);
                        })
                        inboundData.editRowForm.saveEditForm()
                    }
                },
                appendRow: function(data){
                    jQuery.each(data, function(index, record) {
                        var thisDate = record.date.toLowerCase().replace(/[&\/\\#,+()$~%.'":*?<>{}/\s/]/g, '')
                        $('#inbound_grid').append(`
                            <div  id="div-${record.date_id}" class="csgrid_data_row outbound_row" data-date="${thisDate}">

                                <div class="edit_row_form_wrap">
                                    <a href="#" class="edit_row_btn btn-white">Edit</a>
                                    <div class="edit_row_form">
                                        <div class="alert alert-success" role="alert" style="display: none;">Updated successfully.</div>
                                        <div class="form-group">
                                            <label>Total dispanced routes</label>
                                            <input type="number" id="dispancedroutes-${record.date_id}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  name="" class="form-control" value="${record.dispensed_route}"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Manager on duty</label>
                                            <select id="manager-${record.date_id}" class="form-control" name="manager_on_duty">
                                                <option value="">Select a manager</option>
                                                <?php foreach($managers as $manager){ ?>
                                                <option value="{{$manager->id}}">{{$manager->name}}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div>
                                            <button data-date_id='${record.date_id}' type="submit" class="btn btn-primary btn-lg updteRowForm">Save</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail_arrrow"><i class="icofont-rounded-down"></i></div>
                                <div class="grid_title">
                                    <span class="basecolor1 date" >${record.date}</span>
                                    <span class="divider">|</span>
                                    ${record.day}
                                        ${record.week ? '<span class="divider">|</span>' : ''}
                                        ${record.week}
                                </div>
                                <div class="grid_inner">
                                    <div class="row">
                                        <div class="col-cs5">
                                            <div class="inner_col">
                                                <div class="attr">
                                                    <div class="lbl">Dispensing time
                                                    ${record.dispensing_start_time ?record.dispensing_end_time?'':`<a href="#" class="dispensing_btn btn btn-bc1lightest btn-xs" id="dispensing_button_hide_${thisDate}">End</a>`:`<a href="#" class="dispensing_btn btn btn-bc1lightest btn-xs" id="dispensing_button_hide_${thisDate}">Start</a>` }
                                                    </div>
                                                    <div class="value">
                                                        <span id="dispensing_start_time_${thisDate}">${record.dispensing_start_time ? record.dispensing_start_time : '00:00:00'}</span>
                                                        <span class="divider horizontal"></span>
                                                        <span id="dispensing_end_time_${thisDate}">${record.dispensing_end_time ? record.dispensing_end_time : '00:00:00'}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-cs5">
                                            <div class="inner_col">
                                                <div class="attr">
                                                    <div class="lbl">Total Picked Order</div>
                                                    <div class="value">${record.total_picked_order}</div>
                                                </div>
                                                <div class="attr">
                                                    <div class="lbl">Total Mis-sorts</div>
                                                    <div class="value">${record.total_mis_order}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-cs5">
                                            <div class="inner_col">
                                                <div class="attr">
                                                    <div class="lbl">
                                                        Total dispensed routes
                                                    </div>
                                                    <div class="value dispensed_route">
                                                        ${record.dispensed_route}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-cs5">
                                            <div class="inner_col">
                                                <div class="attr">
                                                    <div class="lbl">Total Routes</div>
                                                    <div class="value"> ${record.total_route}</div>
                                                </div>
                                                <div class="attr">
                                                    <div class="lbl">Total System Routes</div>
                                                    <div class="value"> ${record.total_normal_route}</div>
                                                </div>
                                                <div class="attr">
                                                    <div class="lbl">Total Custom Route </div>
                                                    <div class="value"> ${record.total_custom_route}</div>
                                                </div>
                                                <div class="attr">
                                                    <div class="lbl">Total Big Box Route </div>
                                                    <div class="value">${record.total_big_box_route}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-cs5">
                                            <div class="inner_col">
                                                <div class="attr">
                                                        <div class="lbl">Hub</div>
                                                        <div class="value"> ${record.hub_name}</div>
                                                    </div>
                                                    <div class="attr">
                                                        <div class="lbl">Manager on duty</div>
                                                        <div class="value manager_on_duty"> ${record.manager_on_duty}</div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `)
                        $(`#manager-${record.date_id}`).val(record.manager_on_duty_id).change();
                    })
                },
                updateProgress: function(){
                    var pregressPerc = (parseInt(loadedRecords) / parseInt(days.length) * 100).toFixed(2)
                    pregressPerc = pregressPerc + '%'
                    $('.progress-main-wrap .progress-bar').css('width', pregressPerc).text(pregressPerc);

                    if(pregressPerc === '100.00%') {
                        $('.progress-main-wrap').removeClass('show');
                        inboundData.collapsible.init();
                        // inboundData.startEndTime.init();
                        // inboundData.editRowForm.init();
                    }
                },
                downloadCSVFunctions:{
                    sortCSVData:function (){
                        var array = json_data
                        var str = '';
                        str += 'Date,Dispensing Start Time,Dispensing End Time,Total Picked Orders,Total Mis-Sorts,Total Dispensed Routes,Total Route,Total Normal Route,Total Custom Route,Total Big Box Route,' +
                            'Hub,Manager On Duty' + '\r\n';
                        for (var i = 0; i < array.length; i++) {
                            var line = '';
                            for (var index in array[i]) {
                                if ($.inArray(index, ['total_system_routes','date','day','week','manager_on_duty_id','warehouse_sorters_id']) == -1) {
                                    if (line != '') line += ','
                                    var data_value = array[i][index]
                                    if (array[i][index] == null) {
                                        data_value = '00:00:00';
                                    }
                                    line += data_value;
                                }
                            }

                            str += line + '\r\n';
                        }

                        return str;
                    },
                    downloadCSV:function ()
                    {
                        var CSV =  inboundData.downloadCSVFunctions.sortCSVData();
                        var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
                        $('.dash_header_wrap').append('<a href="'+uri+'" id="csv-download-temp-btn" style="visibility:hidden" download="Outbound Report-<?php echo $start_date.' To '.$end_date ?>.csv"> TEST </a>');
                        document.getElementById("csv-download-temp-btn").click();
                        setTimeout(function (){
                            $('#csv-download-temp-btn').remove()
                        },100);




                    },

                },
                init: function() {
                    inboundData.collapsible.expandAll();
                    inboundData.loadNextInbound();
                    $('.progress-main-wrap').addClass('show');
                    $('#convert').on('click',function(){
                        inboundData.downloadCSVFunctions.downloadCSV();
                    });
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
    </script>
@endsection
