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
        /*hoverable dropdown css*/
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
        .hoverable-dropdown-main-wrap ul:hover
        {
            display: block;
        }
        .hoverable-dropdown-main-wrap ul li:hover ul
        {
            display: block;
            z-index: 10;
            position: absolute;
            top: -1px;
            left: 100%;
            padding: 0% 0px 0px 5px;
        }
        .hoverable-dropdown-main-wrap ul > li:hover
        {
            background: #ccc;
        }
        .hoverable-dropdown-main-ul .fa-angle-right {
            position: absolute;
            right: 10px;
        }
        td{
            border: 1px solid #ddd !important;
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
            $('.child-flag-cat').click(function (e) {
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
        });
    </script>

@endsection



@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="page-title">
                <div class="title_left">
                    {{-- @if (isset($sprintId)) --}}
                      <!-- <h3> {{"Tracking Page"}} </h3> -->
                    {{-- @endif --}}
                  
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
<h2>Tracking Page</h2>
                            <form method="get" action="" id="">


                                <div class="row">


                                                   {{-- <textarea rows='1' cols="180"  name="tracking_id" id="tracking_ids" class="form-control" onchange="disableOrEnablebutton(this.value)"
                                                             value="" style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left"
                                                             placeholder="Tracking Id eg:JoeyCo001,JoeyCo002" title='Search with multiple tracking Id.'></textarea> --}}

                                                             <input required type="text" rows='1' cols="180"  name="tracking_id" id="tracking_ids" class="form-control"
                                                             value="<?php if(isset($_GET['tracking_id'])){echo $_GET['tracking_id'];} ?>" style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left;width: 189px;"
                                                             placeholder="Tracking Id eg: test-order-001" title='Search with tracking Id.'>


                                    {{-- <textarea rows='1' cols="180"  name="merchant_order_no" id="merchant_order_no" class="form-control" onchange="disableOrEnablebutton(this.value)"
                                              value="" style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left"
                                              placeholder="Merchant Order No eg:AN5-001,AN5-002" title="Search  with multiple merchants Order no." ></textarea>


                                    <textarea rows='1' cols="180"  name="phone_no"  id="phone_no" class="form-control" onchange="disableOrEnablebutton(this.value)"
                                              value="" style="margin-top:5px; border-radius: 5px; margin-bottom:5px; margin-right:5px;float: left"
                                              placeholder="Phone No eg:phone001,phone002" title="Search  with multiple phone no."></textarea> --}}

                                    <button class="btn c-btn btn-primary" id="search_btn" type="submit" style="margin-top:10px; margin-bottom:8px;  margin-right:5px;float: left" >Search </button>




                                    <!-- csv download btn -->
                                    <?php  $date = date('Y-m-d'); ?>
                                    <?php  if(can_access_route(['generate-csv'],$userPermissoins)){  ?>
                                   <!-- <button onclick="exportTableToCSV('tracking-details-<?php echo $date ?>.csv')" style="margin-top:7px; margin-bottom:5px;  margin-right:5px;float: left" type="button" class="btn orange-gradient">Generate Report in CSV</button>-->
                                   <?php } ?>



                                </div>
                            </form>

                            <!-- <form method="post" enctype="multipart/form-data" action="../../excel/read" id="myform2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                             <input type="file" name="excelFile" id="excelFile" />
                             <button class="btn orange-gradient" type="submit" style="margin-top: -3%,4%">Submit Excel </button>



                           </form> -->

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            {{-- <div class="col-md-2 col-sm-2 col-xs-12 profile_left">

                            </div> --}}
                            {{-- <div class="col-md-10 col-sm-10 col-xs-12"> --}}
                            <div class="">


                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    {{-- <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab"
                                                                                  role="tab" data-toggle="tab"
                                                                                  aria-expanded="true">Order Info </a>
                                        </li> --}}
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    {{-- </ul> --}}
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                             aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <div class="table-responsive">
                                            <table class="table table-bordered">
                                                @if(!isset($data)|| count($data)==0)
                                                <?php if(isset($_GET['tracking_id'])){if(!empty($_GET['tracking_id'])){echo "No data found.";}} ?>
                                                @else
                                                    <?php foreach($data as $response){
                                                    //dd($response->joey_lastname)
                                                    ?>
                                                        {{-- <input type="hidden" id="flag_data" value='{"tracking_id":"{{$response->tracking_id}}","joey_id":"{{$response->joey_id}}","route_id":"{{$response->route_id}}","sprint":"{{$response->sprint_id}}","flag_type":"order"}'> --}}
                                                    <thead>
                                                        <tr>
                                                            {{-- <th colspan="2" >Task Info </th>--}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 30%;"><label>Tracking Id</label></td>
                                                            <td>{{$response->tracking_id}}</td>
                                                            <td><label>Joey</label></td>
                                                            <td>@if($response->joey_id)
                                                                    {{$response->joey_firstname." ".$response->joey_lastname." (".$response->joey_id.")" }}
                                                                @endif</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Sprint Id</label></td>
                                                            <td>{{"CR-".$sprintId}} </td>
                                                            <td><label>Joey Contact</label></td>
                                                            <td>@if($response->joey_id){{$response->joey_contact}} @endif</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Route No</label></td>
                                                            <td>
                                                                @if($response->route_id)
                                                                    {{$response->route_id ."-".$response->stop_number}}</td>
                                                            @endif
                                                            <td><label>Joey Current Location</label></td>
                                                            <td style="<?php if(($is_pickedup==0 && $is_delivered_return==0) || $response->joey_address===0){echo "color: red;";} ?>">{{($is_pickedup==1 && $is_delivered_return==0)?(($response->joey_address===0)?"Invalid joey location":$response->joey_address):(($is_pickedup==0 && $is_delivered_return==0)?"Your order isn't picked yet.":"") }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>Customer Name</label>
                                                            </td>
                                                            <td>{{$response->name}}</td>
                                                            <td><label>Merchant</label></td>
                                                            <td>{{$response->merchant_firstname." ".$response->merchant_lastname }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Customer Phone</label></td>
                                                            <td>{{$response->phone }}</td>
                                                            <td><label>Merchant Order Num</label></td>
                                                            <td>{{$response->merchant_order_num}}</td>
                                                            
                                                        </tr>
                                                    
                                                        <tr>
                                                            <td><label>Customer Email</label></td>
                                                            <td>{{$response->email }}</td>
                                                            <td><label>Remaining Time</label></td>
                                                            <td><?php if($response->duration > 0){$response->duration=$response->duration+5;} ?>{{($is_delivered_return==1)?'':(($is_pickedup==1 && $is_delivered_return==0)?gmdate("H:i:s", ($response->duration)*60):"")}}</td>
                                                            
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td><label>Customer Address</label></td>
                                                            {{-- <td>@if($response->creator_id !=477260 && $response->creator_id!=477282)
                                                                    {{$response->address_line2 }}
                                                                @else
                                                                    {{$response->address }}
                                                                @endif
                                                            </td> --}}
                                                            <td style="border-right: 1px solid #ddd;">@if($response->address==null)
                                                                {{$response->address_line2 }}
                                                            @else
                                                                {{$response->address }}
                                                            @endif
                                                        </td>
                                                        <td style="    border: 1px solid #ddd;"><label><?php if($is_delivered==1){ echo "Delivered At";}else{ ?>Expected Arrival<?php } ?></label></td>
                                                            {{-- <td style="    border: 1px solid #ddd;">{{($is_delivered_return==0 && $response->expected_datetime!='')?date('d M Y h:i:a', strtotime($response->expected_datetime)):""}}</td> --}}
                                                            <td style="border: 1px solid #ddd;">
                                                                    <?php
                                                                        date_default_timezone_set("America/Toronto");
                                                                        if($is_delivered_return==1){
                                                                            echo $msg_deliver_return;
                                                                        }
                                                                        elseif($is_pickedup==1 && $is_delivered_return==0){
                                                                            date('d M Y h:i:a', strtotime($response->expected_datetime));
                                                                            $minutes_to_add = $response->duration;
                                                                            $c_date=date('Y-m-d H:i:s');
                                                                            $time = new \DateTime($c_date);
                                                                            $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));

                                                                            $stamp = $time->format('d M Y h:i a');
                                                                            echo $stamp;
                                                                        }
                                                                    ?>
                                                            </td>
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
                                                            $image = \App\SprintConfirmation::where('task_id', '=', $response['id'])->whereNotNull('attachment_path')->orderBy('id', 'desc')->first();
                                                            if(!empty($image))
                                                            {
                                                            ?>
                                                                <td><label>Image</label></td>
                                                            <td>
                                                                <img onclick="ShowLightBox(this);"
                                                                    src="{{$image->attachment_path}}" width='300' height='200'
                                                                    alt={{"CR-".$response['sprint_id']}}/>
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
                                                            $order_image = \App\OrderImage::where('tracking_id', $tracking_id)->whereNull('deleted_at')->orderBy('id','desc')->first();
                                                            if(!empty($order_image))
                                                            {
                                                            ?>
                                                            <td style="border: 1px solid #ddd;"><label>Additional Image</label></td>
                                                            <td style="border: 1px solid #ddd;">
                                                                <img onclick="ShowLightBox(this);"
                                                                    src="{{$order_image->image}}" width='300' height='200'
                                                                    alt={{"CR-".$response['sprint_id']}}/>
                                                            </td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                                </div>
                                                <!-- end user projects -->
                                                <section class="location_map_section">
                                                    <div id="location_map"  style="width: 100%;"></div>
                                                </section>
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
                                                        echo "<td>".$status['created_at']."</td>";
                                                        //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
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
                                                        echo "<td>".$status['created_at']."</td>";
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
                                                        echo "<td>".$status['created_at']."</td>";
                                                        echo "</tr>";
                                                    }


                                                    ?>
                                                    </tbody>
                                                </table>

                                            
                                                <?php }?>
                                                
                                            @endif
                                        </div>
                                        </table>
                                        

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
    <!-- /#page-wrapper -->
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01" style="height: 600px;">
        <div id="caption"></div>
    </div>

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

    </script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0" type="text/javascript"></script>
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById('myImg');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        // img.onclick = function () {
        //     modal.style.display = "block";
        //     modalImg.src = this.src;
        //     captionText.innerHTML = this.alt;
        // }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

    </script>
    @if(isset($data) && count($data)>0 && $is_delivered_return==0 && $is_pickedup==1 && $data[0]->joey_address!==0)
     <script>
         document.getElementById("location_map").style.height = "400px";
        // var latt=<?=(isset($data[0]))?$data[0]->joey_lat:0?>;
        // var longg=<?=(isset($data[0]))?$data[0]->joey_lng:0?>;
        // var myLatLng = { lat: <?=(isset($data[0]))?$data[0]->joey_lat:0?>, lng: <?=(isset($data[0]))?$data[0]->joey_lng:0?> };
        // var myLatLng = {lat:44.77019576542069, lng:-76.68952076953119};
        // initialize maps
        // var map;
        // function initMap() {
        //     map = new google.maps.Map(document.getElementById('location_map'), {
        //     center: {lat:  44.77019576542069, lng: -76.68952076953119},
        //     zoom: 15,
        //     disableDefaultUI: true,
        //     });
        //     new google.maps.Marker({
        //     position: myLatLng,
        //     map,
        //     title: "Map",
        //     });
        // }
        // initMap();
        var custlat=parseFloat("{{$data[0]->cust_lat}}");
        var custlng=parseFloat("{{$data[0]->cust_lng}}");

        var joeylat=parseFloat("{{$data[0]->joey_lat}}");
        var joeylng=parseFloat("{{$data[0]->joey_lng}}");

        var locations = [
            // ['Bondi Beach', -33.890542, 151.274856, 4],
            // ['Coogee Beach', -33.923036, 151.259052, 5],
            // ['Cronulla Beach', -34.028249, 151.157507, 3],
            ['Customer Name: {{$data[0]->name." ,  Customer Address: ".$data[0]->address }}', custlat, custlng, 2],
            ['Joey Name: {{$data[0]->joey_firstname." ".$data[0]->joey_lastname." ,  Joey Address: ".$data[0]->joey_address }}', joeylat, joeylng, 1]
        ];
        
        var map = new google.maps.Map(document.getElementById('location_map'), {
        zoom: 12,
        // center: new google.maps.LatLng(-33.92, 151.25),
        center: new google.maps.LatLng(joeylat, joeylng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        
        for (i = 0; i < locations.length; i++) { 
            var icon= {
                        url:"<?=url('/')?>/images/joey-on-deliverey.png", // url
                        // scaledSize: new google.maps.Size(40, 40), // scaled size
                        // origin: new google.maps.Point(0,0), // origin
                        // anchor: new google.maps.Point(0, 0) // anchor
                        }; 
            if(i==0){
             icon= {url:"<?=url('/')?>/images/customer-drop.png"}; 
            }
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                icon:icon,
                map: map
            });
        
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
                }
            })(marker, i));
        }
        

    </script>
    @endif


@endsection