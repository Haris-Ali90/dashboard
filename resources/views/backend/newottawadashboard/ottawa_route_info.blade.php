<?php

$user = Auth::user();
if($user->email!="admin@gmail.com")
{

$data = explode(',', $user['rights']);
$dataPermission = explode(',', $user['permissions']);
}

else{
    $data = [];
    $dataPermission=[];
}

 ?>
@extends( 'backend.layouts.app' )

@section('title', 'Ottawa Route Info')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
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
            top: -1px;
            /*bottom: 0px;*/
            left: 100%;
            padding: 0% 0px 0px 5px;
        }
        .hoverable-dropdown-ul > li:hover > .hoverable-dropdown-ul {
            display: block !important;
            z-index: 10;
            position: absolute;
            top: -1px;
            /*bottom: 0px;*/
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
        .modal-content {
            width: 129%;
            height: 230px;
        }
    </style>

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"
            type="text/javascript"></script>

    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->

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
                                            var DataToset = '<button type="button" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                        else
                                        {
                                            var DataToset = '<button type="button" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Active</button>'
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

            //Call to open modal
            $(document).on('click', '.createFlag', function (e) {
                // getting data from button and send to model
                let passing_data = $(this).attr("data-flag_values");
                // showing model and getting el of model
                let model_el = $('#create-flag-modal').modal();
                // setting data to model
                $('#model_flag_data').val(passing_data);
            });

            //Create flag
            $('.can-apply-flag').click(function (e) {
                e.preventDefault();
                let el = $(this);
                //let child_flag_id = el.val();
                let child_flag_id = el.attr("data-id");
                //let order_data = JSON.parse($('#flag_data').val());
                let order_data = JSON.parse($('#model_flag_data').val());
                //getting previous flagged category count
                let previous_flagged_cat_count = $('.flag-tr-cat-bunch-' + child_flag_id).length;
                //let total_flag_cat_count = $('.flag-tr').length;

                // checking child data exist
                if (child_flag_id == '') {
                    return false;
                }

                //multiple flagged errors
                let flagged_errors = {
                    1: "This order is flagged 2nd time, would you like to re-flag this order",
                    2: "This order is flagged 3rd time, would you like to re-flag this order",
                    3: "This order is flagged 4th time, would you like to re-flag this order",
                    4: "The joey of this order has been terminated already",
                };

                if (previous_flagged_cat_count >= 4) // this block check the total flag orders count
                {
                    var confirmatoin = alert(flagged_errors[4]);
                    if (!confirmatoin) {
                        location.reload();
                        return;
                    }
                }
                if (previous_flagged_cat_count in flagged_errors) // this block check the order is already flagged or not
                {
                    var confirmatoin = confirm(flagged_errors[previous_flagged_cat_count]);
                    if (!confirmatoin) {
                        return;
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
                                    url: "{{URL::to('/')}}/flag/create/" + child_flag_id,
                                    data: order_data,
                                    success: function (response) {
                                        hideLoader();
                                        if (response.status == true) // notifying user  the update is completed
                                        {
                                            // getting current url with query string
                                            $current_utl =  window.location.href;
                                            let url_without_query_string = $current_utl.split('?')[0];
                                            // converting query string into jason
                                            let query_json  = urlQueryTOJason($current_utl);
                                            // removeing old message form query string
                                            delete query_json['message'];
                                            // updating new message to query string
                                            query_json['message'] =  response.message;
                                            // creating url string
                                            let url = $.param(query_json);
                                            // redirecting
                                            window.location.href = url_without_query_string+'?'+url;

                                        }
                                        else // update  failed by server
                                        {
                                            // show session alert
                                            ShowSessionAlert('danger', response.message);
                                            $('#create-flag-modal').modal('hide');
                                        }

                                    },
                                    error: function (error) {
                                        hideLoader();
                                        ShowSessionAlert('danger', 'Something wrong');
                                        $('#create-flag-modal').modal('hide');
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
    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
<script>

    $('#datatable1').DataTable({
        "lengthMenu": [250, 500, 750, 1000],
        "pageLength": 250
    });

/*    $('.buttons-excel').on('click',function(event){

        event.preventDefault();

        alert('Processing for Download csv !')

        //showloader()


        //window.open(encodedUri);

        console.log('yes');
        let selected_date = $('.data-selector').val();
        $.ajax({
            type: "get",
            url: '{{ URL::to('ottawa/route-info/list/') }}/'+selected_date,
            data:{},
            success: function (data) {
                //hideloader()
                // checking the rows of csv
                /!*if(data.length <= 0)
                {
                    alert('There is no data to download !');
                    return;
                }*!/

                let csvContent = "data:text/xls;charset=utf-8,";
                data.forEach(function(rowArray) {
                    let row = rowArray.join(",");
                    csvContent += row + "\r\n";
                });
                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "Ottawa-route-info-"+selected_date+".csv");
                document.body.appendChild(link); // Required for FF

                link.click(); // This will download the data file named "my_data.csv".

            },
            error:function (error)
            {
                //hideloader()
                console.log(error);
                alert('something went wrong !');
            }
        });

    });*/

    //javascript function for excel download
    $(document).ready(function(){ var table = $('#datatable1').DataTable();


        $('#btnExport').unbind().on('click', function(){

            $('<table>')
                .append($(table.table().header()).clone())
                .append(table.$('tr').clone())
                .table2excel({
                    exclude: "#actiontab",
                    filename: "Amazon-Ottawa-route-info",
                    fileext: ".csv",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });  });      })

    /*$(function () {
        appConfig.set('yajrabox.ajax', '{{-- route('montreal.data') --}}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.ajax.data', function (data) {
            data.datepicker = jQuery('[name=datepicker]').val();
        });

        appConfig.set('yajrabox.columns', [
            {data: 'order_id',   orderable: true,   searchable: true },
            {data: 'route',   orderable: true,   searchable: true},
            {data: 'joey',   orderable: true,   searchable: true},
            {data: 'address',   orderable: true,   searchable: true ,className:'text-center'},
            {data: 'picked_hub_time',   orderable: true,   searchable: false, className:'text-center'},
            {data: 'sorter_time',   orderable: true,   searchable: false, className:'text-center'},
            {data: 'dropoff_eta',   orderable: true,   searchable: true },
            {data: 'delivery_time',   orderable: true,   searchable: true},
            {data: 'image',   orderable: false,   searchable: false , className:'text-center'},
            {data: 'tracking_id',   orderable: true,   searchable: true, className:'text-center' },
            {data: 'sprint_status',   orderable: true,   searchable: true, className:'text-center'},
            {data: 'action',   orderable: false,   searchable: false, className:'text-center'},
        ]);
    })*/

</script>
    
@endsection

@section('content')

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Ottawa Route Info<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
            <!--Count Div Row Close-->

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Amazon Ottawa <small>Route Info</small></h2>
                          @if(can_access_route('newexport_OttawaRouteInfo.excel',$userPermissoins))
                            <div class="excel-btn" style="float: right">
                                {{--<a href="{{ route('export_OttawaRouteInfo.excel') }}"
                                   class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass" download >
                                    Export to Excel
                                </a>--}}
                                <button id="btnExport" class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">Export to Excel</button>
                            </div>
                     @endif
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                          <form method="get" action="">
                                <label>Search By Date</label>
                              <input type="date" name="datepicker" class="data-selector" required="" value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}" placeholder="Search">
                                 <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">Go</a> </button>
                           </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered">
                            <thead stylesheet="color:black;">
                            <tr>
                                <th class="text-center ">Route #</th>
                                <th class="text-center ">Joey Name</th>
                                <th class="text-center ">Broker Name</th>
                                <th class="text-center "># Of Drops</th>
                                <th class="text-center "># Of Sorted</th>
                                <th class="text-center "># Of Picked</th>
                                <th class="text-center "># Of Drops Completed</th>
                                <th class="text-center "># Of Returns</th>
                                  <th class="text-center "># Of Not Scan</th>
                                        <th class="text-center "># Of Unattempted</th>
									<th class="text-center ">Total Durations</th>
                                <th class="text-center ">Custom Route</th>
                                <th class="text-center ">Drops Per Hour </th>
                                <th id="actiontab">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $ottawa_info as $record )
                                <tr>
                                    <td>{{ $record->id }}</td>
                                    <td>
                                        @if($record->joey)
                                               {{$record->Joey->first_name.' '.$record->Joey->last_name.' ('.$record->Joey->id.')'}}
                                        @else
                                            {{" "}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->joey)
                                            @if($record->Joey->joeyBrooker)

                                                @if($record->Joey->joeyBrooker->brooker)
                                                    {{$record->Joey->joeyBrooker->brooker->name}}
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$record->TotalOrderDropsCount()}}</td>
                                    <td>{{$record->TotalSortedOrdersCount()}}</td>
                                    <td>{{$record->TotalOrderPickedCount()}}</td>
                                    <td>{{$record->TotalOrderDropsCompletedCount()}}</td>
                                    <td>{{$record->TotalOrderReturnCount()}}</td>
                                    <td>{{$record->TotalOrderNotScanCount()}}</td>
                                            <td>{{$record->TotalOrderUnattemptedCount()}}</td>
									<td> {{$record->EstimatedTime()}}</td>
                                    <td class="text-center "> {{$record->isCustom()}}</td>
                                    <td>
                                                @if($record->TotalOrderDropsCompletedCount()!=0 || $record->TotalOrderReturnCount() !=0 )
                                                    {{$record->getDropPerHour()}}
                                                @else
                                                    {{"0"}}
                                                @endif
                                            </td>
                                    <td id="actiontab">
                                        @if(can_access_route('newottawa_route.detail',$userPermissoins))
                                            <a href="{{backend_url('newottawa/route/'.$record->id.'/edit/hub/19')}}" title="Route Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;">Route Details
                                        </a><br>
                                        @endif
                                            @if (!is_null($record->FlagHistoryByRouteID))
                                                @if($record->id == $record->FlagHistoryByRouteID->route_id && $record->FlagHistoryByRouteID->is_approved == 0)
                                                    @if(can_access_route('un-flag',$userPermissoins))
                                                    <a href="{{ backend_url('un-flag/'.$record->FlagHistoryByRouteID->id) }}"
                                                       class="btn btn-danger btn-xs">Un Flag Order</a>
                                                @endif
                                                    @elseif($record->FlagHistoryByRouteID->is_approved == 1)
                                                    <a href="#" class="btn-primary btn-xs">Approved</a>
                                                @endif
                                            @else
                                                @if(can_access_route('flag.create',$userPermissoins))
                                                <button
                                                        data-flag_values='{"joey_id":"0","route_id":"{{$record->id}}","flag_type":"route","hub_id":"{{$record->hub}}"}'
                                                        class='btn btn-warning btn-xs createFlag'>
                                                    Mark Flag
                                                </button>
                                                    @endif
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
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
<!--model-for-flagged-open-->
<div class="modal fade" id="create-flag-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Flag</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12 hoverable-dropdown-main-wrap">
                            <input type="hidden" id="model_flag_data" value=''>
                            <ul class="hoverable-dropdown-main-ul">
                                @foreach($flagCategories as $category)
                                    @if($category->isFliterExist('is_show_on_route','1') && $category->isFliterExist('order_type','ecommerce') && $category->isFliterExist('portal','dashboard') && ( $category->isFliterExist('vendor_relation','477282') || !$category->isFliterExist('vendor_relation')))
                                        <li>
                                            {{$category->category_name}}
                                            <?php $child_data = $category->getChilds->where('is_enable', 1); ?>
                                            @if(!$child_data->isEmpty())
                                                <i class="fa fa-angle-right"></i>
                                                <ul class="hoverable-dropdown-ul">
                                                    @foreach($child_data as $child)
                                                        <li data-id="{{$child->id}}"
                                                            class="child-flag-cat">
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
            </div>
        </div>

    </div>

</div>
<!--model-for-flagged-close-->
<!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

<!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

<!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->



@endsection