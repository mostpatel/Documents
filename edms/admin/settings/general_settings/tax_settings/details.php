<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Tax Details</h4>
<?php 
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$area=getTaxByID($_GET['lid']);

$area_id=$_GET['lid'];
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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
<table id="DetailsTable" class="insertTableStyling">

<tr>
<td class="firstColumnStyling">
Tax name :
</td>

<td>
<?php echo $area['tax_name']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Tax % :
</td>

<td>
<?php echo  $area['tax_percent'] ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Type :
</td>

<td>
<?php if($area['in_out']==0) echo  "INPUT";  else if($area['in_out']==1) echo "OUTPUT"; else if($area['in_out']==2) echo "INCLUDE IN PURCHASE"; ?>
</td>
</tr>


<tr class="no_print">
<td></td>
<td >
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$area_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$area_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>


</table>    
</div>
<div class="clearfix"></div>