<?php 

$customer_id = $_GET['id'];
if (!checkForNumeric($customer_id))
{
	exit;
}

?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a Customer Note</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">
<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Note<span class="requiredField">* </span> :
</td>

<td>
<textarea id="note" class="note" name="note"  cols="5" rows="6"></textarea>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add a Note" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>


       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>