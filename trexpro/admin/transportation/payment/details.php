<?php if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$payment_id=$_GET['lid'];
$invoice_id=$_GET['invoice_id'];
$ledger_id = $_GET['ledger_id'];
$payment=getPaymentById($payment_id);
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
	if($customer!='error')
	{
		
		}
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
<table id="rasidTable" class="detailStylingTable insertTableStyling no_print">

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
					<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><?php echo $customer['customer_name']." ".$file_no." ".$reg_no; ?></a>
                            </td>
</tr>
<?php } ?>                          
<tr>
<td width="220px"> Amount : </td>
				<td>
					<?php echo "Rs. ".number_format($payment['amount'])." /- "; ?>
                    </td>
</tr>

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
 <a href="<?php echo 'index.php?view=edit&lid='.$payment_id.'&id='.$invoice_id.'&state='.$ledger_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $payment_id.'&invoice_id='.$invoice_id.'&ledger_id='.$ledger_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<a href="<?php echo "index.php?id=".$invoice_id.'&state='.$ledger_id; ?>"><button class="btn btn-warning" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
