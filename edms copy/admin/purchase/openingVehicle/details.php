<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$model_id=$_GET['id'];
$opening_vehicle_id_array=getOpeningVehiclesForModel($model_id);
if(!is_numeric($model_id) || !$opening_vehicle_id_array)
{ ?>
<script>
  window.history.back()
</script>
<?php
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Purchase Details </h4>
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

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Vehicle Details</h4>

<table id="insertVehicleTable" class="detailStylingTable insertTableStyling">
<?php $i=1; foreach($opening_vehicle_id_array as $opening_vehicle_id) {
	$vehicle = getVehicleByID($opening_vehicle_id[0]);
	 ?>
<tbody >

<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Vehicle <?php echo $i++; ?></span>
</td>


</tr>


<tr>
<td>Vehicle Model : </td>
				<td>
					<?php echo $vehicle['model_name']; ?>
                            </td>
</tr>


 <tr>
<td>Vehicle Model Year : </td>
				<td>
					<?php echo $vehicle['vehicle_model']; ?>
                            </td>
</tr>

<tr>
<td>Vehicle Color : </td>
				<td>
					<?php echo $vehicle['vehicle_color']; ?>
                            </td>
</tr>

<tr>
<td>Godown : </td>
				<td>
					<?php echo $vehicle['godown_name']; ?>
                            </td>
</tr>

<tr>
       <td>Vehicle Condition :</td>
           
           
       
        <td>
					<?php if($vehicle['vehicle_condition']==1){ ?> NEW <?php } 
                       else if($vehicle['vehicle_condition']==0){ ?> OLD <?php } ?>
                            </select> 
                            </td>
 </tr>
 
<tr >
<td class="firstColumnStyling">
Reg Number : 
</td>

<td>
<?php echo $vehicle['vehicle_reg_no']; ?>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<?php echo $vehicle['vehicle_engine_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<?php echo $vehicle['vehicle_chasis_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Service Book Number : 
</td>

<td>
<?php echo $vehicle['service_book']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Cylinder Number : 
</td>

<td>
<?php echo $vehicle['cng_cylinder_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number : 
</td>

<td>
<?php echo $vehicle['cng_kit_no']; ?></td>
</tr>

<tr>
<td width="220px">Basic Price : </td>
				<td>
					<?php echo "Rs. ".number_format($vehicle['basic_price'],2); ?>
                            </td>
</tr>
<tr>
<td></td>
<td>
<a href="<?php echo 'index.php?action=delete&lid='.$vehicle['vehicle_id'].'&model_id='.$model_id; ?>"><button title="Edit this entry" class="btn delBtn"><span class="edit delbtn">X</span></button></a>
</td>
</tr>
</tbody>
<?php } ?>
</table>

<table class="no_print">
<tr>
<td width="250px;"></td>
<td>
 <a href="<?php echo 'index.php?view=edit&id='.$model_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="edit">E</span></button></a>
<a href="<?php echo WEB_ROOT."admin/settings/vehicle_settings/model_settings/"; ?>"><button class="btn btn-success" >Back</button></a>
</td>
</tr>

</table>


</div>
<div class="clearfix"></div>
