<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$vehicleType=getInventoryItemById($_GET['lid']);
$item_id = $_GET['lid'];
$todays = getTodaysDate();
$to=getNextDate($todays); // return Y-m-d
$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
	
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
$vehicleType_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Item Type Details</h4>
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

<table id="DetailsTable" class="insertTableStyling">

<tr>

<td class="firstColumnStyling">
Item Name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $vehicleType['item_id']; ?>"/>
<?php echo $vehicleType['item_name']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Alias Name / Our Name :
</td>

<td>
<?php echo $vehicleType['alias']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Item Type :
</td>

<td>
	<?php $item_types = listItemTypes(); foreach($item_types as $item_type) { ?>	
    	<?php if($item_type['item_type_id']==$vehicleType['item_type_id']){ ?> <?php echo $item_type['item_type']; ?> <?php } ?>
	<?php } ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Item Unit :
</td>

<td>

	<?php $item_types = listItemUnits(); foreach($item_types as $item_type) { ?>	
    	<?php if($item_type['item_unit_id']==$vehicleType['item_unit_id']){ ?> <?php echo $item_type['unit_name']; ?> <?php } ?>
	<?php } ?>

</td>
</tr>


<tr>
<td class="firstColumnStyling">
SKU :
</td>

<td>
<?php echo $vehicleType['item_code']; ?>
</td>
</tr>



<tr>
<td class="firstColumnStyling">
UPC :
</td>

<td>
<?php echo $vehicleType['mfg_item_code']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Manufacturer :
</td>

<td>

	<?php $mfgs = listItemManufacturers(); foreach($mfgs as $mfg) { ?>	
    	 <?php if(is_numeric($vehicleType['manufacturer_id']) && $mfg['manufacturer_id']==$vehicleType['manufacturer_id']){ ?> <?php echo $mfg['manufacturer_name']; ?> <?php }  ?>
	<?php } if(!is_numeric($vehicleType['manufacturer_id'])) echo "NA"; ?>

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Shelf :
</td>

<td>
<?php echo $vehicleType['godown_name']; ?> 
</td>
</tr>



<tr>
<td class="firstColumnStyling">
Dealer Price / Purchase Price :
</td>

<td>
<?php echo $vehicleType['dealer_price']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
MRP / Sale Price :
</td>

<td>
<?php echo $vehicleType['mrp']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Opening Quantity :
</td>

<td>
<?php echo $vehicleType['opening_quantity']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Opening Rate :
</td>

<td>
<?php echo $vehicleType['opening_rate']; ?> 
</td>
</tr>



<tr>
<td class="firstColumnStyling">
Remarks :
</td>
<td>
<?php echo $vehicleType['remarks']; ?>
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


<tr class="no_print">
<td></td>
<td>
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$vehicleType_id; ?>"><span class="delete btn editBtn">E</span></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$vehicleType_id; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</div>
<div class="clearfix"></div>