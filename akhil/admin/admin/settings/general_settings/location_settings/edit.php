<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Location</h4>
<?php 
$city=getLocationByID($_GET['lid']);
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="lid" value="<?php echo $city['location_id']; ?>"  />
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Parent Location<span class="requiredField">* </span> :
</td>

<td>
<select name="super_location_id" id="super_location_id">
	<option value="-1">--Please Select--</option>
    <?php $super_locations= listSuperLocations();
	foreach($super_locations as $super)
	{
	?>
	<option value="<?php echo $super['super_location_id']; ?>" <?php if($super['super_location_id']==$city['super_location_id']) { ?> selected="selected" <?php } ?>><?php echo $super['super_location_name']; ?></option>
	<?php	
		
		}
	 ?>
</select>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Location name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="location_name" value="<?php echo $city['location_name'] ?>"/>
</td>
</tr>




<tr>

<td class="firstColumnStyling">
About Location<span class="requiredField">* </span> :
</td>

<td>
<textarea  id="about" name="about"  cols="10" rows="7" ><?php echo $city['about'] ?></textarea>
</td>
</tr>




<tr>

<td class="firstColumnStyling">
Images For Slider <br />[width : 1500px | height : 500px]<span class="requiredField">* </span> :
</td>

<td>
<input type="file" id="carosal_image" name="carosal_image[]" multiple />
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>
</div>
<div class="clearfix"></div>