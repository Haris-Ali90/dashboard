<?php
$status = array(
    "136" => "Client requested to cancel the order",
    "137" => "Delay in delivery due to weather or natural disaster",
    "118" => "left at back door",
    "117" => "left with concierge",
    "135" => "Customer refused delivery",
    "108" => "Customer unavailable-Incorrect address",
    "106" => "Customer unavailable - delivery returned",
    "107" => "Customer unavailable - Left voice mail - order returned",
    "109" => "Customer unavailable - Incorrect phone number",
    "142" => "Damaged at hub (before going OFD)",
    "143" => "Damaged on road - undeliverable",
    "144" => "Delivery to mailroom",
    "103" => "Delay at pickup",
    "139" => "Delivery left on front porch",
    "138" => "Delivery left in the garage",
    "114" => "Successful delivery at door",
    "113" => "Successfully hand delivered",
    "120" => "Delivery at Hub",
    "110" => "Returned to hub for re-delivery",
    "111" => "Delivery to hub for return to merchant",
    "121" => "Pickup from Hub",
    "102" => "Joey Incident",
    "104" => "Damaged on road - delivery will be attempted",
    "105" => "Item damaged - returned to merchant",
    "129" => "Joey at hub",
    "128" => "Package on the way to hub",
    "140" => "Delivery missorted, may cause delay",
    "116" => "Successful delivery to neighbour",
    "132" => "Office closed - safe dropped",
    "101" => "Joey on the way to pickup",
    "32" => "Order accepted by Joey",
    "14" => "Merchant accepted",
    "36" => "Cancelled by JoeyCo",
    "124" => "At hub - processing",
    "38" => "Draft",
    "18" => "Delivery failed",
    "56" => "Partially delivered",
    "17" => "Delivery success",
    "68" => "Joey is at dropoff location",
    "67" => "Joey is at pickup location",
    "13" => "At hub - processing",
    "16" => "Joey failed to pickup order",
    "57" => "Not all orders were picked up",
    "15" => "Order is with Joey",
    "112" => "To be re-attempted",
    "131" => "Office closed - returned to hub",
    "125" => "Pickup at store - confirmed",
    "61" => "Scheduled order",
    "37" => "Customer cancelled the order",
    "34" => "Customer is editting the order",
    "35" => "Merchant cancelled the order",
    "42" => "Merchant completed the order",
    "54" => "Merchant declined the order",
    "33" => "Merchant is editting the order",
    "29" => "Merchant is unavailable",
    "24" => "Looking for a Joey",
    "23" => "Waiting for merchant(s) to accept",
    "28" => "Order is with Joey",
    "133" => "Packages sorted",
    "55" => "ONLINE PAYMENT EXPIRED",
    "12" => "ONLINE PAYMENT FAILED",
    "53" => "Waiting for customer to pay",
    "141" => "Lost package",
    "60" => "Task failure",
	"145" => "Returned To Merchant",
    "146" => "Delivery Missorted, Incorrect Address",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow'
);

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

@section('title', 'Return To Merchant')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
<style>
    .btn{
        background-color: #C6DD38;
    }

    .note{
        resize: vertical;
    }

    /*Open Css For Button Group*/
    .table-input-with-btn {
        width: 100%;
        display: block;
        margin: 0 auto;
        box-sizing: border-box;
        position: relative;
    }
    .table-input-with-btn input {
        padding-right: 25px;
        width: 100% !important;
        display: block !important;
        margin: 0px;
    }
    .table-input-with-btn button {
        position: absolute;
        right: 0px;
        top: 10px;
        background: none;
        border: none;
        margin: 0px;
        color: #327ab7;
    }
    .form-submit-btn {
        margin-top: 26px;
        width: 100%;
        background-color: #c6dd38;
    }
    .note-para {
        background: #c6dd38;
        padding: 0px 14px 0px 14px;
    }
    .notes-td {
        min-width: 224px;
    }
    .show-notes{
        border-style:none;
        padding: 6px 9px 6px 9px;
    }
    /*Close Css For Button Group*/
</style>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0&libraries=places" type="text/javascript"></script>
    <!-- Custom JavaScript -->
    <script src="{{ backend_asset('js/joeyco-custom-script.js')}}"></script>
@endsection

