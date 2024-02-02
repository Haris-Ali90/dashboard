<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Wassela</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 
    <link rel="stylesheet" href="resources/views/assests/css/animate.css">
    
    <link rel="stylesheet" href="resources/views/assests/css/owl.carousel.min.css">
    <link rel="stylesheet" href="resources/views/assests/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="resources/views/assests/css/magnific-popup.css">
    <link rel="stylesheet" href="resources/views/assests/js/google-map.js">

    <link rel="stylesheet" href="resources/views/assests/css/flaticon.css">
       <script src="{{url('/')}}/js/jquery.min.js"></script>
    <link rel="stylesheet" href="resources/views/assests/css/style.css">
    <script type="text/javascript" src="resources/views/assests/js/loader.js"></script>
    <script src="resources/views/assests/js/canvasjs.min.js"> </script>
  </head>
<body>

<!-- top header -->
<div class="wrap">
			<div class="container">
				<div class="row justify-content-between">
						<div class="col-12 col-md d-flex align-items-center">
							<p class="mb-0 phone"><span class="mailus">Phone no:</span> <a href="#">+00 1234 567</a> or <span class="mailus">email us:</span> <a href="#">emailsample@email.com</a></p>
						</div>
						<div class="col-12 col-md d-flex justify-content-md-end">
							<div class="social-media">
				    		<p class="mb-0 d-flex">
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-google"><i class="sr-only">Dribbble</i></span></a>
				    		</p>
			        </div>
						</div>
				</div>
			</div>
		</div>
<!-- //top header -->

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	    	<a class="navbar-brand" href="{{url('/')}}">Was<span>sela</span></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="fa fa-bars"></span> Menu
	      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	        	<li class="nav-item"><a href="{{url('/')}}" class="nav-link">Home</a></li>
	        	<li class="nav-item"><a href="{{url('/')}}/categories" class="nav-link">Categories</a></li>
	        	<li class="nav-item"><a href="{{url('/')}}/project" class="nav-link">Project</a></li>
	        	<li class="nav-item"><a href="{{url('/')}}/news" class="nav-link">News</a></li>
	        	<li class="nav-item"><a href="{{url('/')}}/about-us" class="nav-link">About Us</a></li>
	            <li class="nav-item"><a href="{{url('/')}}/contact-us" class="nav-link">Contact Us</a></li>
	            <li class="nav-item"> <a href="{{url('/')}}/tickets" class="nav-link">Create Tickets</a></li>
	            <li class="nav-item"> <a href="{{url('/')}}/view-tickets" class="nav-link">All Tickets</a></li>
	            <!-- <li class="nav-item"> <a href="{{url('/')}}/profile" class="nav-link">Profile</a></li> -->
	            @if(Session::get('users'))
	            <li class="nav-item"> <a href="#" class="nav-link">welcome | {{Session::get('users')}}</a></li>
	            @else
	            <li class="nav-item"> <a href="{{url('/')}}/login" class="nav-link">Log in</a></li>
	            @endif
	            <!-- <li class="nav-item"> <a href="{{url('/')}}/signup" class="nav-link">Sign Up</a></li> -->
	        	<!-- <li class="nav-item"><a href="{{url('/')}}/pricing" class="nav-link">Pricing</a></li> -->
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->