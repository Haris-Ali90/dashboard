<?php 
use App\Joey;
use App\Vehicle;
use App\SlotsPostalCode;
use App\Sprint;
use App\MerchantIds;

?>
@extends( 'backend.layouts.app' )

@section('title', 'Montreal Dashboard')

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
span.select2-selection.select2-selection--multiple {
    height: 39px;
}
.form-control {
    height: 39px !important;
}
.modal-header .close 
{
    opacity: 1;
    margin: 5px 0;
    padding: 0;
}
.modal-footer .close 
{
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
    padding: 5px 15px;
    border-bottom: 1px solid #e5e5e5;
    background: #c6dd38;
}
/*button.button.orange-gradient {
    border: none;
    line-height: 12px;
    display: inline-block;
    margin: 0;
    border-radius: 4px;
    padding: 8px 20px;
    color: #fff;
    background: #e46d24;
}*/
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
input#date {
    height: 30px;
    width: 194px;
}
#tracking_id_chosen {
    width: 280px !important;
    margin-right: 5px !important;
    float: left;
    
}
/* #sprint_id_chosen {
    width: 280px !important;
    margin-right: 5px !important;
    float: left;
} */
select {
    margin: 0px 5px 0 0 !important;
}
/* .tracking_id input {
    padding: 18px !important;
}
.sprint_id input {
    padding: 18px !important;
} */
span.select2.select2-container.select2-container--default {
    width: 100% !important;
}
/* span.select2-selection.select2-selection--multiple {
    height: 35px !important;
} */
.tracking_id {
    width: 25%;
    margin-right: 5px;
    float: left;
}
.sprint_id {
    width: 25%;
    margin-right: 5px;
    float: left;
}

    </style>
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
    "110" => "Delivery to hub for re-delivery",
    "111" => "Delivery to hub for return to merchant",
    "121" => "Out for delivery",
    "102" => "Joey Incident",
    "104" => "Damaged on road - delivery will be attempted",
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
        "141" => "Lost package");
    
    ?>
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
    
@endsection

@section('content')

<div class="right_col" role="main">
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3>Update Status<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
           
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <!-- <button style="margin-left:10px" class="btn green-gradient" data-toggle="modal" data-target="#ex1"> <i class="fa fa-plus"></i> Create Slot</button> -->
                            <div class="clearfix"></div>
                        </div>


                        <div class="x_content">

                         @include( 'backend.layouts.notification_message' )

                         <?php 
                if(!isset($_REQUEST['date']) || empty($_REQUEST['date'])){
                    $date = date('Y-m-d');
                }
                else {
                    $date = $_REQUEST['date'];
                }
                ?> 
            
                 
                      
                        <form id="filter"  style="padding: 10px;" action="" method="get">
                <input id="date" name="date" style="width:35%px" type="date" placeholder="date" value='<?php echo $date; ?>' class="form-control1">
                <button  id="search" type="submit" class="btn green-gradient">Filter</button>

            </form>

                        </div>
                        <div class="x_content">


                        <form id="filter" name='typeCV' style="padding: 10px;" action="../../hub/routific/updatestatus" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="date" id="date" value='<?php echo $date; ?>' class="form-control"  />
                <!-- <div class="selectId">
               
                  <select name='type' id='type' style="width:25%; float:left;"  class="form-control"  onchange="myFunction()"> 

                  <option value='0'>Select type</option>  
                   <option value='1'>Order ID </option>                               
                   <option value='2'>Tracking ID</option>  
                 </select>
                 </div>  -->
            
              <!-- <div class="sprint_id" >
                <select   id="sprint_id" name="sprint_id[]" style="width:45%;  float:left;" multiple class="form-control chosen-select" required >  -->
                <!-- <option value="">Please Select Sprint ID</option> -->
                <?php
                // $sprint=Sprint::join('sprint__tasks','sprint__tasks.sprint_id','=','sprint__sprints.id')
                // ->distinct()->whereNull('sprint__sprints.deleted_at')
                // ->where('sprint__sprints.created_at','like',$date."%")

                // ->get(['sprint__sprints.id']);

                // foreach($sprint as $oc){ ?>
                <!-- //     <option value="<?php //echo $oc->id ?>"> -->
                <!-- //     CR-<?php //echo $oc->id ?></option> -->
             <?php 
                //}
                ?>
                <!-- </select>
        </div> -->
        <label>Select Tracking ID</label><br>
        <div class="tracking_id" style="float: left;" >
            <select multiple id="tracking_id" name="tracking_id[]" style="width:45%;  " multiple class="js-example-basic-multiple form-control col-md-7 col-xs-12" required >
              <!-- <option value="">Please Select Tracking ID</option> -->
			  <?php 
              $tracking=MerchantIds::join('sprint__tasks','merchantids.task_id','=','sprint__tasks.id')
              ->whereNull('sprint__tasks.deleted_at')
              ->whereNull('merchantids.deleted_at')->whereNotNull('merchantids.tracking_id')->distinct()
              ->where('merchantids.created_at','like',$date."%")
              ->get(['merchantids.tracking_id','merchantids.id']);
			 
			  
              foreach($tracking as $v){
				 
			  
				 ?>
                  <option value="<?php echo $v->id ?>"><?php echo $v->tracking_id?></option>
             <?php }
              ?>
            </select>
        </div>
      
        <div class="status" >
            <select  id="status_id" name="status_id" style="width:45%;  float:left;" class='form-control' required > 
              <option value="">Please Select Status</option>
              <?php
            // $s=array(101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131);
            
			

              foreach($status as $key => $oc){ ?>
                  <option value="<?php echo $key ?>">
				  <?php echo $oc ?></option>
             <?php }
              ?>
            </select>
        </div>
                    </div>
                    <br><br>
                  <button style="height:40px; margin-left: 16px;" id="search" type="submit" class="btn green-gradient">Update Status</button>
                  
              </form>
                          
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

    
    <!-- CreateSLotsModal -->
    
    <!-- UpdateSLotModal -->










<script type="text/javascript">
  $(document).ready(function() {
$('#sprint_id').select2();
});
$(document).ready(function() {
$('#tracking_id').select2();
});

</script>

 <script type="text/javascript">
     
        $(document).ready(function() {
   
       
   $(".tracking_id").show();
//    $(".sprint_id").hide();
//    $('#tracking_id').prop('required',false);
//     $('#tracking_id').prop('required',false);


  
});

// function myFunction() {

// if (document.typeCV.type.value == "2")  {
// $(".tracking_id").show();
// //$('.sprint_id').removeAttr('required');​​​​​
// $('#tracking_id').prop('required',true);
// $(".sprint_id").hide();
// $('#sprint_id').prop('required',false);
// }
// else if(document.typeCV.type.value == "1"){
// $(".sprint_id").show();
// $('#tracking_id').prop('required',false);
// $(".tracking_id").hide();
// $('#sprint_id').prop('required',true);
// }
// else{
// $(".tracking_id").hide();
//    $(".sprint_id").hide();
// }
// }
$(".chosen-select").chosen({
no_results_text: "Oops, nothing found!"
})

    </script>
@endsection