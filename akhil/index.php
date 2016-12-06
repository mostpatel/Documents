<?php
require_once("admin/lib/cg.php");
require_once("admin/lib/bd.php");
require_once("admin/lib/common.php");
require_once "admin/lib/location-functions.php";
require_once "admin/lib/package-functions.php";
require_once "admin/lib/hotel-package-functions.php";
require_once "admin/lib/package-category-functions.php";
require_once "admin/lib/package-type-functions.php";
require_once "admin/lib/vehicle-type-functions.php";
require_once "admin/lib/package-itenary-functions.php";
require_once "admin/lib/package-cost-functions.php";
$upcoming_departures  = getUpcomingFivePackages();

include_once('header.php');
?>
<div class="container">
</div>
  <div class="container-fluid onecolumn clearfix">
  
         
   
<section id="maincontent">
<div id="header_box">


<div class="container">
 
<div class="row body_content clearfix clr" style="padding-bottom:0px;">

   
     <div class="col-sm-12" style="margin-top:25px; margin-bottom:25px;">
     
       <div class="col-sm-4">
       <div class="form-group col-sm-12" style="border:2px solid #fafafa; padding:10px; box-sizing:border-box; padding-top:25px; padding-bottom:45px; border-radius:5px;">
      
         <div class="col-sm-11">
       <h2 class="h_title">Searching For a Package?</h2>
<form action="location.php">
      <label for="sel1">Where are you planning to go?</label>
      <select style="margin-top:5px;" class="form-control" id="sel1" name="id">
      <option>Please Select</option>
  
        <?php

                            $locations = listLocations();

							

                            foreach($locations as $super)

							

                              {

                             ?>

                             

                             <option value="<?php echo $super['location_id'] ?>"><?php echo $super['location_name'] ?></option>

                             

                             <?php } ?>
      </select>
      
      

     
        <button type="submit" class="btn btn-danger2" style="margin-top:20px;">Search</button>

</form>
</div>
  <div class="col-sm-1"></div>
</div>
       </div>
       
       <div class="col-sm-8">
     <!-- BEGINNING OF IMAGE SLIDER -->
     
     <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
    <li data-target="#myCarousel" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="images/home_banner01.png" alt="Chania">
    </div>

    <div class="item">
      <img src="images/home_banner02.png" alt="Chania">
    </div>

    <div class="item">
      <img src="images/home_banner03.png" alt="Flower">
    </div>

    <div class="item">
      <img src="images/home_banner04.png" alt="Flower">
    </div>
  </div>

  <!-- Left and right controls -->
 <!-- <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a> -->
  </div>
</div>
</div>
</div>
</div>
<!-- END OF IMAGE SLIDER -->
<div  style="background:#fafafa;">
 <div class="container">
 
 <div class="col-sm-4" style="padding:30px; box-sizing:border-box;">
 <h2 class="h_title">Enquiry? Let us Call you : </h2>
 
 <form role="form" action="<?php echo "processMail.php?action=contact"; ?>" method="post">
  <div class="form-group">
    <label for="name">Name:</label>
    <input type="name" class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="mobile">Contact No:</label>
    <input type="tel" class="form-control" id="mobile" name="mobile">
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control" id="email" name="email">
  </div>
  <div class="form-group">
    <label for="description">Description:</label>
    <textarea rows="4" cols="6" id="description" name="description"></textarea>
  </div>
  <button type="submit" class="btn btn-danger2" style="margin-top:10px;">Submit</button>
</form>
 </div>
 

 
 <div class="col-sm-8">
 
      <div class="departurelist" >
     <div class="row voffset4">
	<div class="col-sm-9" ><h2><a href="#">Upcoming Departure From Akhil Bharat</a></h2></div>
    <div class="col-sm-3 text-right" >
		    </div>
</div>

