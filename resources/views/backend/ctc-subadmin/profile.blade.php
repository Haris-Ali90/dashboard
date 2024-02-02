@extends( 'backend.layouts.app' )



@section('title', 'CTC Sub Admin')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".group1").colorbox({height: "75%"});
        });
    </script>

@endsection



@section('content')

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>{{$users->first_name}} Profile</h3>
                </div>


            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>CTC Sub Admin Profile <small></small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-2 col-sm-2 col-xs-12 profile_left">
                                 <div class="profile_img">
                                    <div id="crop-avatar">

                                        <ul class="main-image" style="list-style: none;">
                                            <li class="col-md-12">

                                                    <img onClick="ShowLightBox(this);" src="{{$users->profile_picture}}" style="width:250px; height:250px; margin-left: -46px;" class="avatar" alt="Avatar"/>

                                            </li>
                                        </ul>

                                    </div>
                                </div>
                                <h3></h3>

                                {{--<ul class="list-unstyled user_data">
                                     <li><label>Full Name :</label> {{$users['first_name'] or "N/A"}}</li>
                                     <li><label>Email Address : </label> {{$users['email'] or "N/A"}}</li>
                                     <li><label>Phone / Mobile no :</label>{{$users['phone'] or "N/A"}}</li>

                                </ul>--}}

                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12">

                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">CTC Sub Admin Detail</a>
                                        </li>
                                        <!-- <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Student Document</a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                            <!-- start user projects -->
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th colspan="2" >CTC Sub Admins Detail</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width: 30%;"><label>Full Name</label></td>
                                                    <td>{{$users->full_name or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><lable>Email</lable></td>
                                                    <td>{{$users->email  or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Phone</label></td>
                                                    <td>{{$users->phone or "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td><label>Address</label></td>
                                                    <td>{{$users->address or "N/A"}}</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                            <!-- end user projects -->

                                        </div>

                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                                            <!-- start user projects -->
                                            <!--  -->
                                            <!-- end user projects -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection