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
  <div class="container-fluid onecolumn clearfix" style="padding:0;">
  
         
   
<section id="maincontent">
<div id="header_box">



   
     <div class="col-sm-12" style="padding:0">
     <img src="images/hotel.jpg" width="100%;"/>
      </div>
   
<div  style="background:#fafafa;">
 <div class="container">
 
 <div class="col-sm-6" style="padding:30px; box-sizing:border-box;">
 <h2 class="h_title">Enquiry For Hotel Rooms? Let us Call you! </h2>
 
 <form action="<?php echo "processMail.php?action=hotel"; ?>" method="post"  class="form1 clearfix" id="contact-form">
	
		
			<label >Name:</label><input type="text" name="name" maxlength="100" placeholder="" id="name">
            <label >Contact Number:</label><input type="text" name="mobile" maxlength="100" placeholder="(10 digits)" id="mobile"></p>
			<label >Email:</label><input type="text" name="email" maxlength="100" placeholder="" id="email"></p>
            
           <label >Booking Date:</label><input type="text" name="booking_date" maxlength="100" placeholder="" id="bd"></p>
            
             <label for="sel1">Location:</label>
      <select style="margin-top:5px;" class="form-control" id="sel1" name="destination">
      <option>Please Select</option>
  
        <?php

                            $locations = listLocations();

							

                            foreach($locations as $super)

							

                              {

                             ?>
                             <option value="<?php echo $super['location_name'] ?>"><?php echo $super['location_name'] ?></option>
                             <?php } ?>
      </select>
      
                
            
			
             <label for="no_of_nights">Number of nights: &nbsp;</label><select name="number_of_nights" maxlength="100" placeholder="no_of_nights" id="no_of_nights">
             <?php for($i=1;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select></p>
             
              <label for="no_of_nights" >No of Rooms: &nbsp;</label><select name="no_of_rooms" maxlength="100" placeholder="" id="no_of_rooms">
             <?php for($i=1;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select>
             
           
            
			
			<label for="message" >Message: &nbsp;</label>
			<textarea id="message" rows="" cols="" name="inquiry" placeholder="Type message here..."></textarea>

			 <button type="submit" class="btn btn-danger2" style="margin-top:20px;">Submit</button>
		</form>
 </div>
 <style>
 label{
	 margin-top:10px;
	 margin-bottom:5px;
	 }
 </style>

 
 
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
	 
	
	
	 
  $(function() {
    $( "#bd" ).datepicker({
      changeMonth: true,
	   changeYear: true,
      numberOfMonths: 1,
	  minDate : 0,
	   dateFormat: 'dd/mm/yy'
  });
  });
  </script>
