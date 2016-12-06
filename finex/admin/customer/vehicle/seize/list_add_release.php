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
$file_id=$_GET['id'];
$seize_id=$_GET['state'];
$seize=getVehicleSeizeDetailsByFileId($file_id);
if(!checkForNumeric($vehicle_id))
{
	$vehicle_id=0;
	}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Release Seized Vehicle</h4>
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
<form onsubmit="return submitSeize();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=release'; ?>" method="post">
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<input name="seize_id" value="<?php echo $seize_id; ?>" type="hidden"/>
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Release Date<span class="requiredField">* </span> : </td>
				<td>
					<input onchange="onChangeDate(this.value,this)" type="text" size="12"  name="release_date" class="datepicker1" placeholder="Click to Select!" /><span class="DateError customError">Please select a date!</span>
</td>
                    
                    
                  
</tr>

<tr>
<td>
Remarks : 
</td>
<td>
<textarea type="text"  name="remarks" id="remarks_remainder"></textarea>
</td>
</tr>

<tr>
<td></td>
<td>
<input  type="submit" value="Release" class="btn btn-danger"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="Back" /></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>