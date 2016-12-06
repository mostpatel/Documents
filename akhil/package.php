<?php
if(!isset($_GET['id']))
header("Location: index.php");
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



$package_id=$_GET['id'];
$package=getPackageByID($package_id);
if(is_array($package) && $package!="error")
{
	$package_types=getPackageTypeForPackage($package_id);
	$package_itenary=getItenaryForPackageId($package_id);
	$package_location = getLocationForPackage($package_id);
	$package_category = getPackageCategoryForPackage($package_id);
	$packages = listPackagesForPackageCategoryId($package_category[0]['pkg_cat_id']);

	$package_dates = getTourDatesForPackageId($package_id);
	$ind_package_cost = getIndividualPackageCostFromToday($package_id);
	$vehicle_package_cost = getVehiclePackageCostIdFromToday($package_id);
$selected_hotel_array=getHotelIDsForPackageId($package_id);

	$package_dates_string = "";
	for($w=0;$w<count($package_dates);$w++) { $package_dates_string = $package_dates_string."'".date('d/m/Y',strtotime($package_dates[$w]))."'"; if($w!=(count($package_dates)-1)) $package_dates_string=$package_dates_string.","; } 
}

include_once('header.php');
?>

 <div class="container onecolumn clearfix">
  
         
   
   
    <div class="row package_container">
  	<div class="col-md-12">
  
    <h2 class="toptitle" itemprop="name" style="margin-top:25px;font-size:18px;"><a style="font-size:20px;" href="<?php if($package_category['type']==0) echo "group_tours.php"; else if($package_category[0]['type']==1) echo "individual_packages.php"; ?>"> <?php if($package_category['type']==0) echo "Group Tours"; else if($package_category[0]['type']==1) echo "Individual Packages"; ?> </a> / <a style="font-size:20px;" href="<?php echo 'packageCategory.php?id='.$package_category[0]['pkg_cat_id']; ?>"> <?php  echo $package_category[0]['pkg_cat_name']; ?> </a> <h2 style="color:#d52737"> <?php echo $package['package_name']; ?> </h2> </h2>
   
    </div>
   
   	 <div class="col-md-8 lt_panelbox clearfix">
      
       <img style="width:100%;" src="<?php echo WEB_ROOT."images/package_icons/".$package['thumb_href']; ?>" alt="">
      
 

	  
      
      
<div id="tabs" style="margin-top:20px;">
  <ul>
    <li><a href="#tabs-1">Itinerary</a></li>
    <li><a href="#tabs-2">Dates & Cost</a></li>
    <li><a href="#tabs-3">Hotels</a></li>
    <li><a href="#tabs-4">Inclusions & Exclusions</a></li>
     <li><a href="#tabs-5">Terms & Conditions</a></li>
  </ul>
  <div id="tabs-1" style="padding-top:40px;">
     
     
     <h2 style="font-size:17px;"><?php echo $package['package_name'] ?>  (<?php echo $package['days']; ?> Days / <?php echo $package['nights']; ?> Nights)</h2>
     <h2 style="font-size:15px;color:#d52737;">Tour Code : <?php echo $package['tour_code'] ?> &nbsp; (<?php echo $package['from_location'] ?> to <?php echo $package['to_location'] ?>)</h2>
     <h2 style="font-size:17px;">Itinerary</h2>
    <table id="insertCustomerTable" class="insertTableStyling detailStylingTable" style="border-top:2px solid #d52737;">


<?php $i=1; foreach($package_itenary as $ite) { ?>

<tr>
	
    <td style="font-size:16px;color:#d52737;font-weight:bold;padding-top:25px;" width="20%;"><?php echo $ite['itenary_heading']; ?></td>

	
    <td style="padding-left:10px;color:#222;"><?php echo $ite['itenary_description']; ?></td>
</tr>
<?php } ?>
                  

