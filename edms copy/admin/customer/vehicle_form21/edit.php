<?php if(!isset($_GET['id']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}

$delivery_challan_id=$_GET['id'];
$delivery_challan = getDeliveryChallanById($delivery_challan_id);

if(is_array($delivery_challan) && $delivery_challan!="error")
{
	$customer=getCustomerDetailsByCustomerId($delivery_challan['customer_id']);
	$vehicle=getVehicleById($delivery_challan['vehicle_id']);
	$sale_cert = getSaleCertByVehicleId($vehicle['vehicle_id']);
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	exit;
}


 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Make Invoice For Vehicle</h4>
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
<form id="addLocForm" onsubmit="return submitOurVehicle();" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurVehicle()">

<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<input name="delivery_challan_id" value="<?php echo $delivery_challan_id; ?>" type="hidden" />
<input name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>" type="hidden" />

<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td>Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="cert_date" class="datepicker1" name="cert_date" value="<?php echo date('d/m/Y', strtotime($sale_cert['cert_date'])); ?>" />
                            </td>
</tr>

<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Make FORM 21"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer['customer_id']; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>
