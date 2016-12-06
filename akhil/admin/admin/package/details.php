<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$package_id=$_GET['id'];
$package=getPackageByID($package_id);
if(is_array($package) && $package!="error")
{
	$package_types=getPackageTypeForPackage($package_id);
	$package_itenary=getItenaryForPackageId($package_id);
	$package_location = getLocationForPackage($package_id);
	$package_category = getPackageCategoryForPackage($package_id);
	$package_dates = getTourDatesForPackageId($package_id);
	$ind_package_cost = getIndividualPackageCostFromToday($package_id);
	$vehicle_package_cost = getVehiclePackageCostIdFromToday($package_id);
	$selected_hotel_array=getHotelIDsForPackageId($package_id);

	$package_dates_string = "";
	for($w=0;$w<count($package_dates);$w++) { $package_dates_string = $package_dates_string."'".date('d/m/Y',strtotime($package_dates[$w]))."'"; if($w!=(count($package_dates)-1)) $package_dates_string=$package_dates_string.","; } 
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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
<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/package/index.php?view=addIndCost&id=<?php echo $package_id; ?>"><button class="btn btn-warning">Add Individual Pkg Cost</button></a>
<a href="<?php echo WEB_ROOT; ?>admin/package/index.php?view=addVehicleCost&id=<?php echo $package_id; ?>"><button class="btn btn-warning">Add Vehicle Pkg Cost</button></a>
<a href="<?php echo WEB_ROOT; ?>admin/package/index.php?view=updateHotels&id=<?php echo $package_id; ?>"><button class="btn btn-warning">Add / Update Hotels</button></a>
  </div>
<div class="detailStyling">

<h4 class="headingAlignment">Package's Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">

<tr>
<td>
Image : 
</td>

<td>

                             <img style="width:70%;" src="<?php echo WEB_ROOT."images/package_icons/".$package['thumb_href']; ?>" alt="">		
                            
</td>
</tr>

<tr>

<td width="90px" class="firstColumnStyling">
Location : 
</td>

<td>

                             <?php foreach($package_location as $location) echo $location['location_name']." | "; ?>					
                            
</td>
</tr>

<tr>

<td width="90px" class="firstColumnStyling">
Package Category : 
</td>

<td>

                             <?php foreach($package_category as $location) echo $location['pkg_cat_name']." "; ?>					
                            
</td>
</tr>

<tr>

<td  class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php echo $package['package_name']; ?>					
                            
</td>
</tr>

<tr>
<td>
Tour Code : 
</td>

<td>

                             <?php echo $package['tour_code'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Departure Location : 
</td>

<td>

                             <?php echo $package['from_location'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Arrival Location : 
</td>

<td>

                             <?php echo $package['to_location'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Places : 
</td>

<td>

                             <?php echo $package['places'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Days : 
</td>

<td>

                             <?php echo $package['days']." Days"; ?>					
                            
</td>
</tr>

<tr>
<td>
Nights : 
</td>

<td>

                             <?php echo $package['nights']." Nights"; ?>					
                            
</td>
</tr>




<tr>
<td>
Inclusions : 
</td>

<td>

                             <?php echo $package['inclusions']; ?>					
                            
</td>
</tr>


<tr>
<td>
Exclusions : 
</td>

<td>

                             <?php echo $package['exclusions'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Currency : 
</td>

<td>

                             <?php if($package['currency']==1) echo "INR"; else if($package['currency']==2) echo "$"; else echo "INR"; ?>					
                            
</td>
</tr>

<tr>
<td>
Individual Cost Heading : 
</td>

<td>

                             <?php echo $package['ind_cost_heading'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Vehicle Cost Heading : 
</td>

<td>

                             <?php echo $package['vehicle_cost_heading'].""; ?>					
                            
</td>
</tr>

</tr>


<tr>
 <td class="no_print"> 
            </td>
             <td class="no_print">
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$package_id; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="edit">E</span></button></a>
              <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$package_id; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
           
      <tr>            

</table>

<h4 class="headingAlignment">Hotel Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<?php if($selected_hotel_array && is_array($selected_hotel_array) && count($selected_hotel_array)>0)  foreach($selected_hotel_array as $hotel_id) {
	$hotel = getHotelPackageByID($hotel_id);
	 ?>

<tr>
	<td><?php echo $hotel['hotel_package_name'] ?> : </td>
    <td><?php echo $hotel['location_name']; ?></td>
</tr>

<?php }
else
{
 ?>
<tr>
	<td colspan="2">Hotels Not Added </td>
    
</tr>                  
<?php } ?>
</table>


</div>
<div class="detailStyling">
<h4 class="headingAlignment">Itenary Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<?php $i=1; foreach($package_itenary as $ite) { ?>
<tr>
	<td colspan="2" style="color:#890507">Section <?php echo $i++; ?></td>
    
</tr>
<tr>
	<td>Heading : </td>
    <td><?php echo $ite['itenary_heading']; ?></td>
</tr>
<tr>
	<td width="120px">Description :</td>
    <td><?php echo $ite['itenary_description']; ?></td>
</tr>
<?php } ?>
                  

</table>
</div>

<div style="clear:both;width:100%;" class="detailStyling" >
<h4 class="headingAlignment">Individual Cost</h4>


<table border="1" cellpadding="10" cellspacing="10" width="100%" id="adminContentTable" class="adminContentReport">

<tr>
	<th >From</th>
    <th>To</th>
    <th>Full Ticket</th>
     <th>Extra Person</th>
      <th>Half ticket with Seat</th>
       <th>Half ticket without Seat</th>
        <th>Per Couple</th>
        <th></th>
</tr>

<?php $i=1; foreach($ind_package_cost as $package_cost) {
	
	 ?>
<tr>
	<td ><?php echo date('d/m/Y',strtotime($package_cost['from_date'])); ?></td>
    <td ><?php echo date('d/m/Y',strtotime($package_cost['to_date'])); ?></td>
    <td ><?php echo $package_cost['full_ticket']; ?></td>
     <td ><?php echo $package_cost['extra_person']; ?></td>
      <td ><?php echo $package_cost['half_ticket_with_seat']; ?></td>
     <td ><?php echo $package_cost['half_ticket_without_seat']; ?></td>
      <td ><?php echo $package_cost['per_couple']; ?></td>
      <td><a href="<?php echo $_SERVER['PHP_SELF'].'?view=editIndCost&id='.$package_cost['ind_cost_id']; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="edit">E</span></button></a></td>

</tr>
<?php } ?>
                  

</table>
</div>

<?php $i=1; foreach($vehicle_package_cost as $package_cost) {
	$vehicle_cost_id = $package_cost[0];
	$vehicle_cost=getVehiclePackageCostByParentID($vehicle_cost_id);
		
		
		 ?>
<div style="clear:both;width:100%;" class="detailStyling" >
<h4 class="headingAlignment">Extra Vehicle Cost ( <?php echo date('d/m/Y',strtotime($vehicle_cost[0]['from_date'])) ?> - <?php echo date('d/m/Y',strtotime($vehicle_cost[0]['to_date'])) ?> ) </h4>


<table border="1" cellpadding="10" cellspacing="10" width="100%" id="adminContentTable" class="adminContentReport">

<tr>
	
    <th>Vehicle</th>
    <th>2 Pax</th>
    <th>3 Pax</th>
    <th>4 Pax</th>
    <th>6 Pax</th>
    <th>9 Pax</th>
    <th></th>
</tr>

<?php foreach($vehicle_cost as $package_cost) { ?>
<tr>
	
    <td ><?php echo getVehicleTypeById($package_cost['vehicle_id']); ?></td>
     <td ><?php echo $package_cost['2_pax']; ?></td>
     <td ><?php echo $package_cost['3_pax']; ?></td>
     <td ><?php echo $package_cost['4_pax']; ?></td>
     <td ><?php echo $package_cost['6_pax']; ?></td>
     <td ><?php echo $package_cost['9_pax']; ?></td>
      
      <td><a href="<?php echo $_SERVER['PHP_SELF'].'?view=editVehicleCost&id='.$vehicle_cost_id; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="edit">E</span></button></a></td>

</tr>

  <?php } ?>                

</table>
</div>
<?php } ?>
<div style="clear:both;" class="detailStyling">
<hr class="firstTableFinishing" />
<h4 class="headingAlignment">Tour Dates</h4>
<table>

<tr>



<div  id="multiyear2"     ></div>

</td>

</tr>


</table>




</div>

</div>
<div class="clearfix"></div>
<script type="text/javascript">
document.package_dates = [<?php echo $package_dates_string; ?>];

</script>
<style>
.adminContentReport tr td{
	padding:7px;
	
	}
.adminContentReport tr th
{
	padding:10px;
	background:#efefef;
	color:#EE6F1F;
	}	
</style>