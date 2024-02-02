<?php
$user = Auth::user();
if ($user->email != "admin@gmail.com") {

    $data = explode(',', $user['rights']);
    $permissions = explode(',', $user['permissions']);
} else {
    $data = [];
    $permissions = [];
}
?>

@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('CSSLibraries')
    <style>
        .dashboard-statistics-box {
            min-height: 400px;
            margin: 15px 0px;
            position: relative;
            box-sizing: border-box;
        }

        .dashboard-statistics-box.dashboard-statistics-tbl-show td {
            padding-top: 52px;
            padding-bottom: 52px;
        }
        .dataTables_filter {
            width: auto !important;
            
        }
    </style>
@endsection
@section('JSLibraries')
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('nprogress/nprogress.js') }}"></script>
    <script src="{{ backend_asset('libraries/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/skycons/skycons.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>

@endsection
@section('inlineJS')
    <script>
        // <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').dataTable({
                "order": [[ 1, "asc" ]],
                // "pageLength": 250,
                "lengthMenu": [ 250, 500, 750, 1000 ]
            });
            $(".group1").colorbox({height:"50%",width:"50%"});




        });

    </script>
    <script>
        $('#myModal form').submit(function () {
            // Get the Login Name value and trim it
            var hub = $.trim($('#hub').val());
            var name = $.trim($('#name').val());
            // Check if empty of not
            if (hub  === '' || name  === '') {
                alert('All fields are required.');
                return false;
            }
        });

        function editModal(id,name,hub_id){
            $('#myeditModal').modal('show');
            var url="{{route('manager.update',1)}}";
            url= url.slice(0, -1)+""+id;
            $('#editname').val(name);
            $('#edithub option[value="'+hub_id+'"]').prop('selected', true);
            $('#myeditModal form').attr('action', url);

        }
    </script>



@endsection


@section('content')
    <!--right_col open-->
    <div class="right_col" role="main">
        @if (Session::has('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {!! Session::pull('error') !!}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif

        <div class="" role="main">
             <div class="page-title">
                    <div class="title_left amazon-text">
                        {{-- <h3 class="text-center">Managers<small></small></h3> --}}
                        <!-- <h3 class="">Managers<small></small></h3> -->

                    </div>
                </div>
          
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                               <div class="col-lg-6">
                               <h2>Managers  List<small></small></h2>
                               </div>
                                <div class="col-lg-6">
                                <button class="btn btn-primary c-btn pull-right" data-toggle="modal" data-target="#myModal" type="button">Add</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @if(session()->has('alert-success'))
                                <div class="alert alert-success">
                                    {{ session()->get('alert-success') }}
                                </div>
                            @endif
                       <div class="x_title">
                       <form method="get"action="{{route('manager.index')}}">
                <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                      <label> Select Hub</label>
                        <select class="form-control" name="hub_id" required>
                            <option value=""> Select Hub</option>
                            @foreach( $hubs as $hub )
                            @if(isset($_GET['hub_id']) && $hub['id']==base64_decode($_GET['hub_id']))
                                <option selected value="{{ base64_encode($hub['id']) }}"> {{ $hub['city_name'] }}</option>
                                @else
                                <option  value="{{ base64_encode($hub['id']) }}"> {{ $hub['city_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-9 d-flex justify-content-end center-align">
                        <button class="btn btn-primary c-btn" type="submit" >
                            Go
                        </button>
                    </div>
                </div>
            </form>
                       </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered">
                                        <thead stylesheet="color:black;">
                                        <tr>
                                            <th>Name</th>
                                            <th>Hub</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($managers)==0)
                                                <td style="text-align: center;    border: 1px solid;" colspan="3">No Data</td>
                                            @else
                                                @foreach ($managers as $manager)
                                                    <tr>
                                                        <td>{{$manager->name}}</td>
                                                        <td>{{$manager->hub_name}}</td>
                                                        <td><button type="button" onclick="editModal({{$manager->id}},'{{$manager->name}}','{{$manager->hub_id}}')"  class="btn btn-info btn-xs sub-ad edit"><i class="fa fa-pencil"></i></button></td>
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
        
<!-- Add Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Manager</h4>
        </div>
        <form method="post" action="{{route('manager.store')}}">
            {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Name:</label>
                        <input required type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Hub:</label>
                        <select required class="form-control" name="hub" id="hub">
                            <option value="">Select Hub</option>
                            @if(count($hubs)>0)
                                @foreach ($hubs as $hub)
                                    <option  value="{{$hub['id']}}">{{$hub['city_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div> 
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary c-close sub-ad" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-default btn-primary c-close" >Save</button>
                </div>
        </form>
      </div>
  
    </div>
  </div>
<!-- Edit Modal -->
<div id="myeditModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Manager</h4>
        </div>
        <form method="post" action="{{route('manager.update',0)}}">
            {{ method_field('PUT') }}
            {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Name:</label>
                        <input required type="text" class="form-control" name="name" id="editname">
                    </div>
                    <div class="form-group">
                        <label for="email">Hub:</label>
                        <select required class="form-control" name="hub" id="edithub">
                            {{-- <option value="">Select Hub</option> --}}
                            @if(count($hubs)>0)
                                @foreach ($hubs as $hub)
                                    <option  value="{{$hub['id']}}">{{$hub['city_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div> 
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary c-close sub-ad" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-default btn-primary sub-ad" >Save</button>
                </div>
        </form>
      </div>
  
    </div>
  </div>









       


    </div>


    <!-- footer content -->
    <footer>
        <div class="pull-right">

        </div>
        <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
    <!-- /#page-wrapper -->
@endsection