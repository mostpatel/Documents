<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$subCategory=getsubCategoryById($_GET['lid']);
$sub_cat_id=$_GET['lid'];
$cat_ids = getCatIdsBySubCategoryId($sub_cat_id);

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Sub Category</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Sub Category<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $subCategory['sub_cat_id'] ?>" />
<input type="text" name="name" id="txtName" value="<?php echo $subCategory['sub_cat_name']; ?>"/>
</td>
</tr>


<tr>
<td>Image [x*y Dimentions]<span class="requiredField">* </span> : </td>
				<td>
					<input type="file" id="subCat_img" name="subCat_img"/>
                            </td>
</tr>

<tr>
<td width="200px">
Category<span class="requiredField">* </span> : 
</td>
<td>

<select type="text" name="cat_id[]" multiple id="deparment_id" style="min-height:250px;">
	<option value="-1">-- Please Select --</option>
    <?php $categories=listCategories();
	foreach($categories as $category)
	{
	?>
    <option value="<?php echo $category['cat_id'] ?>" <?php if(in_array($category['cat_id'],$cat_ids)) { ?> selected="selected" <?php } ?>> <?php echo $category['cat_name']; ?></option>
    <?php 	
		
	}
	 ?>
</select> 
</td>
</tr>

<tr>
<td>Image [500x400 Px]<span class="requiredField">* </span> : </td>
				<td>
					<input type="file" id="subCat_img" name="subCat_img"/>
                            </td>
</tr>

<!-- <tr>
<td>Specification Sheet [Excel] : </td>
				<td>
					<input type="file" id="spec_sheet" name="spec_sheet"/>
                            </td>
</tr> -->

<tr>

<td class="firstColumnStyling">
Website Link :
</td>

<td>
<input type="text" name="youtube_link" id="youtube_link" value="<?php echo $subCategory['youtube_link']; ?>"/>
</td>
</tr>


<tr>
<td width="220px" class="firstColumnStyling"> 
Description : 
</td>

<td>
<textarea id="sub_cat_description" class="sub_cat_description" name="sub_cat_description"  cols="5" rows="6">
<?php echo $subCategory['sub_cat_description']; ?>
</textarea>
</td>
</tr>

 
<tr>
<td></td>
<td>
<input type="submit" class="btn btn-warning" value="Save"/>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>