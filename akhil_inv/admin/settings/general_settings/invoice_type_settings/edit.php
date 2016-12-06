<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$vehicleType=getInvoiceTypeById($_GET['lid']);

$vehicleType_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Receipt Type</h4>
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
Invoice Type<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $vehicleType['invoice_type_id']; ?>"/>
<input type="text" name="name" id="txtName" value="<?php echo $vehicleType['invoice_type']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Invoice Type Print Name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="print_name" id="txtPrintName" value="<?php echo $vehicleType['invoice_type_print_name']; ?>"/>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Invoice Counter (No)<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="invoice_counter" id="txtInvoiceCounter" value="<?php echo $vehicleType['invoice_counter']; ?>"/>
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