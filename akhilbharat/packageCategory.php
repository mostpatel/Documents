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
$package_category_id = $_GET['id'];
if(!checkForNumeric($package_category_id))
exit;

$package_category = getPackageCategoryByID($package_category_id);
$car_images = getCarosalImagesForPackageCategory($package_category_id);
$packages = listPackagesForPackageCategoryId($package_category_id);

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
     <h2 class="h_title"><?php if($package_category['type']==0) echo "Group Tours"; else if($package_category['type']==1) echo "Individual Packages"; ?> / <?php  echo $package_category['pkg_cat_name']; ?></h2>
     </div>
     <div class="col-sm-12">
                 <div class="col-sm-4 col-md-4">
                   <div class="form-group col-sm-12" style="border:2px solid #fafafa; padding:10px; box-sizing:border-box; padding-top:5px; padding-bottom:10px; border-radius:5px;">
                  
                             <div class="col-sm-11">
                           <h2 style="color:#d52737;font-size:18px;" style="margin:0" class="h_title">Searching For a Package?</h2>
                    <form>
                          <label for="sel1">Where are you planning to go?</label>
                          <select style="margin-top:5px;" class="form-control" id="sel1">
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
      
     <div class="col-md-8 col-sm-8">
      <img style="width:100%;" src="<?php echo WEB_ROOT."images/car_images/".$car_images[0]['img_href']; ?>" alt="">
      </div>
   </div>
   </div>
   </div>
   </div>
<!-- END OF IMAGE SLIDER -->
<div  style="background:#fafafa;margin-top:5px;">
 <div class="container">
 
 <div class="col-sm-12 col-md-4" style="padding:30px; box-sizing:border-box;">
 <h2 class="h_title">Enquiry? Let us Call you : </h2>
 
 <form role="form">
  <div class="form-group">
    <label for="email">Name:</label>
    <input type="email" class="form-control" id="email">
  </div>
  <div class="form-group">
    <label for="pwd">Contact No:</label>
    <input type="text" class="form-control" id="pwd">
  </div>
  <div class="form-group">
    <label for="pwd">Email:</label>
    <input type="text" class="form-control" id="pwd">
  </div>
  <div class="form-group">
    <label for="pwd">Description:</label>
    <textarea rows="4" cols="6"></textarea>
  </div>
  <button type="button" class="btn btn-danger2" style="margin-top:10px;">Submit</button>
</form>
 </div>
 

 
 <div class="col-sm-12 col-md-8">
 
      <div class="departurelist" >
     <div class="row voffset4">
	<div class="col-sm-12" ><h2><a href="#">Upcoming Departure From Akhil Bharat</a></h2></div>

</div>

<div class="row departureitem">  
<div class="col-sm-12">	
	<?php foreach($packages as $package ) { ?>
        <div class="table_container listitem clearfix" style="padding:0;" id="listtables">
        <div  class="col-sm-5 col-md-5" style="background:url(<?php echo WEB_ROOT."images/package_icons/".$package['thumb_href']; ?>); background-repeat:no-repeat;
  background-position: center;
  background-size: cover;min-height:215px;">
             
        </div>
        	<div class="col-sm-7 col-md-7" style="padding-top:10px;">
                 <h2 style="font-size:18px;"><?php echo $package['package_name'] ?></h2>   
                 <h2 style="font-size:15px;color:#d52737;padding-bottom:5px;font-weight:normal;">Tour Highlights</h2>
                
                 <?php $places = $package['places']; $places_array = explode(",",$places);
				 $i=0;
				 foreach($places_array as $place)
				 { 
				  ?>
                 <?php echo trim($place); if($i!=(count($places_array)-1)) echo " - "; $i++; ?> 
                  <?php } ?>  
                  
                  
                   <div style="font-size:15px;padding-top:5px;font-weight:normal;color:#555;padding-bottom:10px;"><?php echo $package['days']; ?> Days / <?php echo $package['nights']; ?> Nights</div>
                   <div style="clear:both;"> starting at just <?php echo "( ".$package['from_location']." - ".$package['to_location']." )"; ?></div>
                   <h2 style="font-size:22px;color:#d52737;padding-top:5px;font-weight:normal;width:60%;margin:0;float:left;"> ₹ <?php echo number_format(getLowestIndividualPackageCostFromToday($package['package_id']),0); ?></h2>
                  
                 <a href="<?php echo "package.php?id=".$package['package_id']; ?>"> <button style="float:right;margin-right:20px;" type="button" class="btn btn-danger2" style="margin-top:10px;">View Details</button></a>
                  
                 </div>
                  
        </div>
        <?php } ?>
  </div>
  </div>
  
</div> 
</div>  
        </div>
        </div>
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