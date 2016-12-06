<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$model=getVehicleModelById($_GET['lid']);
$model_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Vehicle Model</h4>
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
<input type="hidden" name="lid"  value="<?php echo $model['vehicle_model_id']; ?>"/>

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Model name<span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="name" id="txtName" value="<?php echo $model['vehicle_model_name']; ?>"/>
</td>
</tr>

<tr>
<td>Vehicle Company<span class="requiredField">* </span> : </td>
				<td>
					<select id="dealer" name="vehicle_company_id">
                        <option value="-1" >--Select Vehicle Company--</option>
                        <?php
                            $companies = listVehicleCompanies();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['vehicle_company_id'] ?>" <?php if($model['vehicle_company_id']==$super['vehicle_company_id']) echo "selected"; ?>><?php echo $super['vehicle_company_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Vehicle Type<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_type" name="vehicle_type_id" onchange="createDropDownModelCompany(this.value)">
                        <option value="-1" >--Select Vehicle Company--</option>
                        <?php
                            $vehicleTypes = listVehicleTypes();
                            foreach($vehicleTypes as $vehicleType)
                              {
                             ?>
                             
                             <option value="<?php echo $vehicleType['vehicle_type_id'] ?>" <?php if($model['vehicle_type_id']==$vehicleType['vehicle_type_id']) echo "selected"; ?>><?php echo $vehicleType['vehicle_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Vehicle CC<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_cc" name="cc_id">
                        <option value="-1" >--Please Select Model--</option>
                        
                        <?php
                            $vehicleTypes = getAllVehicleCCForAVehicleTypeId($model['vehicle_type_id']);
                            foreach($vehicleTypes as $vehicleType)
                              {
                             ?>
                             
                             <option value="<?php echo $vehicleType['vehicle_cc_id'] ?>" <?php if($model['vehicle_cc_id']==$vehicleType['vehicle_cc_id']) echo "selected"; ?>><?php echo $vehicleType['vehicle_cc'] ?></option>
                             <?php } ?>
                     
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