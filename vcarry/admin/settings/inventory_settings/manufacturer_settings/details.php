<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$vehicleType=getItemManufacturerById($_GET['lid']);
$vehicleType_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Item Manufacturer Details</h4>
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
Manufacturer Name<span class="requiredField">* </span> :
</td>

<td>
<?php echo $vehicleType['manufacturer_name'] ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Address<span class="requiredField">* </span> :
</td>

<td>
<?php echo $vehicleType['manufacturer_address'] ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Manufacturer Contact No<span class="requiredField">* </span> :
</td>

<td>
<?php echo $vehicleType['manufacturer_contact_no'] ?>
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