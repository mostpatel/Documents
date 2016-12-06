<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Service</h4>
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

<table class="insertTableStyling no_print">

<tr>
<td class="firstColumnStyling">
Service Name<span class="requiredField">* </span> :
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

<tr style="display:none;">
<td class="firstColumnStyling">
Item Type<span class="requiredField">* </span> :
</td>

<td>
<select  name="item_type_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $item_types = listItemTypesNonStock(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_type_id']; ?>"><?php echo $item_type['item_type']; ?></option>
	<?php } ?>
</select>
<input type="hidden"  id="item_type_id" name="item_type_id" value="3" />
</td>
</tr>

<!-- <tr>
<td class="firstColumnStyling">
Item Unit :
</td>

<td>
<select id="item_unit_id" name="item_unit_id" >	
	<option value="-1">-- Please Select --</option>
	<?php $item_types = listItemUnits(); foreach($item_types as $item_type) { ?>	
    	<option value="<?php echo $item_type['item_unit_id']; ?>"><?php echo $item_type['unit_name']; ?></option>
	<?php } ?>
</select>
</td>
</tr> -->


<tr>
<td class="firstColumnStyling">
Our Service Code :
</td>

<td>
<input type="text" name="item_code" id="txtItemCode"/>
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
</tr> -->

<tr>
<td class="firstColumnStyling">
Fixed Price(if any) :
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
<!-- <tr>
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
<td>Opening Stock Godown</td>
 <td><select id="godown" name="godown_id">
                       
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
</tr>   -->                         

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

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Items</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Alias</th>
          
            <th class="heading">Service Code</th>
            <th class="heading">Fixed Price</th>
            <th class="heading">Tax</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$items=listNonStockItems();
		$no=0;
		foreach($items as $item)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $item['item_name']; ?>
            </td>
             <td><?php if(validateForNull($item['alias'])) echo $item['alias']; else echo "NA"; ?>
            </td>
            
             <td><?php echo $item['item_code']; ?>
            </td>
           
             <td><?php echo $item['mrp']; ?>
            </td>
            <td><?php if(is_numeric($item['tax_group_id'])) $tax_group = getTaxGroupByID($item['tax_group_id']);  echo  $tax_group['tax_group_name']; ?>
			</td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$item['item_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$item['item_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$item['item_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>