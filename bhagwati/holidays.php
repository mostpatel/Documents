<?php $body_id="destinations";
$active_link = "holidays";
require_once('admin/lib/location-functions.php');
require_once('header.php'); ?>

	<div id="main" role="main">

<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAa2yWL8eWEI_HzOkhy37dsocHqr-xwIDA">
    </script>
    <script type="text/javascript">
	
	
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(31.00, 00),
          zoom: 3,
		  maxZoom: 3,
		  minZoom:3,
		  scrollwheel: false,
		  disableDefaultUI: true,
		  styles: [{
        "featureType": "water",
        "stylers": [
            {
                "color": "#5995b7"
            },
            {
                "visibility": "on"
            }
        ]
    },{
        "featureType": "water",
		"elementType": "labels",
        "stylers": [
           
            {
                "visibility": "off"
            }
        ]
    },{
        "featureType": "administrative.province",
		"elementType": "labels",
        "stylers": [
           
            {
                "visibility": "off"
            }
        ]
    },{
        "featureType": "poi",
		"elementType": "labels",
        "stylers": [
           
            {
                "visibility": "off"
            }
        ]
    },
	{
        "featureType": "roads",
		"elementType": "labels",
        "stylers": [
           
            {
                "visibility": "off"
            }
        ]
    },{
        "featureType": "administrative.country",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#9b8232"
            },
            {
                "lightness": 0
            },
            {
                "weight": 0.5
            }
        ]
    },{
        "featureType": "administrative.province",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#d0ae43"
            },
            {
                "lightness": 1
            },
            {
                "weight": 1.2
            },
			{
				"visibility":"off"
			}
        ]
    },{
        "featureType": "landscape",
        "stylers": [
            {
                "color": "#e1c361",
				"visibility": "simplified"
            }
        ]
    }]
	
        };
        var map = new google.maps.Map(document.getElementById("map"),
            mapOptions);
	
	var india_lat = new google.maps.LatLng(21.00,78.00);
	var india = 'images/india-text.png';
	
	var andaman_lat = new google.maps.LatLng(9.497681759706649, 91.590322265625);
	var andaman='images/andaman.png';
	
	var uae_lat = new google.maps.LatLng(20.4667,50.3667);
	var uae = 'images/Uae.png';
	
	var europe_lat = new google.maps.LatLng(48.00, 11.000);
	var europe='images/europe-text.png';
	
	var usa_lat = new google.maps.LatLng(38.4667,-95.3667);
	var usa = 'images/usa-text.png';
	
	var asia_lat = new google.maps.LatLng(35.00, 88.000);
	var asia='images/asia-text.png';
	
	var africa_lat = new google.maps.LatLng(7.1881, 21.0936);
	var africa='images/africa-text.png';
	var aus_lat = new google.maps.LatLng(-25.3080, 135.1245);
	var aus='images/australia-text.png';
	
	india_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: india_lat,
	icon: india
  });
  
  uae_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: uae_lat,
	icon: uae
  });
  
   aus_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: aus_lat,
	icon: aus
  });
  
  africa_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: africa_lat,
	icon: africa
  });
  
   europe_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: europe_lat,
	icon: europe
  });
  
   usa_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: usa_lat,
	icon: usa
  });
  
   asia_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: asia_lat,
	icon: asia
  });
 
  andaman_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: andaman_lat,
	icon: andaman
  });
  google.maps.event.addListener(andaman_marker, 'click', andamanClick);
  google.maps.event.addListener(india_marker, 'click', indiaClick);
  google.maps.event.addListener(asia_marker, 'click', asiaClick);
  google.maps.event.addListener(usa_marker, 'click', usaClick);
  google.maps.event.addListener(europe_marker, 'click', europeClick);
  google.maps.event.addListener(uae_marker, 'click', uaeClick);
   google.maps.event.addListener(aus_marker, 'click', ausClick);
  google.maps.event.addListener(africa_marker, 'click', africaClick);
}

function indiaClick() {
	
  window.location.href="continent.php?id=2";
}

function andamanClick() {
	
  window.location.href="location.php?id=14";
}

function asiaClick() {

  window.location.href="continent.php?id=1";
}

function usaClick() {

  window.location.href="continent.php?id=5";
}

function africaClick() {

  window.location.href="continent.php?id=6";
}

function ausClick() {

  window.location.href="continent.php?id=7";
}

function europeClick() {

  window.location.href="continent.php?id=3";
}

function uaeClick() {

  window.location.href="continent.php?id=4";
}

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>

<section class="worldmap">

	<div id="map"></div>
</section> <!-- .map -->

