<?php

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

@section('title', 'Manual Tracking Report')

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
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')


    <script>

        $(function () {
            appConfig.set('yajrabox.ajax', '{{ route('manual-tracking.data') }}');
            appConfig.set('dt.order', false);
            //appConfig.set('yajrabox.scrollx_responsive',true);
            //appConfig.set('yajrabox.autoWidth',false);
			//appConfig.set('dt.searching',true);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.datepicker = jQuery('[name=datepicker]').val();
                data.datepicker2 = jQuery('[name=datepicker2]').val();
            });

            appConfig.set('yajrabox.columns', [
                {data: 'tracking_id', orderable: false, searchable: true, className: 'text-center'},
                {data: 'status_id', orderable: false, searchable: true, className: 'text-center'},
                {data: 'attachment_path', orderable: false, searchable: false, className: 'text-center'},
                {data: 'reason_id', orderable: false, searchable: false, className: 'text-center'},
                {data: 'user_id', orderable: false, searchable: true, className: 'text-center'},
                {data: 'domain', orderable: false, searchable: true, className: 'text-center'},
                {data: 'created_at', orderable: false, searchable: true, className: 'text-center'}
            ]);
        })

        $('.buttons-excel').on('click',function(event){
            event.preventDefault();
            let href = $(this).attr('href');
            let selected_date = $('.data-selector').val();
            let toselected_date = $('#EndDate').val();
            window.location.href = href+'/'+selected_date+'/'+toselected_date;
        });

    </script>
@endsection