</table>
  </div>
  <div id="tabs-2" >
   <?php $i=1; foreach($ind_package_cost as $package_cost) { 
   $from = $package_cost['from_date'];
   $to = $package_cost['to_date'];
   
  $dates_month_wise= getTourDatesForPackageIdBetweenDates($package_id,$from,$to);
  ?>
  <h2 style="font-size:17px;padding-top:25px;"><?php echo "For Period : ".date('d/m/Y',strtotime($from))." - ".date('d/m/Y',strtotime($to));  ?></h2>
  <table class="cost_table" cellpadding="10" cellspacing="10" width="100%;">
  <tr>
  	<th colspan="2">Reporting Dates at <?php echo ucfirst($package['from_location']); ?> :</th>
   
  </tr>
  <?php
  foreach($dates_month_wise as $date_month)
  { ?>
   <tr>
   	<td class="month_col"><?php echo $date_month['month_name']." ".$date_month['year']; ?></td>
    <td class="date_col"><?php echo $date_month['dates']; ?></td>
   </tr>			
   	
   <?php }  ?>
   </table>
   <h2 style="font-size:15px;padding-top:25px;"><?php echo $package['ind_cost_heading']; ?></h2>
   <table border="1" cellpadding="10" cellspacing="10" width="100%"  class="cost_table red_header">

<tr>
	 <th align="center" width="<?php if($package_cost['per_couple']>0) { echo "16%"; } else echo "20%"; ?>">Vehicle</th>
    <th  align="center" width="20%">Full Ticket</th>
     <th align="center" width="<?php if($package_cost['per_couple']>0) { echo "16%"; } else echo "20%"; ?>">Extra Person</th>
      <th align="center" width="<?php if($package_cost['per_couple']>0) { echo "16%"; } else echo "20%"; ?>">Child with Seat</th>
       <th  align="center" width="<?php if($package_cost['per_couple']>0) { echo "16%"; } else echo "20%"; ?>">Child without seat and Extra Bed (03 to 08 Yrs.)</th>
       <?php if($package_cost['per_couple']>0) { ?>
       
        <th align="center">Per Couple</th>
        <?php } ?>
       
</tr>

<tr>
	<td align="center">AC</td>
    <td align="center"><?php echo $package_cost['full_ticket']; ?>/-</td>
     <td align="center"><?php echo $package_cost['extra_person']; ?>/-</td>
      <td align="center"><?php echo $package_cost['half_ticket_with_seat']; ?>/-</td>
     <td align="center"><?php echo $package_cost['half_ticket_without_seat']; ?>/-</td>
      <?php if($package_cost['per_couple']>0) { ?>
      <td align="center"><?php echo $package_cost['per_couple']; ?>/-</td>
      <?php } ?>

</tr>
</table >
<?php } ?>
   <style>
   .cost_table tr th{
	  
	   font-weight:bold;
	   color:#da251d;
	   font-size:15px;
	   background:#fefefe;
	   }
	  
   .cost_table tr th , .cost_table tr td {
	    border:1px solid #ddd;
		padding:5px;
	   
	   }
	td.month_col{
		color:#da251d;
		font-weight:bold;
		}   
   </style>
   <?php if(is_array($vehicle_package_cost) && count($vehicle_package_cost)>0) { ?>
   <h2 style="font-size:15px;padding-top:25px;"><?php echo ucfirst($package['from_location']); ?> to <?php echo ucfirst($package['to_location']); ?> : <?php echo $package['vehicle_cost_heading']; ?></h2>
  <?php $i=1; foreach($vehicle_package_cost as $package_cost) {
	$vehicle_cost_id = $package_cost[0];
	$vehicle_cost=getVehiclePackageCostByParentID($vehicle_cost_id);
		
		
		 ?>

<h2 style="font-size:15px;padding-top:0px;"> <?php echo date('d/m/Y',strtotime($vehicle_cost[0]['from_date'])) ?> - <?php echo date('d/m/Y',strtotime($vehicle_cost[0]['to_date'])) ?>  </h2>


<table border="1" cellpadding="10" cellspacing="10" width="100%" id="adminContentTable" class="cost_table">

<tr>
	
    <th>Vehicle</th>
    <th>2 Pax</th>
    <th>3 Pax</th>
    <th>4 Pax</th>
    <th>6 Pax</th>
    <th>9 Pax</th>
    
</tr>

<?php foreach($vehicle_cost as $package_cost) {
	
	if($package_cost['2_pax']>0 || $package_cost['3_pax']>0 || $package_cost['4_pax']>0 || $package_cost['6_pax']>0 || $package_cost['9_pax']>0)
	{
	 ?>
<tr>
	
    <td style="color:#000;" ><?php echo getVehicleTypeById($package_cost['vehicle_id']); ?></td>
     <td ><?php echo $package_cost['2_pax']; ?></td>
     <td ><?php echo $package_cost['3_pax']; ?></td>
     <td ><?php echo $package_cost['4_pax']; ?></td>
     <td ><?php echo $package_cost['6_pax']; ?></td>
     <td ><?php echo $package_cost['9_pax']; ?></td>
      
     
</tr>

        <?php } } ?>     

</table>
     
<?php }} ?> 
  </div>
  <div id="tabs-3">
  <h2 style="margin:20px;">Hotels</h2>
   <?php if($selected_hotel_array && is_array($selected_hotel_array) && count($selected_hotel_array)>0)  foreach($selected_hotel_array as $hotel_id) {
	$hotel = getHotelPackageByID($hotel_id);
	 ?>
<div class="popup-gallery col-md-4">
<?php $car_images = getCarosalImagesForHotel($hotel_id);
$i=0;
	foreach($car_images as $car_image)
	{
		
		$i++;
	$img_url = WEB_ROOT.'images/package_icons/'.$car_image['img_href'];	
 ?>

<a  <?php if($i>1) { ?> style="display:none;" <?php } ?> href="<?php echo WEB_ROOT."images/package_icons/".$car_image['img_href']; ?>" title="<?php echo $hotel['hotel_package_name'] ?> 
    <br>
    (<?php echo $hotel['location_name']; ?>)">
    <div class="image-popup-vertical-fit"  class="col-md-12" <?php if($i==1) { ?> style="margin-bottom:15px;background-image:url('<?php echo $img_url; ?>'); height:200px;  background-position: 50% 50%;
    background-repeat:   no-repeat;
    background-size:     cover; "<?php } ?> >
    
    </div>
    <h2 style="padding-top:25px;font-size:15px;padding-left:15px;"><?php echo $hotel['hotel_package_name'] ?> 
    <br>
   (<?php echo $hotel['location_name']; ?>)</h2>
    </a>
<?php

 } ?>
 
 
</div>
	

<?php } ?>
<div style="clear:both;"></div>
  </div>
  <div id="tabs-4" style="" id="inclusions_tab">
  <h2 style="font-size:15px;margin-top:20px;">Inclusions</h2>
  <div id="inclusions" style="padding:10px;padding-top:0px;"> <?php echo $package['inclusions']; ?></div>
    
    <h2 style="font-size:15px;margin-top:20px;">Exclusions</h2>
  <div id="exclusions" style="padding:10px;padding-top:0px;"> <?php echo $package['exclusions']; ?></div>
  </div>
  
  <div id="tabs-5" style="">
  <h2 style="font-size:15px;margin-top:20px;">Terms & Conditions</h2>
  <div id="terms" style="padding:10px;padding-top:0px;"> <?php echo $package['terms_and_conditions']; ?></div>
  </div>
 
</div>
<style>
	#inclusions li,#exclusions li,#terms li{
		list-style:disc;
	}
