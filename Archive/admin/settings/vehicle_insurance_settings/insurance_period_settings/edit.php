<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$periods=getInsurancePeriodById($_GET['lid']);
$period_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Period</h4>
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
<input type="hidden" name="lid" value="<?php echo $periods['period_id'] ?>" />


<tr>
<td width="200px">
 Vehicle Type <span class="requiredField">* </span> : 
</td>
<td>

<select type="text" name="vehicle_type_id" id="vehicle_type_id">
	<option value="-1">-- Please Select --</option>
    <?php $vehicleTypes=listVehicleTypes();
	foreach($vehicleTypes as $vehicleType)
	{
	?>
   <option value="<?php echo $vehicleType['vehicle_type_id'] ?>" <?php if($vehicleType['vehicle_type_id']==$periods['vehicle_type_id']) { ?> selected="selected" <?php } ?>> <?php echo $vehicleType['vehicle_type']; ?></option>
    <?php 	
		
	}
	 ?>
</select> 
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Period <span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="period" id="period" value="<?php echo $periods['period']; ?>"/>
</td>
</tr>

<tr>
<td width="200px">
 Min Range <span class="requiredField">* </span> : 
</td>
<td>

<select type="text" name="min_range" id="min_range">
	<option value="-1">-- Please Select --</option>
      <?php
			for($i=0; $i<=15; $i++)
			{
			?>
 <option value="<?php echo $i ?>" <?php if($i==$periods['min_range']) { ?> selected="selected" <?php } ?>> 
 <?php echo $i; ?>
 </option>
    <?php
		}
	?>
</select> 
</td>
</tr>

<tr>
<td width="200px">
 Max Range <span class="requiredField">* </span> : 
</td>
<td>

<select type="text" name="max_range" id="max_range">
	<option value="-1">-- Please Select --</option>
      <?php
			for($i=0; $i<=15; $i++)
			{
			?>
 <option value="<?php echo $i ?>" <?php if($i==$periods['max_range']) { ?> selected="selected" <?php } ?>> 
 <?php echo $i; ?>
 </option>
    <?php
		}
	?>
</select> 
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