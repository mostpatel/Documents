<?php $units = listItemUnits(); ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Unit Relation</h4>
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
1 <select name="unit_id" id="unit_id">
	<option value="-1">-- Please Select --</option>
    <?php foreach($units as $unit) { ?>
    	<option value="<?php echo $unit['item_unit_id'] ?>"><?php echo $unit['unit_name']; ?></option>
    <?php } ?>
    
</select> 
</td>

<td> =
<input type="text" name="rel" id="txtRel"/>
<select name="base_unit_id" id="base_unit_id" onchange="createDropDownItemItemUnit('getItemsFromUnit.php?id='+this.value,'items');">
	<option value="-1">-- Please Select --</option>
    <?php foreach($units as $unit) { ?>
    	<option value="<?php echo $unit['item_unit_id'] ?>"><?php echo $unit['unit_name']; ?></option>
    <?php } ?>
    
</select> 
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Items<span class="requiredField">* </span> :
</td>

<td>
<select id="items" name="items[]" multiple="multiple" class="selectpicker">	
	<option value="-1">-- Please Select --</option>
</select>
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