@section('inlineJS')

    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {
          let datatable =  $('.return-order-datatable').DataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ],
              scrollX: true,   // enables horizontal scrolling,
              scrollCollapse: true,
              /*columnDefs: [
                  { width: '20%', targets: 0 }
              ],*/
              fixedColumns: true,
            });

            //open model for re-scan return Order
            $(document).on('click', '.return-order-to-merchant', function () {

                // getting current element
                let el = $(this);
                var tracking_id = el.attr("data-tracking_id");
                var sprint_id = el.attr("data-sprint-id");
                var id = el.attr('data-id');

                // opening model for re-scan
                $('#return-confirm-model').modal();
                //hiding return submit button
                $('.return-order').hide();
                //hide input error
                $('.input-error').hide();
                // focus input for re-scan
                $('#return-confirm-model').find(".re-scan-tracking-id").focus();

                //setting up inputs
                $('#return-confirm-model').find(".tracking_id").val(tracking_id);
                $('#return-confirm-model').find(".id").val(id);
                $('#return-confirm-model').find(".sp_id").val(sprint_id);

            });

            // validation of re-scan for return
            $(document).on('submit','.return-order-from',function (e) {

                let el = $(this);
                let from_data_unformated  = el.serializeArray();
                let from_data = {};
                console.log(from_data);
                // creating formated form data
                from_data_unformated.forEach(function (value) {
                    from_data[value.name] = value.value;
                });

                // now validating tracking id and scan id are matched
                if(from_data.tracking_id != from_data.re_scan_tracking_id)
                {
                    //show input error
                    $('.input-error').show();
                    // making re-scan input empty
                    $(this).find('.re-scan-tracking-id').val('');
                    $(this).find('.re-scan-tracking-id').focus();
                    return false;
                }
                else
                {
                    //hide input error
                    $('.input-error').hide();
                    // setting re-attempt btn data
                    $('.return-order').attr({"data-id":from_data.id,"data-tracking_id":from_data.tracking_id,"data-sprint_id":from_data.sp_id});
                    //showing return submit button
                    $('.return-order').show();

                }

                return false;
            });

            // model for re-scan inputs empty when it is closed
            $('#return-confirm-model').on('hidden.bs.modal', function (e) {
                // removeing re-attempt btn data
                $('.return-order').attr({"data-id":"","data-tracking_id":""});
                //removing model from input data
                $('#return-confirm-model').find(".tracking_id").val('');
                $('#return-confirm-model').find(".id").val('');
                $('#return-confirm-model').find(".re-scan-tracking-id").val('');
            });

            //Return to Merchant Order
            $(document).on('click', '.return-order', function (e) {
                 //var $form = $(this);
                 let el = $(this);
                 console.log(el);
                 var sprint_id = el.attr("data-sprint_id");
                var tracking_id = el.attr("data-tracking_id");
                 var return_process_id = el.attr('data-id');
                //console.log(sprint_id,return_process_id);
                 $.confirm({
                     title: 'A secure action',
                     content: 'Are you sure you want to return order to merchant?',
                     icon: 'fa fa-question-circle',
                     animation: 'scale',
                     closeAnimation: 'scale',
                     opacity: 0.5,
                     buttons: {
                         'confirm': {
                             text: 'Proceed',
                             btnClass: 'btn-info',
                             action: function () {
                                 //console.log(sprint_id);
                                 var id = sprint_id;
                                 showLoader();
                                 $.ajax({
                                     type: "GET",
                                     url: "{{URL::to('/')}}/Return/order/"+id,
                                    data: {
                                        return_process_id: return_process_id
                                    },
                                    success: function (res) {
                                    	hideLoader();
                                        // checking responce
                                        if (res.status == false) {
                                            ShowSessionAlert('danger', res.message);
                                            return false;
                                        }
                                        // removing tr in data table
                                        datatable.row($('.return-order-to-merchant-'+tracking_id).parents('tr'))
                                            .remove()
                                            .draw();
                                        $('#return-confirm-model').modal('hide');
                                        ShowSessionAlert('success', res.message);

                                    },
                                    error: function (error) {
                                    	hideLoader();
                                    	console.log(error);
                                        ShowSessionAlert('danger', 'Something critical went wrong');
                                    }
                                });
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

        });

    </script>
@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3 class="text-center">Return To Merchant<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Return To Merchant </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <!-- <div class="table-responsive"> -->
                            @include( 'backend.layouts.notification_message' )
                                @if ($message = Session::pull('error'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                            @endif
                                <!--Open Table Tracking Order List-->
                                <table class="table table-striped table-bordered return-order-datatable" data-form="deleteForm">
                                    <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Tracking Id</th>
                                        <th>Route Number</th>
                                        <th style="width: 29%">Customer Address</th>
                                        <th>Customer Phone</th>
                                        <th>Status</th>
                                        <th>Scan At</th>
                                        <th>Process At</th>
                                        <th>Scan By</th>
                                        <th>Verified By</th>
                                        <th>Verified At</th>
                                        <th>Count Of Reattempts Left</th>
                                        <th>Verify Note</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($expiry_order as $expire_order)

                                        <tr>
                                            <td>{{$expire_order->sprint_id}}</td>
                                            <td>{{$expire_order->tracking_id}}</td>
                                            <td>{{$expire_order->route_id}}</td>
                                            <td>{{$expire_order->customer_address.' '.$expire_order->postal_code}}</td>
                                            <td>{{$expire_order->customer_phone}}</td>
                                            <td>@if (isset($status[$expire_order->status_id])) {{ $status[$expire_order->status_id]}} @endif</td>
                                            <td>{{ConvertTimeZone($expire_order->created_at,'UTC','America/Toronto')}}</td>
                                            <td>
                                                @if($expire_order->proceed_at == null || is_null($expire_order->proceed_at) || $expire_order->proceed_at == 'null' )
                                                    <span class="label label-warning">Waiting for completion by routing support</span>
                                                @elseif($expire_order->proceed_at != null || !is_null($expire_order->proceed_at) || $expire_order->proceed_at != 'null' )
                                                    {{ConvertTimeZone($expire_order->proceed_at,'UTC','America/Toronto')}}
                                                @endif
                                            </td>
                                            <td>@if (isset($expire_order->user)) {{$expire_order->user->full_name}}@endif</td>
                                            <td>@if (isset($expire_order->VerifiedByUser)) {{$expire_order->VerifiedByUser->full_name}}@endif</td>
                                            <td>
                                                @if($expire_order->verified_at == null || is_null($expire_order->verified_at) || $expire_order->verified_at == 'null' )
                                                    <span class="label label-warning"></span>
                                                @elseif($expire_order->verified_at != null || !is_null($expire_order->verified_at) || $expire_order->verified_at != 'null' )
                                                    {{ConvertTimeZone($expire_order->verified_at,'UTC','America/Toronto')}}
                                                @endif
                                            </td>
                                            <td>{{$expire_order->reattempt_left}}</td>
                                            <td class="notes-td">
                                                {{$expire_order->varify_note}}
                                                <br>
                                                @if(can_access_route('add-notes',$userPermissoins))
                                                    <a href="{{backend_url('notes/'.$expire_order->id)}}" target="_blank" title="Detail" class="btn btn-primary btn-xs show-notes"><i class="fa fa-tags notes-icon"></i></a>
                                                @endif
                                            </td>
                                            <td>
                                                @if(can_access_route('return-order.update',$userPermissoins))
                                                <button type="submit" class="col-md-12 btn btn-warning btn-sm return-order-to-merchant return-order-to-merchant-{{$expire_order->tracking_id}}" data-id="{{$expire_order->id}}" data-tracking_id="{{$expire_order->tracking_id}}" data-sprint-id="{{$expire_order->sprint_id}}">Return to merchant</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!--Close Table Tracking Order List-->


                            <!-- </div> -->
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

    <!--re-scan-for-return-model-open-->
     <div id="return-confirm-model" class="modal" style="display: none">
         <div class='modal-dialog'>
             <div class='modal-content'>
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Re-scan order for return order </h4>
                 </div>
                 <div class="modal-body">
                     <form action="" class="return-order-from">
                         <input type="hidden" class="tracking_id" name="tracking_id" value="">
                         <input type="hidden" class="id" name="id" value="">
                         <input type="hidden" class="sp_id" name="sp_id" value="">
                         <div class="form-group">
                             <p>Re-scan tracking id for confirmation</p>
                             <input name="re_scan_tracking_id" class="re-scan-tracking-id form-control" value="">
                             <p class="input-error">Scan id does not match, Please re-scan!</p>
                         </div>
                     </form>
                     <div class="form-group">
                         <a type="submit" class="btn btn-success green-gradient  return-order" data-id="" data-tracking_id="" data-sprint_id="" >Reattempt</a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
    <!--re-scan-for-return-model-close-->

@endsection