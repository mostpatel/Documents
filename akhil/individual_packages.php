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
      
      

     
        <button type="button" class="btn btn-danger2" style="margin-top:20px;">Search</button>

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
 
 
 

 
 <div class="col-sm-12">
 
     <div class="departurelist" >
     	<div class="row voffset4">
			<div class="col-sm-9" ><h2><a href="#">Individual Packages</a></h2>
        </div>
		<div class="row departureitem">  
			<div class="col-sm-12">	
   				 <?php for($i=0;$i<count($individual_tours);$i++) { 
				 $car_images = getCarosalImagesForPackageCategory($individual_tours[$i]['pkg_cat_id']);
				 ?>
                   
                      <div class="col-md-4 col-sm-6 col-xs-12" style="margin-top:10px;margin-bottom:20px;">
                          
                       <div class="imagebox"> <a href="packageCategory.php?id=<?php echo $individual_tours[$i]['pkg_cat_id'] ?>" title="<?php echo $individual_tours[$i]['pkg_cat_name']; ?>"><img src="<?php echo WEB_ROOT."images/car_images/".$car_images[0]['img_href']; ?>" alt="<?php echo $individual_tours[$i]['pkg_cat_name']; ?>" class="img-responsive fullwidth"></a> </div>
          <div class="content clearfix" style="padding:7.5px;min-height:100px;padding-bottom:0px;">
            <h2  style="font-size:14px"><a style="font-size:14px" href="packageCategory.php?id=<?php echo $individual_tours[$i]['pkg_cat_id'] ?>" title="<?php echo $individual_tours[$i]['pkg_cat_name']; ?>"><?php echo $individual_tours[$i]['pkg_cat_name'] ?></a></h2>
                           
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