<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Route Group</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>
<td class="firstColumnStyling">
Route<span class="requiredField">* </span> :
</td>

<td>
<select  name="route_id" id="txtName">
	<?php $route_groups = listRoutes(); foreach($route_groups as $route_group)
	{
	 ?>
     <option value="<?php echo $route_group['route_id'] ?>"><?php echo $route_group['from_area_name']." - ".$route_group['to_area_name']; ?></option>
     <?php } ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Vehicle Type<span class="requiredField">* </span> :
</td>

<td>
<select  name="vehicle_type_id" id="vehicle_type_id">
	<?php $route_groups = listVehicleTypes(); foreach($route_groups as $route_group)
	{
	 ?>
     <option value="<?php echo $route_group['vehicle_type_id'] ?>"><?php echo $route_group['vehicle_type']; ?></option>
     <?php } ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Fare Category<span class="requiredField">* </span> :
</td>

<td>
<select  name="fare_category_id" id="fare_category_id">
	<?php $route_groups = listFareCategories(); foreach($route_groups as $route_group)
	{
	 ?>
     <option value="<?php echo $route_group['fare_category_id'] ?>"><?php echo $route_group['fare_category']; ?></option>
     <?php } ?>
</select>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Fare<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="fare" id="txtDistance"/>
</td>

</tr>

<tr>

<td class="firstColumnStyling">
Driver Fare<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="driver_fare" id="driver_fare"/>
</td>

</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add Sub Route" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Sub Routes</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Route</th> 
             <th class="heading">Vehicle Type</th> 
            <th class="heading">Fare Category</th>
            <th class="heading">Fare</th>
             <th class="heading">Driver Fare</th>
           <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$vehicles=listRouteFares();
		$no=0;
		foreach($vehicles as $vehicleType)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
             <td><?php $route = getRouteById($vehicleType['route_id']); echo $route['from_area_name']." - ".$route['to_area_name']; ?>
            </td>
            <td><?php echo getVehicleTypeNameById($vehicleType['vehicle_type_id']); ?>
            </td>
            <td><?php echo getFareCategoryNameById($vehicleType['fare_category_id']); ?>
            </td>
              <td><?php echo $vehicleType['fare']; ?></td>
              <td><?php echo $vehicleType['driver_fare']; ?></td>
             
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$vehicleType['route_fare_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$vehicleType['route_fare_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>