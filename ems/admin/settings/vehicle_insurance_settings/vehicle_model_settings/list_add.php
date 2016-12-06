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
                             
                             <option value="<?php echo $super['vehicle_company_id'] ?>"><?php echo $super['vehicle_company_name'] ?></option>
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
					<select id="vehicle_type" name="vehicle_type_id" onchange="createDropDownModelCompany(this.value)">
                        <option value="-1" >--Select Vehicle Type--</option>
                        <?php
                            $vehicleTypes = listVehicleTypes();
                            foreach($vehicleTypes as $vehicleType)
                              {
                             ?>
                             
                             <option value="<?php echo $vehicleType['vehicle_type_id'] ?>"><?php echo $vehicleType['vehicle_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Vehicle CC<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_cc" name="cc_id">
                        <option value="-1" >--Please Select Model--</option>
                     
                            </select> 
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
             <th class="heading">Vehicle Type</th>
             <th class="heading">Vehicle CC</th>
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
            <td><span  class="editLocationName" id="<?php echo $model['vehicle_model_id'] ?>"><?php echo $model['vehicle_model_name']; ?></span>
            </td>
            
             <td>
             <span  class="editLocationName" id="<?php echo $model['vehicle_company_id'] ?>">
			 <?php 
			  $company_id = $model['vehicle_company_id']; 
			  $companyDetails = getVehicleCompanyById($company_id);
			  $companyName = $companyDetails['vehicle_company_name'];
			  echo $companyName;
			 ?>
             </span>
            </td>
            
            <td>
             <span  class="editLocationName" id="<?php echo $model['vehicle_type_id'] ?>">
			 <?php 
			  $type_id = $model['vehicle_type_id']; 
			  $typeDetails = getVehicleTypeById($type_id);
			  $vehicleType = $typeDetails['vehicle_type'];
			  echo $vehicleType;
			 ?>
             </span>
            </td>
            
            <td>
             <span  class="editLocationName" id="<?php echo $model['vehicle_cc_id'] ?>">
			 <?php 
			  $cc_id = $model['vehicle_cc_id']; 
			  $ccDetails = getVehicleCCById($cc_id);
			  $cc = $ccDetails['vehicle_cc'];
			  echo $cc;
			 ?>
             </span>
            </td>
            
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$model['vehicle_model_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$model['vehicle_model_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$model['vehicle_model_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>