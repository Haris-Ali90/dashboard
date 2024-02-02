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

@section('title', 'Roles')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
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
        $(document).ready(function () 
        {
            $('#datatable').dataTable({
                "order": [[ 1, "asc" ]],
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
                    <!-- <h3 class="text-center">Roles<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Admin Roles </h2>
                            <div class="clearfix"></div>
                        </div>

                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <div class="x_content">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Total Users</th>
                                        <th>Total Dashboard Cards</th>
                                        <th>Created At</th>
                                        <th>Set Privileges</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                        @foreach( $Roles as $record )
                                            <tr>
                                                <td>{{ $record->id }}</td>
                                                <td>{{ $record->display_name }}</td>
                                                <td>{{$record->User->count()}}</td>
                                                <td>0</td>
                                                <td>{{ $record->created_at }}</td>
                                                <td>
                                                    @if(can_access_route('role.set-permissions',$userPermissoins))
                                                    <a href="{{ backend_url('role/set-permissions/'.$record->id) }}" title="Detail" class="btn sub-ad btn-primary btn-xs" style="float: left;">
                                                        <i class="fa fa-folder">
                                                        </i></a>
                                                        @endif
                                                </td>
                                                <td>
                                                    @if(can_access_route('role.show',$userPermissoins))
                                                    <a href="{{ backend_url('role/'.base64_encode($record->id)) }}" title="Detail" class="btn sub-ad btn-primary btn-xs" style="float: left;">
                                                        <i class="fa fa-folder">
                                                        </i></a>
                                                    @endif
                                                        @if(can_access_route('role.edit',$userPermissoins))
                                                    <a href="{{ backend_url('role/'.base64_encode($record->id).'/edit') }}" class="btn btn-info btn-xs edit" style="float: left;"><i class="fa fa-pencil">
                                                        </i></a>
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

@endsection