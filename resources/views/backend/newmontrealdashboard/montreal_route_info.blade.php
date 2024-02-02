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

@section('title', 'Montreal Route Info')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">

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

        .label-success {
            background-color: #5cb85c;
        }
        .modal-content {
            width: 129%;
            height: 230px;
            }
    </style>

@endsection

@section('inlineJS')

    <script>
        $('#datatable').DataTable({
            "lengthMenu": [250, 500, 750, 1000],
            "pageLength": 250
        });


        //javascript function for excel download
        $(document).ready(function () {
            var table = $('#datatable').DataTable();


            $('#btnExport').unbind().on('click', function () {
                $('<table>')
                    .append($(table.table().header()).clone())
                    .append(table.$('tr').clone())
                    .table2excel({
                        exclude: "#actiontab",
                        filename: "Montreal-route-info",
                        fileext: ".csv",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true
                    });
            });
        })

        /*-$(function () {
            appConfig.set('yajrabox.ajax', '{{-- route('montreal.route-info.data') --}}');
        appConfig.set('dt.order', [0, 'desc']);
        appConfig.set('yajrabox.ajax.data', function (data) {
            //data.datepicker = jQuery('[name=datepicker]').val();
        });

        appConfig.set('yajrabox.columns', [
            {data: 'id',   orderable: true,   searchable: true },
            {data: 'total_no_drops', orderable: true,   searchable: true},
            {data: 'total_no_orders_picked',   orderable: true,   searchable: true},
            {data: 'total_no_drops_completed',   orderable: true,   searchable: true},
            {data: 'total_no_return',   orderable: true,   searchable: true},
            {data: 'total_no_unattempted',   orderable: true,   searchable: true},
        ]);
    });*/

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
                                        $('#create-flag-modal').modal('hide');
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
    </script>

@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Montreal Route Info
                        <small></small>
                    </h3>
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
                            <h2>Amazon Montreal
                                <small>Route Info</small>
                            </h2>
                            @if(can_access_route('newexport_MontrealRouteInfo.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right">
                                    <button id="btnExport"
                                            class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                        Export to Excel
                                    </button>


                                    {{--  <a href="{{ route('export_MontrealRouteInfo.excel') }}"
                                         class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass" download>
                                          Export to Excel
                                      </a>--}}

                                </div>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                            <form method="get" action="">
                                <label>Search By Date</label>
                                <input type="date" name="datepicker" class="data-selector" required=""
                                       value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                                       placeholder="Search">
                                <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                                    Go</a> </button>
                            </form>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
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
                                        <th class="text-center ">Drops Per Hour</th>
                                        <th id="actiontab">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $montreal_info as $record )
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
                                                @if(can_access_route('newmontreal_route.detail',$userPermissoins))
                                                    <a href="{{backend_url('newmontreal/route/'.$record->id.'/edit/hub/16')}}"
                                                       title="Route Details" target='_blank'
                                                       class="btn btn-warning btn-xs" style="float: left;">Route Details
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
                                        @if($category->isFliterExist('is_show_on_route','1') && $category->isFliterExist('order_type','ecommerce') && $category->isFliterExist('portal','dashboard') && ( $category->isFliterExist('vendor_relation','477260') || !$category->isFliterExist('vendor_relation')))
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