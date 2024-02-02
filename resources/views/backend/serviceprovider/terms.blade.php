<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap -->
    <link href="{{ backend_asset('libraries/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ backend_asset('libraries/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
body {
    background-color: white;
}

h1 {
    color: red;
    text-align:center;
} 
p{
	color:blue;
}
</style>

</head>

<body class="">
<div class="">
 <h1>{{$cms->title}}</h1>
    <div class="row">
<div class="col-md-4"></div><div class="col-md-4"><p>{{$cms->body}}</p></div><div class= "col-md-4"></div>
    </div>
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ backend_asset('libraries/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ backend_asset('libraries/jquery/dist/jquery-ui.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ backend_asset('libraries/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ backend_asset('libraries/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

<script src="{{ backend_asset('js/custom.min.js')}}"></script>
<script src="{{ backend_asset('js/jquery-confirm.js') }}"></script>

</body>

</html>