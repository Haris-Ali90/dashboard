<?php 
use App\Joey;
use App\Vehicle;
use App\SlotsPostalCode;
use App\SprintConfirmation;


?>
@extends( 'backend.layouts.app' )

@section('title', 'Montreal Route Order Detail ')

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
.half-sec{
      width:50%;
      float:left;
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

.lineEdit {
    width: 100%;
    float: left;
    margin: 5px 0;
}
.addInputs {
    width: 75%;
    float: left;
}
.lineEdit input {
    width: 80% !important;
    float: left;
}
button.remScntedit {
    height: 30px;
    margin: 0 5px;
}
button.remScnt {
    height: 30px;
}
.addMoresec {
    text-align: right;
    padding: 0 50px;
}
.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}
span.lbl {
    color: #000;
}
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform: scale(0.1)} 
  to {transform: scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
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
    
@endsection

@section('content')

<div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3>Order Details <small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
           
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

    <?php foreach($data as $response){

      // dd($response);

         ?>
     
        <h2 style="background: #e36d24;padding: 10px 5px;color: #fff;"><?php echo "CR-".$response->sprint_id;?></h1>
        
        <?php
        echo "<div class='half-sec'>";
        echo "<h4 style='color: #000;background: #0000000d; padding: 5px 2px;'>Task Info</h4>";
        echo "<h5><span class='lbl'>Type</span> : ".$response->type."</h5>";
        echo "<h5><span class='lbl'>Name</span> : ".$response->name."</h5>";
        echo "<h5><span class='lbl'>Phone</span> : ".$response->phone."</h5>";
        echo "<h5><span class='lbl'>Email</span> : ".$response->email."</h5>";
       
       
        echo "<h5><span class='lbl'>Address</span> : ".$response->address."</h5>";
        echo "<h5><span class='lbl'>Description</span> : ".$response->description."</h5>";
        echo "<h5><span class='lbl'>Joey ID</span> : ".$response->joey_id."</h5>";
        echo "<h5><span class='lbl'>Joey</span> : ".$response->joey_firstname." ".$response->joey_lastname."</h5>";
        echo "<h5><span class='lbl'>Merchant</span> : ".$response->merchant_firstname." ".$response->merchant_lastname."</h5>";
        echo "</div>";
        echo "<div class='half-sec'>";
        $statuses = array_merge($response['status'],$response['status1'],$response['status2']
    );
        $sort_key = array_column($statuses, 'created_at');
        $sort_id_key = array_column($statuses, 'id');
        array_multisort($sort_key, SORT_ASC, $statuses);
        
       
        if (array_intersect([114,116,117,118], $sort_id_key))
          {
           
            echo "<br>";
            echo "<h5><span class='lbl'>Route No</span> :".$response['route_id']."</h5>";
            echo "<h5><span class='lbl'>Stop No</span> : ".$response['stop_number']."</h5>";
            echo "<h5><span class='lbl'>Tracking Id</span> : ".$response['tracking_id']."</h5>";
            echo "<h5><span class='lbl'>Joey Contact</span> : ".$response['joey_contact']."</h5>";
           
            $image=SprintConfirmation::where('task_id','=',$response['id'])->whereNotNull('attachment_path')->orderBy('id','desc')->first();
            if(!empty($image))
            {
            //     echo "<a target='_blank' href=src='../".$image->attachment_path."' >
            //     <img src='".$image->attachment_path."' alt='".$response['sprint_id']."' style='width:150px'>
            //   </a>";
           echo "<img id='myImg' src='".$image->attachment_path."' alt='CR-".$response['sprint_id']."' width='300' height='200' >";
             
            }
          }
          echo "</div>";
        ?>
        <h5 style="clear:both;text-align:left" class="accordion"><button class="btn btn-xs orange-gradient color:#000 !important;">Status History 
        <i class="fa fa-angle-down"></i></button></h5>
      <table id="main"  class="table table-striped table-bordered panel">
        
        <thead>
        <tr>
            <th id="main" >Code</th>
            <th id="main">Description</th>
            <th id="main" >Date</th>
        </tr>
        </thead>

        <tbody>
        <?php
       // dd($response);
           
            
             foreach ($statuses as $status){
               echo "<tr>";
               echo "<td>".$status['id']."</td>";
               echo "<td>".$status['description']."</td>";
               echo "<td>".date("Y-m-d H:i:s", strtotime($status['created_at']) )."</td>"; 
               //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>"; 
               echo "</tr>";
             }
//  if(isset($response['status2'])){
//                       foreach ($response['status2'] as $key => $status){
//                       echo "<tr>";
//                       echo "<td>".$status['id']."</td>";
//                       echo "<td>".$status['description']."</td>";
//                       echo "<td>".date("Y-m-d H:i:s", strtotime($status['created_at']) )."</td>";
//                       //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
//                       echo "</tr>";
//                       }
//                       }
//                       if(isset($response['status'])){
//                       foreach ($response['status'] as $key => $status){
//                       echo "<tr>";
//                       echo "<td>".$status['id']."</td>";
//                       echo "<td>".$status['description']."</td>";
//                       echo "<td>".date("Y-m-d H:i:s", strtotime($status['created_at']) )."</td>";
//                       //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
//                       echo "</tr>";
//                       }
//                       }
//                       if(isset($response['status1'])){
//                       foreach ($response['status1'] as $key => $status){
//                       echo "<tr>";
//                       echo "<td>".$status["id"]."</td>";
//                       echo "<td>".$status['description']."</td>";
//                       echo "<td>".$status['created_at']."</td>";
//                       //echo "<td>20".date("y-m-d h:i:s",strtotime($status['created_at']."- 4 hours"))."</td>";
//                       echo "</tr>";

//                       }
//                       }
         ?>
        </tbody> 
      </table>

     
     <?php 
     } 
     ?>



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
  <img class="modal-content" id="img01"  style="height: 600px;" >
  <div id="caption"></div>
</div>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
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
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script>



@endsection