</style>
      
      
            </div>
    <div class="col-md-4 rt_panelbox ">
      <div class="sidebar_content">
        <div class="rt_inquirebox " itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                  <div class="guaranteeprice">
            <div class="detailprice">
              <div class="startprice">Starting price per adult<sup>*</sup></div>
              <div class="dealprice"  itemprop="price"> ₹ <?php echo number_format(getLowestIndividualPackageCostFromToday($package['package_id']),0); ?></div>
            </div>
            
          </div>
                    <div class="inquirebtn clr clearfix">
           <link itemprop="availability" href="http://schema.org/InStock" />
                       <a href="/departure/book/JEKV6JNE.html" title="Book Now" class="blue_btn" rel="noindex,nofollow">Enquire Now</a>
                     <br />
          
          
          

   
                    
          </div>
       
        
     
          <div class="title clr">This trip is fully customizible</div>
          <ul class="holiday_point"><li>Have a big group? We can help.</li>
            <li>We can customize the trip as per your need.</li>
            <li>We can help you make it fit your budget.</li>
          </ul>
        </div>
        
        
        <div class="rt_whitepanel clearfix">
          <div class="title clr">Here's several reasons why you should book with us:</div>
          <ul class="whybox_point">
            <li>
              <div class="floatleft"><span class="sprite check_tag"></span></div>
              <div class="content">Best Price &amp; Value</div>
            </li>
            <li class="clr">
              <div class="floatleft"><span class="sprite check_tag"></span></div>
              <div class="content">Comfortable Accommodation</div>
            </li>
            <li class="clr clearfix">
              <div class="floatleft"><span class="sprite check_tag"></span></div>
              <div class="content">Have a Big Group? We can help.</div>
            </li>
            <li class="clr clearfix">
              <div class="floatleft"><span class="sprite check_tag"></span></div>
              <div class="content">Top Notch Customer Service</div>
            </li>
            <li class="clr clearfix">
              <div class="floatleft"><span class="sprite check_tag"></span></div>
              <div class="content">Trip designed for Your Family</div>
            </li>
          </ul>
          
        </div>
        
          <div class="rt_whitepanel clearfix">
          <div class="title clr">Recommended Packages:</div>
          <ul class="">
            <?php foreach($packages as $p) { 
			if($p['package_id']!=$package_id)
			{
			?>
            <li>
              <a href="<?php echo "package.php?id=".$p['package_id']; ?>" style="color:#d52737"><?php echo $p['package_name']; ?> (<?php echo $p['days']; ?> Days)</a>
            </li>
            <?php }} ?>
          </ul>
          
        </div>
                  
      </div>
    </div>
  </div>
      
      </div>
  </div>
  <script>
   
  </script>
<?php
include_once('footer.php');
?>  
<script>
 
  
  $( "#tabs" ).tabs();

 		$('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
			}
		}
	});

  </script>
