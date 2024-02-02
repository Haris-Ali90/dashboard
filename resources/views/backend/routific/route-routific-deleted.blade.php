<?php 
use App\Joey;
use App\Vehicle;
use App\SlotsPostalCode;


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
                    <h3>Delete Route<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
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
            
                 
                      
                        <form id="filter"  style="padding: 10px;" action="../route/delete" method="post">  
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id" name="id" style="width:35%px" type="text" placeholder="Route ID "  required  class="form-control1">
                        <button class='green-gradient btn'  title='Delete Route'>Delete Route</button>
                
            </form>

                        </div>
                        <div class="x_content">

                          
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
    var scntDiv = $('#add_words');
    var wordscount = 1;
    // var i = $('.line').size() + 1;
    var i = 0;
    $('#add').click(function() {
    	// alert()
        var inputFields = parseInt($('#inputs').val());
        for (var n = i; n < inputFields; ++ n){
            wordscount++;
            $('<div class="line"><label>Postal Code </label><input type="text" value="" name="postal_code[]" maxlength="3 " /> <button class="remScnt">x</button></div>').appendTo(scntDiv);
            i++;
        }  
        return false;
    });

    //    Remove button
    $('#add_words').on('click', '.remScnt', function() {
        if (i > 1) {
            $(this).parent().remove();
            i--;
        }
        return false;
    });
});






$(document).ready(function(){
  $(".update").click(function(){
    var a;
    var element = $(this);
    var del_id = element.attr("data-id");
    console.log(del_id);
    $.ajax({
        type: "GET",
        url: '../../slots/'+del_id+'/update',

        success: function(data)
        {   
         a = JSON.parse(data);

          
          $('#id_time').val(''+a['data']['id']);
          $('#start_time_edit').val(''+a['data']['start_time']);
          $('#end_time_edit').val(''+a['data']['end_time']);

          // $( "#start_time_edit").prop('type','datetime-local');
          // starttime = a['data']['start_time'];
          // starttime = starttime.replace(" ","T");
          // starttime = starttime.slice(0,-3);
          // $('#start_time_edit').val(starttime);

          // $( "#end_time_edit").prop('type','datetime-local');
          // endtime = a['data']['end_time'];
          // endtime = endtime.replace(" ","T");
          // endtime = endtime.slice(0,-3);
          // $('#end_time_edit').val(endtime);

          $('#vehicle_edit').val(''+a['data']['vehicle']);
          $('#joey_count_edit').val(''+a['data']['joey_count']);

          console.log(a['postalcodedata']);
          arrNew = [];
          $.each(a['postalcodedata'], function (i , val) 
          {
             arrNew.push(val['attributes']['postal_code'])  
          })
          console.log(arrNew);
          var addInputs = $('.addInputs');
          var inputcount = arrNew.length;
          // console.log(inputcount);
          var i = 0;
          $(addInputs).empty();
          for (var n = i; n < inputcount; ++ n)
          {
            $('<div class="lineEdit"><input type="text" value='+arrNew[i]+' name="postal_code_edit[]" maxlength="3" class="form-control"><button class="remScntedit" >x</button></div>').appendTo(addInputs);
            i++;
          }  

          $('.addInputs').on('click', '.remScntedit', function() {
            if (i > 1) {
                $(this).parent().remove();
                i--;
         }
          return false;
    });
          // var addMoresec = $('.addMoresec');

          $("#addmore").click(function(){
            $('<div class="lineEdit"><input type="text" value="" name="postal_code_edit[]" maxlength="3" class="form-control"><button class="remScntedit" >x</button></div>').appendTo(addInputs);
          });

          // // $( "#addmore" ).click(function() {
          // //   $('<div class="lineEdit"><input type="text" value='' name="postal_code_edit[]" class="form-control"><button class="remScntedit" >x</button></div>').appendTo(addMoresec);
          // // });


            // arrNew = [];
            // $.each(a['joeysdata'], function(i, val)
            // {
            //   arrNew.push(val['attributes']['joey_id']);    
            // });

            // $('.chosen-select').val(arrNew);
            // $('.chosen-select').trigger('change');

          $('#ex2').modal();

         
        }
      });
});
});
</script>

 <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').DataTable({
              "lengthMenu": [ 250, 500, 750, 1000 ]
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
                    buttons: {
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
                                            var DataToset = '<button type="btn" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                        else
                                        {
                                            var DataToset = '<button type="btn" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Active</button>'
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

    </script>
@endsection