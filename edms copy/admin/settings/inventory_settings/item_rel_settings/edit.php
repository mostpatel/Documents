<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$item_id =$_GET['lid'];	
$vehicleType=getInventoryItemById($_GET['lid']);
$todays = getTodaysDate();
$to=getNextDate($todays); // return Y-m-d
$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
$vehicleType_id=$_GET['lid'];	

if(USE_BARCODE==1 && $vehicleType['use_barcode']==1)
$barcode_array=getBarcodesFromTransIdAndTransType($item_id,7);

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Item</h4>
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

<table id="insertItemTable" class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Item Name<span class="requiredField">* </span> :
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

<tr>
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
</td>
</tr>

<tr>
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
</tr>


<tr>
<td class="firstColumnStyling">
Our Item Code :
</td>

<td>
<input type="text" name="item_code" id="txtItemCode" value="<?php echo $vehicleType['item_code']; ?>"/>
</td>
</tr>

<tr>
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
</tr>

<tr>
<td class="firstColumnStyling">
MRP / Sale Price :
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
<?php if(USE_BARCODE==1) { ?>
<tr>
<td class="firstColumnStyling">
Use Barcode :
</td>

<td>
<table>
               <tr><td><input type="radio" <?php if($vehicleType['use_barcode']==1) { ?>  disabled="disabled" <?php } ?> name="use_barcode" id="use_barcode_no"   value="0" <?php if($vehicleType['use_barcode']==0) { ?> checked="checked" <?php } ?>></td><td><label for="use_barcode_no">No</label></td></tr>
            <tr><td><input type="radio"  <?php if($vehicleType['use_barcode']==1) { ?>  disabled="disabled" <?php } ?> id="use_barcode_yes" name="use_barcode"   value="1" <?php if($vehicleType['use_barcode']==1) { ?> checked="checked" <?php } ?> ></td><td><label for="use_barcode_yes">Yes</label></td>
               </tr> 
            </table>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Barcode Genration:
</td>

<td>
<table>
               <tr><td><input type="radio" name="gen_barcode" <?php if($vehicleType['use_barcode']==1) { ?>  disabled="disabled" <?php } ?>  id="gen_barcode_no"  value="0" checked="checked" <?php if(USE_BARCODE==1) { ?> onchange="toggleQuantityBarcode(this.value);" <?php } ?>></td><td><label for="gen_barcode_no">Automatic</label></td></tr>
            <tr><td><input type="radio"  id="gen_barcode_yes" name="gen_barcode" <?php if($vehicleType['use_barcode']==1) { ?>  disabled="disabled" <?php } ?>   value="1" onchange="toggleQuantityBarcode(this.value);" ></td><td><label for="gen_barcode_yes">Manual</label></td>
               </tr> 
            </table>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Barcode Prefix :
</td>

<td>
<input type="text" name="barcode_prefix" id="barcode_prefix" disabled="disabled" value="<?php echo $vehicleType['barcode_prefix']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Barcode Counter :
</td>

<td>
<input type="text" name="barcode_counter" id="barcode_counter"  disabled="disabled" value="<?php echo $vehicleType['barcode_counter']; ?>" />
</td>
</tr>
<?php  } ?>
<tr id="quantity_row" <?php if($vehicleType['use_barcode']==1 && $vehicleType['gen_barcode']==1) { ?> style="display:none;" <?php } ?>>
<td class="firstColumnStyling">
Opening Quantity :
</td>

<td>
<input type="text" name="opening_quantity" id="txtOpeningQuantity" value="<?php echo $vehicleType['opening_quantity']; ?>" />
</td>
</tr>
<?php if(USE_BARCODE==1 && $vehicleType['gen_barcode']==1) {
	?>
    <tr id="addcontactTrCustomer" <?php if($vehicleType['use_barcode']==0) { ?> style="display:none;" <?php } ?> >
                <td>
                Barcode<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="item_barcode" name="item_barcode[]"   /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Barcode : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="item_barcode[]"  />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span>
                </td>
            </td>
            </tr>
    <?php
	if(is_array($barcode_array) && count($barcode_array)>0) 
	{
		foreach($barcode_array as $barcode)
		{
	 ?>
       <tr >
            <td>
            Barcode : 
            </td>
            
            <td >
            <input type="text" class="contact" name="item_barcode[]" value="<?php echo $barcode[0];  ?>" <?php if(checkIfBarcodeInUse($barcode[0])>1) { ?> disabled="disabled" title="Item Used In Transaction!" <?php } ?> />  
                </td>
            </td>
            </tr>
<?php }} ?>

               
       
       
<!-- end for regenreation purpose -->
<?php } ?>
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
</tr>                            


<tr>
<td class="firstColumnStyling">
Remarks :
</td>
<td>
<textarea name="remarks" id="txtRemarks"><?php echo $vehicleType['remarks']; ?></textarea>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Quantity As On <?php echo date('d/m/Y',strtotime($todays));  ?>:
</td>

<td>
<?php echo $opening_balance_quantity; ?>
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