<div class="row departureitem">  
<div class="col-sm-12">	
    <div class="table_container listitem clearfix" id="listtables">
		<table class="table table-hover">
          <thead>
            <tr>
              <th><strong>Trip</strong></th>	
              <th><strong>Trip Date</strong></th>
              <th class="text-center"><strong>Starting Price</strong></th>
              
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php 
			
		  foreach($upcoming_departures as $up)
		  {
			  $package_id = $up['package_id'];
			  $package = getPackageByID($package_id);
		   ?>
		            <tr>
			<td data-title="Trip"><span> </span> <a href="package.php?id=<?php echo $up['package_id']; ?>"><?php echo $package['package_name']; ?></a></td>          
            <td data-title="Trip Dates"><span><img src="images/trip-calendar.png" width="16" border="0" alt="calendar">  &nbsp;  </span><?php echo date('M d, Y',strtotime($up['upcoming_package_date'])) ?></td>
            <td class="text-center" data-title="Price"><span><img src="images/tag.png" alt="tag"> &nbsp; </span> ₹<?php echo number_format(getLowestIndividualPackageCostFromToday($package['package_id']),0); ?>*</td>
                
            <td class="text-center">
                                        <a href="package.php?id=<?php echo $up['package_id']; ?>" title="View More" class="blue_btn" rel="noindex,nofollow">View More</a>
                    			</td>
          </tr>
          <?php } ?>
                 
                    </tbody>
        </table>
    </div>
  </div>
  </div>
  <div class="col-sm-12">
      </div>
</div> 
</div>  
        </div>
        </div>
        </div>
        
    <div class="container" style="margin-top:40px;">
    <div class="row body_content clearfix clr">
    <div class="col-sm-12">
      <h2>Featured Packages</h2>
     </div>
      <div class="col-sm-12 latest_boxes">
      <div class="row">
      
        
        <?php 
	$featured_packages =  getAllFeaturedPackages();
	  foreach($featured_packages as $featured_package) { ?>
        <div class="col-sm-4 col-sm-6 list" style="">
          <div class="imagebox"> <a href="<?php echo "package.php?id=".$featured_package['package_id']; ?>" title="<?php echo $featured_package['package_name']; ?>"><img src="<?php echo WEB_ROOT."images/package_icons/".$featured_package['thumb_href']; ?>" alt="<?php echo $featured_package['package_name']; ?>" class="img-responsive fullwidth"></a> </div>
          <div class="content clearfix" style="padding:7.5px;border:1px solid #eee;">
            <div class="title"><a href="<?php echo "package.php?id=".$featured_package['package_id']; ?>" title="<?php echo $featured_package['package_name']; ?>"><?php echo $featured_package['package_name']; ?></a></div>
            <p> <?php $places = $featured_package['places']; $places_array = explode(",",$places);
				 $i=0;
				 foreach($places_array as $place)
				 { 
				  ?>
                 <?php echo trim($place); if($i!=(count($places_array)-1)) echo " - "; $i++; ?> 
                  <?php } ?>   </p>
                   <div style="font-size:15px;padding-top:5px;font-weight:normal;color:#555;padding-bottom:10px;"><?php echo $featured_package['days']; ?> Days / <?php echo $featured_package['nights']; ?> Nights</div>
                   <div style="clear:both;"> starting at just <?php echo "( ".$featured_package['from_location']." - ".$featured_package['to_location']." )"; ?></div>
                   <h2 style="font-size:22px;color:#d52737;padding-top:5px;font-weight:normal;width:60%;margin:0;float:left;"> ₹ <?php echo number_format(getLowestIndividualPackageCostFromToday($featured_package['package_id']),0); ?></h2>
          </div>
          
        </div>
       
        <?php } ?>
       
      </div>
      </div>
    </div>
  
    
    <div class="row traveller_review clearfix clr">
    <div class="col-sm-4  clearfix"></div>
      <div class="col-sm-4  clearfix"><a href="group_tours.php" title="Find More" target="_blank"><div class="morereview">Find out More!</div></a></div>
  </div>
  
  
  </div>
  </div>
 </section>
</div>
<?php
include_once('footer.php');
?>  
<script>
$( ".datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy',
	   numberOfMonths : 1,
	    onSelect: function(dateText, inst) {
			$(this).focus();
		}
    });
</script>