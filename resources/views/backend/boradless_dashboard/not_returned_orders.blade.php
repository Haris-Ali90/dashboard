
@extends( 'backend.layouts.app' )

@section('title', 'Toronto Returns Not Received At Hub')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')

    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
    <script>

        $(function () {
            var check = 1;
            var startDate = document.getElementById("start_date").value;
            var endDate = document.getElementById("end_date").value;
            var CurrentDate = new Date();
            var newstartDate = new Date(startDate);
            var newendDate = new Date(endDate);
            var diffDays =    (diffDays = (newendDate.getTime() - newstartDate.getTime()) / (1000 * 3600 * 24))+1;
            if(newstartDate > CurrentDate || newendDate > CurrentDate){
                check =0;
                alert('Given date range is greater than today . Please select valid dates.');
            }
            else if ((Date.parse(startDate) > Date.parse(endDate))) {
                check =0;
                alert("To date should be greater than or equal to From date");
            }
            else if (diffDays > 15) {
                check =0;
                alert("Maximum date range is 15 days.");
            }
            if(check == 0){
                document.getElementById("start_date").value = CurrentDate.toISOString().slice(0, 10);
                document.getElementById("end_date").value = CurrentDate.toISOString().slice(0, 10);
            }
            appConfig.set('yajrabox.ajax', '{{ route('new-notreturned-borderless.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.scrollx_responsive',true);
            appConfig.set('yajrabox.autoWidth', false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.datepicker_start = jQuery('[name=datepicker_start]').val();
                data.datepicker_end = jQuery('[name=datepicker_end]').val();
                data.store_name = jQuery('select[name=store_name]').val();

            });

            appConfig.set('yajrabox.columns', [
                {data: 'sprint_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'route_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'joey_name', orderable: true, searchable: true,className: 'text-center'},
                {data: 'address_line_1', orderable: true, searchable: true, className: 'text-center'},
                {data: 'picked_up_at', orderable: true, searchable: false, className: 'text-center'},
                {data: 'sorted_at', orderable: true, searchable: false, className: 'text-center'},
                {data: 'returned_at', orderable: true, searchable: true,className: 'text-center'},
               // {data: 'delivered_at', orderable: true, searchable: true,className: 'text-center'},
                {data: 'order_image', orderable: false, searchable: false, className: 'text-center'},
                {data: 'hub_return_scan', orderable: true, searchable: true,className: 'text-center'},
                {data: 'tracking_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'task_status_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'action', orderable: false, searchable: false, className: 'text-center'},
            ]);
        })


        function getTotalOrderData() {
            // let selected_date = $('.data-selector').val();
            var startDate = document.getElementById("start_date").value;
            var endDate = document.getElementById("end_date").value;
            let type = $('#type').val();
            var e = document.getElementById("store-filter");
            var selected_vendor = e.value;


            if (selected_vendor == '' )
            {
                selected_vendor = 'all-vendors';
            }
            // show loader
            $('.total-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/toronto/totalcards/" + startDate + "/" + endDate + "/" + type + "/" +selected_vendor,

                data: {},
                success: function(data)
                {
                    $('#return_orders').text(data['boradless_count']['return_orders']);
                    $('#hub_return_scan').text(data['boradless_count']['hub_return_scan']);
                    $('#hub_not_return_scan').text(data['boradless_count']['return_orders'] - data['boradless_count']['hub_return_scan']);
                    $('#reattempted_orders').text(data['boradless_count']['reattempted_orders']);
                    $('#re-delivery_orders').text(data['boradless_count']['re-delivery_orders']);
                    // hide loader
                    $('.total-order').removeClass('show');
                },
                error:function (error) {
                    console.log(error);
                    // hide loader
                    $('.total-order').removeClass('show');
                }
            });
        }


        setTimeout(function(){
            getTotalOrderData();

        }, 1000);


        $('.buttons-reload').on('click',function(event){
            event.preventDefault();
            // show main loader
            showLoader();

            // update data table data
            var ref = $('#yajra-reload').DataTable();
            ref.ajax.reload(function(){
                // hide loader
                hideLoader()
            });

            // updating cards data
            getTotalOrderData();



        });


        $('.buttons-excel').on('click', function (event) {
            event.preventDefault();
            let href = $(this).attr('href');
            //let selected_date = $('.data-selector').val();
            var e = document.getElementById("store-filter");
            var selected_vendor = e.value;
            if (selected_vendor == '' )
            {
                selected_vendor = 'all-vendors';
            }
            var startDate = document.getElementById("start_date").value;
            var endDate = document.getElementById("end_date").value;
            window.location.href = href + '/' + startDate + "/" + endDate + '/' + selected_vendor;
        });
    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <!-- <h3 class="text-center">{{ $title_name }} Returns Not Received At Hub<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        @include('backend.boradless_dashboard.boradless_cards')
            <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ $title_name }} Returns Not Received At Hub<small></small></h2>
                            {{--@if(can_access_route('new-notreturned-borderless-export.excel',$userPermissoins))
                            <div class="excel-btn" style="float: right">
                                <a href="{{ route('new-notreturned-borderless-export.excel') }}"
                                   class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                    Export to Excel
                                </a>
                            </div>
                            @endif--}}
                            @if(can_access_route('new-notreturned-borderless-tracking-export.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right">
                                    <a href="{{ route('new-notreturned-borderless-tracking-export.excel') }}"
                                       class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass c-btn">
                                        Export Tracking List
                                    </a>
                                </div>
                            @endif
                           {{-- <div class="excel-btn" style="float: right">
                                <a href="#"
                                   class="btn buttons-reload buttons-html5 btn-sm btn-danger excelstyleclass">
                                    Reload
                                </a>
                            </div>--}}
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title">
                            <form method="get" action="">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Start Date :</label>
                                        <input type="date" id="start_date" name="datepicker_start" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker_start'])?$_GET['datepicker_start']: date('Y-m-d') }}">
                                       
                                    </div>
                                    <div class="col-md-2">
                                        <label>End Date :</label>
                                        <input type="date" id="end_date" name="datepicker_end" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker_end'])?$_GET['datepicker_end']: date('Y-m-d') }}">
                                        <input type="hidden" name="type" value="return" id="type">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Search Store Name</label>
                                            <select class="form-control js-example-basic-multiple" name="store_name" id="store-filter">
                                                <option value="">Select Store Name</option>
                                                <option value="borderless_vendors" {{ ($selectVendor=='borderless_vendors')?'selected': '' }}>Border Less</option>
                                                <option value="ctc_vendors" {{ ($selectVendor=='ctc_vendors')?'selected': '' }}>CTC Store</option>
                                                <option value="darwynn_ltd" {{ ($selectVendor=='darwynn_ltd')?'selected': '' }}>Darwynn Ltd</option>
                                                <option value="logx" {{ ($selectVendor=='logx')?'selected': '' }}>Logx</option>
                                                <option value="shiphero_vendors" {{ ($selectVendor=='shiphero_vendors')?'selected': '' }}>Ship Hero</option>
                                                <option value="walmart_vendors" {{ ($selectVendor=='walmart_vendors')?'selected': '' }}>Walmart</option>
                                                <option value="wild_fork" {{ ($selectVendor=='wild_fork')?'selected': '' }}>Wild Fork</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary c-btn" type="submit" style="       margin-top: 25px;">
                                            Go
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )


                                <table class="table table-striped table-bordered yajrabox" id="yajra-reload">
                    <thead stylesheet="color:black;">
                    <tr>
                        <th>JoeyCo Order #</th>
                        <th>Route Number</th>
                        <th>Joey</th>
                        <th>Customer Address</th>
                        <th>Out For Delivery</th>
                        <th>Sorted Time</th>
                        <th>Joey Returned Scan</th>
                        {{--<th>Actual Arrival @ CX</th>--}}
                        <th>Image</th>
                        <th>Hub Returned Scan</th>
                        <th>Tracking #</th>
                        <th>Status</th>
                        <th>Action</th>
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
    </div>
    <!-- /#page-wrapper -->

@endsection