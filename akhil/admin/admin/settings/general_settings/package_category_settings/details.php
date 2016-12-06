<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Package Category Details</h4>
<?php 
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$city=getPackageCategoryByID($_GET['lid']);
$city_id=$_GET['lid'];
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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
<table id="DetailsTable" class="insertTableStyling">

<tr>

<td width="160px" class="firstColumnStyling">
Package Category name :
</td>

<td>
<?php echo $city['pkg_cat_name']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
About Package Category :
</td>

<td>
<?php echo $city['about']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Type :
</td>

<td>
<?php if($city['type']==0) echo "Group Tour"; else if($city['type']==1) echo "Individual"; ?>
</td>
</tr>



<?php $car_images = getCarosalImagesForPackageCategory($city_id);
	foreach($car_images as $car_image)
	{
 ?>
<tr>

<td class="firstColumnStyling">
Image For Slider :
</td>

<td>
<img style="background:rgba(0,0,0,0.1);padding:10px;" src="<?php echo WEB_ROOT."images/car_images/".$car_image['img_href']; ?>" />
<br /><br />
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteCarosalImage&id='.$car_image['pkg_cat_image_id'].'&lid='.$city_id ?>"><button title="Delete this Image" class="btn delBtn"><span class="delete">X</span></button></a>
</td>
</tr>
<?php } ?>
<tr class="no_print">
<td></td>
<td >
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$city_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$city_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>


</table>    
</div>
<div class="clearfix"></div>