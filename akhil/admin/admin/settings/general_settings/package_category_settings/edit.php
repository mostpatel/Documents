<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Package Category</h4>
<?php 
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="lid" value="<?php echo $city['pkg_cat_id']; ?>"  />
<table class="insertTableStyling no_print">




<tr>

<td class="firstColumnStyling">
Package Category name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="location_name" value="<?php echo $city['pkg_cat_name'] ?>"/>
</td>
</tr>



<tr>

<td class="firstColumnStyling">
About Package Category<span class="requiredField">* </span> :
</td>

<td>
<textarea  id="about" name="about"  cols="10" rows="7" ><?php echo $city['about'] ?></textarea>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Type<span class="requiredField">* </span> :
</td>

<td>
<select id="type" name="type">
	<option value="0" <?php if($city['type']==0) { ?> selected="selected" <?php } ?> >Group Tours</option>
    <option value="1" <?php if($city['type']==1) { ?> selected="selected" <?php } ?> >Individual</option>
</select>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Images For Slider <br />[width : 900-1000px | height : 275-300px]<span class="requiredField">* </span> :
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