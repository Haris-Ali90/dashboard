@extends( 'backend.layouts.app' )

@section('title', 'Reason')
@section('CSSLibraries')
        <!-- DataTables CSS -->
<link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
        <!-- DataTables JavaScript -->
<script src="{{ backend_asset('libraries/moment/min/moment.min.js') }}"></script>
<script src="{{ backend_asset('libraries//bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('inlineJS')
    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },

                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>
    @endsection

@section('content')



    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <!-- <h3>Reason </h3> -->
                </div>



            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="x_panel">



                        <div class="x_title">
                            <h2>Add Reason<small></small></h2>



                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                        </div>
                        @include( 'backend.layouts.notification_message' )
                        {!! Form::open( ['url' => ['reason/create'], 'files'=> true , 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}
                        @include( 'backend.reason.form' )
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /page content -->



@endsection
