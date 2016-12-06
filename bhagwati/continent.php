<?php
require_once('admin/lib/location-functions.php'); 
$body_id="destinations";
$active_link = "holidays";
$super_location_id = $_GET['id'];

if(!is_numeric($super_location_id))
{
	
	header('Location: holidays.php');
	exit;
	}

$super_location = getSuperLocationById($super_location_id);

$locations = listLocationsForSuperLocation($super_location_id);	
if(is_array($locations) && count($locations)==1)
{
	
header('Location: location.php?id='.$locations[0]['location_id']);
exit;
}


require_once('header.php'); 	 ?>

	<div id="main" role="main">

<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAa2yWL8eWEI_HzOkhy37dsocHqr-xwIDA">
    </script>
    <script type="text/javascript">
	
	
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(<?php echo $super_location['lat'] ?>, <?php echo $super_location['lng'] ?>),
          zoom: 4,
		  maxZoom: 4,
		  minZoom:4,
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

<?php foreach($locations as $location) {

	 ?>	
	var <?php echo "a".$location['location_id']; ?>_lat = new google.maps.LatLng(<?php echo $location['latitude'] ?>,<?php echo $location['longitude']; ?>);
	var <?php echo "a".$location['location_id']; ?> = 'admin/images/location_icons/<?php echo $location['img_href']; ?>';
	
	<?php echo "a".$location['location_id']; ?>_marker = new google.maps.Marker({
    map:map,
    draggable:false,
    position: <?php echo "a".$location['location_id']; ?>_lat,
	icon: <?php echo "a".$location['location_id']; ?>
  });
  
  google.maps.event.addListener(<?php echo "a".$location['location_id']; ?>_marker, 'click', <?php echo "a".$location['location_id']; ?>Click);
  
  
	
<?php } ?>	
	

}

<?php foreach($locations as $location) { ?>	
	
function <?php echo "a".$location['location_id']; ?>Click(){
	
	 window.location.href="location.php?id=<?php echo $location['location_id']; ?>";
	
	}
  
  
	
<?php } ?>	

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
			<p>Bhagwati Holidays offers customized journeys to the most exotic travel destinations in Asia, India and the Himalayas, Africa, the Middle East, Latin America, and the South Pacific. We have explored every inch of these regions and the countries in them; we’ve wandered their cities, hiked their mountains, eaten at their Michelin-starred restaurants (and their tiny street-side vendors), and relaxed at their most spectacular hotels. We are opinionated luxury travel specialists with a true passion for luxury travel, and we’d love to share our insider knowledge of the world’s most unique travel destinations with you.</p>
				
		</div>
				<a href="#" class="more-link typ16 more2">Read More <span class="icon-down"></span></a>
		<div class="more-after">
			<p>We have over two decades of experience in the luxury travel business, so we know exactly which island in the South Pacific has the best diving, and which has the emptiest beaches. We know which Middle Eastern country has the most opulent mosques and impressive collections of Arabic art, which vineyard in Latin America produces the best Malbec and which has the best views of the Andes, which country in Asia has the best trekking, and which offers the best museums, ancient temples, or street food. No other travel company can match the depth of our knowledge about these exotic locations, or our excitement about sharing them with you.</p>
<p>Though all of our trips are one-of-a-kind, we’ve created multiple sample itineraries for each of our unique travel destinations so you can see what each one offers. These itineraries show how you might see a country’s most famous sites, and different ways to get off-the-beaten-path. We offer travel by interest journeys that focus on specific experiences, such as adventure travel, cooking, and yoga, as well as journeys according to purpose, such as weddings, golf, honeymoons, and family vacations or reunions.  </p>
<h2 class="typ17">Exotic Travel Destinations</h2>
<p>Our trips can be short jaunts to capital cities, such as Buenos Aires, or to specific sites, such as Angkor Wat. They can be multi-week treks across a single country or across entire regions, such Patagonia, Central Asia, and other enthralling and exotic travel destinations.</p>
<p>No matter where you decide to go, if you go with Bhagwati Holidays, you are guaranteed an intimate and unique experience that no other company can provide.</p>
		</div>
			</article>

		<div class="cta">
		<hr class="line1">
		<a href="contact.php" class="typ16 shadow2 btn" id="syj">Start Your Journey</a>
	</div>



</div> <!-- .row -->



	</div> <!-- #main -->
	

<?php require_once('footer.php'); ?>