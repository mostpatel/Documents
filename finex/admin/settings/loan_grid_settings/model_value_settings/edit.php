<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$rto_work_rate=getModelValueById($_GET['id']);

$model_id=$_GET['id'];
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Model Value</h4>
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
<form id="addAgencyForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" onsubmit="return checkCheckBox()">
<input type="hidden" name="model_id" value="<?php echo $rto_work_rate['model_id']; ?>" />
<input type="hidden" name="model_value_id" value="<?php echo $rto_work_rate['model_value_id']; ?>" />
<table class="insertTableStyling no_print">
<tr>

<td class="firstColumnStyling">
Model<span class="requiredField">* </span> : 
</td>

<td>
<?php echo $rto_work_rate['model_name']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Value<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="rate" id="rate" value="<?php echo $rto_work_rate['value']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Model Year<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" readonly name="model_year" id="model_year" value="<?php echo date('Y',strtotime(getTodaysDate())); ?>" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Dep Percent<span class="requiredField">* </span> : 
</td>
<td>
<input type="text" name="dep_percent" id="dep_percent" value="<?php echo $rto_work_rate['dep_percent']; ?>"/>
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