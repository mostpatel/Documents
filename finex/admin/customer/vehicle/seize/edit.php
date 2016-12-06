<?php
if(!isset($_GET['id']))
{
header("Location : ".WEB_ROOT."admin/search");
exit;
}
if(!isset($_GET['state']))
{
header("Location : ".WEB_ROOT."admin/search");
exit;
}

if(!isset($_GET['state2']))
{
header("Location : ".WEB_ROOT."admin/search");
exit;
}

$file_id=$_GET['id'];
$vehicle_id=$_GET['state'];
$seize_id=$_GET['state2'];
$seize=getVehicleSeizeDetailsByVehicleId($vehicle_id);
?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Seize Vehicle</h4>
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
<form onsubmit="return submitSeize();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data">
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden">
<input name="lid" value="<?php echo $seize_id; ?>" type="hidden">
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Seize Date<span class="requiredField">* </span>  : </td>
				<td>
					<input onchange="onChangeDate(this.value,this)"  type="text"  autocomplete="off" size="12"  name="seize_date" class="datepicker1" placeholder="Click to Select!" value="<?php echo date('d/m/Y',strtotime($seize['seize_date'])); ?>" /><span class="DateError customError">Please select a date!</span>
</td>
                    
                    
                  
</tr>
<?php ?>
<tr>
<td>Sold<span class="requiredField">* </span> : </td>
				<td>
					<table>
               <tr><td><input type="radio"   name="sold" id="no" onChange="releaseVehicle(this.value)"  value="0" <?php if($seize['sold']==0) { ?> checked="checked" <?php } ?>></td><td><label for="no">No</label></td></tr>
            <tr><td><input type="radio"  id="yes" name="sold"  onChange="releaseVehicle(this.value)" value="1" <?php if($seize['sold']==1) { ?> checked="checked" <?php } ?> ></td><td><label for="yes">Yes (to other party)</label></td>
             <tr><td><input type="radio"  id="rel" name="sold" onChange="releaseVehicle(this.value)"  value="2" <?php if($seize['sold']==2) { ?> checked="checked" <?php } ?> ></td><td><label for="rel">Release to party</label></td>
               </tr> 
            </table>
                            </td>
</tr>

<tr id="release_date">
<td width="220px">Release Date<span class="requiredField">* </span> : </td>
				<td>
					<input onchange="onChangeDate(this.value,this)" type="text" size="12"  name="release_date" class="datepicker1" placeholder="Click to Select!" /><span class="DateError customError">Please select a date!</span>
</td>
                    
                    
                  
</tr>

<tr id="release_remarks">
<td>
Remarks : 
</td>
<td>
<textarea type="text"  name="remarks" id="remarks_remainder"></textarea>
</td>
</tr>

<tr>
<td>Godown<span class="requiredField">* </span> : </td>
				<td>
					<select name="godown_id">
                    	<?php	$vehicles=listVehicleGodowns();
		$no=0;
		foreach($vehicles as $vehicleType)
		{ ?>
        <option value="<?php echo $vehicleType['godown_id'] ?>" <?php if($seize['godown_id']== $vehicleType['godown_id']) { ?> selected="selected" <?php } ?>><?php echo $vehicleType['godown_name']; ?></option>
        <?php } ?>
                    </select>
                    
                            </td>
</tr>
<tr>
<td>
Remarks : 
</td>
<td>
<textarea type="text"  name="remarks" id="remarks_remainder" ><?php echo $seize['remarks']; ?></textarea>
</td>
</tr>

<tr>
<td></td>
<td>
<input  type="submit" value="Edit" class="btn btn-danger"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="Back" /></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
$('#release_date').hide();
		$('#release_remarks').hide();
function releaseVehicle(release)
{
	
	if(release==2)
	{
		
		$('#release_date').show();
		$('#release_remarks').show();
		
	}
	else
	{
		
		$('#release_date').hide();
		$('#release_remarks').hide();
	}
	
}
</script>