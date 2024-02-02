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

@endsection

@section('inlineJS')

    <script>
        $('#datatable').DataTable({
            "lengthMenu": [250, 500, 750, 1000],
            "pageLength": 250
        });

  /*      $('.buttons-excel').on('click', function (event) {
            alert('asd');
            event.preventDefault();

            alert('Processing for Download csv !')

            //showloader()


            //window.open(encodedUri);

            console.log('yes');
            let selected_date = $('.data-selector').val();
            $.ajax({
                type: "get",
                url: '{{ URL::to('montreal/route-info/list/') }}/' + selected_date,
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
                    link.setAttribute("download", "Montreal-route-info-" + selected_date + ".csv");
                    document.body.appendChild(link); // Required for FF

                    link.click(); // This will download the data file named "my_data.csv".

                },
                error: function (error) {
                    //hideloader()
                    console.log(error);
                    alert('something went wrong !');
                }
            });

        });*/

        //javascript function for excel download
        $(document).ready(function(){ var table = $('#datatable').DataTable();


            $('#btnExport').unbind().on('click', function(){
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
                    });  });      })

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

    </script>

@endsection

@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Montreal Route Info<small></small></h3>
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
                            <h2>Amazon Montreal <small>Route Info</small></h2>
                          @if(can_access_route('export_MontrealRouteInfo.excel',$userPermissoins))
                            <div class="excel-btn" style="float: right">
                                <button id="btnExport" class="btn btn-circle buttons-excel buttons-html5 btn-sm btn-primary excelstyleclass">Export to Excel</button>


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
                                    @foreach( $montreal_info as $record )
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>
                                                @if($record->joey)
                                            {{$record->Joey->first_name.' '.$record->Joey->last_name}}
                                                    
                                            @else
                                                  {{" "}}
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
                                                @if(can_access_route('montreal_route.detail',$userPermissoins))
                                                    <a href="{{backend_url('montreal/route/'.$record->id.'/edit/hub/16')}}" title="Route Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;">Route Details
                                                </a>
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

    <!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

    <!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

    <!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->



@endsection