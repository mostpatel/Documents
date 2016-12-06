<div class="addDetailsBtnStyling no_print"> <a href="index.php?view=all"><button class="btn btn-success">View All Items</button></a> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Item</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">

<table id="insertItemTable" class="insertTableStyling no_print">

<tr>
<td class="firstColumnStyling">
Item Name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Alias Name / Our Name :
</td>

<td>
<input type="text" name="alias" id="txtAlias"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Item Type<span class="requiredField">* </span> :
</td>

<td>
<select id="item_type_id" name="item_type_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $item_types = listItemTypesInventory(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_type_id']; ?>"><?php echo $item_type['item_type']; ?></option>
	<?php } ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Item Unit<span class="requiredField">* </span> :
</td>

<td>
<select id="item_unit_id" name="item_unit_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $item_types = listItemUnits(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_unit_id']; ?>"><?php echo $item_type['unit_name']; ?></option>
	<?php } ?>
</select>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Our Item Code :
</td>

<td>
<input type="text" name="item_code" id="txtItemCode"/>
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
    	<option value="<?php echo $mfg['manufacturer_id']; ?>"><?php echo $mfg['manufacturer_name']; ?></option>
	<?php } ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Manufacturer Item Code :
</td>

<td>
<input type="text" name="mfg_item_code" id="txtMfgItemCode"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Minimum Order Quantity (MOQ) :
</td>

<td>
<input type="text" name="min_quantity" id="txtMinQuantity"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Dealer Price / Purchase Price :
</td>

<td>
<input type="text" name="dealer_price" id="txtDealerPrice"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
MRP / Sale Price :
</td>

<td>
<input type="text" name="mrp" id="txtMrp"/>
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
                                 <option value="<?php echo $model['tax_group_id'] ?>" ><?php echo $model['tax_group_name']; ?></option>
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
               <tr><td><input type="radio"   name="use_barcode" id="use_barcode_no"  value="0" checked="checked"></td><td><label for="use_barcode_no">No</label></td></tr>
            <tr><td><input type="radio"  id="use_barcode_yes" name="use_barcode"  value="1" ></td><td><label for="use_barcode_yes">Yes</label></td>
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
               <tr><td><input type="radio" name="gen_barcode" id="gen_barcode_no"  value="0" checked="checked" <?php if(USE_BARCODE==1) { ?> onchange="toggleQuantityBarcode(this.value);" <?php } ?>></td><td><label for="gen_barcode_no">Automatic</label></td></tr>
            <tr><td><input type="radio"  id="gen_barcode_yes" name="gen_barcode"  value="1" onchange="toggleQuantityBarcode(this.value);" ></td><td><label for="gen_barcode_yes">Manual</label></td>
               </tr> 
            </table>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Barcode Prefix :
</td>

<td>
<input type="text" name="barcode_prefix" id="barcode_prefix"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Barcode Counter :
</td>

<td>
<input type="text" name="barcode_counter" id="barcode_counter" value="<?php if(defined('BARCODE_COUNTER')) echo BARCODE_COUNTER; ?>" />
</td>
</tr>
<?php } ?>
<tr id="quantity_row">
<td class="firstColumnStyling">
Opening Quantity :
</td>

<td>
<input type="text" name="opening_quantity" id="txtOpeningQuantity"/>
</td>
</tr>
<?php if(USE_BARCODE==1) { ?>
<tr id="addcontactTrCustomer" style="display:none;">
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
               
       
       
<!-- end for regenreation purpose -->
<?php } ?>
<tr>
<td class="firstColumnStyling">
Opening Rate :
</td>

<td>
<input type="text" name="opening_rate" id="txtOpeningRate"/>
</td>
</tr>
<tr>
<td>Opening Stock Godown</td>
 <td><select id="godown" name="godown_id">
                       
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
</tr>   

<tr>
<td class="firstColumnStyling">
Remarks :
</td>
<td>
<textarea name="remarks" id="txtRemarks"></textarea>
</td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add Item" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>
</div>
<div class="clearfix"></div>