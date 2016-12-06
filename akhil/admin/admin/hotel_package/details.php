<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$package_id=$_GET['id'];
$package=getHotelPackageByID($package_id);
if(is_array($package) && $package!="error")
{
	
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

<h4 class="headingAlignment">Hotel Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td width="90px" class="firstColumnStyling">
Location : 
</td>

<td>

                             <?php echo $package['location_name']; ?>					
                            
</td>
</tr>

<tr>

<td  class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php echo $package['hotel_package_name']; ?>					
                            
</td>
</tr>



<tr>
<td>
Stars : 
</td>

<td>

                             <?php echo $package['stars'].""; ?>					
                            
</td>
<tr>
	<td></td>
    <td><a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$package['hotel_package_id'] ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="edit">E</span></button></a> <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$location['hotel_package_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a></td>
</tr>
</tr>




<?php $car_images = getCarosalImagesForHotel($package_id);
	foreach($car_images as $car_image)
	{
 ?>
<tr>

<td class="firstColumnStyling">
Image :
</td>

<td>
<img style="background:rgba(0,0,0,0.1);padding:10px;" src="<?php echo WEB_ROOT."images/package_icons/".$car_image['img_href']; ?>" />
<br /><br />
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteCarosalImage&id='.$car_image['hotel_image_id'].'&lid='.$package_id ?>"><button title="Delete this Image" class="btn delBtn"><span class="delete">X</span></button></a>
</td>
</tr>
<?php } ?>

                  

</table>





</div>

</div>
<div class="clearfix"></div>