
@extends( 'backend.layouts.app' )

@section('title', 'Logx E-commerce Dashboard')

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
            appConfig.set('yajrabox.ajax', '{{ route('hub-return-scan-logx-ecommerce.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.scrollx_responsive',true);
            appConfig.set('yajrabox.autoWidth', false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.start_date = jQuery('[name=start_date]').val();
                data.end_date = jQuery('[name=end_date]').val();
                // data.status = jQuery('[name=status]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'sprint_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'tracking_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'route_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'address_line_1', orderable: true, searchable: true, className: 'text-center'},
                {data: 'current_status', orderable: true, searchable: true, className: 'text-center'},
                {data: 'date', orderable: true, searchable: false, className: 'text-center'},
                {data: 'action', orderable: false, searchable: false, className: 'text-center'},
            ]);
        })



        function getTotalOrderData() {
            let selected_date = $('.data-selector').val();
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
                url: "<?php echo URL::to('/'); ?>/logx/e-commerce/totalcards/" + selected_date + "/" + type + "/" +selected_vendor,
                data: {},
                success: function(data)
                {

                    $('#sorted_orders').text(data['boradless_count']['sorted']);

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
            let selected_date = $('.data-selector').val();

            var e = document.getElementById("store-filter");
            var selected_vendor = e.value;
            if (selected_vendor == '' )
            {
                selected_vendor = 'all-vendors';
            }

            window.location.href = href + '/' + selected_date + '/' + selected_vendor;
        });
    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">{{ $title_name }} Hub Return Scan<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
{{--        @include('backend.logxDashboard.logx_cards')--}}
        <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <h2>{{ $title_name }} <small>Hub Return Scan</small></h2>
                            {{--                            @if(can_access_route('new-sort-logx-ecommerce-export.excel',$userPermissoins))--}}
                            {{--                            <div class="excel-btn" style="float: right">--}}
                            {{--                                <a href="{{ route('new-sort-logx-ecommerce-export.excel') }}"--}}
                            {{--                                   class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">--}}
                            {{--                                    Export to Excel--}}
                            {{--                                </a>--}}
                            {{--                            </div>--}}
                            {{--                            @endif--}}
                            <div class="excel-btn" style="float: right">
                                <a href="#"
                                   class="btn buttons-reload buttons-html5 btn-sm btn-danger excelstyleclass">
                                    Reload
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title">
                            <form method="get" action="">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Search By Date :</label>
                                        <input type="date" name="start_date" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['start_date'])?$_GET['start_date']: date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Search By Date :</label>
                                        <input type="date" name="end_date" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['end_date'])?$_GET['end_date']: date('Y-m-d') }}">
                                    </div>
{{--                                    <div class="col-md-3">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>Status:</label>--}}
{{--                                            <select class="form-control js-example-basic-multiple" name="status" id="status">--}}
{{--                                                <option value="all" {{( $status == 'all') ? 'Selected' : ''}}>All</option>--}}
{{--                                                <option value="delivered" {{( $status == 'delivered') ? 'Selected' : ''}}>Delivered</option>--}}
{{--                                                <option value="return" {{( $status == 'return') ? 'Selected' : ''}}>Return</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-md-3">
                                        <button class="btn btn-primary" type="submit" style="       margin-top: 25px;">
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
                                    <th>Tracking #</th>
                                    <th>Route Number</th>
                                    <th>Customer Address</th>
                                    <th>Current Status</th>
                                    <th>Date</th>
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
