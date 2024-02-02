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

@section('title', 'Generate Walmart Orders Report')

@section('CSSLibraries')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />
@endsection

@section('JSLibraries')

    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $(".group1").colorbox({height: "50%", width: "50%"});

            $("#fromdatepicker").datetimepicker
            ({
                format :'YYYY-MM-DD',
                maxDate: '{{date('Y-m-d',strtotime( '-1 days' ))}}',
            });

            $("#todatepicker").datetimepicker({
                format :'YYYY-MM-DD',
                maxDate: '{{date('Y-m-d',strtotime( '-1 days' ))}}',
            });

            $("#fromdatepicker").on("dp.change", function (e) {
                $('#todatepicker').data("DateTimePicker").minDate(e.date);
            });
            $("#todatepicker").on("dp.change", function (e) {
                $('#fromdatepicker').data("DateTimePicker").maxDate(e.date);
            });


            // show loader on submit
            $('form').submit(function (event) {
                //ShowSessionAlert('info','The downloading will start in a moment please be patient !');
                // stoping form submit
                event.preventDefault()

                // get from inputs data
                let  method = $(this).attr('method');
                let  url = $(this).attr('action');
                let  all_inputs = $(this).serializeArray();
                let  request_data = {};
                all_inputs.forEach(function (data,index) {
                    request_data[data.name] = data.value;
                });
                // setting limimt
                request_data['limit'] = 500;
                // setting page
                request_data['page'] = 1;

                // calling ajax
                sendDownloadFileajax(url,method,request_data,true,0);

            });

            //function for call ajax for download file
            function sendDownloadFileajax(url ='' , method = '' , request_data = {} , progress_bar_create = false , pregress_per = 0) {
                //create progress bar
                if(progress_bar_create)
                {
                    showProgressBar();
                }
                else // update progress bar
                {
                    updateProgressBar(pregress_per);
                }

                // sending ajax
                $.ajax({
                    type: method,
                    url: url,
                    data:request_data,
                    success: function (response) {

                        // hide error connection alert
                        progressBarErrorHide();

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
                            updateProgressBar(Percentage_Completed.toFixed(2));

                            // trigger downloading
                            downloadFile(response.metaData.downloadPath);

                            // remove progress bar
                            setTimeout(function(){
                                hideProgressBar()
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

//                        console.log(error);
//                        console.log('todatepicker' in error);
//                        return;

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
                                        ShowSessionAlert('danger',value);
                                    });

                                }
                                else
                                {
                                    ShowSessionAlert('danger',single_error);
                                }
                            }

                            // removeing header
                            hideProgressBar();
                            // return with error show and stop the ajax
                            return false;

                        }
                        else if('todatepicker' in error.responseJSON)
                        {
                            let errors = JSON.parse(error.responseText);
                            // looping the errors
                            for(const index in  errors)
                            {
                                var single_error = errors[index];
                                // checking the type of error
                                if(typeof single_error == 'object')
                                {
                                    // showing errors by loop
                                    single_error.forEach(function(value){
                                        ShowSessionAlert('danger',value);
                                    });

                                }
                                else
                                {
                                    ShowSessionAlert('danger',single_error);
                                }
                            }

                            // removeing header
                            hideProgressBar();
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
                        progressBarErrorShow();

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

        });



    </script>
@endsection

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Generate Walmart Orders Report
                        <small></small>
                    </h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Generate Walmart Orders Report
                                <small></small>
                            </h2>
                            {{-- @if(can_access_route('export_ctc_reporting.excel',$userPermissoins))
                                 <div class="excel-btn" style="float: right; display: none" id="excel">
                                     <a href="{{ route('export_ctc_reporting.excel') }}"
                                        class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                         Export to Excel
                                     </a>
                                 </div>
                             @endif--}}

                            <div class="clearfix"></div>

                        </div>
                        @include( 'backend.layouts.notification_message' )
                        <div class="x_title">
                            <form method="POST" action="{{route('generate-walmart-report-csv')}}">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>From Date:</label>
                                        <input id="fromdatepicker" type="text" name="fromdatepicker"
                                               class="data-selector form-control" required=""
                                               value="{{ isset($_GET['fromdatepicker'])?$_GET['fromdatepicker']: date('Y-m-d',strtotime( '-1 days' )) }}"
                                               max="{{date('Y-m-d',strtotime( '-1 days' ))}}"
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <label>To Date:</label>
                                        <input
                                                type="text" id="todatepicker" name="todatepicker"
                                                class="data-selector form-control" required=""
                                                value="{{ isset($_GET['todatepicker'])?$_GET['todatepicker']: date('Y-m-d',strtotime( '-1 days' ))  }}"
                                                max="{{date('Y-m-d',strtotime( '-1 days' ))}}"
                                        >
                                    </div>
                                    {{--<label style="    margin-left: 223px;">Select Vendor</label>
                                    {!! Form::select('vendors', $vendors, null, ['class' => 'sel']) !!}--}}
                                    <div class="col-md-2">
                                        <button class="btn btn-block btn-primary" type="submit" style="    margin-top: 23px;">Generate CSV</button>
                                    </div>
                                </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <!--Count Div Row Open-->
                    <!--Count Div Row Close-->


                        <div class="x_content">
                        <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->
@endsection