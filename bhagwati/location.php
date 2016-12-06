<?php
require_once('admin/lib/location-functions.php');
require_once('admin/lib/package-functions.php'); 
$body_id="home";
$location_id = $_GET['id'];
$active_link = "holidays";
if(!is_numeric($location_id))
{
	
	header('Location: holidays.php');
	exit;
	}

$location = getLocationByID($location_id);
$packages = listPackagesForLocationId($location_id);	
$car_images = getCarosalImagesForLocation($location_id);
require_once('header.php'); 	 ?>

	<div id="main" role="main">

<div id="herowrap">
<div id="scroll_pager"></div>	
	<div id="hero" style="position: relative; width: 1500px; margin-left: -106.5px; height: 482px;margin:0 auto;">
				<?php if(!$car_images) { ?>
				<img src="images/slider/absolute-travel-home-23.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 13; width: 1500px; opacity: 0; min-height:482px;">
				<img src="images/3.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 13; width: 1500px; opacity: 0;">
				<img src="images/5.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 13; width: 1500px; opacity: 0; min-height:482px;">
                <?php } else { foreach($car_images as $car_images) { ?>
                <img src="admin/images/car_images/<?php echo $car_images['img_href']; ?>" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 13; width: 1500px; opacity: 0; min-height:482px;">
				
                <?php } } ?>
				
				
			</div>

</div>


<div class="row" style="padding-top:35px;">

	<section class="maincontent center p2">
		<h2 class="typ3u" style="padding-bottom:15px;">About <?php echo $location['location_name']; ?> and Why should you go?</h2>
		<div class="more-before">
			<p><?php echo $location['about']; ?></p>
				
		</div>
        
        <a href="#" class="more-link typ16">Read More <span class="icon-down"></span></a>
		<div class="more-after">
			<p></p>
<h2 class="typ3u">Whay you should go..</h2>
<p><?php echo $location['why_should'];  ?></p>

		</div>
				
		
		
			<div class="cta">
		<hr class="line1">
		<a href="start_journey.php?id=<?php echo $location_id; ?>" class="typ16  btn">Start Your Journey</a>
	</div>

	

	</section>

</div>

<?php if(is_array($packages) && count($packages)>0) { ?>
<div class="container">

<header class="headline shadow">
		<span class="typ1">Sample Journeys</span> <h2 class="p4">Your journey will be private and customized based on your schedule, budget, and interests.</h2>
	</header>

	<hr>
	<br>

	
	<section class="boxes clearfix">


	<?php foreach($packages as $package) { ?>
	
		


	<div class="box border shadow center">
		<a href="package.php?id=<?php echo $package['package_id']; ?>" title="<?php echo $package['package_name']; ?>">
			<div class="journey-link">
				<img src="images/journey_overlay.png" height="100%;" alt="" class="mask" />
				<img width="306"  src="<?php echo "admin/images/package_icons/".$package['thumb_href']; ?>" class="attachment-full wp-post-image" alt="<?php echo $package['package_name']; ?>" />			</div>
			<div class="journey-info">
				<h5 class="boxhead typ4u"><?php echo $package['package_name']; ?></h5>
				<hr class="line1">
				<p><?php echo $package['days']." Days / ".$package['nights']." Nights"; ?></p>
				<p><?php echo str_replace(',',', ',$package['places']); ?></p>
			</div>
		</a>
		
	</div>


	<?php } ?>

	


	
		


	</section>
		
	
    
   
</div> <!-- .container -->


<?php } ?>

	</div> <!-- #main -->
	

<?php require_once('footer.php'); ?>