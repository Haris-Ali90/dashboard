<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>Al Rafeeq :: @yield('title')</title>
  {{--  <link rel="icon" href="{!! asset('public/images/logo-no-background.png') !!}"/>--}}
    <link rel="icon" href="{!! app_asset('images/logo-no-background.png') !!}"/>

    <!-- Bootstrap -->
    <link href="{{ backend_asset('libraries/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ backend_asset('libraries/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ backend_asset('css/custom.min.css') }}" rel="stylesheet">
    <!-- Confirm Alert CSS -->
    <link href="{{ backend_asset('css/jquery-confirm.css') }}" rel="stylesheet">
    <link rel="icon" href="demo_icon.gif" type="image/gif" sizes="16x16">

    <!--joey-custom-css-->
    <link href="{{ backend_asset('css/joey_custom.css') }}" rel="stylesheet">


@yield('CSSLibraries')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />


</head>

<body class="nav-md">
<div class="container body">
   <div class="main_container">

    <!-- Start Chat -->
    <input type="hidden" value="{{Auth::user()->id}}" name="chat_user_id">
    <input type="hidden" value="" name="chat_other_user_id">
    <input type="hidden" value="" name="chat_thread_id">
    <input type="hidden" value="dashboard" name="chat_user_type">
    <input type="hidden" value="joey" name="other_user_type">
    <input type="hidden" value="" name="chat_message" id="chat_message">
    <input type="hidden" value="{{Auth::user()->id}}" name="userId" id="userId">
    <input type="hidden" value="3" name="source" id="source">
    <input
        type="file"
        id="attachFileInput"
        name="attachFileInput"
        accept="image/jpeg,image/gif,image/jpg,image/png,application/pdf"
        multiple class="hide">

        <!-- <div id="wrapper">-->
    <?php /*?>@if ( Auth::check() )<?php */?>
    <!-- Navigation -->

    @if(Auth::check())
        @include('backend.layouts.sidebar')
    @endif

    <!-- Navigation [END] -->
        <?php /*?> @endif<?php */?>
        @include('backend.layouts.loader')  
        
        @yield('content')
       

    </div>

 

</div>
@include('backend.layouts.footer')
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>
<!-- <script src="{{ backend_asset('libraries/jquery/dist/jquery-ui.min.js') }}"></script> -->
<!-- Bootstrap -->
<script src="{{ backend_asset('libraries/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

@yield('JSLibraries')


<!-- Custom Theme JavaScript -->
<script src="{{ backend_asset('js/custom.min.js')}}"></script>
<script src="{{ backend_asset('js/jquery-confirm.js') }}"></script>
<!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
<link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
    var app_url = "{{url('/')}}";
</script>
<script src="{{ backend_asset('js/custom-dashbaord.js')}}"></script>

<script src="{{ backend_asset('js/socket.io.js') }}"></script>
    <script src="{{ backend_asset('js/chat.js') }}"></script>

@yield('inlineJS')

<script src="{{ backend_asset('js/customyajra.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
        let tracking_datatable = $('.joey-order-datatable').DataTable({
            // scrollX: true,   // enables horizontal scrolling,
        });
    });
</script>
<!--Montreal Bar-chart Script Yield-->
@yield('montreal-script')

<!--Ottawa Bar-chart Script Yield-->
@yield('ottawa-script')

<!--CTC Bar-chart Script Yield-->
@yield('ctc-script')

@yield('status-script')

@yield('multi-script')


</body>

</html>