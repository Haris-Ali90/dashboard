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

@section('title', 'Complain List')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">

    <style>
        .green-gradient, .green-gradient:hover {
            color: #fff;
            background: #bad709;
            background: -moz-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #bad709), color-stop(100%, #afca09));
            background: -webkit-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: linear-gradient(to bottom, #bad709 0%, #afca09 100%);
        }
    </style>


@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').dataTable({
                "order": [[ 0, "desc" ]],
                "lengthMenu": [ 250, 500, 750, 1000 ]
            });
            $(".group1").colorbox({height:"50%",width:"50%"});

        });

    </script>



@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3 class="text-center">Complain List<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Complains List </h2>
                            <div class="clearfix"></div>
                        </div>
                        
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <div class="x_content">
                            <?php
                            if(empty($_REQUEST['date'])){
                                $date = date('Y-m-d');
                            }
                            else{
                                $date = $_REQUEST['date'];
                            }
                            ?>
                            <form id="filter" style="padding: 10px;margin-left: 6px;" action="" method="get">
                            <div class="col-lg-2">
                            <div class="form-group">
                            <label>Search By Date :</label>
                                <input id="date" name="date" style="width:35%px" type="date" placeholder="date" value="<?php echo $date ?>"  class="form-control">
                            </div>
                            </div>
                               <div class="col-6 d-flex center-align ">
                               <button  id="search" type="submit" class="btn c-btn green-gradient" style="margin:6px 0px 0px 0px !important">Submit</button>
                               </div>
                            </form>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered table-responsive">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <th class="col-sm-1 col-md-1">Date</th>
                                        <th class="col-sm-1 col-md-1">User Id</th>
                                        <th class="col-sm-1 col-md-1">User Type</th>
                                        <th class="col-sm-1 col-md-1">Title</th>
                                        <th class="col-sm-6 col-md-4">Complain</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if($complain_data == [] && $complain_data == '')
                                        @else
                                            @foreach( $complain_data as $complain )
                                                <tr>
                                                    <td>{{ $complain ? ConvertTimeZone($complain->created_at,'UTC','America/Toronto') : 'N/A' }}</td>
                                                    <td>{{ $complain ? $complain->joey_id :'N/A' }}</td>
                                                    <td>@if ($complain->dashboard_user->role_id === 2 ) Customer Support @elseif($complain->dashboard_user->role_id === 3 ) Warehouse @endif </td>
                                                    <td>{{ $complain ? $complain->type :'N/A'}}</td>
                                                    <td>{{ $complain ? $complain->description : 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
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

@endsection