<?php
require_once('admin/lib/hotel-location-functions.php');
require_once('admin/lib/hotel-package-functions.php'); 
$body_id="home";
$active_link = "holidays";
$location_id = $_GET['id'];

if(!is_numeric($location_id) || $location_id==-1)
{
	
	$packages = listHotelPackages();
	}
else
{
$location = getHotelLocationByID($location_id);
$packages = listHotelPackagesForLocationId($location_id);	
}

require_once('header.php'); 	 ?>

	<div id="main" role="main">


<div class="row" style="padding-top:35px;">

	

</div>

<?php if(is_array($packages) && count($packages)>0) { ?>
<div class="container">

<header class="headline shadow">
		<span class="typ1">Featured Hotel Packages</span> <span style="float:right;margin-top:10px;"><select id="location"  name="location_id" onchange="document.location.href='<?php echo $_SERVER['PHP_SELF']."?id=" ?>'+this.value" >
                        <option value="-1" >All Locations</option>
                        <?php
                            $locations = listValidHotelLocations();
							
                            foreach($locations as $super)
							
                              {
                             ?>
                             
                             <option value="<?php echo $super['location_id'] ?>" <?php if($location_id== $super['location_id']) { ?> selected="selected" <?php } ?>><?php echo $super['location_name'] ?></option>
                             
                             <?php } ?>
                            </select> </span>
	</header>

	<hr>
	<br>

	
	<section class="boxes clearfix">


	<?php foreach($packages as $package) { ?>
	
		


	<div class="box border shadow center">
		<a href="hotel_package.php?id=<?php echo $package['hotel_package_id']; ?>" title="<?php echo $package['hotel_package_name']; ?>">
			<div class="journey-link">
				<img src="images/journey_overlay.png" height="100%;" alt="" class="mask" />
				<img width="306"  src="<?php echo "admin/images/package_icons/".$package['thumb_href']; ?>" class="attachment-full wp-post-image" alt="<?php echo $package['package_name']; ?>" />			</div>
			<div class="journey-info">
				<h5 class="boxhead typ4u"><?php echo $package['hotel_package_name']." (".$package['stars']." Stars )"; ?></h5>
				  <hr class="line1">
				<p style="padding-bottom:10px;"><?php echo $package['tarriff']." Rs "; ?></p>
                <hr class="line1">
				<p><?php echo $package['days']." Days / ".$package['nights']." Nights"; ?></p>
               
              
			</div>
		</a>
		
	</div>


	<?php } ?>

	


	
		


	</section>
		
	
    
   
</div> <!-- .container -->


<?php } ?>

	</div> <!-- #main -->
	

<?php require_once('footer.php'); ?>