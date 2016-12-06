<?php
require_once('admin/lib/hotel-location-functions.php');
require_once('admin/lib/hotel-package-functions.php');
$body_id="journey";
$active_link = "hotels";
$location_id = $_GET['id'];

$package=getHotelPackageByID($location_id);

if(!is_numeric($location_id))
{
	
	header('Location: holidays.php');
	exit;
	}

require_once('header.php'); 	 ?>
<div id="main" role="main">


<div class="row top">

		<div class="col col2">


			<div class="box3 shadow clearfix">
            	
            	<div style="width:350px;float:left;"><img style="text-align:center;"  src="<?php echo "admin/images/package_icons/".$package['thumb_href']; ?>" class="attachment-full wp-post-image" alt="<?php echo $package['package_name']; ?>" /></div>
                <div class="col col2">	
				<h3 style="margin-top:20px;" class="typ13"><span>Package Details:</span></h3>
				<dl>
					<dt><span><strong><?php echo $package['hotel_package_name']; ?></strong></span></dt>
					
					<dd><span><strong><?php echo $package['days']." Days / ".$package['nights']." Nights "; ?></strong></span></dd>
					
					<dd><span>Tariff : <?php echo $package['tarriff']." Rs"; ?></span></dd>
					<dt><span><h1>Location : <?php echo $package['location_name']; ?></h1></span></dt>
					
				</dl>
				<div class="spacer"><span>&nbsp;</span></div>
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