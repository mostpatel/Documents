<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$jv_id=$_GET['id'];
$payment=getJVById($jv_id);

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
$to_customer_id=$payment['to_customer_id'];

if(validateForNull($to_customer_id) && is_numeric($to_customer_id))
{
	$to_customer=getCustomerDetailsByCustomerId($to_customer_id);
}
$debit_details=$payment['debit_details'];
$credit_details=$payment['credit_details'];
$debit_details = getDebitJVCDsForJVID($jv_id);
$credit_details = getCreditJVCDsForJVID($jv_id);
$debit_string = "";
$credit_string = "";
foreach($debit_details as $debit_detail)
{
	$debit_detail=$debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id);
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
		$name = $customer['customer_name'];
		
	}
	$debit_string = $debit_string.$name." : ".$amount." <br>";
}

foreach($credit_details as $debit_detail)
{
	$debit_detail = $debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id);
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
		$name = $customer['customer_name'];
		
	}
	$credit_string = $credit_string.$name." : ".$amount." <br>";
}

if(validateForNUll($ledger_id) && is_numeric($ledger_id))
$from_ledger=getLedgerById($ledger_id);

if(validateForNUll($to_ledger_id) && is_numeric($to_ledger_id))
$to_ledger=getLedgerById($to_ledger_id);



?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Journal Entry Details </h4>
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

<?php if(validateForNull($debit_string)) { ?>
<tr>
<td> Debit : </td>
				<td>
					<?php echo $debit_string; ?>
                            </td>
</tr>
<?php } ?>
<?php if(validateForNull($credit_string)) { ?>
<tr>
<td> Credit : </td>
				<td>
					<?php echo $credit_string; ?>
                            </td>
</tr>
<?php } ?>
<?php if(validateForNull($to_ledger_id) && checkForNumeric($to_ledger_id)) { ?>
<tr>
<td> By Ledger (Debit) : </td>
				<td>
					<?php echo $to_ledger['ledger_name']; ?>
                            </td>
</tr>
<?php } else if(validateForNull($to_customer_id) && checkForNumeric($to_customer_id)) { ?>
<tr>
<td> By Ledger (Dedit) : </td>
				<td>
					<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $to_file_id; ?>"><?php echo $to_customer['customer_name']." ".$to_file_no." ".$to_reg_no; ?></a>
                            </td>
</tr>
<?php } ?>            
<?php if(validateForNull($ledger_id) && checkForNumeric($ledger_id)) { ?>
<tr>
<td> To Ledger (Credit) : </td>
				<td>
					<?php echo $from_ledger['ledger_name']; ?>
                            </td>
</tr>
<?php } else if(validateForNull($customer_id) && checkForNumeric($customer_id)) { ?>
<tr>
<td> To Ledger (Credit) : </td>
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

<?php if(QTY_IN_JV==1) { ?>
</tr>


<td class="firstColumnStyling">
Quantity : 
</td>

<td>
<?php echo $payment['qty']; ?>
</td>
</tr>
<?php } ?>



</table>

<table class="no_print">
<tr>
<td width="250px;"></td>
<td>
<?php if($payment['auto_rasid_type']==0) { ?>
 <a href="<?php echo 'index.php?view=edit&lid='.$jv_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $jv_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<?php } ?>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button class="btn btn-warning" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
