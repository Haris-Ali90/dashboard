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
                          @if(can_access_route('export_OttawaRouteInfo.excel',$userPermissoins))
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
                            @foreach( $ottawa_info as $record )
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
                                        @if(can_access_route('ottawa_route.detail',$userPermissoins))
                                            <a href="{{backend_url('ottawa/route/'.$record->id.'/edit/hub/19')}}" title="Route Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;">Route Details
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