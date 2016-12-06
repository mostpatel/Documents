<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$purchase_id=$_GET['id'];
$purchase_vehicle=getVehiclePurchaseById($purchase_id);

$purchase = $purchase_vehicle[0];
$vehicles = $purchase_vehicle[1];
$purchase_tax = $purchase_vehicle[2];
$all_purchase_jv_ledger_ids = listPurchaseJvLedgerIds();

if($purchase=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$customer_id=$purchase['from_customer_id'];

if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	
}
$ledger_id=$purchase['from_ledger_id'];
$by_account_id=$purchase['to_ledger_id'];

if(validateForNUll($ledger_id) && is_numeric($ledger_id))
$from_ledger=getLedgerById($ledger_id);

$by_account=getLedgerById($by_account_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Purchase Details </h4>
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
<table id="rasidTable" class="detailStylingTable insertTableStyling">

<tr class="no_print">
<td width="220px">Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($purchase['trans_date'])); ?>
                            </td>
</tr>

<tr>
<td> By Account : </td>
				<td>
					<?php echo $by_account['ledger_name']; ?>
                            </td>
</tr>
<?php if(validateForNull($ledger_id) && checkForNumeric($ledger_id)) { ?>
<tr>
<td> To Ledger : </td>
				<td>
					<?php echo $from_ledger['ledger_name']; ?>
                            </td>
</tr>
<?php } else if(validateForNull($customer_id) && checkForNumeric($customer_id)) { ?>
<tr>
<td> To Ledger : </td>
				<td>
					<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><?php echo $customer['customer_name']." ".$file_no." ".$reg_no; ?></a>
                            </td>
</tr>
<?php } ?>                          
</table>
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Vehicle Details</h4>

<table id="insertVehicleTable" class="detailStylingTable insertTableStyling">
<?php $i=1; foreach($vehicles as $vehicle) {
	$purchase_jvs = getPurchaseJvForVehicleId($vehicle['vehicle_id']);
	
	 ?>
<tbody >

<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Vehicle <?php echo $i++; ?></span>
</td>


</tr>


<tr>
<td>Vehicle Model : </td>
				<td>
					<?php echo $vehicle['model_name']; ?>
                            </td>
</tr>


 <tr>
<td>Vehicle Model Year : </td>
				<td>
					<?php echo $vehicle['vehicle_model']; ?>
                            </td>
</tr>

<tr>
<td>Vehicle Color : </td>
				<td>
					<?php echo $vehicle['vehicle_color']; ?>
                            </td>
</tr>

<tr>
<td>Godown : </td>
				<td>
					<?php echo $vehicle['godown_name']; ?>
                            </td>
</tr>

<tr>
       <td>Vehicle Condition :</td>
           
           
       
        <td>
					<?php if($vehicle['vehicle_condition']==1){ ?> NEW <?php } 
                       else if($vehicle['vehicle_condition']==0){ ?> OLD <?php } ?>
                            </select> 
                            </td>
 </tr>
 
<tr >
<td class="firstColumnStyling">
Reg Number : 
</td>

<td>
<?php echo $vehicle['vehicle_reg_no']; ?>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<?php echo $vehicle['vehicle_engine_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<?php echo $vehicle['vehicle_chasis_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Service Book Number : 
</td>

<td>
<?php echo $vehicle['service_book']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Cylinder Number : 
</td>

<td>
<?php echo $vehicle['cng_cylinder_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number : 
</td>

<td>
<?php echo $vehicle['cng_kit_no']; ?></td>
</tr>

<tr>
<td width="220px">Basic Price : </td>
				<td>
					<?php echo "Rs. ".number_format($vehicle['basic_price'],2); ?>
                            </td>
</tr>

<tr>
<td>Tax Group : </td>
				<td>
					<?php $vehicle_tax = getTaxForVehicleId($vehicle['vehicle_id']);  echo $vehicle_tax['tax_group_name']; ?>
                            </td>
</tr>

<tr>
<td>Tax Amount : </td>
				<td>
					<?php  echo "Rs. ".number_format($vehicle_tax['tax_amount'],2); ?>
                            </td>
</tr>
<?php if(is_array($purchase_jvs) && count($purchase_jvs)>0) { 

foreach($purchase_jvs as $purchase_jv)
{
	
	$from_ledger_id = $purchase_jv['from_ledger_id'];
	$to_ledger_id = $purchase_jv['to_ledger_id'];
	if(in_array($from_ledger_id,$all_purchase_jv_ledger_ids))
	$purchase_jv_ledger_id = $from_ledger_id;
	else
	$purchase_jv_ledger_id = $to_ledger_id;
?>	
<tr>
	<td><?php echo getLedgerNameFromLedgerId($purchase_jv_ledger_id); ?> :</td>
    <td>Rs. <?php echo number_format($purchase_jv['amount'],2); ?></td>
</tr>
<?php	
}}
?>

</tbody>
<?php } ?>
</table>
<hr class="firstTableFinishing" />
<table class="detailStylingTable insertTableStyling">
<tr>

<td width="220px"> Amount : </td>
				<td>
					<?php echo "Rs. ".number_format($purchase['amount'],2)." /- "; ?>
                    </td>
</tr>

<?php $total_tax = 0; foreach($purchase_tax as $vh_tax) { $total_tax=$total_tax+$vh_tax['tax_amount'];  ?>
<tr>

<td width="220px"><?php echo $vh_tax['tax_name_in_out']; ?> : </td>
				<td>
					<?php echo "Rs. ".number_format($vh_tax['tax_amount'],2)." /- "; ?>
                    </td>
</tr>
<?php } $net_amount = $purchase['amount'] + $total_tax; ?>

<tr>

<td width="220px"> Amount : </td>
				<td>
					<?php echo "Rs. ".number_format($net_amount,2)." /- "; ?>
                    </td>
</tr>


<tr>
<td width="220px"> Remarks : </td>
				<td>
					<?php if(validateForNull($purchase['remarks'])) echo $purchase['remarks']; else echo "NA"; ?>
                    </td>
</tr>



</table>

<table class="no_print">
<tr>
<td width="250px;"></td>
<td>
 <a href="<?php echo 'index.php?view=edit&lid='.$purchase_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="edit">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $purchase_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?view=list"; ?>"><button class="btn btn-success" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
