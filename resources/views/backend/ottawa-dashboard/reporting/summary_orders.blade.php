
@extends( 'backend.layouts.app' )

@section('title', 'Ottawa Orders')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
	<link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"
          integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous"/>
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
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
            integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
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
            appConfig.set('yajrabox.ajax', '{{ route('AtStore-ottawa.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.scrollx_responsive',true);
            appConfig.set('yajrabox.autoWidth', false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.datepicker_start = jQuery('[name=datepicker_start]').val();
                data.datepicker_end = jQuery('[name=datepicker_end]').val();
                data.type = jQuery('[name=type]').val();
                data.store_name = jQuery('select[name=store_name]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'sprint_id', orderable: false, searchable: false},
                {data: 'store_name', orderable: true, searchable: false},
                {data: 'store_address', orderable: false, searchable: false},
                {data: 'address', orderable: false, searchable: false},
                {data: 'tracking_id', orderable: false, searchable: false},
                {data: 'city', orderable: false, searchable: false},
                {data: 'postal_code', orderable: false, searchable: false},
                {data: 'weight', orderable: false, searchable: false},
                {data: 'status_id', orderable: false, searchable: false},
                {data: 'created_at', orderable: false, searchable: false},
                {data: 'updated_at', orderable: false, searchable: false},
            ]);
        })




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



        });


       /* $('.buttons-excel').on('click',function(event){
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
        });*/
        $("#datepicker_start").datetimepicker
        ({
            format: 'YYYY-MM-DD',

        });

        $("#datepicker_end").datetimepicker({
            format: 'YYYY-MM-DD',

        });
        $("#datepicker_start").on("dp.change", function (e) {
            $('#datepicker_end').data("DateTimePicker").minDate(e.date);
        });
        $("#datepicker_end").on("dp.change", function (e) {
            $('#datepicker_start').data("DateTimePicker").maxDate(e.date);
        });
    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">{{ $title_name }} {{ str_replace('_', ' ', $type) }} Orders<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        @include('backend.vancouver_dashboard.vancouver_cards')
            <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{ $title_name }} <small>{{ str_replace('_', ' ', $type) }} Orders</small></h2>
                            {{--@if(can_access_route('new-not-scan-vancouver-export.excel',$userPermissoins))
                            <div class="excel-btn" style="float: right">
                                <a href="{{ route('new-not-scan-vancouver-export.excel') }}"
                                   class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                    Export to Excel
                                </a>
                            </div>
                            @endif--}}
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
                                        <label>From Date:</label>
                                        <input id="select_data_type" type="hidden" name="select_data_type">
                                        <input id="type" type="hidden" name="type" value="{{$type}}">
                                        <input id="datepicker_start" type="text" name="datepicker_start"
                                               class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker_start'])?$_GET['datepicker_start']: date('Y-m-d') }}"
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <label>To Date:</label>
                                        <input
                                                type="text" id="datepicker_end" name="datepicker_end"
                                                class="data-selector form-control" required=""
                                                value="{{ isset($_GET['datepicker_end'])?$_GET['datepicker_end']: date('Y-m-d') }}"
                                        >
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

                            @include( 'backend.layouts.notification_message' )


                            <table class="table table-striped table-bordered yajrabox" id="yajra-reload">
                    <thead stylesheet="color:black;">
                    <tr>
                        <th style="width: 10%">Sprint ID</th>
                        <th style="width: 20%">Store Name</th>
                        <th style="width: 20%">Store Address</th>
                        <th style="width: 20%">Address</th>
                        <th style="width: 12%">Tracking ID</th>
                        <th style="width: 12%">City</th>
                        <th style="width: 12%">Postal Code</th>
                        <th style="width: 12%">Weight</th>
                        <th style="width: 25%">Status</th>
                        <th style="width: 12%">Created At</th>
                        <th style="width: 12%">Updated At</th>
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
