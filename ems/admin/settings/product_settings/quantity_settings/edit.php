<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$qunatityDetails=getQuantityById($_GET['lid']);
$quantity_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Qunatity</h4>
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
Quantity <span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $qunatityDetails['quantity_id'] ?>" />
<input type="text" name="quantity" id="txtName" value="<?php echo $qunatityDetails['quantity']; ?>"/>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" class="btn btn-warning" value="Save"/>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>