@section('content')
<style>
    .mynewloader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        position: fixed;
        left: 50%;
        top: 50%;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
        z-index: 9999;
    }
    /* .loader{
         filter: blur(2px);
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('//upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Phi_fenomeni.gif/50px-Phi_fenomeni.gif') 
                    50% 50% no-repeat rgb(249,249,249);
    } */
    
    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    </style>


    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  
    <div class="right_col" role="main">
       
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3 class="text-center">Manual Tracking Report<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
       {{-- @include('backend.ctc.ctc_new_cards')--}}
        <!--Count Div Row Close-->
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                           <div class="col-lg-6">
                           <h2>Manual Tracking Report<small></small></h2>
                           </div>
                           <div class="col-lg-6">
                           @if(can_access_route('manual-tracking.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right">
                                    {{-- <a href="{{ route('manual-tracking.excel') }}"
                                       class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                        Export to Excel
                                    </a> --}}
                                    <button type="button" id="getcsv" class="btn c-btn btn-sm btn-primary excelstyleclass" onclick="getcsv();"> Export to Excel</button>
                                </div>
                            @endif
                           </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                            <form id="form1" method="get" action="">
                                {{-- onchange="checkdatediff()" --}}
                              <div class="form-group">
                              <label>Search By Date</label>
                                <br>
                                <div class="col-lg-2">
                                <label>From Date</label>
                                <input  id="StartDate" type="date" name="datepicker" class=" form-control data-selector" required="" value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}" placeholder="Search">
                                </div>
                               <div class="col-lg-2">
                               <label>To Date</label>
                                <input  id="EndDate" type="date" name="datepicker2" class=" form-control data-selector2" required="" value="{{ isset($_GET['datepicker2'])?$_GET['datepicker2']: date('Y-m-d') }}" placeholder="Search">
                               </div>
                              </div>
                              <div class="col-lg-2" style="margin: 8px 0px 0px 0px   !important;">
                                  <button class="btn btn-primary c-btn" type="button" onclick="submitform()" style="margin-top: -3%,4%">Go</a> </button>
                              </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                            <table class="table table-striped table-bordered yajrabox" id="yajra-reload">
                                <thead stylesheet="color:black;">
                                <tr>
                                    <th class="text-center " style="width: 10%">Tracking #</th>
                                    <th class="text-center " id="status" style="width: 20%">Status</th>
                                    <th class="text-center "style="width: 10%">Image</th>
                                    <th class="text-center "style="width: 20%">Reason</th>
                                    <th class="text-center "style="width: 15%">User</th>
                                    <th class="text-center "style="width: 10%">Domain</th>
                                    <th class="text-center "style="width: 10%">Created At</th>


                                </tr>
                                </thead>
                                <tbody>
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

     <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<!--<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

    <!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

    <!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->

<script>
    $(document).ready(function(){
        /*setTimeout(function() {
                $( "th" ).removeClass( "sorting_asc");
        }, 1000);*/
    });
    const loader='<div class="mynewloader"></div>';
    function showLoader() {
        $('.main_container').css('filter','blur(2px)');
        $(".container.body").prepend(loader);
    }
    function hideLoader() {
        $('.container.body .mynewloader').remove();
        $('.main_container').css('filter','none');
    }

    // $(document).ready(function(){
        // $("#EndDate").change(function () {
        // function checkdatediff() {
            
        //     var startDate = document.getElementById("StartDate").value;
        //     var endDate = document.getElementById("EndDate").value;
        //     var CurrentDate = new Date();
        //     var newstartDate = new Date(startDate);
        //     var newendDate = new Date(endDate);
        //     var diffDays = newendDate.getDate() - newstartDate.getDate(); 
        //     // console.log(diffDays);
        //     if(newstartDate > CurrentDate || newendDate > CurrentDate){
        //         alert('Given date range is greater than today . Please select valid dates.');
        //         document.getElementById("StartDate").value = CurrentDate.toISOString().slice(0, 10);
        //         document.getElementById("EndDate").value = CurrentDate.toISOString().slice(0, 10);
        //     }

        //     else if ((Date.parse(startDate) > Date.parse(endDate))) {
        //         alert("To date should be greater than or equal to From date");
        //         document.getElementById("StartDate").value = CurrentDate.toISOString().slice(0, 10);
        //         document.getElementById("EndDate").value = CurrentDate.toISOString().slice(0, 10);
        //     }
        //     else if (diffDays > 7) {
        //         alert("Max date range is 7 days.");
        //         document.getElementById("StartDate").value = CurrentDate.toISOString().slice(0, 10);
        //         document.getElementById("EndDate").value = CurrentDate.toISOString().slice(0, 10);
        //     }

        // }    
    function submitform() {
        var startDate = document.getElementById("StartDate").value;
        var endDate = document.getElementById("EndDate").value;
        var CurrentDate = new Date();
        var newstartDate = new Date(startDate);
        var newendDate = new Date(endDate);
        var diffDays =    (diffDays = (newendDate.getTime() - newstartDate.getTime()) / (1000 * 3600 * 24))+1;
        // console.log((diffDays));
        if(newstartDate > CurrentDate || newendDate > CurrentDate){
            alert('Given date range is greater than today . Please select valid dates.');
            document.getElementById("StartDate").value = CurrentDate.toISOString().slice(0, 10);
            document.getElementById("EndDate").value = CurrentDate.toISOString().slice(0, 10);
        }
        else if ((Date.parse(startDate) > Date.parse(endDate))) {
            alert("To date should be greater than or equal to From date");
            document.getElementById("StartDate").value = CurrentDate.toISOString().slice(0, 10);
            document.getElementById("EndDate").value = CurrentDate.toISOString().slice(0, 10);
        }
        else if (diffDays > 30) {
            alert("Maximum date range is 30 days.");
            document.getElementById("StartDate").value = CurrentDate.toISOString().slice(0, 10);
            document.getElementById("EndDate").value = CurrentDate.toISOString().slice(0, 10);
        }
        else{
            // console.log('submit');
            $('#form1').submit();
        }
    }
        // });
    // });
</script>
<script>
    function downloadFile(url) {
        // create element
        let requested_file_path = url.replace(app_url+'/','');
        requested_file_path =  requested_file_path.replace(/ /g,'%20');
        //console.log(app_url+'/download-file?file_path='+requested_file_path);
        window.location.href = app_url+'/download-file-tracking?file_path='+requested_file_path;
    }



    // show loader on submit
    // $('form').submit(function (event) {
    //    //ShowSessionAlert('info','The downloading will start in a moment please be patient !');
    //     // stoping form submit
    //     event.preventDefault()

    //     // get from inputs data
    //     let  method = $(this).attr('method');
    //     let  url = $(this).attr('action');
    //     let  all_inputs = $(this).serializeArray();
    //     let  request_data = {};
    //     all_inputs.forEach(function (data,index) {
    //         request_data[data.name] = data.value;
    //     });
    //     // setting limimt
    //     request_data['limit'] = 500;
    //     // setting page
    //     request_data['page'] = 1;

    //     // calling ajax
    //     sendDownloadFileajax(url,method,request_data,true,0);

    // });
    function getcsv() {
        showLoader();

        // get from inputs data
        let  method ='post';
        let  url = "{{route('manual-tracking.excel')}}";
        // let  all_inputs = $(this).serializeArray();
        let  request_data = {};
        // all_inputs.forEach(function (data,index) {
            request_data['fromdatepicker'] = document.getElementById("StartDate").value;
            request_data['todatepicker'] = document.getElementById("EndDate").value;

        // });
        // setting limimt
        request_data['limit'] = 500;
        // setting page
        request_data['page'] = 1;

        // calling ajax
        sendDownloadFileajax(url,method,request_data,true,0);
    }


    //function for call ajax for download file 
    function sendDownloadFileajax(url ='' , method = '' , request_data = {} , progress_bar_create = false , pregress_per = 0) {
        //create progress bar
        // if(progress_bar_create)
        // {
        //     showProgressBar();
        // }
        // else // update progress bar
        // {
        //     updateProgressBar(pregress_per);
        // }

        // sending ajax
        $.ajax({
            type: method,
            url: url,
            data:request_data,
            success: function (response) {

                // hide error connection alert
                // progressBarErrorHide();

                let request_data = response.metaData;
                let totalRecords = response.paging.total_records;
                let completed_records = response.paging.current_page * response.paging.limit;
                let Percentage_Completed = 100;
                // checking  the total records is not zero
                if(totalRecords > 0 ) {
                    Percentage_Completed = (completed_records / totalRecords ) * 100;
                }
                // checking the record is grather then 0
                if(completed_records >= totalRecords)
                {
                    // update progress bar
                    // updateProgressBar(Percentage_Completed.toFixed(2));

                    // trigger downloading
                    downloadFile(response.metaData.downloadPath);

                    // remove progress bar
                    setTimeout(function(){
                        // hideProgressBar()
                        hideLoader();
                    }, 1000);
                }
                else if(completed_records < totalRecords)
                {
                    request_data['page'] = response.paging.current_page + 1;
                    // calling ajax
                    sendDownloadFileajax(url,method,request_data,false,Percentage_Completed.toFixed(2));

                }

            },
            error:function (error) {

                console.log(error);
                // checking the date validaion
                // checking key exist
                if('errors' in error.responseJSON)
                {

                    let errors = error.responseJSON.errors;
                    // looping the errors
                    for(const index in  errors)
                    {
                        var single_error = errors[index];
                        // checking the type of error
                        if(typeof single_error == 'object')
                        {
                            // showing errors by loop
                            single_error.forEach(function(value){
                                // ShowSessionAlert('danger',value);
                            });

                        }
                        else
                        {
                            // ShowSessionAlert('danger',single_error);
                        }
                    }

                    // removeing header
                    // hideProgressBar();
                    hideLoader();
                    // return with error show and stop the ajax
                    return false;

                }
                else if('error' in error.responseJSON) // session end error handling
                {
                    if(error.responseJSON.error == 'Unauthenticated.')
                    {
                        alert("Your Session is expired");
                        location.reload();
                        return false;
                    }
                }

                // show error connection alert
                // progressBarErrorShow();

                request_data['error'] = 'block';
                let metaData = request_data.metaData;
                // checking metaData is exsit or not
                if(typeof metaData !== 'undefined')
                {
                    let totalRecords = parseInt(metaData.total_records);
                    let completed_records = parseInt(metaData.page) * parseInt(metaData.limit);
                    let Percentage_Completed = 100;
                    // checking  the total records is not zero
                    if(totalRecords > 0 ) {
                        Percentage_Completed = (completed_records / totalRecords ) * 100;
                    }

                    sendDownloadFileajax(url,method,request_data,false,Percentage_Completed.toFixed(2));
                }
                else
                {
                    var current_persent = parseFloat($('.progress-main-wrap').find('.progress-bar').text());
                    sendDownloadFileajax(url,method,request_data,false,current_persent);
                }

            }
        });
    }
</script>

@endsection