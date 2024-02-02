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

@section('title', 'CTC Route Info')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
        .modal-dialog.map-model {
            width: 94%;
        }

        .btn {
            font-size: 12px;
        }

        .modal.fade {
            opacity: 1
        }

        .modal-header {
            font-size: 16px;
        }

        .modal-body h4 {
            background: #f6762c;
            padding: 8px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #fff;
        }

        .form-control {
            display: block;
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        }

        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
        }

        .form-group {
            margin-bottom: 15px;
        }

        #ex1 form {
            padding: 10px;
        }

        div#delay .modal-content, div#details .modal-content {
            padding: 20px;
        }

        #details .modal-content {
            overflow-y: scroll;
            height: 500px;
        }

        /*hoverable dropdown css*/
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

        .hoverable-dropdown-main-wrap ul {
            list-style: none;
            box-sizing: border-box;
            padding: 0px;
            margin: 0px;
        }

        .hoverable-dropdown-main-wrap ul li {
            box-sizing: border-box;
            cursor: pointer;
            position: relative;
            background: #f6f6f6;
            padding: 8px;
            width: 210px;
            margin: 1px 0;
            padding-right: 25px;
        }

        .hoverable-dropdown-ul {
            display: none;
        }

        .hoverable-dropdown-main-wrap ul:hover {
            display: block;
        }

        .hoverable-dropdown-main-wrap ul li:hover ul {
            display: block;
            z-index: 10;
            position: absolute;
            top: -1px;
            left: 100%;
            padding: 0% 0px 0px 5px;
        }

        .hoverable-dropdown-main-wrap ul > li:hover {
            background: #ccc;
        }

        .hoverable-dropdown-main-ul .fa-angle-right {
            position: absolute;
            right: 10px;
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

    <script>
        $('#datatable').DataTable({
            "lengthMenu": [250, 500, 750, 1000],
            "pageLength": 250
        });

        /*  $('.buttons-excel').on('click', function (event) {

              event.preventDefault();

              alert('Processing for Download csv !')

              //showloader()


              //window.open(encodedUri);

              console.log('yes');
              let selected_date = $('.data-selector').val();
              $.ajax({
                  type: "get",
                  url: '{{ URL::to('ctc/route-info/list/') }}/' + selected_date,
                data: {},
                success: function (data) {
                    //hideloader()
                    // checking the rows of csv
                    /!*if(data.length <= 0)
                    {
                        alert('There is no data to download !');
                        return;
                    }*!/

                    let csvContent = "data:text/xls;charset=utf-8,";
                    data.forEach(function (rowArray) {
                        let row = rowArray.join(",");
                        csvContent += row + "\r\n";
                    });
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "Ctc-route-info-" + selected_date + ".csv");
                    document.body.appendChild(link); // Required for FF

                    link.click(); // This will download the data file named "my_data.csv".

                },
                error: function (error) {
                    //hideloader()
                    console.log(error);
                    alert('something went wrong !');
                }
            });

        });
*/

        //javascript function for excel download
        $(document).ready(function () {
            var table = $('#datatable').DataTable();
            $('#btnExport').unbind().on('click', function () {
                $('<table>')
                    .append($(table.table().header()).clone())
                    .append(table.$('tr').clone())
                    .table2excel({
                        exclude: "#actiontab",
                        filename: "CTC-route-info",
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
        $('.child-flag-cat').click(function (e) {
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

    </script>

    <script>
        $(document).on('click', '.delay', function (e) {

            e.preventDefault();

            var routeId = this.getAttribute('data-route-id');
            $('#route-id').val(routeId);

            $('#delay').modal();
            $('#delay .order-id').text("Route No. : " + routeId);

            return false;
        });


        function mark_delay() {
            let date = $('#delay-date').val();
            let route_id = $('#route-id').val()

            $.ajax({
                type: "POST",
                url: '../route/mark/delay',
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {date: date, route_id: route_id},
                success: function (data) {

                    alert('Your route has been mark delay')
                    $('#delay').modal('toggle');

                },
                error: function (error) {
                }
            });
        }
    </script>
@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            @if ($message = Session::pull('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Canadian Tire Route Info
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
                            <h2>CTC
                                <small>Route Info</small>
                            </h2>
                            @if(can_access_route('export_CTCRouteInfo.excel',$userPermissoins))
                                <div class="excel-btn" style="float: right">
                                    {{--  <a href="{{ route('export_CTCRouteInfo.excel') }}"
                                         class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass" download>
                                          Export to Excel
                                      </a>--}}
                                    <button id="btnExport"
                                            class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">
                                        Export to Excel
                                    </button>
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
                                        <th class="text-center "># of drops</th>
                                        <th class="text-center "># of sorted</th>
                                        <th class="text-center "># of picked</th>
                                        <th class="text-center "># of drops completed</th>
                                        <th class="text-center "># of Returns</th>
                                        <th class="text-center "># of Not Scan</th>
                                        <th class="text-center "># of unattempted</th>
                                        <th class="text-center ">Total Durations</th>
                                        <th class="text-center ">Drops Per Hour</th>
                                        <th id="actiontab">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $ctc_info as $record )
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
                                            <td>
                                                @if($record->TotalOrderDropsCompletedCount()!=0 || $record->TotalOrderReturnCount() !=0 )
                                                    {{$record->getDropPerHour()}}
                                                @else
                                                    {{"0"}}
                                                @endif
                                            </td>

                                            <td id="actiontab">
                                                @if(can_access_route('ctc_route.detail',$userPermissoins))
                                                    <a href="{{backend_url('ctc/route/'.$record->id.'/edit/hub/17')}}"
                                                       title="Route Details" target='_blank'
                                                       class="btn btn-warning btn-xs" style="float: left;">Route Details
                                                    </a><br>
                                                @endif
                                                @if(can_access_route('route-mark-delay',$userPermissoins))
                                                    <button class='delay  btn btn-danger btn-xs'
                                                            data-route-id="{{ $record->id }}" title='MArk Delay'>Mark
                                                        Delay
                                                    </button><br>
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
                                                                data-flag_values='{"joey_id":"0","route_id":"{{$record->id}}","flag_type":"route"}'
                                                                class='btn btn-warning btn-xs createFlag'>
                                                            Create Flag
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
    <div id="delay" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-body">
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <p><strong class="order-id green"></strong></p>
                    <form method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <label>Please Select Date</label>
                        <input type="date" name="delay_date" id="delay-date" class="data-selector" required=""
                               value="{{ date('Y-m-d') }}"
                        >
                        <input type="hidden" name="route_id" id="route-id">
                        <br>
                        <br>
                        <a type="submit" data-selected-row="false" onclick="mark_delay()"
                           class="btn btn-success transfer-model-btn">Submit</a>
                        <a class="btn btn-warning " data-dismiss="modal" aria-hidden="true">Close</a>
                    </form>
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
                                        @if($category->isFliterExist('is_show_on_route','1') && $category->isFliterExist('order_type','ecommerce') && $category->isFliterExist('portal','dashboard') && ( $category->isFliterExist('vendor_relation',$ctcVendorIds) || !$category->isFliterExist('vendor_relation')))
                                            <li>
                                                {{$category->category_name}}
                                                <?php $child_data = $category->getChilds->where('is_enable', 1); ?>
                                                @if(!$child_data->isEmpty())
                                                    <i class="fa fa-angle-right"></i>
                                                    <ul class="hoverable-dropdown-ul">
                                                        @foreach($child_data as $child)
                                                            <li data-id="{{$child->id}}"
                                                                class="child-flag-cat">{{$child->category_name}}</li>
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