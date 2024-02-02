<?php
use App\JoeyRoutes;
use Illuminate\Http\Request;
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
    "110" => "Delivery to hub for re-delivery",
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
    "145" => 'Returned To Merchant',
    "146" => "Delivery Missorted, Incorrect Address",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
$route_status = [];
foreach ($route as $rec)
{
    $route_status[] = $rec->status_id;
}

?>
@extends( 'backend.layouts.app' )

@section('title', 'Ottawa Route Hub ')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 10px;
            display: none;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .pageloader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('images/yourLoaderImage.gif') 50% 50% no-repeat rgb(249, 249, 249);
            opacity: .8;
        }
    </style>
    <style>
        .green-gradient, .green-gradient:hover {
            color: #fff;
            background: #bad709;
            background: -moz-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#bad709), color-stop(100%,#afca09));
            background: -webkit-linear-gradient(top, #bad709 0%,#afca09 100%);
            background: linear-gradient(to bottom, #bad709 0%,#afca09 100%);
        }
        .black-gradient,
        .black-gradient:hover {
            color: #fff;
            background: #535353;
            background: -moz-linear-gradient(top,  #535353 0%, #353535 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#535353), color-stop(100%,#353535));
            background: -webkit-linear-gradient(top,  #535353 0%,#353535 100%);
            background: linear-gradient(to bottom,  #535353 0%,#353535 100%);
        }

        .red-gradient,
        .red-gradient:hover {
            color: #fff;
            background: #da4927;
            background: -moz-linear-gradient(top,  #da4927 0%, #c94323 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#da4927), color-stop(100%,#c94323));
            background: -webkit-linear-gradient(top,  #da4927 0%,#c94323 100%);
            background: linear-gradient(to bottom,  #da4927 0%,#c94323 100%);
        }

        .orange-gradient,
        .orange-gradient:hover {
            color: #fff;
            background: #f6762c;
            background: -moz-linear-gradient(top,  #f6762c 0%, #d66626 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f6762c), color-stop(100%,#d66626));
            background: -webkit-linear-gradient(top,  #f6762c 0%,#d66626 100%);
            background: linear-gradient(to bottom,  #f6762c     0%,#d66626 100%);
        }

        .btn{
            font-size : 12px;
        }
        #transfer span.select2.select2-container.select2-container--default {
            width: 75%!important;
        }
        .modal.fade {
            opacity: 1
        }

        .modal-header {
            font-size: 16px;
        }

        .modal-body h4 {
            background: #f6762c;
            padding: 8px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #fff;
        }

        .form-control {
            display: block;
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        }

        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
        }

        .form-group {
            margin-bottom: 15px;
        }

        div#transfer .modal-content {
            padding: 20px;
        }
        /*!*hoverable dropdown css*!*/
        .hoverable-dropdown-main-wrap {
            display: block;
            position: relative;
            box-sizing: border-box;
            margin: 0px 0px 0px 20px;
            width: 100%;
            padding: 0px;
        }
        .hoverable-dropdown-main-ul {
            display: inline-block;
        }
        .hoverable-dropdown-main-wrap ul
        {
            list-style: none;
            box-sizing: border-box;
            padding: 0px;
            margin: 0px;
        }
        .hoverable-dropdown-main-wrap ul li
        {
            box-sizing: border-box;
            cursor: pointer;
            position: relative;
            background: #f6f6f6;
            padding: 8px;
            width: 210px;
            margin: 1px 0;
            padding-right: 25px;
        }
        .hoverable-dropdown-ul
        {
            display: none;
        }
        /*.hoverable-dropdown-main-wrap  ul:hover*/
        /*{*/
        /*    display: block;*/
        /*}*/
        .hoverable-dropdown-main-ul > li:hover > .hoverable-dropdown-ul {
            display: block !important;
            z-index: 10;
            position: absolute;
            top: -1px;
            left: 100%;
            padding: 0% 0px 0px 5px;
        }
        .hoverable-dropdown-ul > li:hover > .hoverable-dropdown-ul {
            display: block !important;
            z-index: 10;
            position: absolute;
            /*top: -1px;*/
            top: -1px;
            left: 100%;
            padding: 0% 0px 0px 5px;
        }
        /*.hoverable-dropdown-main-wrap ul li:hover ul*/
        /*{*/
        /*    display: block;*/
        /*    z-index: 10;*/
        /*    position: absolute;*/
        /*    top: -1px;*/
        /*    left: 100%;*/
        /*    padding: 0% 0px 0px 5px;*/
        /*}*/
        /*.hoverable-dropdown-main-wrap ul > li:hover*/
        /*{*/
        /*    background: #ccc;*/
        /*}*/
        .hoverable-dropdown-main-ul .fa-angle-right {
            position: absolute;
            right: 10px;
        }

    </style>

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->

@endsection

@section('inlineJS')
    <!-- <script type="text/javascript">
   $( function() {
    $( "#datepicker" ).datepicker({changeMonth: true,
      changeYear: true, showOtherMonths: true,
      selectOtherMonths: true}).attr('autocomplete','off');
  } );
  </script> -->

    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').DataTable({
                "lengthMenu": [ 50,100, 250, 500, 750, 1000]
            });

            $(".group1").colorbox({height:"50%",width:"50%"});

            $(document).on('click', '.status_change', function(e){
                var Uid = $(this).data('id');

                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to change user status??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    btns: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {

                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo URL::to('/'); ?>/api/changeUserStatus/"+Uid,
                                    data: {},
                                    success: function(data)
                                    {
                                        if(data== '0' || data== 0 )
                                        {
                                            var DataToset = '<btn type="btn" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Blocked</btn>';
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                        else
                                        {
                                            var DataToset = '<btn type="btn" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Active</btn>'
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
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

            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete user ??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    btns: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                $form.submit();
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

            //Call to open modal
            $(document).on('click', '.createFlag', function (e) {
                // getting data from button and send to model
                let passing_data = $(this).attr("data-flag_values");

                // sending request to get data
                getDataOrderFlagHistory(passing_data);

            });

            // get data of flag history and show on model
            function getDataOrderFlagHistory(data) {
                //show loader
                showLoader();

                let parse_data =  JSON.parse(data);

                $.ajax({
                    type: "POST",
                    url: "{{URL::to('/newottawa/route-details/flag-history-model-html-render')}}",
                    data:parse_data,
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function (response) {
                        hideLoader();
                        // showing model and getting el of model
                        let model_el= $('#create-flag-modal').modal();
                        // appending html of flag history
                        model_el.find('.model-flag-append-html-main-wrap').html(response.html);
                        // setting data to model
                        $('#model_flag_data').val(data);

                    },
                    error: function (error) {
                        hideLoader();
                        ShowSessionAlert('danger', 'Something wrong');
                        console.log(error);
                    }
                });



            }

            //Create flag
            $(document).on('click','.can-apply-flag',function (e) {

                e.preventDefault();
                let el = $(this);
                //let child_flag_id = el.val();
                let child_flag_id = el.attr("data-id");
                // let order_data = JSON.parse($('#flag_data').val());
                let order_data = JSON.parse($('#model_flag_data').val());

                //getting previous flagged category count
                let previous_flagged_cat_count = $('.flag-tr-cat-bunch-'+child_flag_id).length;
                //let total_flag_cat_count = $('.flag-tr').length;
                console.log(previous_flagged_cat_count)
                // checking child data exist
                if(child_flag_id == '')
                {
                    return false;
                }

                //multiple flagged errors
                let flagged_errors ={
                    1:"This order is flagged 2nd time, would you like to re-flag this order",
                    2:"This order is flagged 3rd time, would you like to re-flag this order",
                    3:"This order is flagged 4th time, would you like to re-flag this order",
                    4:"The joey of this order has been terminated already",
                };

                if(previous_flagged_cat_count >= 4) // this block check the total flag orders count
                {
                    var confirmatoin = alert(flagged_errors[4]);
                    if(!confirmatoin)
                    {
                        location.reload();
                        return ;
                    }
                }
                if(previous_flagged_cat_count in flagged_errors) // this block check the order is already flagged or not
                {
                    var confirmatoin = confirm(flagged_errors[previous_flagged_cat_count]);
                    if(!confirmatoin)
                    {
                        return ;
                    }

                }
                $.confirm({
                    title: 'Confirmation',
                    content: 'Are you sure you want to create flag?',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                showLoader();
                                $.ajax({
                                    type: "GET",
                                    url: "{{URL::to('/')}}/flag/create/"+child_flag_id,
                                    data: order_data,
                                    success: function (response) {
                                        hideLoader();
                                        if (response.status == true) // notifying user  the update is completed
                                        {
                                            ShowSessionAlert('success', response.message);
                                            $('#create-flag-modal').modal('hide');

                                        }
                                        else // update  failed by server
                                        {
                                            $('#create-flag-modal').modal('hide');
                                            // show session alert
                                            ShowSessionAlert('danger', response.message);
                                        }

                                    },
                                    error: function (error) {
                                        hideLoader();
                                        ShowSessionAlert('danger', 'Something wrong');
                                        $('#create-flag-modal').modal('hide');
                                        console.log(error.responseText);
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

@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Route Detail<small></small></h3>
                </div>
            </div>
            {{-- <button class="transfer-but transfer btn orange-gradient" disabled>Transfer</button>--}}
            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">



                    <div class="x_panel">

                        <div class="x_title">
                            <h2>Ottawa <small>Route Detail</small></h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                            <form method="get" action="">
                                <label>Tracking Id</label>
                                <select class="js-example-basic-multiple col-md-4 col-sm-4 col-xs-4" name="tracking-id">
                                    <option value=""> Select Tracking ID </option>
                                    @foreach( $route as $trackinid )
                                        <option value="{{ $trackinid->tracking_id }}" {{ ($trackinid->tracking_id ==  $tracking_id)?'selected': '' }}> {{ $trackinid->tracking_id }}</option>
                                    @endforeach
                                </select>
                                <label>Status</label>
                                <select class="js-example-basic-multiple col-md-4 col-sm-4 col-xs-4" name="status">
                                    <option value=""> Select Status </option>
                                    @foreach( $status as $key=>$statusrecord )
                                        <?php if(in_array($key,$route_status)){?>
                                        <option value="{{ $key }}" {{ ($key ==  $status_select)?'selected': '' }}> {{ $statusrecord }}</option>
                                        <?php }?>
                                    @endforeach
                                </select>
                                {{--<input type="date" name="datepicker" class="data-selector" required=""--}}
                                {{--value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"--}}
                                {{--placeholder="Search">--}}
                                <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                                    Go</a> </button>
                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        {{--   <th><input class='check' type='checkbox' name='check' id="checkAll"></th>--}}
                                        {{-- <th>Id</th>
                                         <th>Task Id</th>
                                         <th>Sprint Id</th>--}}
                                        <th>Tracking Id</th>
                                        <th>Route Label</th>
                                        <th>Ordinal</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone</th>
                                        {{--<th>Delivery Window</th>
                                        <th>Duration</th>--}}
                                        <th>Delivery Time</th>
                                        <th>Address</th>
                                        <th>Distance</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    date_default_timezone_set('America/Toronto');

                                    $i=1;
                                    foreach($route as $routeLoc) {
                                    if ($routeLoc->taskSprintConfirmation){
                                        $image_url =  '<img onclick="ShowLightBox(this);"  src="'.$routeLoc->taskSprintConfirmation->attachment_path.'" style="width: 80px;
    height: 80px;" alt="'.$routeLoc->tracking_id.'"/>';
                                    }else{
                                        $image_url = '';
                                    }
                                    echo "<tr>";
                                    /*echo "<td><input class='check' id='check' type='checkbox' name='check' value='".$routeLoc->id."'></td>";*/
                                    /* echo "<td>".$i."</td>";
                                     echo "<td>".$routeLoc->task_id."</td>";
                                     echo "<td>CR-".$routeLoc->sprint_id."</td>";*/
                                    echo "<td>".$routeLoc->tracking_id."</td>";
                                    echo "<td>R-".$routeLoc->route_id."</td>";
                                    echo "<td>".$routeLoc->ordinal."</td>";
                                    echo "<td>".$routeLoc->name."</td>";
                                    echo "<td>".$routeLoc->phone."</td>";
                                    /* echo "<td>".$routeLoc->merchant_order_num."</td>";
                                     echo "<td>".$routeLoc->arrival_time."-".$routeLoc->finish_time."</td>";


                                     echo"<td>";
                                     if ($i==1) {
                                        $firstfinish = $routeLoc->finish_time;
                                        echo"0";
                                     }
                                     else
                                     {
                                         $date1 = new DateTime("2020-01-01 ".$firstfinish.":00");
                                         $date2 = new DateTime("2020-01-01 ".$routeLoc->arrival_time.":00");
                                         $interval = $date1->diff($date2);

                                         echo $interval->format("%H:%I:%S");
                                         $firstfinish = $routeLoc->finish_time;

                                     }
                                     "</td>";*/
                                    $delivery_time = \App\SprintTaskHistory::where('sprint_id',$routeLoc->sprint_id)
                                        ->select((\Illuminate\Support\Facades\DB::raw('MAX(CASE WHEN status_id IN (17,113,114,116,117,118,132,138,139,144,101,102,103,104,105,106,107,108,109,110,111,112,131,135,136) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();
                                    $delivery  = $delivery_time?$delivery_time->delivery_time:'';
                                    echo "<td>".$delivery."</td>";
                                    echo "<td>".$routeLoc->address.','.$routeLoc->postal_code."</td>";
                                    echo "<td>".round($routeLoc->distance/1000,2)."km</td>";
                                    echo "<td>".$image_url."</td>";
                                    echo "<td>".$status[$routeLoc->status_id]."</td>";
                                    echo "<td>"?>
                                    @if(can_access_route('newottawa-route-info.getNotes',$userPermissoins))
                                        @if ($joey != null)
                                            <button type="button" class='btn btn-warning btn-xs' onclick="showAddNoteModal('{{$routeLoc->tracking_id}}','{{$routeLoc->route_id}}')">Notes</button>
                                        @endif
                                    @endif
                                    @if(can_access_route('newottawainfo_route.detail',$userPermissoins))
                                        <a href="{{backend_url('newottawa/route/orders/trackingid/'.$routeLoc->sprint_id.'/details')}}" target='_blank' class='btn btn-warning btn-xs'>Detail</a>

                                    @endif
                                    @if(can_access_route(['flag.create','un-flag'],$userPermissoins))
                                    <button
                                            data-flag_values='{"tracking_id":"{{$routeLoc->tracking_id}}","joey_id":"0","route_id":"{{$routeLoc->route_id}}","sprint":"{{$routeLoc->sprint_id}}","flag_type":"order","vendor_id":"{{$routeLoc->creator_id}}","hub_id":"{{$hub_id}}"}'
                                            data-sprint_id="{{$routeLoc->sprint_id}}"
                                            class='btn btn-warning btn-xs createFlag' >
                                        Mark Flag
                                    </button>
                                    @endif
                                    <?php echo "</td>";
                                    echo "</tr>";
                                    $i++;
                                    }  ?>

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

    <!--model-for-flagged-open-->
    <div class="modal fade" id="create-flag-modal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Flag</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 hoverable-dropdown-main-wrap">
                                <input type="hidden" id="model_flag_data" value=''>

                                <!--model-append-html-main-wrap-open-->
                                <div class="col-sm-12 model-flag-append-html-main-wrap">

                                </div>
                                <!--model-append-html-main-wrap-close-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!--model-for-flagged-close-->
    <!-- Modal for notes -->
    <div id="myNotesModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Notes</h4>
                </div>
                <div class="modal-body">
                    <div id="appendNotes" style="max-height: 400px;overflow-y: scroll;border: 1px solid #000;
    padding: 4px;">

                    </div>
                    <br>
                    <div>
                        <form method="post" action="" id="addNotesForm">
                            {!! csrf_field() !!}
                            <input type="hidden" name="routeId" id="routeId">
                            <input type="hidden" name="tracking_id" id="tracking_id">
                            <textarea style="resize: vertical;" placeholder="Add Note...." class="form-control" name="note" id="note"  rows="2"></textarea>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="sendNote()">Add Note</button>
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                </div>
            </div>

        </div>
    </div>
    <!-- Modal for notes -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

        function showAddNoteModal(t_id,r_id) {
            $('#appendNotes').html('');
            $('#myNotesModal #tracking_id').val(t_id);
            $('#myNotesModal #routeId').val(r_id);
            $('#myNotesModal').modal('show');
            $.ajax({
                type:'GET',
                url:'{{route("newottawa-route-info.getNotes")}}',
                data: {tracking_id:t_id},
                success: function (res) {
                    //    console.log(res.length);
                    //    console.log(res);
                    if(res.length > 0){
                        res.forEach(appendNotes);
                    }

                }
            });
        }
        function appendNotes(item, index) {
            var data='';

            // console.log(item.type);
            if(item.type=="dashboard"){
                data=`<div style="text-align: right;"><p style="margin:0;padding-right: 8px;font-size: 10px;"> ${item.dashboard.full_name}</p></div>
                <div style="text-align: right; background: lightgrey;  color: black; border-radius: 8px;padding: 10px;"><p style="margin:0;padding-right: 8px;">${item.note}.</p></div>
                <div style="text-align: right;"><p style="margin:0;padding-right: 8px;font-size: 10px;">${item.created_at}</p></div>
                <br>`;
            }
            else if(item.type=="joey"){
                data=`<div style="text-align: left;"><p style="margin:0;padding-left: 8px;font-size: 10px;"> ${item.joey.first_name} ${item.joey.last_name}</p></div>
                <div style="text-align: left; background: #f5f0f0;  color: black; border-radius: 8px;padding: 10px;"><p style="margin:0;padding-left: 8px;">${item.note}.</p></div>
                <div style="text-align: left;"><p style="margin:0;padding-left: 8px;font-size: 10px;">${item.created_at}</p></div>
                <br>`;
            }
            $("#appendNotes").append(data);
        }
        function sendNote() {
            if($('#myNotesModal #note').val()==''){
                alert('Note is required.');
            }
            else{
                var formData= $('#addNotesForm').serialize();
                $("#pageloader").attr("class", "pageloader");
                $.ajax({
                    type:'POST',
                    url:'{{route("newottawa-route-info.addNote")}}',
                    data: formData,
                    // contentType: false,
                    // processData: false,
                    success: function (res) {

                        $('#myNotesModal').modal('hide');
                        $("#pageloader").removeClass("pageloader");
                        // alert('Note added successfully!.');
                        swal("¡Success!", "Note added successfully!", "success");
                    }
                });
                $('#myNotesModal #note').val('');

            }
        }

        $(document).ready(function()
        {
            $('#route').select2();
        });


        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            // $('.transfer').prop('disabled', true);
        });


        var locs = [];
        $(document).on('click', '.transfer', function(e) {

            e.preventDefault();

            $.each($("input[name='check']:checked"), function(){
                locs.push($(this).val());
            });

            $('#locs').html(locs.length);
            $('#transfer').modal();

            return false;

        });

        $(document).on('click', '.check', function(e) {
            var checked = $(this).prop('checked');
            // console.log(checked);
            if(checked){
                $('.transfer').prop('disabled', false);
            } else{
                $('.transfer').prop('disabled', true);
            }
        });

        function transferLocs(){

            $.ajax({
                type: "POST",
                data : {
                    'locations' : locs,
                    'route_id' : $('#route').val(),
                    'hub_id' : <?php echo $hub_id ?>,
                    '_token' : '{{ csrf_token() }}'
                },
                url: '<?php echo URL::to('/'); ?>/backend/route/locations/transfer',
                success: function(){
                    // location.reload();
                }
            });
        }

    </script>

@endsection