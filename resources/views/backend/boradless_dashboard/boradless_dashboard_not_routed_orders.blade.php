
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
            appConfig.set('yajrabox.ajax', '{{ route('not.routed.orders.list.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.scrollx_responsive',true);
            appConfig.set('yajrabox.autoWidth', false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.hub_id = jQuery('[name=hub_id]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'sprint_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'tracking_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'task_status_id', orderable: true, searchable: true, className: 'text-center'},
                // {data: 'address', orderable: true, searchable: true, className: 'text-center'},
                {data: 'date', orderable: true, searchable: false, className: 'text-center'},
                {data: 'action', orderable: false, searchable: false, className: 'text-center'},
            ]);
        })
    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 class="text-center">{{ $title_name }} Not Routed Orders<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <h2>{{ $title_name }} <small>Not Routed Orders</small></h2>

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
                                    <input type="hidden" name="hub_id" id="hub_id" value="{{ $id }}">
{{--                                    <div class="col-md-3">--}}
{{--                                        <button class="btn btn-primary" type="submit" style="margin-top: 25px;">--}}
{{--                                            Go--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
                                </div>
                            </form>


                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )


                            <table class="table table-striped table-bordered yajrabox">
                                <thead stylesheet="color:black;">
                                <tr>
                                    <th>JoeyCo Order #</th>
                                    <th>Tracking #</th>
                                    <th>Status</th>
{{--                                    <th>Customer Address</th>--}}
                                    <th>Created At</th>
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