<section class="co-columns">

	<ul class="boxgrid clearfix names typ15">

		<li class="col"><a href="continent.php?id=1" title="Asia" ><span>Asia</span> <img src="images/asia.png" alt="Asia"></a></li>
	
		<li class="col"><a href="continent.php?id=2" title="India &amp; The Himalayas" ><span>India</span> <img src="images/india.png" alt="India &amp; The Himalayas"></a></li>
		<li class="col"><a href="continent.php?id=3" title="Latin America" ><span>Europe</span> <img src="images/our-europe.png" alt="Latin America"></a></li>
		<li class="col"><a href="continent.php?id=4" title="Middle East" ><span>Middle East</span> <img src="images/middle-east.png" alt="Middle East"></a></li>
		
	
		<li class="col"><a href="continent.php?id=5" title="Getaways" ><span>USA</span> <img src="images/north-america-icon.png" alt="Getaways"></a></li>
		<li class="col"><a href="continent.php?id=6" title="Africa" ><span>Africa</span> <img src="images/africa.png" alt="Africa"></a></li>
		<li class="col"><a href="continent.php?id=7" title="Australia" ><span>Australia</span> <img src="images/australia.png" alt="Australia"></a></li>
	</ul>


	<ul class="co-list clearfix">
		<li>

			<h3 class="reg typ16"><a href="continent.php?id=1" title="Asia">Holidays In Asia</a></h3>
			<div class="group">
			<?php $locations = listLocationsForSuperLocation(1); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

		</li>

	

		<li>

			<h3 class="reg typ16"><a href="continent.php?id=2" title="India">Holidays in India</a></h3>
			<div class="group">
			<?php $locations = listLocationsForSuperLocation(2); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

		</li>

		<li>

			<h3 class="reg typ16"><a href="continent.php?id=3" title="Europe">Holidays In Europe</a></h3>			
			<div class="group">


            <?php $locations = listLocationsForSuperLocation(3); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

			

		</li>

		<li>

			<h3 class="reg typ16"><a href="continent.php?id=4" title="Holidays In Middle East">Middle East</a></h3>
			<div class="group">

		  <?php $locations = listLocationsForSuperLocation(4); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

		</li>

		

		<li>

			<h3 class="reg typ16"><a href="continent.php?id=5" title="Holidays in USA">USA</a></h3>
			<div class="group">

			  <?php $locations = listLocationsForSuperLocation(5); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

		</li>
		
		<li>

			<h3 class="reg typ16"><a href="continent.php?id=6" title="Holidays in Africa">Africa</a></h3>
			<div class="group">

			  <?php $locations = listLocationsForSuperLocation(6); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

		</li>
		
		<li>

			<h3 class="reg typ16"><a href="continent.php?id=7" title="Holidays in Australia">Australia</a></h3>
			<div class="group">

			  <?php $locations = listLocationsForSuperLocation(7); 
				foreach($locations as $location)
				{
			 ?>
			<a href="<?php echo "location.php?id=".$location['location_id']; ?>" title="Bhagwati Holidays - Holidays In <?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></a>
            <?php } ?>
			</div> <!-- .group -->

		</li>


	</ul>
</section>

<div class="row">

	<article class="text">
		<h1 class="typ15">Travel Destinations</h1>
		<div class="more-before more2">
			<p>Bhagwati Holidays offers customized journeys to the most exotic travel destinations in India, Asia, Europe , the Middle East, America, and many more locations. Here’s a quick guide, how you can choose best suitable travel destination according to your interests and needs. From the World Map, choose a particular geo location and furthermore choose a destination in that chosen geo location. It’ll lead you to that travel destination page. On that page, you’ll find why you should go to that particular page and a very basic overview and idea about that travel destination. Also you’ll find Sample Journeys for that destination.</p>
				
		</div>
				<a href="#" class="more-link typ16 more2">Read More <span class="icon-down"></span></a>
		<div class="more-after">
			<p>A Sample Journey is an ideal package which can be obviously customized according to your convenience. Whether you are willing to go on a beach or you want to explore the mountains, whether you want to explore your country or you want to travel abroad, Bhagwati Holidays will make your travel experience lavish, luxurious, enriching & memorable.</p>
<p>We at Bhagwati Holidays know how to make most out of your visit to Andaman Nicobar Islands. Even if you have visited the islands previously, we assure you that we’ll show you Andaman Nicobar with a different perspective, more beautiful and thrilling ever before.  </p>
<p>We are equally passionate about your journeys and we leave no stone unturned in making that trip of yours memorable for lifetime. So, go ahead and Start Your Journey. 
</p>
<p>No matter where you decide to go, if you go with Bhagwati Holidays, you are guaranteed an intimate and unique experience that no other company can provide.</p>
		</div>
			</article>

		<div class="cta">
		<hr class="line1">
		<a href="start_journey.php" class="typ16 shadow2 btn" id="syj">Start Your Journey</a>
	</div>



</div> <!-- .row -->



	</div> <!-- #main -->
	

<?php require_once('footer.php'); ?>