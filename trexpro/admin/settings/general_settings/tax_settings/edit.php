<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Tax</h4>
<?php 
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">

<table class="insertTableStyling no_print">


<tr>

<td class="firstColumnStyling">
Tax name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $area['tax_id']; ?>"/>
<input type="text" id="txtlocation" name="name" value="<?php echo $area['tax_name']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Tax %<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="tax_percent" name="percent" value="<?php echo $area['tax_percent']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Type<span class="requiredField">* </span> :
</td>

<td>
<select  id="tax_type" name="in_out">
	<option value="0" <?php if($area['in_out']==0) { ?> selected="selected" <?php } ?>>INPUT</option>
    <option value="1" <?php if($area['in_out']==1) { ?> selected="selected" <?php } ?>>OUTPUT</option>
    <option value="2" <?php if($area['in_out']==2) { ?> selected="selected" <?php } ?>>INCLUDE IN PURCHASE</option>
    <option value="3" <?php if($area['in_out']==3) { ?> selected="selected" <?php } ?>>INCLUDE IN SALE</option>
</select>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>
</div>
<div class="clearfix"></div>