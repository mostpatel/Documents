<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<!-- Basic Page Needs
	================================================== -->
	
    <title>Mahavir Interface</title>
    <meta name="description" content="">	
	<meta name="author" content="">

	<!-- Mobile Specific Metas
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Favicons
	================================================== -->
	
	
	<!-- CSS
	================================================== -->
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Template styles-->
	<link rel="stylesheet" href="css/style.css">
	<!-- Responsive styles-->
	<link rel="stylesheet" href="css/responsive.css">
	<!-- FontAwesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Animation -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/owl.theme-min.css">
	<!-- Flexslider -->
	<link rel="stylesheet" href="css/flexslider.css">
	<!-- CD Hero -->
	<link rel="stylesheet" href="css/cd-hero.css">
	<!-- Style Swicther -->
	<link id="style-switch" href="css/preset1.css" media="screen" rel="stylesheet" type="text/css">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
	
<body>

	<!-- Style switcher start -->
	<div class="style-switch-wrapper">
		<div class="style-switch-button">
			<i class="fa fa-sliders"></i>
		</div>
		<h3>Style Options</h3>
		<button id="preset1" class="btn btn-sm btn-primary"></button>
		<button id="preset2" class="btn btn-sm btn-primary"></button>
		<button id="preset3" class="btn btn-sm btn-primary"></button>
		<button id="preset4" class="btn btn-sm btn-primary"></button>
		<button id="preset5" class="btn btn-sm btn-primary"></button>
		<button id="preset6" class="btn btn-sm btn-primary"></button>
		<br><br>
		<a class="btn btn-sm btn-primary close-styler pull-right">Close X</a>
	</div>
	<!-- Style switcher end -->

	<div class="body-inner">

	<!-- Header start -->
	<header id="header" class="ts-header">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-6 col-xs-12 logo-wrapper">
					<!-- Logo start -->
					<div class="navbar-header">
					    <div class="navbar-brand">
						    <a href="#">
						    	<div class="logo"></div>
						    </a> 
					    </div>                   
					</div><!--/ Logo end -->
				</div>

				<div class="col-md-8 col-sm-6 col-xs-12">
					<!--
					<div class="consult">
						<a href="#"><i class="icon icon-mail3"></i> <span>Request Free Consultation</span></a>
					</div>
					-->
					<ul class="top-info">
						<li>
							<div class="info-box"><span class="info-icon"><i class="fa fa-phone">&nbsp;</i></span>
								<div class="info-box-content">
									<p class="info-box-title">Customer Support</p>
									<p class="info-box-subtitle">(+91) 9825690912</p>
								</div>
							</div>
						</li>
						<li>
							<div class="info-box"><span class="info-icon"><i class="fa fa-compass">&nbsp;</i></span>
								<div class="info-box-content">
									<p class="info-box-title">Office Timings</p>
									<p class="info-box-subtitle">Mon - Sat 10.00 AM - 06.00 PM</p>
								</div>
							</div>
						</li>
					</ul>
				</div>


			</div>
		</div>
	</header><!--/ Header end -->

	<!-- Navigation start -->
	<div class="navbar ts-mainnav">
		<div class="container">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		    </button>

			<nav class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class=" <?php if($page=="home") echo "active"; ?>">
                   		<a href="index.php" >Home </i></a>
                   		
                    </li>

                   	<li class=" <?php if($page=="about") echo "active"; ?>">
                   		<a href="about.php" >About Company </a>
                   		
                    </li>

                   <!-- <li class="dropdown">
                   		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Industrial Market <i class="fa fa-angle-down"></i></a>
                   		<div class="dropdown-menu">
							<ul>
	                             <li><a href="/building-installations.html">Building Installations</a></li>
				                <li><a href="/welding-automation.html">Welding Automation</a></li>
				                <li><a href="#">Steel Industry</a></li>
				                <li><a href="#">District Energy, Gas</a></li>
				                <li><a href="#">Mining</a></li>
				                <li><a href="#">Oil Industry</a></li>
				                <li><a href="#">Cement</a></li>
				                <li><a href="#">Automative</a></li>
				                <li><a href="#">Metal Production</a></li>
	                        </ul>
                    	</div>
                    </li> -->

                    <li class="dropdown visible-lg visible-md">
                   		<a href="#" class="dropdown-toggle" data-toggle="dropdown"> Products & Services <i class="fa fa-angle-down"></i></a>
                   		
                    </li>

                    <li class="<?php if($page=="clients") echo "active"; ?>">
                   		<a href="clients.php" >Clients </a>
                   		
                    </li>

                    
                    <li class="<?php if($page=="contact") echo "active"; ?>"><a href="contact_us.php">Contact</a></li>
				</ul><!--/ Navbar-nav end -->
			</nav><!--/ Navigation end -->

			

		</div><!--/ Container end -->
	</div> <!-- Navbar end -->

	