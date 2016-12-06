<?php
require_once('admin/lib/cg.php');
require_once('admin/lib/location-functions.php');
require_once('admin/lib/package-functions.php');
require_once('admin/lib/inquiry-location-type-functions.php');
require_once('admin/lib/inquiry-package-type-functions.php');
$body_id="destinations";
$active_link = "holidays";
$location_id = $_GET['id'];

if(is_numeric($location_id))
{
$location_array=getLocationsForPackageId($location_id); 
$location_id = $location_array[0][0];
$location = getLocationByID($location_id);
}

require_once('header.php'); ?>

<link href="css/jquery-ui.min.css" rel="stylesheet" />
<link href="css/jquery-ui.theme.min.css" rel="stylesheet" />
<style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>
<script type="text/javascript" src="js/jquery-ui.min.js" ></script>
<div id="main" role="main">
<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAa2yWL8eWEI_HzOkhy37dsocHqr-xwIDA">
    </script>
    <script type="text/javascript">
	
	
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(31.00, 00),
          zoom: 2,
		  maxZoom: 2,
		  minZoom:2,
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
	
	
}


      google.maps.event.addDomListener(window, 'load', initialize);
    </script>

<section class="worldmap">

	<div id="map"></div>
</section> <!-- .map -->        
<div class="row" style="margin-top:20px;">

<section class="page-twocols clearfix">
	<div class="col">
		<h1 class="typ5u" style="margin-bottom:10px;">Contact (International): <span style="color:#666;"> 079-40223313-23</span> </h1>	
       <h1 class="typ5u" style="margin-bottom:20px;">Contact (Domestic): <span style="color:#666;">079-40223315-25 | 9978130555</span> </h1>	
		<h1 class="typ2u underline">Plese Fill Up The Details Below</h1>	
       
		<br />
        
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if(isset($type)  && $type>0 && $type<4) { ?> <strong>Success!</strong> <?php } else if(isset($type) && $type>3) { ?> <strong>Warning!</strong> <?php } ?> <?php echo $msg; ?>
</div>
<?php
		
		
		}
	if(isset($type) && $type>0)
		$_SESSION['ack']['type']=0;
	if($msg!="")
		$_SESSION['ack']['msg']=="";
}

?>

		<form action="<?php echo "processMail.php?action=start_journey"; ?>" method="post"  class="form1 clearfix" id="contact-form">
	
		
			<p class="medium box border shadow clearfix"><label class="hidden">Name:</label><input type="text" name="contact.firstname" maxlength="100" placeholder="Name" id="fn"></p>
            <p class="medium box border shadow clearfix"><label class="hidden">Contact Number:</label><input type="text" name="contact.mobile" maxlength="100" placeholder="Mobile Number (10 digits)" id="ln"></p>
			<p class="medium box border shadow clearfix"><label class="hidden">Email:</label><input type="text" name="contact.emailaddress1" maxlength="100" placeholder="Email" id="em"></p>
            
            <p class="medium box border shadow clearfix"><label class="hidden">Booking Date:</label><input type="text" name="contact.booking_date" maxlength="100" placeholder="Booking Date" id="bd"></p>
            
              <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;line-height: 1.6666em;font-weight: 400;font-style: italic;font-size: 14px;">Type: &nbsp;</label><select name="contact.location_type" maxlength="100" placeholder="no_of_nights" id="no_of_nights">
            	<?php $package_types = listInquiryLocTypes();
			 	foreach($package_types as $package_type)
				{
			  ?>
             	<option value="<?php echo $package_type['loc_type_id']; ?>" <?php if(isset($location['super_location_id']) && $location['super_location_id']!= $package_type['loc_type_id']) { ?> selected="selected" <?php } ?> ><?php echo $package_type['loc_type']; ?></option>
            	
                <?php } ?>
             </select></p>	
            
              <p class="medium box border shadow clearfix"><label class="hidden">Destination:</label><input type="text" name="contact.destination" maxlength="100" placeholder="Destination" id="destination" value="<?php if(is_numeric($location_id) && isset($location['location_name'])) echo $location['location_name']; ?>"></p>
			
             <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;
line-height: 1.6666em;
font-weight: 400;
font-style: italic;
font-size: 14px;">Number of nights: &nbsp;</label><select name="contact.number_of_nights" maxlength="100" placeholder="no_of_nights" id="no_of_nights">
             <?php for($i=1;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select></p>
             
             <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;
line-height: 1.6666em;
font-weight: 400;
font-style: italic;
font-size: 14px;">Adult: &nbsp;</label><select name="contact.adult" maxlength="100" placeholder="adult" id="adult">
             <?php for($i=1;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select></p>
             
              <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;
line-height: 1.6666em;
font-weight: 400;
font-style: italic;
font-size: 14px;">Child: &nbsp;</label><select name="contact.child" maxlength="100" placeholder="child" id="child">
             <?php for($i=0;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select></p>
             
             
              <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;line-height: 1.6666em;font-weight: 400;font-style: italic;font-size: 14px;">Package Type: &nbsp;</label><select name="contact.package_type" maxlength="100" placeholder="no_of_nights" id="no_of_nights">
             <?php $package_types = listInquiryPackageTypes();
			 	foreach($package_types as $package_type)
				{
			  ?>
             	<option value="<?php echo $package_type['pck_type_id']; ?>"><?php echo $package_type['package_type']; ?></option>
            	
                <?php } ?>
             </select></p>
			

			<p class="comment-form-comment box border shadow"><textarea rows="" cols="" name="contact.inquiry" placeholder="Type message here..."></textarea></p>

			<p><input type="submit" value="submit" class="btn big-btn typ16"></p>
		</form>

		<br /><br /><br />

	

	</div>

	


</section>

</div> <!-- .row -->


	</div> <!-- #main -->
     <script>
  $(function() {
    $( "#bd" ).datepicker({
      changeMonth: true,
      numberOfMonths: 3,
	  minDate : 0,
	  dateFormat: 'dd/mm/yy'
		});
  });
  </script>
  <script>
    $( "#destination" ).autocomplete({
      minLength: 3,
      source:  function(request, response) {
                $.getJSON ('admin/json/destination_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#destination" ).val(ui.item.label);
			return false;
		}
    });	
  </script>
<?php require_once('footer.php'); ?>