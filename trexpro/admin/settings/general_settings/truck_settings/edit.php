<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$vehicleType=getTruckById($_GET['lid']);
$vehicleType_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Trcuk</h4>
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
<input type="hidden" name="lid" value="<?php echo $vehicleType['truck_id']; ?>"/>
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Truck Name<span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="name" id="txtName" value="<?php echo $vehicleType['truck_name']; ?>"/>
</td>
</tr>



<tr>

<td class="firstColumnStyling">
Truck Number<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="truck_no" id="truck_no" value="<?php echo $vehicleType['truck_no']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Owner<span class="requiredField">* </span> :
</td>

<td>
<select id="customer_city_id" name="owner_ledger_id" class="city">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listTruckOwners();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['ledger_id'] ?>" <?php if($super['ledger_id']==$vehicleType['owner_ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $super['ledger_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Remarks<span class="requiredField">* </span> :
</td>

<td>
<textarea  name="remarks" id="remarks"></textarea>
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