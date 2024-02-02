@extends( 'backend.layouts.app' )

@section('title', 'Register Complain')

@section('CSSLibraries')

    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
        .green-gradient, .green-gradient:hover {
            color: #fff;
            background: #bad709;
            background: -moz-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #bad709), color-stop(100%, #afca09));
            background: -webkit-linear-gradient(top, #bad709 0%, #afca09 100%);
            background: linear-gradient(to bottom, #bad709 0%, #afca09 100%);
        }

        .black-gradient,
        .black-gradient:hover {
            color: #fff;
            background: #535353;
            background: -moz-linear-gradient(top, #535353 0%, #353535 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #535353), color-stop(100%, #353535));
            background: -webkit-linear-gradient(top, #535353 0%, #353535 100%);
            background: linear-gradient(to bottom, #535353 0%, #353535 100%);
        }

        .red-gradient,
        .red-gradient:hover {
            color: #fff;
            background: #da4927;
            background: -moz-linear-gradient(top, #da4927 0%, #c94323 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #da4927), color-stop(100%, #c94323));
            background: -webkit-linear-gradient(top, #da4927 0%, #c94323 100%);
            background: linear-gradient(to bottom, #da4927 0%, #c94323 100%);
        }

        .orange-gradient,
        .orange-gradient:hover {
            color: #fff;
            background: #f6762c;
            background: -moz-linear-gradient(top, #f6762c 0%, #d66626 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f6762c), color-stop(100%, #d66626));
            background: -webkit-linear-gradient(top, #f6762c 0%, #d66626 100%);
            background: linear-gradient(to bottom, #f6762c 0%, #d66626 100%);
        }

        .alert.alert {
            margin-top: 50px;
        }
    </style>
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>


@endsection

@section('inlineJS')

@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('warning'))
                <div class="alert alert-warning alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('info'))
                <div class="alert alert-info alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($errors->any())

            @endif
            <div class="page-title">
                <div class="title_left amazon-text">
                    <!-- <h3>Register Complain<small></small></h3> -->
                </div>
            </div>
 <div class="x_panel">
<div class="x_title">
<h2>Register Complain</h2>
</div>
 <div class="row gap-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form method="post" action="{{route('complain.create')}}" name="complain_form" novalidate>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group ">
                            <select   name="reason" class="form-control c-mb" id="reason" required>
                                <option selected disabled>Please Select Reason</option>
                                <option value="Forgot Password">Forgot Password</option>
                                <option value="Login Issue">Login Issue</option>
                                <option value="Complain your Account">Complain your Account</option>
                                <option value="Registration Issue">Registration Issue</option>
                            </select>
                            @if ( $errors->has('reason') )
                                <p class="invalid-feedback text-danger">{{ $errors->first('reason') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <textarea name="complain_data" placeholder="Type your Complain here..." class=" c-mb form-control" rows="14" required></textarea>
                            @if ( $errors->has('complain_data') )
                                <p class="invalid-feedback text-danger">{{ $errors->first('complain_data') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <button class="btn c-btn orange-gradient pull-right" type="submit" style="padding:8px 18px">
                                Submit
                            </button>
                        </div>

                    </form>
                </div>
            </div>
 </div>
        </div>
    </div>
@endsection
