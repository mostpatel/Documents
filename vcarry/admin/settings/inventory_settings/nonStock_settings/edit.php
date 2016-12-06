<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$vehicleType=getInventoryItemById($_GET['lid']);
$vehicleType_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Service</h4>
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
Service Name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $vehicleType['item_id']; ?>"/>
<input type="text" name="name" id="txtName" value="<?php echo $vehicleType['item_name']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Alias Name / Our Name :
</td>

<td>
<input type="text" name="alias" id="txtAlias" value="<?php echo $vehicleType['alias']; ?>" />
</td>
</tr>

<tr style="display:none">
<td class="firstColumnStyling">
Item Type :
</td>

<td>
<select id="item_type_id" name="item_type_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $item_types = listItemTypes(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_type_id']; ?>" <?php if($item_type['item_type_id']==$vehicleType['item_type_id']){ ?> selected="selected" <?php } ?>><?php echo $item_type['item_type']; ?></option>
	<?php } ?>
</select>
<input type="hidden"  id="item_type_id" name="item_type_id" value="3" />
</td>
</tr>

<!--<tr>
<td class="firstColumnStyling">
Item Unit :
</td>

<td>
<select id="item_unit_id" name="item_unit_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $item_types = listItemUnits(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_unit_id']; ?>" <?php if($item_type['item_unit_id']==$vehicleType['item_unit_id']){ ?> selected="selected" <?php } ?>><?php echo $item_type['unit_name']; ?></option>
	<?php } ?>
</select>
</td>
</tr> -->


<tr>
<td class="firstColumnStyling">
Our Service Code :
</td>

<td>
<input type="text" name="item_code" id="txtItemCode" value="<?php echo $vehicleType['item_code']; ?>"/>
</td>
</tr>

<!-- <tr>
<td class="firstColumnStyling">
Manufacturer :
</td>

<td>
<select id="manufacturer_id" name="manufacturer_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $mfgs = listItemManufacturers(); foreach($mfgs as $mfg) { ?>	
    	<option value="<?php echo $mfg['manufacturer_id']; ?>" <?php if($mfg['manufacturer_id']==$vehicleType['manufacturer_id']){ ?> selected="selected" <?php } ?>><?php echo $mfg['manufacturer_name']; ?></option>
	<?php } ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Manufacturer Item Code :
</td>

<td>
<input type="text" name="mfg_item_code" id="txtMfgItemCode" value="<?php echo $vehicleType['mfg_item_code']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Minimum Order Quantity (MOQ) :
</td>

<td>
<input type="text" name="min_quantity" id="txtMinQuantity" value="<?php echo $vehicleType['min_quantity_purchase']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Dealer Price / Purchase Price :
</td>

<td>
<input type="text" name="dealer_price" id="txtDealerPrice" value="<?php echo $vehicleType['dealer_price']; ?>"/>
</td>
</tr> -->

<tr>
<td class="firstColumnStyling">
Fixed Price(if any) :
</td>

<td>
<input type="text" name="mrp" id="txtMrp" value="<?php echo $vehicleType['mrp']; ?>"/>
</td>
</tr>
<tr>
<td class="firstColumnStyling">
Tax Group :
</td>
 <td><select class="tax_group" name="tax_group_id" >
                        <option value="-1" >--Select Tax--</option>
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" <?php if($model['tax_group_id']==$vehicleType['tax_group_id']){ ?> selected="selected" <?php } ?>><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
</tr>
<!-- <tr>
<td class="firstColumnStyling">
Opening Quantity :
</td>

<td>
<input type="text" name="opening_quantity" id="txtOpeningQuantity" value="<?php echo $vehicleType['opening_quantity']; ?>" />
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Opening Rate :
</td>

<td>
<input type="text" name="opening_rate" id="txtOpeningRate" value="<?php echo $vehicleType['opening_rate']; ?>" />
</td>
</tr>
<tr>
<td>Opening Stock Godown</td>
 <td><select id="godown" name="godown_id">
                       
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if($model['godown_id']==$vehicleType['opening_godown_id']){ ?> selected="selected" <?php } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
</tr>  -->                          

<tr>

<tr>
<td class="firstColumnStyling">
Remarks :
</td>
<td>
<textarea name="remarks" id="txtRemarks"><?php echo $vehicleType['remarks']; ?></textarea>
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