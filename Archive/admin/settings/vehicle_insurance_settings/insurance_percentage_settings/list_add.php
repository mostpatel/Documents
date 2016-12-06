<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add Insurance Percentage</h4>
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
                        <option value="-1" >--Please Select CC--</option>
                     
                            </select> 
                            </td>
</tr>





<tr>
<td> Insurance Period <span class="requiredField">* </span> : </td>
				<td>
					<select id="period_id" name="period_id">
                        <option value="-1" >--Select Period--</option>
                        <?php
                            $periods = listInsurancePeriod();
                            foreach($periods as $period)
                              {
                             ?>
                             
                             <option value="<?php echo $period['period_id'] ?>"><?php echo $period['period'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td> Insurance Company <span class="requiredField">* </span> : </td>
				<td>
					<select id="company" name="company">
                        <option value="-1" >--Select Insurance Company--</option>
                        <?php
                            $companies = listInsuranceCompanies();
                            foreach($companies as $company)
                              {
                             ?>
                             
                             <option value="<?php echo $company['insure_com_id'] ?>"><?php echo $company['insure_com_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Percentage<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="percentage" id="percentage" /> &nbsp; %

</td>
</tr>

<tr>

<td class="firstColumnStyling">
Liability Only Premium <span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="lPremium" id="lPremium"/> 

</td>
</tr>

<tr>

<td class="firstColumnStyling">
Compulsory PA <span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="CPA" id="CPA"/> 

</td>
</tr>


<tr>

<td class="firstColumnStyling">
PA Paid Driver <span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="paDriver" id="paDriver"/> 

</td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Insurance Percentage</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Vehicle Type</th>
             <th class="heading">Vehicle CC</th>
             <th class="heading">Insurance Company</th>
             <th class="heading">Period</th>
             <th class="heading">Percentage</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$percentages=listInsurancePercentage();
		$i=0;
		foreach($percentages as $percentage)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><span  class="editLocationName" id="<?php echo $percentage['vehicle_type_id'] ?>">
			<?php 
			$vTypeId = $percentage['vehicle_type_id'];
			$vehicleTypeDetails = getVehicleTypeById($vTypeId);
			echo $vehicleTypeDetails['vehicle_type'];
			?></span>
            </td>
            
             <td>
             <span  class="editLocationName" id="<?php echo $percentage['vehicle_cc_id'] ?>">
			 <?php 
			  
			 $ccId = $percentage['vehicle_cc_id'];
			 $ccDetails = getVehicleCCById($ccId);
			 echo $ccDetails['vehicle_cc'];
			 ?>
             </span>
            </td>
            
            <td>
             <span  class="editLocationName" id="<?php echo $percentage['insure_com_id'] ?>">
			 <?php 
			  
			  $inComId = $percentage['insure_com_id'];
			  $companyDetails =  getInsuranceCompanyById($inComId);
			  echo $companyDetails['insure_com_name'];
			 ?>
             </span>
            </td>
            
            <td>
             <span  class="editLocationName" id="<?php echo $percentage['period_id'] ?>">
			 <?php 
			  
			  $periodId =  $percentage['period_id'];
			  $periodDetails = getInsurancePeriodById($periodId);
			  echo $periodDetails['period'];
			 ?>
             </span>
            </td>
            
            <td>
             <span  class="editLocationName" id="<?php echo $percentage['percentage'] ?>">
			 <?php 
			  
			   echo $percentage['percentage']. "%";
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