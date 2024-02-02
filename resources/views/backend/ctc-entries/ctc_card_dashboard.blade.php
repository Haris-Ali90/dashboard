@extends( 'backend.layouts.app' )

@section('title', 'CTC Statistics')

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
    <!--  <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')

    <script>
        $(document).ready(function () {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
    <script>

        function getTotalOrderData() {
            let selected_date = $('.data-selector').val();
            let type = $('#type').val();
            // show loader
            $('.total-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/new/ctc/totalcards/" + selected_date + "/" + type,
                data: {},
                success: function (data) {
                    $('#total_orders').text(data['ctc_count']['total']);
                    $('#return_orders').text(data['ctc_count']['return_orders']);
                    $('#sorted_orders').text(data['ctc_count']['sorted']);
                    $('#picked_orders').text(data['ctc_count']['pickup']);
                    $('#delivered_orders').text(data['ctc_count']['delivered_order']);
                    $('#notscan_orders').text(data['ctc_count']['notscan']);
                    $('#reattempted_orders').text(data['ctc_count']['reattempted']);
                    $('#completion_order').text(data['ctc_count']['completion_ratio']);
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
        function getInProgressOrdersData() {
            let selected_date = $('.data-selector').val();
            let type = $('#type').val();
            // show loader
            $('.total-summary').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/new/ctc/inprogress/" + selected_date + "/" + type,
                data: {},
                success: function (data) {
                    $('#sorted_remain').text(data['ctc_inprogess_count']['remaining_sorted']);
                    $('#picked_remain').text(data['ctc_inprogess_count']['remaining_pickup']);
                    $('#route_picked_remain').text(data['ctc_inprogess_count']['remaining_route']);
                    // hide loader
                    $('.total-summary').removeClass('show');
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.total-summary').removeClass('show');
                }
            });
        }

        function getCustomRouteData() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.custom-route').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/new/ctc/customroutecards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#custom_orders').text(data['custom_route']);
                    // hide loader
                    $('.custom-route').removeClass('show');
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.custom-route').removeClass('show');
                }
            });
        }

        function getYesterdayOrderData() {
            let selected_date = $('.data-selector').val();
            // show loader
            $('.yesterday-order').addClass('show');
            $.ajax({
                type: "GET",
                url: "<?php echo URL::to('/'); ?>/new/ctc/yesterdaycards/" + selected_date,
                data: {},
                success: function (data) {
                    $('#yesterday_orders').text(data['yesterday_return_orders']);
                    // hide loader
                    $('.yesterday-order').removeClass('show');
                },
                error: function (error) {
                    console.log(error);
                    // hide loader
                    $('.yesterday-order').removeClass('show');
                }
            });
        }


        setTimeout(function () {
            getTotalOrderData();
            getInProgressOrdersData();
            getCustomRouteData();
            getYesterdayOrderData();
        }, 1000);


        $('.buttons-reload').on('click', function (event) {
            event.preventDefault();

            // updating cards data
            getTotalOrderData();
            getInProgressOrdersData();
            getCustomRouteData();
            getYesterdayOrderData();


        });

    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">CTC Statistics
                        <small></small>
                    </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                <small>CTC Statistics</small>
                            </h2>

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
                                        <input type="date" name="datepicker" class="data-selector form-control"
                                               required=""
                                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
                                        <input type="hidden" name="type" value="all" id="type">
                                    </div>


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

                            @include('backend.ctc-entries.ctc_cards')
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection