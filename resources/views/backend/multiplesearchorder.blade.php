<?php
$status = array("136" => "Client requested to cancel the order",
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
    "121" => "Pickup from Hub",
    "102" => "Joey Incident",
    //  "104" => "Damaged on road - delivery will be attempted",
    "140" => "Delivery missorted, may cause delay",
    "143" => "Damaged on road - undeliverable",
    "105" => "Item damaged - returned to merchant",
    "129" => "Joey at hub",
    "128" => "Package on the way to hub",
    "116" => "Successful delivery to neighbour",
    "132" => "Office closed - safe dropped",
    "101" => "Joey on the way to pickup",
    "124" => "At hub - processing",
//    "112" => "To be re-attempted",
    "131" => "Office closed - returned to hub",
    "125" => "Pickup at store - confirmed",
    "133" => "Packages sorted",
    "141" => "Lost package",
    "255" => 'Order Delay',
    '147' => 'Scanned at Hub',
    '148' => 'Scanned at Hub and labelled',
    '149' => 'pick from hub',
    '150' => 'drop to other hub',
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route',
    '155' => 'To be re-attempted tomorrow'
);



$statusid = array("136" => "Client requested to cancel the order",
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
    "255" => 'Order Delay',
    "145" => 'Returned To Merchant',
    "146" => "Delivery Missorted, Incorrect Address",
    '147' => 'Scanned at Hub',
    '153' => 'Miss sorted to be reattempt',
    '154' => 'Joey unable to complete the route',
    '155' => 'To be re-attempted tomorrow',
);

$userPermissoins = Auth::user()->getPermissions();
?>
@extends( 'backend.layouts.app' )

