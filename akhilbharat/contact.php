<?php
require_once("admin/lib/cg.php");
require_once("admin/lib/bd.php");
require_once("admin/lib/common.php");
require_once "admin/lib/office-location-functions.php";
require_once "admin/lib/location-functions.php";
require_once "admin/lib/package-functions.php";
require_once "admin/lib/hotel-package-functions.php";
require_once "admin/lib/package-category-functions.php";
require_once "admin/lib/package-type-functions.php";
require_once "admin/lib/vehicle-type-functions.php";
require_once "admin/lib/package-itenary-functions.php";
require_once "admin/lib/package-cost-functions.php";
require_once "admin/lib/office-addresses-functions.php";

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
      
      

     
         <input type="submit" style="color:#fff;margin-top:20px;" class="btn btn-danger2"  value="Search"/>

</form>
</div>
  <div class="col-sm-1"></div>
</div>
       </div>
       
       <div class="col-sm-8">
       
       <h2 class="h_title" style="padding:30px">We are Happy to help you. Contact us : </h2>
       
         <div class="location">
         
      <select style="margin-top:5px;" class="form-control locationList" id="sel1" name="id" onChange="onchangeOfficeLocation(this.value)">
     
  
        <?php

                            $locations = listOfficeLocations();
                            foreach($locations as $super)
							{
							?>

                            <option value="<?php echo $super['location_id'] ?>"><?php echo $super['location_name'] ?></option>

                             <?php } ?>
                            </select>
         </div>
         <?php
		 $i=0;
		  foreach($locations as $super)
							{
								$office_address = listAddressForLocationId($super['location_id']);
								$office_address = $office_address[0];
								 ?>
         <div class="office_address" id="location_<?php echo $super['location_id']; ?>" <?php if($i==0) { ?><?php }else {?> style="display:none;" <?php } ?>>                   
         <div class="address" id="office_address" style="margin-top:15px">
         <?php echo $office_address['address']; ?>
         </div>
         
         <div id="contact_person" class="contactPerson" style="margin-top:15px; font-weight:bold">
        <?php echo $office_address['contact_person']; ?>
         </div>
         
         <div id="contact_no" class="contactNo" style="margin-top:10px">
          <?php echo $office_address['contact_number']; ?>
         </div>
         
         <div id="email" class="email" style="margin-top:0px">
         <?php echo $office_address['email']; ?>
         </div>
         </div>
         <?php $i++;} ?>
     
     
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
 <h2 style="padding:30px;"> Location Map of Ahmedabad Head Office</h2>
 <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14686.568834785838!2d72.5616459!3d23.0369057!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6aacd4e6e937d848!2sAsarvawala+Akhil+Bharat+Tours+%26+Travels+Pvt.+Ltd.!5e0!3m2!1sen!2sin!4v1466342066070" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
      
</div>  
        </div>
        </div>
        </div>
        
    <div class="container" style="margin-top:40px;">
    
  
    
    
  
  
  </div>
  </div>
 </section>
</div>
<?php
include_once('footer.php');
?>  
<script>
function onchangeOfficeLocation(location_id)
{
	$('.office_address').hide();
	$('#location_'+location_id).show();
}
</script>