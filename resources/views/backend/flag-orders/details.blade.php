@extends( 'backend.layouts.app' )



@section('title', 'Flag Order Detail')

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
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".group1").colorbox({height: "75%"});
        });
    </script>

@endsection



@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Flagged Order Details</h3>
                </div>


            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Flagged Order Details <small></small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <!--detrail table box open-->
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th colspan="2" >Joey Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="width: 30%;"><label>Full Name</label></td>
                                        <td>{{$JoeyPerformanceHistory->joeyName->FullName or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Email</label></td>
                                        <td>{{$JoeyPerformanceHistory->joeyName->email  or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Phone</label></td>
                                        <td>{{$JoeyPerformanceHistory->joeyName->phone or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Address</label></td>
                                        <td>{{$JoeyPerformanceHistory->joeyName->address or "N/A"}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--detrail table box close-->

                            <!--detrail table box open-->
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th colspan="2" >Order Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="width: 30%;"><label>Tracking ID </label></td>
                                        <td>{{$JoeyPerformanceHistory->tracking_id or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>JoeyCo Order # </label></td>
                                        <td>{{$JoeyPerformanceHistory->sprint_id  or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Merchant Order #</label></td>
                                        <td>{{(isset($JoeyPerformanceHistory->MerchantidsByTrackingID->merchant_order_num)) ? $JoeyPerformanceHistory->MerchantidsByTrackingID->merchant_order_num : "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Contact # / Address</label></td>
                                        <td>{{(isset($JoeyPerformanceHistory->MerchantidsByTrackingID->Task->taskContact->phone)) ? $JoeyPerformanceHistory->MerchantidsByTrackingID->Task->taskContact->phone: "N/A"}} / {{(isset($JoeyPerformanceHistory->MerchantidsByTrackingID->Task->task_Location->address)) ? $JoeyPerformanceHistory->MerchantidsByTrackingID->Task->task_Location->address: "N/A"}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--detrail table box close-->

                            <!--detrail table box open-->
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th colspan="2" >Applied Values By Flag</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="width: 20%;"><label>Flag Category Name</label></td>
                                        <td>{{$JoeyPerformanceHistory->flag_cat_name or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Flagged By</label></td>
                                        <td>{{$JoeyPerformanceHistory->flagByName->full_name  or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Flagged From</label></td>
                                        <td>{{$JoeyPerformanceHistory->portal_type or "N/A"}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Approved Date</label></td>
                                        <td>{{ConvertTimeZone($JoeyPerformanceHistory->created_at)}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Category Value Applied</label></td>
                                        <td>{{ str_replace("_"," ",$JoeyPerformanceHistory->incident_value_applied)}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Finance Value Applied</label></td>
                                        <td>{{ $JoeyPerformanceHistory->JosnValuesDecode('finance')['operator'].''.$JoeyPerformanceHistory->JosnValuesDecode('finance')['value']}}</td>
                                    </tr>
                                    <tr>
                                        <td><label>Rating Value Applied</label></td>
                                        <td>{{ $JoeyPerformanceHistory->JosnValuesDecode('rating')['operator'].''.$JoeyPerformanceHistory->JosnValuesDecode('rating')['value']}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--detail table box close-->

                            <!--joey flag detail open-->
                            <div class="x_title">
                                <h2>All Approved Flagged Order History "{{$JoeyPerformanceHistory->joeyName->FullName}}" <small></small></h2>

                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table table-striped table-bordered flag-orders-table" >
                                    <thead>
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th>Tracking ID</th>
                                        <th>Order NO</th>
                                        <th>Flag Category Names</th>
                                        <th>Flag From</th>
                                        <th>Flag By</th>
                                        <th>Flagged Date</th>
                                        <th>Category Value Applied</th>
                                        <th>Finance Value Applied</th>
                                        <th>Rating Value Applied</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($AllFlagsOrderJoeys as $key => $FlagOrder)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$FlagOrder->tracking_id}}</td>
                                            <td>{{$FlagOrder->sprint_id}}</td>
                                            <td>{{$FlagOrder->flag_cat_name}}</td>
                                            <td>{{$FlagOrder->portal_type}}</td>
                                            <td>{{$FlagOrder->flagByName->full_name}}</td>
                                            <td>{{ConvertTimeZone($FlagOrder->created_at)}}</td>
                                            <td>{{str_replace("_"," ",$FlagOrder->incident_value_applied)}}</td>
                                            <td>{{$FlagOrder->JosnValuesDecode('finance')['operator'].''.$FlagOrder->JosnValuesDecode('finance')['value']}}</td>
                                            <td>{{$FlagOrder->JosnValuesDecode('rating')['operator'].''.$FlagOrder->JosnValuesDecode('rating')['value']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--joey flag detail close-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection