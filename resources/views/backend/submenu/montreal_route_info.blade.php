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

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_title">
                          <form method="get" action="">
                                <label>Search By Date</label>
                              <input type="date" name="datepicker" required="" value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}" placeholder="Search">
                                 <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">Go</a> </button>
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
                          <th class="text-center "># of drops</th>
                          <th class="text-center "># of picked</th>
                          <th class="text-center "># of drops completed</th>
                          <th class="text-center "># of Returns</th>
                          <th class="text-center "># of unattempted</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach( $montreal_info as $record )
                          <tr>
                              <td>{{ $record->id }}</td>
                              <td>{{$record->TotalOrderDropsCount()}}</td>
                              <td>{{$record->TotalOrderPickedCount()}}</td>
                              <td>{{$record->TotalOrderDropsCompletedCount()}}</td>
                              <td>{{$record->TotalOrderReturnCount()}}</td>
                              <td>{{$record->TotalOrderUnattemptedCount()}}</td>
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