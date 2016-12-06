<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$attributeType=getAttributeTypeById($_GET['lid']);
$attribute_type_id=$_GET['lid'];	

$selectedSuperCatIdArray = getSuperCatIdsByAttributeTypeId($attribute_type_id);
$selectedCatIdArray = getCatIdsByAttributeTypeId($attribute_type_id);
$selectedSubCatIdArray = getSubCatIdsByAttributeTypeId($attribute_type_id);

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Attribute Type</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Attribute Type <span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $attributeType['attribute_type_id'] ?>" />
<input type="text" name="name" id="txtName" value="<?php echo $attributeType['attribute_type']; ?>"/>
</td>
</tr>

<tr>
<td> Select Super Category : </td>
<td>
<select id="bs3Select superCat" class="selectpic selectpic1 show-tick form-control" multiple data-live-search="true" name="super_cat_id[]">
       
       <?php
	                 $superCats = listSuperCategories();
						 
						 foreach($superCats as $superCat)
						 {
							 
						?>
                      <option value="<?php echo $superCat['super_cat_id'] ?>" <?php if(in_array($superCat['super_cat_id'],$selectedSuperCatIdArray)) { ?> selected="selected" <?php } ?>><?php echo $superCat['super_cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    <td>
    <input type="button" id="select_all" name="select_all" value="Select All">
    </td>
    
</tr>

<tr>
<td> Select Category : </td>
<td>
<select id="bs3Select" class="selectpic selectpic2 show-tick form-control" multiple data-live-search="true" name="cat_id[]">
       
       <?php
	                 $Cats = listCategories();
						 
						 foreach($Cats as $Cat)
						 {
							 
						?>
                      <option value="<?php echo $Cat['cat_id'] ?>" <?php if( in_array($Cat['cat_id'], $selectedCatIdArray)) { ?> selected="selected" <?php } ?>><?php echo $Cat['cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    <td>
    <input type="button" id="select_all2" name="select_all" value="Select All">
    </td>
    
</tr>

<tr>
<td> Select Sub Category : </td>
<td>
<select id="bs3Select" class="selectpic selectpic3 show-tick form-control" multiple data-live-search="true" name="sub_cat_id[]">
       
       <?php
	                 $subCats = listSubCategories();
						 
						 foreach($subCats as $subCat)
						 {
							 
						?>
                      <option value="<?php echo $subCat['sub_cat_id'] ?>"<?php if( in_array($subCat['sub_cat_id'], $selectedSubCatIdArray)) { ?> selected="selected" <?php } ?> ><?php echo $subCat['sub_cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    <td>
    <input type="button" id="select_all3" name="select_all" value="Select All">
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