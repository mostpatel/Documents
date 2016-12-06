<?php 
$body_id="home";
$active_link = "home";
require_once('admin/lib/cg.php');
require_once('admin/lib/bd.php');
require_once('admin/lib/common.php');
require_once('admin/lib/package-functions.php');
require_once('admin/lib/location-functions.php');
require_once('admin/lib/testonomial-functions.php');
require_once('header.php');
 ?>
<div id="main" role="main">

<div id="herowrap">
	<div id="scroll_pager"></div>	
	<div id="hero" >
			
				<img src="images/slider/Bhagwati-Holidays-the-siam-thailand.jpg" alt="the siam_banner_home" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 16; width: 100%; opacity: 0; min-height:482px;">
				
				<img src="images/slider/Bhagwati-Holidays-1.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 3; width: 100%; opacity: 0; min-height:482px;">
                
				<img src="images/slider/Bhagwati-Holidays-2.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 13; width: 100%; opacity: 0; min-height:482px;">
				
				<img src="images/slider/Bhagwati-Holidays-salt-pans-bolivia.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 11; width: 100%; opacity: 0; min-height:482px;">
				
				<img src="images/slider/Bhagwati-Holidays-3.jpg" alt="" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 9; width: 100%; opacity: 0; min-height:482px;">
				
				
				<img src="images/slider/Bhagwati-Holidays-Indian-Slippers_Home.jpg" alt="Indian Slippers_Home_Banner_India" style="position: absolute; top: 0px; left: 0px; display: none; z-index: 2; width: 100%; opacity: 0;  min-height:482px; ">
				
			</div>

	

	
</div>




<div class="container three-amigos">

	<a href="air_tickets.php" title="Fly with us" class="grid-2"><img src="images/amigos/air-tickets.jpg" alt="Fly With Us" width="306" height="182" class="border shadow"></a>

	<a href="holidays.php" title="Enjoy Your Tour" class="grid-2"><img src="images/amigos/destinations.jpg" alt="Enjoy Your Tour" width="306" height="182" class="border shadow"></a>

	<a href="hotels.php" title="Stay with us" class="grid-2"><img src="images/amigos/hotels.jpg" alt="Stay with us" width="306" height="182" class="border shadow"></a>
</div>






<div class="row" style="padding-top:35px;">

	<section class="maincontent center p2">
		<h2 class="typ13">Gujarat's top luxury travel experts, creating your ideal journey</h2>
		<div class="more-before">
			<p>Bhagwati Holidays Pvt. Ltd is an Ahmedabad based company which deals with Luxury Travelling and provides services such as providing customized trips, air ticket booking, hotel booking and visa consulting. Here you won’t find packed tour with a fix schedule anda fix departure date. We travel Differently. We at Bhagwati Holidays provide luxury travel options which can be customized according to your budget, schedule, interests and convenience.</p>
				
		</div>
				<a href="#" class="more-link typ16">Read More <span class="icon-down"></span></a>
		<div class="more-after">
			<p></p>
<h2 class="typ3u">Bhagwati Holidays</h2>
<p>At Bhagwati Holidays, you won’t find a fix itinerary for a particular Travel Destination. We customize each traveller’s itinerary according to the traveller’s interests. Whether you know where you exactly want to go, or you have a very vague idea like I want to explore mountains this year or I want to go to a place which has a beach or you don’t know anything, you just know you want to go on  a holiday, we’ll take care of everything in each of the case. 
</p>

<p>We’ll suggest you best possible holiday destination according to your interests, convenience & budget. We have an experience of over a decade in private luxury tours and planning. So, when it comes to best possible tailor made, customized tours, Bhagwati Holidays is the trusted name. We don’t want your Holiday Trip just to be a vacation from routine life, we want to create an experience for lifetime. So, go ahead and Start Your Journey. </p>

		</div>
		
			<div class="cta">
		<hr class="line1">
		<a href="start_journey.php" class="typ16  btn">Start Your Journey</a>
	</div>

	

	</section>

</div>











<div class="container">
	<h2 class="typ13 typ3u center" style="padding-bottom:20px;">Featured Packages</h2>
		<section class="boxes clearfix" style="min-height:500px">

			<?php $packages = getAllFeaturedPackages(); foreach($packages as $package) { ?>
		<div class="box border shadow center">
		<a href="package.php?id=<?php echo $package['package_id']; ?>" title="<?php echo $package['package_name']; ?>">
			<div class="journey-link">
				<img src="images/journey_overlay.png" height="100%;" alt="" class="mask" />
				<img width="306"  src="<?php echo "admin/images/package_icons/".$package['thumb_href']; ?>" class="attachment-full wp-post-image" alt="<?php echo $package['package_name']; ?>" />			</div>
			<div class="journey-info">
				<h5 class="boxhead typ4u"><?php echo $package['package_name']; ?></h5>
				<hr class="line1">
				<p><?php echo $package['days']." Days / ".$package['nights']." Nights"; ?></p>
			</div>
		</a>
		
	</div>

        
        <?php } ?>

		
	</section> <!-- .three-cols -->

</div> <!-- .container -->

<div class="container">
	<h2 class="typ13 typ3u center" style="padding-bottom:20px;">Testomonials</h2>
    <div id="testonomials">
    
    <?php $testonomials = listTestonomials();
	for($i=0;$i<count($testonomials);$i++)
	{
	$testonomial = $testonomials[$i];	
	if($i%3==0)
	{	
	 ?>
	<div class="three-col sameheight clearfix">
	<?php } ?>
    		
		<div class="col same-height-left" style="height: 375px;padding-top:20px;">
			<div class="testo_by"><div style="position:relative;float:left;width:30%;padding-left:5%"><img src="<?php echo "admin/images/testonomial_icons/".$testonomial['img_href']; ?>" style="border-radius:10px; width:auto; height:70px;position:relative;"/></div><div class="typ3u" style="padding-left:00px;position:relative; float:left;width:66%;"><?php echo $testonomial['person_name']; ?><br><span style="font-size:12px;position:relative;left:00px;color:#000;top:-5px;"><?php echo $testonomial['person_designation'] ?></span><br><span style="font-size:12px;position:relative;left:00px;top:-10px;"><?php echo $testonomial['person_company']; ?></span></div></div>
			
            <p class="text" style="line-height:25px;font-size:20px;position:relative;margin-top:10px;"><span style="font-size:30px;">"</span><?php echo $testonomial['testonomial']; ?><span style="font-size:30px;">"</span></p>		
			
			
		</div>

		
	<?php if(($i+1)%3==0) { ?>	
	</div> <!-- .three-cols -->
    <?php } ?>
<?php } ?>
    
    

</div> <!-- id= testonomials -->
</div> <!-- .container -->
	</div> <!-- #main -->
<?php require_once('footer.php'); ?>