@section('title', 'Search Orders')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
        .green-gradient, .green-gradient:hover {
            color: #fff;
            background: #bad709;
            background: -moz-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #bad709), color-stop(100%, #afca09));
            background: -webkit-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: linear-gradient(to bottom, #bad709 0%, #afca09 100%);
        }

        .black-gradient,
        .black-gradient:hover {
            color: #fff;
            background: #535353;
            background: -moz-linear-gradient(top, #535353 0%, #353535 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #535353), color-stop(100%, #353535));
            background: -webkit-linear-gradient(top, #535353 0%, #353535 100%);
            background: linear-gradient(to bottom, #535353 0%, #353535 100%);
        }

        .red-gradient,
        .red-gradient:hover {
            color: #fff;
            background: #da4927;
            background: -moz-linear-gradient(top, #da4927 0%, #c94323 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #da4927), color-stop(100%, #c94323));
            background: -webkit-linear-gradient(top, #da4927 0%, #c94323 100%);
            background: linear-gradient(to bottom, #da4927 0%, #c94323 100%);
        }

        .orange-gradient,
        .orange-gradient:hover {
            color: #fff;
            background: #f6762c;
            background: -moz-linear-gradient(top, #f6762c 0%, #d66626 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f6762c), color-stop(100%, #d66626));
            background: -webkit-linear-gradient(top, #f6762c 0%, #d66626 100%);
            background: linear-gradient(to bottom, #f6762c 0%, #d66626 100%);
        }

        .btn {
            font-size: 12px;
        }

        .modal-header .close {
            opacity: 1;
            margin: 5px 0;
            padding: 0;
        }

        .modal-footer .close {
            opacity: 1;
            margin: 5px 0;
            padding: 0;
        }

        .modal-header h4 {
            color: #000;
        }

        .modal-footer {
            padding: 0 10px;
            text-align: right;
            border-top: 1px solid #e5e5e5;
        }

        .modal-header {
            border-radius: 6px;
            padding: 5px 15px;
            border-bottom: 1px solid #e5e5e5;
            background: #c6dd38;
        }

        .form-group {
            width: 100%;
            margin: 10px 0;
            padding: 0 15px;
        }

        .form-group input, .form-group select {
            width: 65% !important;
            height: 30px;
        }

        .form-group label {
            width: 25%;
            float: left;
            clear: both;
        }

        /* textarea
          {
            /* padding: 10px; */
        /* vertical-align: top;
        width: 200px;
        width: 85%;
        height: 29px;
        border: 1px solid #aaa;
        border-radius: 4px;

        outline: none;
        padding: 8px;
        box-sizing: border-box;
        transition: 0.3s;
        line-height: 10px;
    }
    */
        form#myform1 {
            width: 50%;

        }

        form#myform2 {
            width: 30%;

        }

        textarea {
            max-width: 300px;
            min-width: 300px;
            min-height: 35px;

        }

    </style>
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

@endsection

@section('inlineJS')

    <!-- /#page-wrapper -->
    <script type="text/javascript">

        // var textarea = document.querySelector('textarea');
        // textarea.addEventListener('keydown', autosize);
        // function autosize(){
        //   var el = this;
        //   setTimeout(function(){
        //     el.style.cssText = 'height:auto; padding:0';
        //     // for box-sizing other than "content-box" use:
        //     // el.style.cssText = '-moz-box-sizing:content-box';
        //     el.style.cssText = 'height:' + el.scrollHeight + 'px';
        //   },0);
        // }
        // $(function(){


        //     // เมื่อฟอร์มการเรียกใช้ evnet submit ข้อมูล
        //     $("#excelFile").on("change",function(e){
        //         e.preventDefault();


        //        var formData = new FormData($("#myform1")[0]);

        //         $.ajax({
        //             url: '../../excel/read',
        //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //             type: 'POST',
        //             data: formData,
        //             /*async: false,*/
        //             cache: false,
        //             contentType: false,
        //             processData: false
        //         }).done(function(data){
        //                 console.log(data);
        //                 // $("#fname").val(data.A2);
        //                 // $("#lname").val(data.B2);
        //         });

        //     });


        // });
        function disableOrEnablebutton(context) {
            if ($("#merchant_order_no").val().trim() == "" && $("#phone_no").val().trim() == "" && $("#tracking_ids").val().trim() == "" && $("#joeyco_order_num").val().trim() == "") {
                $("#search_btn").prop('disabled', true);
            } else {
                $("#search_btn").prop('disabled', false);
            }
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("#datatable tr");
            var cols = rows[1].querySelectorAll("td, th");

            if (cols.length > 1) {
                for (var i = 0; i < rows.length; i++) {
                    var row = [], cols = rows[i].querySelectorAll("td, th");

                    for (var j = 0; j < cols.length - 2; j++) {

                        if (j == 5) {
                            let value = cols[j].innerText.replace(',', ' ').replace(',', ' ').replace(',', ' ').replace(',', ' ');

                            value = value.replace('é', "e");
                            value = value.replace('è', "e");
                            value = value.replace('ê', "e");
                            value = value.replace('ë', "e");

                            value = value.replace('à', "a");
                            value = value.replace('â', "a");

                            value = value.replace('î', "i");
                            value = value.replace('ï', "i");

                            value = value.replace('û', "u");
                            value = value.replace('ù', "u");

                            value = value.replace('ç', "c");

                            value = value.replace('ô', "o");
                            value = value.replace("d’", "d");

                            row.push(value);
                        } else {
                            row.push(cols[j].innerText.replace(',', ' ').replace(',', ' ').replace(',', ' ').replace(',', ' '));

                        }


                    }


                    csv.push(row.join(","));
                }

                // Download CSV file
                downloadCSV(csv.join("\n"), filename);
            } else {
                $('#ex66').modal();
            }

        }

        //statusFunc
        $(function () {
            $(".Status").click(function () {
                // alert("");
                var element = $(this);
                context = element;
                currentRow = element.closest("tr");
                var sprint_id = currentRow.find("td:eq(1)").text();

                var status = currentRow.find("td:eq(7)").find('option:selected').val();

                //   var del_id = element.attr("data-id");


                if (status == '') {
                    $('#ex7').modal();
                } else {
                    var del_id = element.attr("data-id");
                    $('#sprint_id').val('' + sprint_id);
                    $('#statusId').val('' + status);
                    $('#ex4').modal();
                }

            });
        });

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV file
            csvFile = new Blob([csv], {type: "text/csv"});

            // Download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = "none";

            // Add the link to DOM
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();


        }


    </script>
    <script type="text/javascript">
        // Datatable
        $(document).ready(function () {

            $('#datatable').DataTable({
                "lengthMenu": [250, 500, 750, 1000]
            });

            $(".group1").colorbox({height: "50%", width: "50%"});

            $(document).on('click', '.status_change', function (e) {
                var Uid = $(this).data('id');

                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to change user status??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {

                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo URL::to('/'); ?>/api/changeUserStatus/" + Uid,
                                    data: {},
                                    success: function (data) {
                                        if (data == '0' || data == 0) {
                                            var DataToset = '<button type="btn" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="' + Uid + '" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv' + Uid).html(DataToset);
                                        } else {
                                            var DataToset = '<button type="btn" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="' + Uid + '" data-target=".bs-example-modal-sm">Active</button>'
                                            $('#CurerntStatusDiv' + Uid).html(DataToset);
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

            $(document).on('click', '.form-delete', function (e) {

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete user ??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
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

        });
        $(document).ready(function () {
            setTimeout(() => {
                i = $('#datatable').DataTable().rows()
                    .data().length;
                console.log(i);

                if (i > 15) {
                    $(".right_col").css({"min-height": "auto"});
                }
            }, 1000);
        });
    </script>
@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('warning'))
                <div class="alert alert-warning alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('info'))
                <div class="alert alert-info alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    Please check the form below for errors
                </div>
            @endif
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3>Search Orders<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
<h2>Search Orders </h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">

                            <form method="get" action="" id="">


                                <div class="row w-100 d-flex justify-content-center">


                                                   <textarea rows='1' cols="180" name="tracking_id" id="tracking_ids"
                                                             class="form-control"
                                                             onchange="disableOrEnablebutton(this.value)"
                                                             value=""
                                                             style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left"
                                                             placeholder="Tracking Id eg: Test-Order-001"
                                                             title='Search with multiple tracking Id.'></textarea>


                                    <textarea rows='1' cols="180" name="merchant_order_no" id="merchant_order_no"
                                              class="form-control" onchange="disableOrEnablebutton(this.value)"
                                              value=""
                                              style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left"
                                              placeholder="Merchant Order No eg:AN5-001,AN5-002"
                                              title="Search  with multiple merchants Order no."></textarea>

                                    <textarea rows='1' cols="180" name="joeyco_order_num" id="joeyco_order_num"
                                              class="form-control" onchange="disableOrEnablebutton(this.value)"
                                              value=""
                                              style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left"
                                              placeholder="Order No eg:CR-12345678"
                                              title='Search with Order No.'></textarea>

                                    <textarea rows='1' cols="180" name="phone_no" id="phone_no" class="form-control"
                                              onchange="disableOrEnablebutton(this.value)"
                                              value=""
                                              style="margin-top:5px; border-radius: 5px; margin-bottom:5px; margin-right:5px;float: left"
                                              placeholder="Phone No eg:phone001,phone002"
                                              title="Search  with multiple phone no."></textarea>

                                    <button class="btn green-gradient c-btn" id="search_btn" type="submit"
                                            style="margin-top:7px; margin-bottom:5px;  margin-right:5px;float: left">
                                        Search
                                    </button>


                                    <!-- csv download btn -->
                                    <?php  $date = date('Y-m-d'); ?>
                                    <?php  if(can_access_route(['generate-csv'], $userPermissoins)){  ?>
                                    <button onclick="exportTableToCSV('tracking-details-<?php echo $date ?>.csv')"
                                            style="margin-top:7px; margin-bottom:5px;  margin-right:5px;float: left"
                                            type="button" class="btn c-btn orange-gradient">Generate Report in CSV
                                    </button>
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

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <th>Tracking Id</th>
                                        <th>Order Id</th>
                                        <th>Merchant Order No</th>
                                        <th>Customer Phone</th>
                                        <th>Status</th>
                                        <th>Address</th>
                                        <th>Date</th>
                                        <th>Select Status</th>
                                        <th>Control</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($data as $response){

                                    ?>
                                    <tr>
                                        <td><?php echo $response->tracking_id ?></td>
                                        <td><?php echo $response->id ?></td>
                                        <td><?php echo $response->merchant_order_num ?></td>
                                        <td><?php echo $response->phone ?></td>

                                        <td>

                                            <?php
                                            // dd($response->status_id);
                                            if (isset($statusid[$response->status_id])) {
                                                echo $statusid[$response->status_id];
                                            } else {
                                                echo "";
                                            }
                                            ?>


                                        </td>
                                        <td>
                                            @if(!empty($response->address_line2))
                                                <?php echo $response->address_line2; ?>
                                            @else
                                                <?php echo $response->address; ?>
                                            @endif
                                        </td>
                                        <td><?php echo $response->created_at; ?></td>
                                        <td>
                                            <select id="status_id" name="status_id" class='form-control' required>
                                                <option value="">Please Select Status</option>
                                                <?php
                                                foreach($status as $key => $oc){ ?>
                                                <option value="<?php echo $key ?>">
                                                    <?php echo $oc ?></option>
                                                <?php }
                                                ?>
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            @if($response->merchant_order_num == null && $response->tracking_id  == null )
											<?php  if(can_access_route(['searchorderid.show'], $userPermissoins)){  ?>
                                            <a class="btn orange-gradient btn"
                                               href="../../search/ordersid/<?php echo $response->sprint_task_id; ?>/details">Details</a>
											<?php } ?>
                                            @else
												<a class="Status btn green-gradient btn"
                                               data-id='<?php echo $response->id; ?>'>Update Status</a>
                                                <?php  if(can_access_route(['searchorder.show'], $userPermissoins)){  ?>
                                                <a class="btn orange-gradient btn"
                                                   href="../../search/orders/trackingid/<?php echo $response->id; ?>/details">Details</a>
                                                <?php } ?>
                                            @endif


                                        </td>
                                    </tr>
                                    <?php
                                    }

                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        <!-- <?php $date //= //date('Y-m-d H:i:s');  ?>
                                <button onclick="exportTableToCSV('ctc-reprt-<?php //echo $date ?>.csv')" class="btn green-gradient">Generate Report in CSV</button> -->


                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <!-- UpdateStatusModal -->
    <div id="ex4" class="modal" style="display: none">
        <div class='modal-dialog'>

            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Status</h4>
                </div>
                <form action="<?php echo URL::to('/'); ?>/update/order/status" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="sprint_id" name="sprint_id" value="">
                    <input type="hidden" id="statusId" name="statusId" value="">


                    <div class="form-group">
                        <p><b>Are you sure you want to update Status?</b></p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn green-gradient  btn-xs">Yes</button>
                        <button type="button" class="btn red-gradient btn-xs" data-dismiss="modal">No</button>

                    </div>

                </form>


            </div>
        </div>
    </div>

    <div id="ex7" class="modal" style="display: none">
        <div class='modal-dialog'>

            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alert </h4>

                </div>
                <div class="form-group">
                    <p><b>Please Select Status?</b></p>
                </div>


            </div>
        </div>
    </div>

    <div id="ex66" class="modal" style="display: none">
        <div class='modal-dialog'>

            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alert </h4>

                </div>
                <div class="form-group">
                    <p><b>No data to generate CSV report.</b></p>
                </div>


            </div>
        </div>
    </div>

@endsection