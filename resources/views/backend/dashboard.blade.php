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
    </style>
@endsection
@section('JSLibraries')
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('nprogress/nprogress.js') }}"></script>
    <script src="{{ backend_asset('libraries/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/skycons/skycons.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>

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
        <div style="text-align: center;margin-top: 32%;" >
            <h2 style="font-size: 35px;color: #E36D28;font-weight: bold;">Dashboard Coming Soon......</h2>
        </div>

                        <!-- Dashboard All Graph -->

        {{--@if($graph == null)
            @if (count($data)== 0)
                <div class="row">
                    @include('backend.dashboard-layout.montreal')
                    @include('backend.dashboard-layout.ottawa')
                    @include('backend.dashboard-layout.ctc')
                </div>
            @endif

                <div class="row">
                    @if(in_array("montreal_dashboard", $data))
                        @include('backend.dashboard-layout.montreal')
                    @endif
                    @if(in_array("ottawa_dashboard", $data))
                        @include('backend.dashboard-layout.ottawa')
                    @endif
                    @if(in_array("ctc_dashboard", $data))
                        @include('backend.dashboard-layout.ctc')
                    @endif

                    @if(in_array("montreal_dashboard", $data) && in_array("ottawa_dashboard", $data) && in_array("ctc_dashboard", $data))

                    @else
                        @if(in_array("montreal_dashboard", $data))
                            @if(in_array("ottawa_dashboard", $data))

                            @elseif(in_array("ctc_dashboard", $data))

                            @else

                            @endif


                        @elseif(in_array("ottawa_dashboard", $data))
                            @if(in_array("montreal_dashboard", $data))

                            @elseif(in_array("ctc_dashboard", $data))

                            @else

                            @endif

                        @elseif(in_array("ctc_dashboard", $data))
                            @if(in_array("montreal_dashboard", $data))
                            @elseif(in_array("ottawa_dashboard", $data))
                            @else
                            @endif
                        @endif

                    @endif

                </div>
            @endif

        @if($graph == 'montreal')
            @if (count($data)== 0)
                <div class="row">
                    @include('backend.dashboard-layout.montreal')
                </div>
            @endif

                <div class="row">
                    @if(in_array("montreal_dashboard", $data))
                        @include('backend.dashboard-layout.montreal')
                    @endif
                </div>

        @endif

        @if($graph == 'ottawa')
            @if (count($data)== 0)
                <div class="row">
                    @include('backend.dashboard-layout.ottawa')
                </div>
            @endif

                <div class="row">
                    @if(in_array("ottawa_dashboard", $data))
                        @include('backend.dashboard-layout.ottawa')
                    @endif

                </div>

        @endif

        @if($graph == 'ctc')
            @if (count($data)== 0)
                <div class="row">
                    @include('backend.dashboard-layout.ctc')

                </div>
            @endif

                <div class="row">
                    @if(in_array("ctc_dashboard", $data))
                        @include('backend.dashboard-layout.ctc')

                    @endif

                </div>

        @endif
 --}}
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