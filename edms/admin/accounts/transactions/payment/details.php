<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}


$extra_payment_details = getPaymentDetailsForPaymentId($payment_id);

if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$customer_id=$payment['from_customer_id'];
if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
}
$ledger_id=$payment['from_ledger_id'];
$by_account_id=$payment['to_ledger_id'];
if(validateForNUll($ledger_id) && is_numeric($ledger_id))
$from_ledger=getLedgerById($ledger_id);

$by_account=getLedgerById($by_account_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Payment Details </h4>
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
<td>Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
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
					<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer_id; ?>"><?php echo $customer['customer_name']; ?></a>
                            </td>
</tr>
<?php } ?>                          
<tr>
<td width="220px"> Amount : </td>
				<td>
					<?php echo "Rs. ".number_format($payment['amount'])." /- "; ?>
                    </td>
</tr>

</table>
<?php if($extra_payment_details) { ?>
<table id="chequePaymentTable" class="detailStylingTable insertTableStyling" <?php if(!$extra_payment_details){ ?> style="display:none;" <?php } else { ?> style="display:table" <?php } ?>>

<tr>
<td>Payment Mode : </td>
				<td>
					<?php $payment_mode = getPaymentModeById($extra_payment_details['payment_mode_id']); echo $payment_mode['payment_mode']; ?>
                            </td>
</tr>
<tr>
<td width="220px">Bank Name : </td>
				<td>
					<?php  if($extra_payment_details) echo $extra_payment_details['bank_name']; ?>
                            </td>
</tr>
<tr>
<td width="220px">Branch Name : </td>
				<td>
					<?php  if($extra_payment_details) echo $extra_payment_details['branch_name']; ?>
                            </td>
</tr>
<tr>
<td width="220px">Cheque No : </td>
				<td>
					<?php  if($extra_payment_details) echo $extra_payment_details['chq_no']; ?>
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date : </td>
				<td>
					<?php  if($extra_payment_details) echo date('d/m/Y',strtotime($extra_payment_details['chq_date'])); ?>
                            </td>
</tr>
</table>
<?php } ?>
<table class="detailStylingTable insertTableStyling">

<tr>
<td width="220px"> Remarks : </td>
				<td>
					<?php if(validateForNull($payment['remarks'])) echo $payment['remarks']; else echo "NA"; ?>
                    </td>
</tr>



</table>

<table class="no_print">
<tr>
<td width="250px;"></td>
<td>
 <a href="<?php echo 'index.php?view=edit&lid='.$payment_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $payment_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button class="btn btn-warning" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
