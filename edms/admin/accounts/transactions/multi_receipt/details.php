<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}

$total_amount = 0;
$receipt_ids = "";
$i=0;
foreach($payment as $p)
{
	if($i!=0)
	$receipt_ids = $receipt_ids.",".$p['receipt_id'];
	else
	$receipt_ids = $receipt_ids.$p['receipt_id'];
	$total_amount = $total_amount + $p['amount'];
	$i++;
}
$extra_payment_details = getReceiptDetailsForReceiptId($payment[0]['parent_id']);

if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}

$by_account_id=$payment[0]['from_ledger_id'];
$by_account=getLedgerById($by_account_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Receipt Details </h4>
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
					<?php echo date('d/m/Y',strtotime($payment[0]['trans_date'])); ?>
                            </td>
</tr>

<tr>
<td> By Account : </td>
				<td>
					<?php echo $by_account['ledger_name']; ?>
                            </td>
</tr>
<tr>
<td> Total Amount : </td>
				<td>
					<?php echo moneyFormatIndia($total_amount); ?>
                            </td>
</tr>
</table>
 <?php  if($extra_payment_details){ ?>
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


<table class="detailStylingTable insertTableStyling"  >


<?php foreach($payment as $p) {
	
$customer_id=$p['to_customer_id'];
if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	$ledger_customer_id='C'.$customer_id;
	$name = $customer['customer_name']." | [".$ledger_customer_id."]";
}
$ledger_id=$p['to_ledger_id'];
if(validateForNUll($ledger_id) && is_numeric($ledger_id))
{
$to_ledger=getLedgerById($ledger_id);
$ledger_customer_id='L'.$ledger_id;
$name = $to_ledger['ledger_name']." | [".$ledger_customer_id."]";
} ?>
<tr>
<td colspan="2"><h4 class="headingAlignment"> Credit Details </h4></td>
</tr>
<tr>
<td width="220px">Amount : </td>
				<td>
					<?php echo moneyFormatIndia($p['amount']); ?>
                            </td>
</tr>
<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                
              <?php echo $name; ?>
                   
                            </td>
</tr>
<tr>
<td width="220px">Receipt Type<span class="requiredField">* </span> : </td>
				<td>
				<?php if($p['receipt_ref_type']==0) { ?> NEW <?php } ?> <?php if($p['receipt_ref_type']==1) { ?> ADVANCE <?php } ?>  <?php if($p['receipt_ref_type']==2) { ?> Against Sales <?php } ?> 
                 <?php if($p['receipt_ref_type']==3) { ?>On Account <?php } ?> 
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_new">
<td> </td>
				<td>
					
                  	
                            </td>
</tr>

<tr  <?php if($p['receipt_ref_type']!=2) { ?> style="display:none;" <?php } ?> id="pay_ref_against">
<td>Receipt Ref<span class="requiredField">* </span> : </td>
				<td>
					
                    <?php if($p['receipt_ref_type']==2) {
					$sales = generalSalesReports($ledger_customer_id,NULL,$p['trans_date'],NULL,NULL,NULL,1,$receipt_ids);
						 
					foreach($sales as $s)
					{	
					if($p['receipt_ref']==$s['sales_id']) 
					echo $s['invoice_no']." ".date("d/m/Y",strtotime($s['trans_date']))." ".$s['outstanding_amount']." Rs";
						 }} ?>
                    
                   
                </td>
                
</tr>


<?php } ?>

</table>

<table class="detailStylingTable insertTableStyling" style="margin-top:10px;margin-bottom:10px;">



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
 <a href="<?php echo 'index.php?view=edit&lid='.$receipt_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $receipt_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button class="btn btn-warning" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>