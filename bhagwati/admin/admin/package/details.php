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
<div class="detailStyling">

<h4 class="headingAlignment">Package's Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td width="90px" class="firstColumnStyling">
Location : 
</td>

<td>

                             <?php echo $package_location[0]['location_name']; ?>					
                            
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
Places : 
</td>

<td>

                             <?php echo $package['places'].""; ?>					
                            
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

                             <?php if($package['currency']==1) echo "INR"; else if($package['currency']==2) echo "$"; ?>					
                            
</td>
</tr>


                  

</table>

<h4 class="headingAlignment">Tariff Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<?php if($package_types && is_array($package_types) && count($package_types)>0)  foreach($package_types as $pte) { ?>

<tr>
	<td><?php echo $pte['package_type'] ?> : </td>
    <td><?php echo $pte['price']." Rs"; ?></td>
</tr>

<?php }
else
{
 ?>
<tr>
	<td>Price : </td>
    <td><?php echo " On Request"; ?></td>
</tr>                  
<?php } ?>
</table>


<h4 class="headingAlignment">Itenary Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<?php $i=1; foreach($package_itenary as $ite) { ?>
<tr>
	<td colspan="2">Day <?php echo $i++; ?></td>
    
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

</div>
<div class="clearfix"></div>