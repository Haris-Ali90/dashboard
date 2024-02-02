<?php


?>
@extends( 'backend.layouts.app' )

@section('title', ' DNR')

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



            $(".group1").colorbox({height: "50%", width: "50%"});




        });

    </script>
    <script>
        $(document).ready(function () {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>

    <script>

        $(function () {
            
            appConfig.set('yajrabox.ajax', '{{ route('dnr.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            //appConfig.set('yajrabox.scrollx_responsive',true);
            appConfig.set('yajrabox.autoWidth',false);

            //appConfig.set('dt.searching',false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.tracking_id = jQuery('[name=tracking_id]').val();
            });

            appConfig.set('yajrabox.columns', [
               {data: 'tracking_id', orderable: true, searchable: true, className:'text-center'},
                {data: 'route_id', orderable: false, searchable: false, className:'text-center'},
                {data: 'joey', orderable: false, searchable: false, className:'text-center'},
				{data: 'address', orderable: false, searchable: false, className:'text-center'},

            ]);
        })

        //pageRefresh();
        /* $(document).ready(function () {
             setInterval(function () {
                 $("#ctcCards").load(window.location.href + " #ctc-dashbord-tiles-id");
                 var ref = $('#yajra-reload').DataTable();
                 ref.ajax.reload();
             }, 50000);
         });*/

         $('.buttons-excel').on('click', function (event) {
            event.preventDefault();
            let href = $(this).attr('href');
            <?php  $tracking_id = isset($_GET['tracking_id'])? $_GET['tracking_id'] : "";
            ?>
            let tracking_id = '<?php echo implode(',',preg_split('/[\ \r\n\,]+/',$tracking_id )); ?>';
            window.location.href = href + '/' + tracking_id;
        });

        function disableOrEnablebutton(context)
        {
            if($("#tracking_ids").val().trim()=="")
            {
                $("#search_btn").prop('disabled', true);
            }
            else
            {
                $("#search_btn").prop('disabled', false);
            }
        }
    </script>
@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">DNR Reporting
                        <small></small>
                    </h3>
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
                            <h2>DNR Reporting
                                <small></small>
                            </h2>
@if(isset($_GET['tracking_id']) && $_GET['tracking_id'] != "" )
                            <div class="excel-btn" style="float: right">
                                <a href="{{ route('dnr.export') }}"
                                   class="btn buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass"
                                   target="_blank">
                                    Export to Excel
                                </a>
                            </div>
@endif
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                            <form method="get" action="reporting">
                                <div class="row">

                                    <div class="col-md-6">
                                                   <textarea rows='1' cols="180" name="tracking_id" id="tracking_ids" style="resize: vertical;"
                                                             class="form-control"
                                                             onchange="disableOrEnablebutton(this.value)"
                                                             value=""
                                                             style="margin-top:5px; margin-bottom:5px; border-radius: 5px; margin-right:5px;float: left"
                                                             required
                                                             placeholder="Tracking Id eg:JoeyCo001,JoeyCo002"
                                                             title='Search with multiple tracking Id.'>{{isset($_GET['tracking_id'])? $_GET['tracking_id'] : ""}}</textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary" type="submit" style="  margin-top: 5px;" id="search_btn">
                                            Search</a> </button>
                                    </div>
                                </div>


                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )


                            <table class="table table-striped table-bordered yajrabox" id="yajra-reload">
                                <thead stylesheet="color:black;">
                                <tr>
                                    <th style="width: 25%" class="text-center ">Tracking #</th>
                                    <th style="width: 20%" class="text-center ">Route #</th>
                                    <th style="width: 30%" class="text-center ">Joey</th>
<th style="width: 25%" class="text-center ">Address</th>



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
    <!-- /#page-wrapper -->

    <!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

    <!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

    <!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->



@endsection