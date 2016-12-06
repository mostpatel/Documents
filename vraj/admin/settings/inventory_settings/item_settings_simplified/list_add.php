<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Search Item</h4>
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
<form  id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=search'; ?>" method="post">
<table id="insertItemTable" class="insertTableStyling no_print">



<tr>
<td class="firstColumnStyling">
UPC :
</td>
<td>
<input name="item_id" type="text" id="inventory_item_autocomplete" placeholder="Select Only From Avl Suggestions" <?php if(!isset($_SESSION['search_item_simplified']['item_id'])) { ?> autofocus <?php } ?> />
<input type="submit" value="Search" class="btn btn-warning" style="margin-top:-10px;">
</td>
</tr>
</table>
</form>
<?php 
if(isset($_SESSION['search_item_simplified']['item_id']))
{
$item_id= $_SESSION['search_item_simplified']['item_id'];
unset($_SESSION['search_item_simplified']['item_id']);
if(is_numeric($item_id))
$item=getInventoryItemById($item_id);
else
$item=NULL;
 ?>
<h4 class="headingAlignment no_print"><?php if(is_array($item)) { ?>Update <?php }else { ?>Add a New <?php } ?> Item</h4>
<form name="f" id="addLocForm" <?php if(is_array($item)) { ?>action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" <?php }else { ?> action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" <?php } ?> method="post">
<?php if(is_array($item)) { ?>
<input type="hidden" name="lid" value="<?php echo $item_id; ?>"/>
<?php } else { ?>
<input type="hidden" name="name" value="No Name"/>
<input type="hidden" name="alias" id="txtAlias" value=""/>
<input type="hidden" value="2" id="item_type_id" name="item_type_id" >	 
<input type="hidden" value="4" id="item_unit_id" name="item_unit_id" >	
<input type="hidden" name="supplier_id" value="<?php echo getNotAvailableSupplier(); ?>" >	
<input type="hidden" id="manufacturer_id" name="manufacturer_id" value="<?php echo getNotAvailableItemManufacturer(); ?>" >
<?php } ?>
<table id="insertItemTable" class="insertTableStyling no_print">



<tr>
<td class="firstColumnStyling">
Universal Product Code (UPC)<span class="requiredField">* </span> :
</td>

<td>
<input type="text" onkeypress="return SendTab(document.forms['f'], this, event);"  name="mfg_item_code" id="txtMfgItemCode" <?php if(isset($item['mfg_item_code'])) { ?> disabled <?php } ?> value="<?php if(isset($item['mfg_item_code'])) echo $item['mfg_item_code']; ?>"/>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
SKU<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="item_code" onkeypress="return SendTab(document.forms['f'], this, event);"  id="txtItemCode" <?php if(isset($item['item_code'])) { ?> disabled <?php } ?> value="<?php if(isset($item['item_code'])) echo $item['item_code']; ?>"/>
</td>

		


<tr>
<td>Item Shelf</td>
 <td><select id="godown" name="godown_id">
                       <option value="-1">-- Please Select --</option>
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if(isset($item['opening_godown_id'])) { if($item['opening_godown_id'] == $model['godown_id']) { ?> selected="selected" <?php } } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
</tr>  

<tr id="quantity_row">
<td class="firstColumnStyling">
Opening Quantity :
</td>

<td>
<input type="text" name="opening_quantity" id="txtOpeningQuantity" value="<?php if(isset($item['opening_quantity'])) echo $item['opening_quantity']; ?>"/>
</td>
</tr> 


<tr>
<td class="firstColumnStyling">
Expiration Date (dd/mm/yyyy) :
</td>

<td>
<input type="text" name="expiration_date" id="txtExpiryDate" class="datepicker1" placeholder="dd/mm/yyyy" value="<?php if(isset($item['expiration_date'])) echo date('d/m/Y',strtotime($item['expiration_date'])); ?>" />
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="<?php if(isset($item['mfg_item_code'])) { ?> Update <?php }else { ?>Add<?php } ?> Item" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>
<?php } ?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">

 $( "#inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_item_upc.php',
                { term: request.term }, 
                response );
            },
		autoFocus: true,
    		 selectFirst: true,
	 select: function( event, ui ) {
			$( "#inventory_item_autocomplete" ).val(ui.item.label);
			return false;
		}
    });

</script>