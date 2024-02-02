@extends( 'backend.layouts.app' )

@section('title', 'Sub Admin')

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
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')

@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Sub Admin <small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Sub Admin <small>Sub Admin listing</small></h2>
                            <form method="get" action="">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Search By Date :</label>
                                        <input type="date" name="datepicker" class="data-selector form-control" required=""
                                               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                                        >
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-primary" type="submit" style="       margin-top: 24px;
    padding-bottom: 9px;">
                                            Go</a> </button>
                                    </div>
                                </div>



                            </form>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            <div class="row">
                            @foreach($hubs as $hub)
                                    <a href="#" id="hub"><div class="col-md-4">{{$hub->city_name}}
                                    <input type="text" readonly="readonly" id="ontd" value="{{$odt_data_1['y1']}}%">
                                        <input type="text" readonly="readonly" id="oftd" value="{{$odt_data_1['y2']}}"></div></a>

                            @endforeach
                                @foreach($counts as $count)
                                    <a href="<?php echo URL::to('/'); ?>/statistics/?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=total"
                                       id="hub"><div class="col-md-4">Cards - {{$hub->$count}}</div></a>

                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

@endsection
