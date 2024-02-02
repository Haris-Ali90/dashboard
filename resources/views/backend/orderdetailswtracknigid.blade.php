<?php
$status_show = array("136" => "Client requested to cancel the order",
    "137" => "Delay in delivery due to weather or natural disaster",
    "118" => "left at back door",
    "117" => "left with concierge",
    "135" => "Customer refused delivery",
    "108" => "Customer unavailable-Incorrect address",
    "106" => "Customer unavailable - delivery returned",
    "107" => "Customer unavailable - Left voice mail - order returned",
    "109" => "Customer unavailable - Incorrect phone number",
    "103" => "Delay at pickup",
    "114" => "Successful delivery at door",
    "113" => "Successfully hand delivered",
    "120" => "Delivery at Hub",
    "110" => "Returned to hub for re-delivery",
    "111" => "Delivery to hub for return to merchant",
    "121" => "Out for delivery",
    "102" => "Joey Incident",
    "140" => "Delivery missorted, may cause delay",
   // "104" => "Damaged on road - delivery will be attempted",
    "143" => "Damaged on road - undeliverable",
    "105" => "Item damaged - returned to merchant",
    "129" => "Joey at hub",
    "128" => "Package on the way to hub",
    "116" => "Successful delivery to neighbour",
    "132" => "Office closed - safe dropped",
    "101" => "Joey on the way to pickup",
    "124" => "At hub - processing",
    "112" => "To be re-attempted",
    "131" => "Office closed - returned to hub",
    "125" => "Pickup at store - confirmed",
    "133" => "Packages sorted",
    "141" => "Lost package",
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
?>
@extends( 'backend.layouts.app' )


@section('title', 'Route Order Details')

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
    <!-- DataTables JavaScript -->

    <style>
        tr.reattemptrow {
            background: #c6dd38 !important;
            color: #555;
        }
        .flag-head {
            color: black;
        }

        .sub-cat-id {
            margin-top: 3%;
        }
        .flag-btn {
            padding: 7px 0px 0px 20px;
        }
        .flag-order-details {
            margin-left: 10px;
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
            bottom: 0px;
            left: 100%;
            padding: 0% 0px 0px 5px;
        }
        .hoverable-dropdown-ul > li:hover > .hoverable-dropdown-ul {
            display: block !important;
            z-index: 10;
            position: absolute;
            /*top: -1px;*/
            bottom: 0px;
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
        .radio_butn label.control-label {
            margin-right: 10px;
        }
        .radio_butn label.control-label .radio {
            padding: 0px;
        }
        label.col-md-2.control-label {
            margin: 0px 0px 0px 119px;
        }
        .edit-image-icon{
            font-size: 20px;
            float: left;
            position: absolute;
            padding: 5px;
            background-color: #c7dd1f;
            color: black;
            border: none;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
    </style>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".group1").colorbox({height: "75%"});

            // append child data for flag
            $('.main-flag-select').change(function () {
                let selected_val = $(this).val();

                let selected_option_data = (selected_val == '')? '' : JSON.parse(selected_val);
                let child_select_box = $('#sub_cat');
                // checking child data exist
                if(selected_option_data == '' || Object.keys(selected_option_data.child_data).length <= 0 )
                {
                    alert('selected category does not contain any child ');
                    child_select_box.hide();
                    return false;
                }

                // show child select box
                child_select_box.show();
                // remove all old values and add default value
                child_select_box.empty().append('<option value="">Select an option</option>');
                //adding responce options
                $.each(selected_option_data.child_data, function(val, text) {
                    child_select_box.append("<option value="+val+">"+text+"</option>");
                });

            });

            //Create flag
            $('.can-apply-flag').click(function (e) {
                e.preventDefault();
                let el = $(this);
                //let child_flag_id = el.val();
                let child_flag_id = el.attr("data-id");
                let order_data = JSON.parse($('#flag_data').val());
                //getting previous flagged category count
                let previous_flagged_cat_count = $('.flag-tr-cat-bunch-'+child_flag_id).length;
                //let total_flag_cat_count = $('.flag-tr').length;

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
                                            // getting current url with query string
                                            $current_utl = window.location.href;

                                            let url_without_query_string = $current_utl.split('?')[0];
                                            // converting query string into jason
                                            let query_json = urlQueryTOJason($current_utl);
                                                                                        // removeing old message form query string
                                            delete query_json['message'];
                                            // updating new message to query string
                                            query_json['message'] = response.message;
                                            // creating url string
                                            let url = $.param(query_json);
                                            // redirecting
                                            window.location.href = url_without_query_string + '?' + url;

                                        }
                                        else // update  failed by server
                                        {
                                            // show session alert
                                            ShowSessionAlert('danger', response.message);
                                        }

                                    },
                                    error: function (error) {
                                        hideLoader();
                                        ShowSessionAlert('danger', 'Something wrong');
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

            //Grocery Create flag
            $('.can-apply-grocery-flag').click(function (e) {
                e.preventDefault();
                let el = $(this);
                //let child_flag_id = el.val();
                let child_flag_id = el.attr("data-id");
                let order_data = JSON.parse($('#flag_data').val());
                //getting previous flagged category count
                let previous_flagged_cat_count = $('.flag-tr-cat-bunch-'+child_flag_id).length;
                //let total_flag_cat_count = $('.flag-tr').length;

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
                                    url: "{{URL::to('/')}}/grocery/flag/create/"+child_flag_id,
                                    data: order_data,
                                    success: function (response) {
                                        hideLoader();
                                        if (response.status == true) // notifying user  the update is completed
                                        {
                                            // getting current url with query string
                                            $current_utl = window.location.href;

                                            let url_without_query_string = $current_utl.split('?')[0];
                                            // converting query string into jason
                                            let query_json = urlQueryTOJason($current_utl);
                                            // removeing old message form query string
                                            delete query_json['message'];
                                            // updating new message to query string
                                            query_json['message'] = response.message;
                                            // creating url string
                                            let url = $.param(query_json);
                                            // redirecting
                                            window.location.href = url_without_query_string + '?' + url;

                                        }
                                        else // update  failed by server
                                        {
                                            // show session alert
                                            ShowSessionAlert('danger', response.message);
                                        }

                                    },
                                    error: function (error) {
                                        hideLoader();
                                        ShowSessionAlert('danger', 'Something wrong');
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

@endsection



@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="page-title">
                <div class="title_left">
                    <h3> {{"CR-".$sprintId}} </h3>
                </div>
            </div>
            <div class="clearfix"></div>
            @include( 'backend.layouts.notification_message' )
            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Route Order Details
                                <small></small>
                            </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-2 col-sm-2 col-xs-12 profile_left">

                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab"
                                                                                  role="tab" data-toggle="tab"
                                                                                  aria-expanded="true">Order Info </a>
                                        </li>
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                             aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <?php
                                                foreach($data as $response){
                                                //dd($response->joey_lastname)
                                                ?>
                                                    <input type="hidden" id="flag_data" value='{"order_type":"{{$order_type}}","merchant_joey_id":"{{$response->merchant_joey_id}}","merchant_order_no":"{{$response->merchant_order_num}}","tracking_id":"{{$response->tracking_id}}","joey_id":"{{$response->joey_id}}","route_id":"{{$response->route_id}}","sprint":"{{$response->sprint_id}}","flag_type":"order","hub_id":"{{$response->hub}}"}'>
                                                <thead>
                                                <tr>
                                                    {{-- <th colspan="2" >Task Info </th>--}}
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><label>Tracking Id</label></td>
                                                    <td>{{$response->tracking_id}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Merchant Order Num</label></td>
                                                    <td>{{$response->merchant_order_num}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Route No</label></td>
                                                    <td>
                                                        @if($response->route_id)
                                                            {{$response->route_id ."-".$response->stop_number}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <lable>Customer Name</lable>
                                                    </td>
                                                    <td>{{$response->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Phone</label></td>
                                                    <td>{{$response->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Email</label></td>
                                                    <td>{{$response->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Customer Address</label></td>
                                                    <td>@if(!empty($response->address_line2))
                                                            {{$response->address_line2 }}
                                                        @else
                                                            {{$response->address }}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><label>Joey</label></td>
                                                    <td>
                                                        @if(!empty($response->joey_id))
                                                        @if($response->joey_id)
                                                            {{$response->joey_firstname." ".$response->joey_lastname." (".$response->joey_id.")" }}
                                                        @endif
                                                        @elseif(empty($response->joey_id))
                                                            @if(isset($response->joeyName))
                                                           {{$response->joeyName->joey_name." (".$response->joeyName->id.")" }}
                                                           @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label>Joey contact</label></td>
                                                    <td>{{$response->joey_contact }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Merchant</label></td>
                                                    <td>{{$response->business_name }}</td>
                                                </tr>


                                                <?php
                                                $statuses = array_merge($response['status'], $response['status1'], $response['status2']
                                                );

                                                $sort_key = array_column($statuses, 'created_at');
                                                $sort_id_key = array_column($statuses, 'id');
                                                array_multisort($sort_key, SORT_ASC, $statuses);

                                                $status_array = array_merge(getStatusCodes('competed'), getStatusCodes('return'));
                                                if (array_intersect($status_array, $sort_id_key))
                                                {
                                                ?>
                                                <tr>

                                                    <?php
                                                    $image = \App\SprintConfirmation::where('task_id', '=', $response['id'])->whereNotNull('attachment_path')->orderBy('id', 'desc')->get();
                                                    if(!empty($image))
                                                    {
                                                    ?>
                                                        <td><label>Image</label></td>
                                                    <td>
                                                        @foreach($image as $images)
                                                            <button class="fa fa-edit edit-image-icon" onclick="showEditPODModal({{$images->id}},{{$images->task_id}},'image')"></button>
                                                            <img onclick="ShowLightBox(this);"
                                                                src="{{$images->attachment_path}}" width='100' height='100'
                                                                alt={{"CR-".$response['sprint_id']}}/>
                                                        @endforeach
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php }?>

                                                <tr>
                                                    <?php
                                                    if(str_contains($response->tracking_id, 'old_')){
                                                        $tracking_id = substr($response->tracking_id, strrpos($response->tracking_id, '_') + 1);
                                                    }
                                                    else{
                                                        $tracking_id = $response->tracking_id;
                                                    }
                                                    $order_image = \App\OrderImage::where('tracking_id', $tracking_id)->whereNull('deleted_at')->orderBy('id','asc')->get();
                                                    if(!empty($order_image))
                                                    {
                                                    ?>
                                                    <td><label>Additional Image</label></td>
                                                    <td>
                                                        @foreach($order_image as $img)
                                                            <button class="fa fa-edit edit-image-icon" onclick="showEditPODModal({{$img->id}},{{$img->task_id}},'additional_image')"></button>
                                                        <img onclick="ShowLightBox(this);"
                                                             src="{{$img->image}}" width='100' height='100'
                                                             alt={{"CR-".$response['sprint_id']}}/>
                                                            @endforeach
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>

                                                </tbody>
                                            </table>
                                            </div>
                                            <!-- end user projects -->

                                            <h5 style="clear:both;text-align:left" class="accordion">
                                                <button class="btn btn-xs orange-gradient color:#000 !important;">Status
                                                    History
                                                    <i class="fa fa-angle-down"></i></button>
                                            </h5>
                                            <table id="main" class="table table-striped table-bordered panel"
                                                   style="display: none">

                                                <thead>
                                                <tr>
                                                    <th id="main">Code</th>
                                                    <th id="main">Description</th>
                                                    <th id="main">Date</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php
                                                // dd($response);


                                               /* foreach ($statuses as $status) {
                                                    echo "<tr>";
                                                    echo "<td>" . $status['id'] . "</td>";
                                                    echo "<td>" . $status['description'] . "</td>";
                                                    echo "<td>" . date("Y-m-d H:i:s", strtotime($status['created_at'])) . "</td>";
                                                    //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
                                                    echo "</tr>";
                                                }*/

                                                $firstattempt=[];
                                                $secondattempt=[];
                                                $thirdattempt=[];

                                                if(!empty($response['status2'])){

                                                    $firstattempt = $response['status2'];
                                                    $secondattempt = $response['status'];
                                                    $thirdattempt = $response['status1'];

                                                }
                                                else if(!empty($response['status'])){
                                                    $firstattempt = $response['status'];
                                                    $secondattempt = $response['status1'];
                                                }
                                                else{
                                                    $firstattempt = $response['status1'];
                                                }

                                                echo "<tr class='reattemptrow'>";
                                                echo "<td colspan='3'><strong>First Attempt</strong></td>";
                                                echo "</tr>";
                                                foreach ($firstattempt as $status){
                                                    echo "<tr>";
                                                    echo "<td>".$status['id']."</td>";
                                                    echo "<td>".$status['description']."</td>";
                                                    // echo "<td>".$status['created_at']."</td>";
                                                    echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."+ 5 hours"))."</td>";
                                                    echo "</tr>";
                                                }
                                                if( $secondattempt)
                                                {
                                                    echo "<tr class='reattemptrow'>";
                                                    echo "<td colspan='3'><strong>Second Attempt</strong></td>";
                                                    echo "</tr>";
                                                }

                                                foreach ( $secondattempt as $status){
                                                    if($status['id']==125 || $status['id']==61){
                                                        continue;
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>".$status['id']."</td>";
                                                    echo "<td>".$status['description']."</td>";
                                                    echo "<td>".date("y-m-d h:i:s",strtotime($status['created_at']."+ 5 hours"))."</td>";
                                                    echo "</tr>";
                                                }
                                                if($thirdattempt)
                                                {
                                                    echo "<tr class='reattemptrow'>";

                                                    echo "<td colspan='3'><strong>Third Attempt</strong></td>";

                                                    echo "</tr>";
                                                }
                                                foreach ( $thirdattempt  as $status){
                                                    if($status['id']==125 || $status['id']==61){
                                                        continue;
                                                    }
                                                    echo "<tr>";
                                                    echo "<td>".$status['id']."</td>";
                                                    echo "<td>".$status['description']."</td>";
                                                    echo "<td>".date("y-m-d h:i:s",strtotime($status['created_at']."+ 5 hours"))."</td>";
                                                    echo "</tr>";
                                                }


                                                ?>
                                                </tbody>
                                            </table>

                                            <h5 style="clear:both;text-align:left" class="accordionnew">
                                                <button class="btn btn-xs orange-gradient color:#000 !important;">Manual Status
                                                    History
                                                    <i class="fa fa-angle-down"></i></button>
                                            </h5>
                                            <div class="table-responsive" style="display: none">
                                                <table id="main" class="table table-striped table-bordered panel"
                                                    style="display: block">

                                                    <thead>
                                                    <tr>
                                                        <th id="main"  style="width: 10%">Tracking #</th>
                                                        <th id="main" style="width: 20%">Status</th>
                                                        <th id="main" style="width: 10%">Image</th>
                                                        <th id="main" style="width: 20%">Reason</th>
                                                        <th id="main" style="width: 15%">User</th>
                                                        <th id="main" style="width: 10%">Domain</th>
                                                        <th id="main" style="width: 10%">Created At</th>

                                                

                    
                                                    </tr>
                                                    </thead>
                                                    @if (count($manualHistory) > 0)
                                                        @foreach ($manualHistory as $manualValue)
                                                            <tr>
                                                                <td>{{$manualValue->tracking_id}}</td>
                                                                <td>{{$manualValue->status_id}}</td>
                                                                <td>
                                                                    @if($manualValue->attachment_path!='')
                                                                        <img onClick="ShowLightBox(this);" style="width:50px;height:50px" src ="{{$manualValue->attachment_path}}" />
                                                                    @endif
                                                                </td>
                                                                <td>{{$manualValue->reason_id}}</td>
                                                                <td>{{$manualValue->user_id}}</td>
                                                                <td>{{$manualValue->domain}}</td>
                                                                <td>{{date("y-m-d h:i:s",strtotime($manualValue->created_at."+ 5 hours"))}}</td>

                                                            </tr>
                                                        @endforeach
                                                    @else
                                                    <tr>
                                                        <td class="text-center " colspan="7">No data...</td>
                                                    </tr>    
                                                    @endif
                                                    <tbody>
                                                
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php  if(can_access_route(['sprint-image-upload'], $userPermissoins)) {  ?>
                                            <div>
                                                <h1> Upload Image </h1>
                                                {!! Form::open( ['url' => ['sprint/image/upload'], 'files'=> true , 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}

                                                {{ Form::hidden('sprint_id',$response->sprint_id, ['class' => 'form-control col-md-7 col-xs-12']) }}
                                                {{ Form::hidden('creator_id',$response->creator_id, ['class' => 'form-control col-md-7 col-xs-12']) }}
                                                {{ Form::hidden('contact_id',$response->contact_id, ['class' => 'form-control col-md-7 col-xs-12']) }}
                                                {{ Form::hidden('tracking_id',$response->tracking_id, ['class' => 'form-control col-md-7 col-xs-12']) }}
                                                <div class="form-group{{ $errors->has('sprint_image') ? ' has-error' : '' }}">
                                                    {{ Form::label('sprint_image', 'Image', ['class'=>'control-label-sm col-md-3 col-sm-3 col-xs-12']) }}
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="file" name="sprint_image"
                                                               class="form-control col-md-7 col-xs-12" required>
                                                    </div>
                                                    @if ( $errors->has('sprint_image') )
                                                        <p class="help-block">{{ $errors->first('sprint_image') }}</p>
                                                    @endif
                                                </div>
                                                <div class="form-group{{ $errors->has('status_id') ? ' has-error' : '' }}">
                                                    {{ Form::label('status_id', 'Status', ['class'=>'control-label-sm col-md-3 col-sm-3 col-xs-12']) }}
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select id="status_id" name="status_id"
                                                                class='form-control col-md-7 col-xs-12' required>
                                                            <option value="">Please Select Status</option>
                                                            <?php
                                                            foreach($status_show as $key => $oc){ ?>
                                                            <option value="<?php echo $key ?>">
                                                                <?php echo $oc ?></option>
                                                            <?php }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('reason_id') ? ' has-error' : '' }}">
                                                    {{ Form::label('reason_id', 'Reason', ['class'=>'control-label-sm col-md-3 col-sm-3 col-xs-12']) }}
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select id="reason_id" name="reason_id"
                                                                class='form-control col-md-7 col-xs-12' required>
                                                            <option value="">Please Select Reason</option>
                                                            <?php
                                                            foreach($reasons as $reason){ ?>
                                                            <option value="<?php echo $reason->id ?>">
                                                                <?php echo $reason->title ?></option>
                                                            <?php }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="ln_solid"></div>
                                                <div class="form-group">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        {{ Form::submit('Save Image', ['class' => 'btn btn-primary']) }}
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                            <?php } ?>
                                            <?php }?>
                                        </div>

                                        @if(in_array($response->hub,$hubIds))
                                            @if($order_type == 'ecommerce')
                                            @if(can_access_route(['flag.create','un-flag'],$userPermissoins))
                                            <!--E-Commerce Flagging Work Open-->
                                                <h5 style="clear:both;text-align:left" class="accordion flag-btn e-commerce-div">
                                                    <button class="btn btn-xs orange-gradient color:#000 !important;">
                                                        E-commerce Flag Category List
                                                        <i class="fa fa-angle-down"></i></button>
                                                </h5>
                                                <div class="row" style="display: none">
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12 hoverable-dropdown-main-wrap">
                                                            <ul class="hoverable-dropdown-main-ul">
                                                                @foreach($flagCategories as $category)
                                                                    @if($category->isFliterExist('order_type',$order_type) && $category->isFliterExist('portal','dashboard') && ( $category->isFliterExist('vendor_relation',[$response->merchant_id]) || !$category->isFliterExist('vendor_relation')) && ($category->isFliterExist('is_show_on_route','0') || $category->isFliterExist('is_show_on_route','2')))
                                                                        <li>
                                                                            {{$category->category_name}}
                                                                            <?php $child_data = $category->getChilds->where('is_enable', 1); ?>
                                                                            @if(!$child_data->isEmpty())
                                                                                <i class="fa fa-angle-right"></i>
                                                                                <ul class="hoverable-dropdown-ul">
                                                                                    @foreach($child_data as $child)
                                                                                        <li data-id="{{$child->id}}" class="child-flag-cat">
                                                                                            {{$child->category_name}}
                                                                                            <?php $grand_child_data = $child->getChilds->where('is_enable', 1); ?>
                                                                                            @if(!$grand_child_data->isEmpty())
                                                                                                <i class="fa fa-angle-right"></i>
                                                                                                <ul class="hoverable-dropdown-ul">
                                                                                                    @foreach($grand_child_data as $grand_child)
                                                                                                        <li data-id="{{$grand_child->id}}" class="child-flag-cat can-apply-flag">
                                                                                                            {{$grand_child->category_name}}
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            @endif
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!--E-Commerce Flagging Work Close-->

                                            <!-- Div row flag open -->
                                            {{--<div class="row">--}}
                                        <!--Div open for col-->
                                            {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
                                        <!--Div open for flag category-->
                                            {{-- <div class="col-md-6 col-sm-6">
                                                 <h2 class="flag-head">Flag Order</h2>
                                                 <select class="form-control main-flag-select" id="parent_id" name="parent_id">
                                                     <option value="">Select Category</option>--}}
                                            {{--@foreach($flagCategories as $category)--}}
                                            <?php /*$child_data_array = ["parent_id"=>$category->id,"child_data"=>$category->getChilds->where('is_enable', 1)->pluck('category_name','id')->toArray()]; $child_data_json = json_encode($child_data_array);*/?>
                                            {{--<option value="{{$child_data_json}}">{{$category->category_name}}</option>--}}
                                            {{--@endforeach--}}
                                            {{--</select>
                                        </div>
                                        <!--Div close for flag category-->
                                        <!--Div open for flag sub category-->
                                        <div class="col-md-6 col-sm-6 sub-cat-id">
                                            <select class="form-control child-flag-cat" id="sub_cat" name="sub_cat" style="display: none" >
                                                <option>Select Sub Category</option>
                                            </select>
                                        </div>
                                        <!--Div close for flag sub category-->
                                    </div>--}}
                                        <!--Div close for col-->

                                                @if(!is_null($joey_flags_history))

                                                <!--Div open for flag order detail-->
                                                    <h5 style="clear:both;text-align:left" class="accordion flag-btn">
                                                        <button class="btn btn-xs orange-gradient color:#000 !important;">
                                                            E-commerce Flag History
                                                            <i class="fa fa-angle-down"></i></button>
                                                    </h5>

                                                    <div class="col-md-12 col-sm-12 col-xs-12 flag-order-details" style="display: none">
                                                        <h2>E-commerce Flag Order Detail</h2>
                                                        <div class="table-responsive">
                                                            <!--Table open for flag order details-->
                                                            <table id="datatable" class="table table-bordered flag-order-details-table" >
                                                                <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Joey</th>
                                                                    <th>Flag By</th>
                                                                    <th>Flag Category Name</th>
                                                                    <th>Created At</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($joey_flags_history as $key => $joey_flag_history)
                                                                    <tr class="flag-tr flag-tr-cat-bunch-{{$joey_flag_history->flag_cat_id}}">
                                                                        @if($joey_flag_history->tracking_id != null)
                                                                        <td>{{$key+1}}</td>
                                                                        <td>
                                                                            <input type="hidden"  value="{{$joey_flag_history->flag_cat_id}}">
                                                                            @if(isset($joey_flag_history->joeyName->first_name))
                                                                                {{$joey_flag_history->joeyName->first_name." ".$joey_flag_history->joeyName->last_name}}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{$joey_flag_history->flagByName->full_name}}</td>
                                                                        <td>{{$joey_flag_history->flag_cat_name}}</td>
                                                                        <td>{{ConvertTimeZone($joey_flag_history->created_at,'UTC','America/Toronto')}}</td>
                                                                        <td>
                                                                            @if($joey_flag_history->is_approved == 0)
                                                                                <a href="{{ backend_url('un-flag/'.$joey_flag_history->id) }}" class="btn btn-danger" >Un Flag Order</a>
                                                                            @else
                                                                                <a class="btn-info btn-xs">Approved</a>
                                                                            @endif
                                                                        </td>
                                                                            @endif
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                            <!--Table close for flag order details-->
                                                        </div>
                                                    </div>
                                                    <!--Div close for flag order detail-->
                                                @endif
                                                @endif
                                            @endif

                                        </div>
                                        <!-- Div row flag close -->
                                        @endif
                                @if($order_type == 'grocery')
                                @if(can_access_route(['grocery-flag.create','grocery-un-flag'],$userPermissoins))
                                    <!--Grocery Flagging Work Close-->
                                        <h5 style="clear:both;text-align:left" class="grocery flag-btn grocery-div">
                                            <button class="btn btn-xs orange-gradient color:#000 !important;">
                                                Flag Category List Grocery
                                                <i class="fa fa-angle-down"></i></button>
                                        </h5>
                                        <div class="row" style="display: none">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12 hoverable-dropdown-main-wrap">
                                                    <ul class="hoverable-dropdown-main-ul">
                                                        @foreach($flagCategories as $groceryCategory)

                                                            @if($groceryCategory->isFliterExist('order_type',$order_type) && $groceryCategory->isFliterExist('portal','dashboard') && ( $groceryCategory->isFliterExist('vendor_relation',[$response->merchant_id]) || !$groceryCategory->isFliterExist('vendor_relation')) && ($groceryCategory->isFliterExist('is_show_on_route','0') || $groceryCategory->isFliterExist('is_show_on_route','2')))
                                                                <li>
                                                                    {{$groceryCategory->category_name}}
                                                                    <?php $child_data = $groceryCategory->getChilds->where('is_enable', 1); ?>
                                                                    @if(!$child_data->isEmpty())
                                                                        <i class="fa fa-angle-right"></i>
                                                                        <ul class="hoverable-dropdown-ul">
                                                                            @foreach($child_data as $child)
                                                                                <li data-id="{{$child->id}}" class="child-flag-cat">
                                                                                    {{$child->category_name}}
                                                                                    <?php $grand_child_data = $child->getChilds->where('is_enable', 1); ?>
                                                                                    @if(!$grand_child_data->isEmpty())
                                                                                        <i class="fa fa-angle-right"></i>
                                                                                        <ul class="hoverable-dropdown-ul">
                                                                                            @foreach($grand_child_data as $grand_child)
                                                                                                <li data-id="{{$grand_child->id}}" class="child-flag-cat can-apply-grocery-flag">
                                                                                                    {{$grand_child->category_name}}
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Grocery Flagging Work Close-->

                                    @if(!is_null($joey_flags_history))

                                        <!--Div open for flag order detail-->
                                            <h5 style="clear:both;text-align:left" class="accordion flag-btn">
                                                <button class="btn btn-xs orange-gradient color:#000 !important;">
                                                    Grocery Flag History
                                                    <i class="fa fa-angle-down"></i></button>
                                            </h5>

                                            <div class="col-md-12 col-sm-12 col-xs-12 flag-order-details" style="display: none">
                                                <h2>Grocery Flag Order Detail</h2>
                                                <div class="table-responsive">
                                                    <!--Table open for flag order details-->
                                                    <table id="datatable" class="table table-bordered flag-order-details-table" >
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Joey</th>
                                                            <th>Flag By</th>
                                                            <th>Flag Category Name</th>
                                                            <th>Created At</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($joey_flags_history as $key => $joey_flag_history)

                                                            <tr class="flag-tr flag-tr-cat-bunch-{{$joey_flag_history->flag_cat_id}}">
                                                                @if($joey_flag_history->tracking_id == null)
                                                                <td>{{$key+1}}</td>
                                                                <td>
                                                                    <input type="hidden"  value="{{$joey_flag_history->flag_cat_id}}">
                                                                    @if(isset($joey_flag_history->joeyName->first_name))
                                                                        {{$joey_flag_history->joeyName->first_name." ".$joey_flag_history->joeyName->last_name}}
                                                                    @endif
                                                                </td>
                                                                <td>{{$joey_flag_history->flagByName->full_name}}</td>
                                                                <td>{{$joey_flag_history->flag_cat_name}}</td>
                                                                <td>{{ConvertTimeZone($joey_flag_history->created_at,'UTC','America/Toronto')}}</td>
                                                                <td>
                                                                    @if($joey_flag_history->is_approved == 0)
                                                                        <a href="{{ backend_url('grocery/un-flag/'.$joey_flag_history->id) }}" class="btn btn-danger" >Un Flag Order</a>
                                                                    @else
                                                                        <a class="btn-info btn-xs">Approved</a>
                                                                    @endif
                                                                </td>
                                                                    @endif
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    <!--Table close for flag order details-->
                                                </div>
                                            </div>
                                            <!--Div close for flag order detail-->
                                        @endif
                                        @endif
                                    @endif

                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                             aria-labelledby="profile-tab">

                                            <!-- start user projects -->
                                            <!--  -->
                                            <!-- end user projects -->

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    {{--  modal for pod update  --}}
    <div id="EditPOD" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Image</h4>
                </div>
                <form method="post" action="{{ route('sprint-image-update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div>
                            {!! csrf_field() !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="image_id" id="image_id">
                                <input type="hidden" name="task_id" id="task_id">
                                <input type="hidden" name="type" id="image" value="image">
                                <input type="file" name="sprint_image" class="form-control col-md-7 col-xs-12" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{--  Edit POD additional Image  --}}
    <div id="EditPODAdditionalImage" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Additional Image</h4>
                </div>
                <form method="post" action="{{ route('sprint-image-update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div>
                            {!! csrf_field() !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="image_id" id="additional_image_id">
                                <input type="hidden" name="task_id" id="additional_task_id">
                                <input type="hidden" name="type" id="additional_image" value="additional_image">
                                <input type="file" name="sprint_image" class="form-control col-md-7 col-xs-12" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01" style="height: 600px;">
        <div id="caption"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>

        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "inline-table") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "inline-table";
                }
            });
        }

        var acc2 = document.getElementsByClassName("accordionnew");
        var j;

        for (j = 0; j < acc2.length; j++) {
            acc2[j].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }

        var grocery = document.getElementsByClassName("grocery");
        var k;

        for (k = 0; k < grocery.length; k++) {
            grocery[k].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }


        function showEditPODModal(imageId, taskId, type){
            console.log(imageId, taskId);

            if(type == 'image'){
                document.getElementById("image_id").value = imageId
                document.getElementById("task_id").value = taskId
                $('#EditPOD').modal('show');
            }
            else{
                document.getElementById("additional_image_id").value = imageId
                document.getElementById("additional_task_id").value = taskId
                $('#EditPODAdditionalImage').modal('show');
            }
        }




    </script>
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById('myImg');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    </script>



@endsection