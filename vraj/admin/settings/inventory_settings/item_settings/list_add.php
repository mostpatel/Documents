<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT ?>lib/backup_rasid_excel.php"><button class="btn btn-success">Export All Items</button></a> <a href="index.php?view=upload"><button class="btn btn-warning">Upload New Item Excel</button></a>
<a href="index.php?view=uploadSkus"><button class="btn btn-warning">Update Secondary Skus</button></a>
<a href="index.php?view=updateShelfAndQty"><button class="btn btn-warning">Update Shelf, Qty, Sku, Name</button></a>
 </div>

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
	
	<?php $item_types = listItemUnits(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_unit_id']; ?>"><?php echo $item_type['unit_name']; ?></option>
	<?php } ?>
</select>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
SKU<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="item_code" id="txtItemCode"/>
</td>
</tr>

<tr id="addcontactTrCustomer">
                <td>
                Secondary SKU : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="sku[]"  /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
           Secondary SKU : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="sku[]"  />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span>
                </td>
            </td>
            </tr>
               
       
       
<!-- end for regenreation purpose -->


<tr>
<td class="firstColumnStyling">
Universal Product Code (UPC)<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="mfg_item_code" id="txtMfgItemCode"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Supplier<span class="requiredField">* </span> :
</td>

<td>
<select id="supplier_id" name="supplier_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $mfgs = listSuppliers(); foreach($mfgs as $mfg) { ?>	
    	<option value="<?php echo $mfg['ledger_id']; ?>" ><?php echo $mfg['ledger_name']; ?></option>
	<?php } ?>
</select>
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
<td>Item Shelf</td>
 <td><select id="godown" name="godown_id">
                       <option value="-1">-- Please Select --</option>
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
Expiration Date :
</td>

<td>
<input type="text" name="expiration_date" id="txtExpiryDate" class="datepicker1" />
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

<tr id="quantity_row">
<td class="firstColumnStyling">
Opening Quantity :
</td>

<td>
<input type="text" name="opening_quantity" id="txtOpeningQuantity"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Opening Rate :
</td>

<td>
<input type="text" name="opening_rate" id="txtOpeningRate"/>
</td>
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