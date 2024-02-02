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

@section('title', 'Permissions')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
<style>

    .section-heading {
        background-color: #3E3E3E;
        padding: 5px 10px;
        color: #fff;
        margin-bottom: 5px;
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
                    <!-- <h3 class="text-center">Permissions<small></small></h3> -->
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>{{$role->display_name}} Permissions </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                                <form method="POST"  action="{{ route('role.set-permissions.update',$role->id) }}" class="form-horizontal"
                                      role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                {{--@method('POST')--}}

                                @foreach($permissions_list as $permission_label => $permissions)
                                    <!--from-input-wraper-open-->
                                        <div class="row from-input-wraper">

                                            <div class="col-md-12">
                                                <p class="section-heading">{{$permission_label}}</p>
                                            </div>

                                        @foreach($permissions as  $name =>  $permission)
                                            <?php check_permission_exist($permission,$role->Permissions->pluck('route_name')->toArray()) ?>
                                            <!--from-input-col-open-->
                                                <div class="col-md-2 col-sm-2 from-input-col">
                                                 
                                                        <input type="checkbox" name="permissions[]"  value="{{ $permission }}" @if(check_permission_exist($permission,$role->Permissions->pluck('route_name')->toArray())) checked @endif />
                                                        <label class="control-label">{{$name}}</label>
                                                    
                                                </div>
                                                <!--from-input-col-close-->
                                            @endforeach

                                        </div>
                                        <!--from-input-wraper-close-->
                                @endforeach


                                <!--from-input-wraper-open-->
                                    <div class="row from-input-wraper">
                                        <!--from-input-col-open-->
                                        <div class="col-sm-12 text-right from-input-col mt-27">
                                            <div class="d-flex justify-content-end">
                                            <a href="{{route('role.index')}}"  class="btn sub-ad c-close btn-default" id="cancel"> Cancel </a>
                                                <button type="submit" class="btn btn-primary sub-ad" id="save"> Update </button>
                                                
                                            </div>
                                        </div>
                                        <!--from-input-col-close-->
                                    </div>
                                    <!--from-input-wraper-close-->

                                </form>

                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection