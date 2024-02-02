<?php

?>
@extends( 'backend.layouts.app' )



@section('title', 'CTC Route Order Details')

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
                    <h3> {{"CR-".$sprintId}} </h3>
                </div>
            </div>

            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>CTC Route Order Details <small></small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-2 col-sm-2 col-xs-12 profile_left">

                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Task Info </a>
                                        </li>
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <table class="table table-bordered">
                                                <?php foreach($data as $response){
                                                    //dd($response->joey_lastname)
                                                ?>
                                                <thead>
                                                <tr>
                                                   {{-- <th colspan="2" >Task Info </th>--}}
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><label>Type</label></td>
                                                    <td>{{$response->type }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Name</label></td>
                                                    <td>{{$response->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><lable>Email</lable></td>
                                                    <td>{{$response->email  }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Phone</label></td>
                                                    <td>{{$response->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Address</label></td>
                                                    <td>{{$response->address }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Description</label></td>
                                                    <td>{{$response->description }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Joey ID</label></td>
                                                    <td>{{$response->joey_id }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Joey</label></td>
                                                    <td>{{$response->joey_firstname." ".$response->joey_lastname }}</td>
                                                </tr>
                                                <tr>
                                                     <td><label>Merchant</label></td>
                                                     <td>{{$response->merchant_firstname." ".$response->merchant_lastname }}</td>
                                                 </tr>


                                                <?php
                                                $statuses = array_merge($response['status'],$response['status1'],$response['status2']
                                                );

                                                $sort_key = array_column($statuses, 'created_at');
                                                $sort_id_key = array_column($statuses, 'id');
                                                array_multisort($sort_key, SORT_ASC, $statuses);


                                                if (array_intersect([114,116,117,118], $sort_id_key))
                                                {
                                                ?>
                                                <tr>
                                                    <td><label>Route No</label></td>
                                                    <td>{{ $response['route_id'] or "N/A" }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Stop No</label></td>
                                                    <td>{{ $response['stop_number'] or "N/A" }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Tracking Id</label></td>
                                                    <td>{{ $response['tracking_id'] or "N/A" }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Joey Contact</label></td>
                                                    <td>{{ $response['joey_contact'] or "N/A" }}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Image</label></td>
                                                    <?php
                                                    $image=\App\SprintConfirmation::where('task_id','=',$response['id'])->whereNotNull('attachment_path')->orderBy('id','desc')->first();
                                                    if(!empty($image))
                                                    {
                                                    ?>
                                                    <td>
                                                        <img onclick="ShowLightBox(this);"  src="{{$image->attachment_path}}" width='300' height='200' alt={{"CR-".$response['sprint_id']}}/>
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    if(str_contains($response['tracking_id'], 'old_')){
                                                        $tracking_id = substr($response['tracking_id'], strrpos($response['tracking_id'], '_') + 1);
                                                    }
                                                    else{
                                                        $tracking_id = $response['tracking_id'];
                                                    }
                                                    $order_image = \App\OrderImage::where('tracking_id', $tracking_id)->whereNull('deleted_at')->orderBy('id','desc')->first();
                                                    if(!empty($order_image))
                                                    {
                                                    ?>
                                                    <td><label>Additional Image</label></td>
                                                    <td>
                                                        <img onclick="ShowLightBox(this);"
                                                             src="{{$order_image->image}}" width='300' height='200'
                                                             alt={{"CR-".$response['sprint_id']}}/>
                                                    </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                            <!-- end user projects -->

                                            <h5 style="clear:both;text-align:left" class="accordion"><button class="btn btn-xs orange-gradient color:#000 !important;">Status History
                                                    <i class="fa fa-angle-down"></i></button></h5>
                                            <table id="main"  class="table table-striped table-bordered panel" style="display: none">

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
                                                ?>
                                                </tbody>
                                            </table>
                                            <?php }?>
                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

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