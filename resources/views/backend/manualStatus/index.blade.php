<?php

$user = Auth::user();
if ($user->email != "admin@gmail.com") {

    $data = explode(',', $user['rights']);
    $dataPermission = explode(',', $user['permissions']);
} else {
    $data = [];
    $dataPermission = [];
}

?>
@extends( 'backend.layouts.app' )

@section('title', 'Manual Status History')

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

        $(function () {
            appConfig.set('yajrabox.ajax', '{{ route('manual-status.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            //appConfig.set('yajrabox.scrollx_responsive',true);
            appConfig.set('yajrabox.autoWidth',false);
            appConfig.set('dt.searching',true);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.tracking_id = jQuery('[name=tracking_id]').val();
                data.filter_custom = jQuery('[name=filter_custom]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'tracking_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'status_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'attachment_path', orderable: false, searchable: false, className: 'text-center'},
                {data: 'reason_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'user_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'route_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'plan_id', orderable: true, searchable: true, className: 'text-center'},
                {data: 'domain', orderable: false, searchable: false, className: 'text-center'},
                {data: 'created_at', orderable: false, searchable: false, className: 'text-center'}
            ]);
        })

    </script>
@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3 class="text-center">Manual Status History<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        {{-- @include('backend.ctc.ctc_new_cards')--}}
        <!--Count Div Row Close-->
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Manual Status History<small></small></h2>



                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                            <form method="get" action="">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4">
                                       <div class="form-group">
                                       <label>Search By Tracking ID</label>
                                        <input type="text" name="tracking_id" class="form-control"
                                               value="{{ isset($_GET['tracking_id'])?$_GET['tracking_id']: "" }}"
                                               placeholder="Tracking Id">
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <div class="form-group">
                                        <label>Select Option</label>
                                        <select name="filter_custom" id="filter_custom" class="form-control select2">
                                            <option value="">Select an option</option>
                                            <option value="dispatch" {{( $custom_filteration == 'dispatch') ? 'Selected' : ''}}>Dispatch</option>
                                            <option value="plan" {{( $custom_filteration == 'plan') ? 'Selected' : ''}}>Plans</option>
                                            <option value="tracking_ids" {{( $custom_filteration == 'tracking_ids') ? 'Selected' : ''}}>Tracking Ids</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary c-btn" type="submit" style=" margin-top: 27  px;">
                                            Go</a> </button>
                                    </div>
                                </div>



                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered yajrabox" id="yajra-reload">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <th class="text-center " style="width: 10%">Tracking #</th>
                                        <th class="text-center "style="width: 20%">Status</th>
                                        <th class="text-center "style="width: 10%">Image</th>
                                        <th class="text-center "style="width: 20%">Reason</th>
                                        <th class="text-center "style="width: 15%">User</th>
                                        <th class="text-center "style="width: 5%">Route Id</th>
                                        <th class="text-center "style="width: 5%">Plan Id</th>
                                        <th class="text-center "style="width: 10%">Domain</th>
                                        <th class="text-center "style="width: 10%">Created At</th>


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
    </div>
    <!-- /#page-wrapper -->

    <!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

    <!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

    <!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->



@endsection