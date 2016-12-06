<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$contra_id=$_GET['id'];
$payment=getContraById($contra_id);
if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$credit_account_id=$payment['from_ledger_id'];
$debit_account_id=$payment['to_ledger_id'];
if(validateForNUll($credit_account_id) && is_numeric($credit_account_id))
$credit_ledger=getLedgerById($credit_account_id);

if(validateForNUll($debit_account_id) && is_numeric($debit_account_id))
$debit_ledger=getLedgerById($debit_account_id);
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
<td> By (Debit) : </td>
				<td>
					<?php echo $debit_ledger['ledger_name']; ?>
                            </td>
</tr>
<tr>
<td> To (Credit) : </td>
				<td>
					<?php echo $credit_ledger['ledger_name']; ?>
                            </td>
</tr>
                    
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
 <a href="<?php echo 'index.php?view=edit&lid='.$contra_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $contra_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button class="btn btn-warning" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
