<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Al Rafeeq :: @yield('title')</title>
    <link rel="icon" href="{!! app_asset('images/logo-no-background.png') !!}"/>

@yield('CSSLibraries')

    <!-- Bootstrap -->
    <link href="{{ backend_asset('libraries/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ backend_asset('libraries/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ backend_asset('libraries/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ backend_asset('libraries/animate.css/animate.min.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ backend_asset('css/custom.min.css')}}" rel="stylesheet">
	

</head>

  <body class="login">
   @yield('content')
</body>

</html>