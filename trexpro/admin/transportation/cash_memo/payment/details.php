<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$payment_id=$_GET['id'];
$payment=getCashMemoById($payment_id);
if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}


?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Cash Memo Details </h4>
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

<tr class="">
<td>Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($payment['memo_date'])); ?>
                            </td>
</tr>

<tr class="">
<td>Memo No : </td>
				<td>
					<?php echo $payment['memo_no']; ?>
                            </td>
</tr>

<tr class="">
<td>Lr amount : </td>
				<td>
					<?php echo $payment['lr_amount']; ?>
                            </td>
</tr>

<tr class="">
<td>Labour : </td>
				<td>
					<?php echo $payment['labour']; ?>
                            </td>
</tr>

<tr class="">
<td>Other Charges : </td>
				<td>
					<?php echo $payment['other_charges']; ?>
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
 <a href="<?php echo 'index.php?view=edit&lid='.$payment_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="index.php?action=delete&lid=<?php echo $payment_id; ?>"><button class="btn delBtn" ><span class="delete">X</span></button></a>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button class="btn btn-warning" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
