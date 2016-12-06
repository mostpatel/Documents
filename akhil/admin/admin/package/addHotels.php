<?php 
$package_id = $_GET['id'];
if (!checkForNumeric($package_id))
{
	exit;
}
$package_location = getLocationIDsForPackageId($package_id);
$package_location_string = implode(",",$package_location);

$hotel_packages = listHotelPackagesForLocationIdString($package_location_string);

$selected_hotel_array=getHotelIDsForPackageId($package_id);

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add Hotels To Package</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addHotels'; ?>" method="post">

<input type="hidden" name="package_id" value="<?php echo $package_id; ?>" />

<table class="insertTableStyling no_print">



<tr>
<td> Select Hotels <span class="requiredField">* </span>: </td>
				<td>
					<select id="bs3Select" name="hotel_package_id[]" data-live-search="true" class="city_area selectpicker" multiple="multiple" >
                       
                        <?php
                            
                            foreach($hotel_packages as $customerGroup)
							
                              {
								 
                             ?>
                             
                             <option value="<?php echo $customerGroup['hotel_package_id'] ?>" <?php if(in_array($customerGroup['hotel_package_id'], $selected_hotel_array)) { ?> selected="selected" <?php } ?>> <?php echo $customerGroup['hotel_package_name'] ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/package/index.php?view=details&id=".$package_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>