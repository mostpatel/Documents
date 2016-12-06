<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Vehicle Model</h4>
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
<td>Vehicle Company<span class="requiredField">* </span> : </td>
				<td>
					<select id="dealer" name="vehicle_company_id">
                        <option value="-1" >--Select Vehicle Company--</option>
                        <?php
                            $companies = listVehicleCompanies();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['vehicle_company_id'] ?>"><?php echo $super['company_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>

<td class="firstColumnStyling">
Model name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>

</td>
</tr>

<tr>
<td>Vehicle Type<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_type_id" name="vehicle_type_id">
                        <option value="-1" >--Select Vehicle Type--</option>
                        <?php
                            $companies = listVehicleTypes();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['vehicle_type_id'] ?>"><?php echo $super['vehicle_type'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Cubic Capacity (cc)<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="cubic_capacity" id="cubic_capacity"/> CC

</td>
</tr>

<tr>
<td>Fuel Type<span class="requiredField">* </span> : </td>
				<td>
					<select id="fuel_type_id" name="fuel_type_id">
                        <option value="-1" >--Select Fuel Type--</option>
                        <?php
                            $companies = listFuelTypes();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['fuel_type_id'] ?>"><?php echo $super['fuel_type'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
No of Cylinders<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="no_of_cylinders" id="no_of_cylinders"/>

</td>
</tr>


<tr>

<td class="firstColumnStyling">
Seating Capacity<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="seating_capacity" id="seating_capacity"/>

</td>
</tr>


<tr>

<td class="firstColumnStyling">
Unladen Weight<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="unladen_weight" id="unladen_weight"/> Kgs

</td>
</tr>


<tr>

<td class="firstColumnStyling">
Gross Weight<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="gross_weight" id="gross_weight"/> Kgs

</td>
</tr>

<tr>

<td class="firstColumnStyling">
Axle Weight Front<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="axle_wt_fr" id="axle_wt_fr"/> Kgs

</td>
</tr>

<tr>

<td class="firstColumnStyling">
Axle Weight Rear<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="axle_wt_rr" id="axle_wt_rr"/> Kgs

</td>
</tr>

<tr>

<td class="firstColumnStyling">
No of Tyres Front<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="no_tyres_fr" id="no_tyres_fr"/>

</td>
</tr>


<tr>

<td class="firstColumnStyling">
No of Tyres Rear<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="no_tyres_rr" id="no_tyres_rr"/>

</td>
</tr>


<tr>

<td class="firstColumnStyling">
Type of Tyres Front<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="tyre_type_fr" id="tyre_type_fr"/>

</td>
</tr>


<tr>

<td class="firstColumnStyling">
Type of Tyres Rear<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="tyre_type_rr" id="tyre_type_rr"/>

</td>
</tr>

<tr>

<td class="firstColumnStyling">
Wheelbase<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="wheelbase" id="wheelbase"/>

</td>
</tr>

<tr>

<td class="firstColumnStyling">
MRP<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="mrp" id="mrp"/> Rs

</td>
</tr>





<tr>
<td></td>
<td>
<input type="submit" value="Add Model" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Vehicle Models</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Model Name</th>
             <th class="heading">Company Name</th>
              <th class="heading">Set Opening Vehicles</th>
              <th class="heading">View Opening Vehicles</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$vehicleModels=listVehicleModels();
		$i=0;
		foreach($vehicleModels as $model)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><span  class="editLocationName" id="<?php echo $model['model_name'] ?>"><?php echo $model['model_name']; ?></span>
            </td>
             <td><span  class="editLocationName" id="<?php echo $model['vehicle_company_id'] ?>"><?php echo $model['company_name']; ?></span>
            </td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/purchase/openingVehicle/index.php?view=edit&id='.$model['model_id']; ?>"><button title="View this entry" class="btn  btn-warning editBtn">Update Opening Vehicles</button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/purchase/openingVehicle/index.php?view=details&id='.$model['model_id']; ?>"><button title="Edit this entry" class="btn btn-success editBtn">View Opening Vehicles</button></a>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$model['model_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$model['model_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$model['model_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>