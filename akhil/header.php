<?php
$group_tours=listPackageCategory(0);
$individual_tours=listPackageCategory(1);
 ?>
<!DOCTYPE html>
<!-- saved from url=(0028)/ -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Akhil Bharat Tours And Travels</title>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="robots" content="index,follow">
<meta name="description" content="">
<meta name="keywords" content="Tour Packages">
<meta name="revisit-after" content="7 days">
 

<link href="css/css" rel="stylesheet" type="text/css">
<!-- Bootstrap -->
<link href="css/bootstrap.min.all.css" rel="stylesheet">
	

   
	
	
	<link href="css/style_v27.css" rel="stylesheet" type="text/css">
    <link href="css/responsive.css" rel="stylesheet" type="text/css">
 
    <link href="css/groupdeals_v5.css" rel="stylesheet" type="text/css">
    <link href="css/flexslider.css" rel="stylesheet" type="text/css">
    <link href="css/jquery-ui.min.all.css" rel="stylesheet" type="text/css">
<!--[if lte IE 6]>
    <script type="text/javascript" src="/assets/shared/js/ie6fixes.js"></script>
    <link href="/assets/shared/css/ie6fixes.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" async="" src="js/ec.js"></script>
</head>
<body>
<header>
  <!-- Navigation -->
  <nav class="navbar navbar-default navbar-inverse navbar-static-top">
    <div class="container" id="header">
      <div class="row">
        <div class="col-sm-12">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            	<div class="navbar-brand v-middle"> 
            		<div class="logo_div">
            			<a href="" title=""><img style="padding-top:7px;" src="images/akhil_bharat_tours_and_travels_logo_med.png" alt="" ></a>
           			 </div>
             	</div>
            
            	<div class="social floatright">
            		<div class="social2">
             			<span class="sprite db_callus floatleft"></span> &nbsp; <span style="font-size:18px;font-weight:bold;"> +91 (79) 26420579 &nbsp; <a target="_blank" title="Follow us on Twitter" href="#"><span class="sprite tweet_big"></span></a>&nbsp;&nbsp;<a target="_blank" title="Follow us on Facebook" href="#"><span class="sprite fb_big"></span></a>
             			</span>
              		</div>
          		</div> 
            </div>    
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="visible-xs">
            <div class="collapse navbar-collapse navbar-left" id="navbar-collapse">
              <ul class="nav navbar-nav">
                <li class="hidden"> <a href="/#page-top"></a> </li>
                <li class="page-scroll"><a href="/" title="Akhil Bharat Tours">Home</a></li>
                <li class="page-scroll"><a href="group_tours.php" title="Group Tours" event_label="Group Tours">Group Tours</a></li>
                <li class="page-scroll"><a href="individual_packages.php" title="Individual Packages" event_label="Individual Packages">Individual Packages</a></li>
                <li class="page-scroll"><a href="hotel_booking.php" title="Hotel Booking" event_label="Hotel Booking">Hotel</a></li>
                <li class="page-scroll"><a href="air_ticket.php" title="Air Ticket" event_label="Air Ticket">Air Ticket</a></li>
                <li class="page-scroll"><a href="vehicle_booking.php" title="Vehicle Booking" event_label="Vehicle Booking">Vehicle Booking</a></li>
                <li class="page-scroll"><a href="contact.php" title="Contact Us" event_label="Contact">Contact Us</a></li>
              </ul>
            </div>
          </div>
          <!-- /.navbar-collapse -->
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </nav>
</header><div id="menu" class="hidden-xs">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="topmenu">
        
          <ul id="show">
          
            <li><a href="index.php" title="">Home</a></li>
            <li><a href="group_tours.php" title="Group Tours" event_label="Group Tours">Group Tours</a>
              <ul>
                <li class="row">
                  <div class="col-sm-12 dropdown_box">
                    <div class="row">
                    <?php for($i=0;$i<count($group_tours);$i++) { ?>
                    <?php if($i%5==0 || $i==0) { ?>
                      <div class="col-sm-6 col-sm-6" style="margin-top:20px;">
                          <div class="dropdowns">
                    <?php } ?>    
                      
                           <span><a href="packageCategory.php?id=<?php echo $group_tours[$i]['pkg_cat_id'] ?>" title="<?php echo $group_tours[$i]['pkg_cat_name'] ?>" ><?php echo $group_tours[$i]['pkg_cat_name'] ?></a></span>
        
          <?php if($i%5==4 || $i==(count($group_tours)-1)) { ?>
                        </div></div>
                        <?php }} ?>
                       
                        
                        
                    
                    </div>
                  </div>
                </li>
              </ul>
              </li>
            <li><a href="individual_packages.php" title="Individual Packages" event_label="Individual Packages">Individual Packages</a>
              <ul>
                <li class="row">
                    <div class="col-sm-12 dropdown_box">
                      <div class="row">
                       
                      
                         <?php for($i=0;$i<count($individual_tours);$i++) { ?>
                    <?php if($i%5==0 || $i==0) { ?>
                      <div class="col-sm-6 col-sm-6" style="margin-top:20px;">
                          <div class="dropdowns">
                    <?php } ?>    
                      
                           <span><a href="packageCategory.php?id=<?php echo $individual_tours[$i]['pkg_cat_id'] ?>" title="<?php echo $individual_tours[$i]['pkg_cat_name'] ?>" ><?php echo $individual_tours[$i]['pkg_cat_name'] ?></a></span>
        
          <?php if($i%5==4 || $i==(count($individual_tours)-1)) { ?>
                        </div></div>
                        <?php }} ?>
                        
                      </div>
                    </div>
                </li>
              </ul>
            </li>
            <li><a href="hotel_booking.php" title="" event_label="Hotel Booking">Hotel</a> </li>
            <li><a href="air_ticket.php" title="" event_label="Air Ticket Booking">Air Ticket</a> </li>
            <li><a href="vehicle_booking.php" title="" event_label="Blog">Vehicle Booking</a> </li>
            <li><a href="about_akhilbharat.php" title="About Us" event_label="About">About Us</a>
              
            </li>
            <li class="last"><a href="contact.php" title="Contact Us" event_label="Contact">Contact Us</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=NULL && $msg!="" && $type>0)
		{
?>
<div class="col-md-1 col-sm-1 col-xs-1"></div>
<div style="margin-top:10px;" class="alert no_print col-md-10 col-sm-10 col-xs-10 <?php  if(isset($type) && $type==1) echo "alert-success"; else echo "alert-warning" ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if(isset($type)  && $type==1) { ?> <strong>Success!</strong> <?php } else if(isset($type) && $type>1) { ?> <strong>Warning!</strong> <?php } ?> <?php echo $msg; ?>
</div>
<?php
		
		
		}
	if(isset($type) && $type>0)
		$_SESSION['ack']['type']=0;
	if($msg!="")
		$_SESSION['ack']['msg']=="";
}

?>
