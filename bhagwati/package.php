<?php

require_once('admin/lib/location-functions.php');

require_once('admin/lib/package-functions.php');

require_once("admin/lib/package-itenary-functions.php");

require_once("admin/lib/package-type-functions.php");

 

$body_id="journey";

$active_link = "holidays";

$location_id = $_GET['id'];





if(!is_numeric($location_id))

{

	

	header('Location: holidays.php');

	exit;

	}

$package_itenary = getItenaryForPackageId($location_id);

$package = getPackageByID($location_id);



$package_type = getPackageTypeForPackage($location_id);

require_once('header.php'); 	 ?>

<div id="main" role="main">





<div class="row top">



	<section class="page-twocols sameheight clearfix">

		<div class="col col1">

			

			<div class="box2 shadow">

				<h3 class="typ1u"><span></span>Itinerary<span></span></h3>

				<div class="spacer"><span>&nbsp;</span></div>

				<div class="wrap" style="padding-left:20px;">

					<?php $i=1; foreach($package_itenary as $ite) {

						?>

                        <h4 style="margin-top:10px;border:none;padding:none" class="typ5u"><span></span>Day <?php echo $i++." - ".$ite['itenary_heading']; ?><span></span></h4>

                        <p style="border:none;padding:none"> <?php echo $ite['itenary_description']; ?></p>

						<?php 

						} ?>

                       <?php if(validateForNull($package['inclusions'])) { ?>

                        <h4 style="margin-top:10px;border:none;padding:none" class="typ5u"><span></span>Inclusions<span></span></h4>

                        <pre><?php echo $package['inclusions']; ?></pre>

                       <?php } ?> 

                        <?php if(validateForNull($package['exclusions'])) { ?>

                         <h4 style="margin-top:10px;border:none;padding:none" class="typ5u"><span></span>Exclusions<span></span></h4>

                            <pre><?php echo $package['exclusions']; ?></pre>

                       <?php } ?> 

				</div>

				<div class="spacer"><span>&nbsp;</span></div>

			</div>

		</div>

		<div class="col col2">





			<div class="box3 shadow clearfix">

            	<h3  class="typ14" style="border:none"><span style="border:none"><?php echo $package['package_name']; ?></span></h3>

            	<img width="100%"  src="<?php echo "admin/images/package_icons/".$package['thumb_href']; ?>" class="attachment-full wp-post-image" alt="<?php echo $package['package_name']; ?>" />	

				<h3 style="margin-top:20px;" class="typ13"><span>Details:</span></h3>

				<dl>

		

					<dt><span>Suggested Length:</span></dt>

					<dd><span><strong><?php echo $package['days']." Days"; ?></strong></span></dd>

					<dt><span>Price from:</span></dt>

					<dd><span><?php if(is_array($package_type) && count($package_type)>0) { foreach($package_type as $pt) echo $pt['package_type']." : ".$pt['price']." ".$package['currency']."<br>"; } else echo "On Request"; ?></span></dd>

					<dt><span><h1>Itinerary Highlights</h1></span></dt>

					<dd><span><?php echo $package['places']; ?></span></dd>

				</dl>

				<div class="spacer"><span>&nbsp;</span></div>

				<div class="center">

					<br />

					<h4 class="typ15">The Bhagwati Travel Experience</h4>

					<p>Your journey will be a private, customized travel experience, based on your individual schedule, interests and budget.</p>

					<a href="start_journey.php?id=<?php echo $location_id; ?>" class="typ16 shadow2 btn">Book Now</a>

				</div>

			</div>





		</div>



	</section>



	<hr>

	<br>



	

	</div>







</div> <!-- .row -->







	</div> <!-- #main -->



<?php require_once('footer.php'); ?>