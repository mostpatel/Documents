<?php $loan_percent_slabs = listAllLoanPercentSlabs(); ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Add a Model Value</h4>
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
<form id="addAgencyForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" onsubmit="return checkCheckBox()">
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Model<span class="requiredField">* </span> : 
</td>

<td>
<select  name="model_id" id="model_id" class="selectpicker" >
<option value="-1">-- Please Select --</option>
<?php $models = listVehicleModelsCompanyWise();
$old_vehicle_company_id = 0;
foreach($models as $model)
{
	$vehicle_company_id = $model['vehicle_company_id'];
	
	if($old_vehicle_company_id!=$vehicle_company_id)
	{ 
 ?>
 <optgroup label="<?php echo $model['company_name']; ?>">
 
 <?php

  } ?>
 <option value="<?php echo $model['model_id']; ?>"><?php echo $model['model_name']; ?></option>
 <?php 
 
 
 
 $old_vehicle_company_id = $vehicle_company_id;
 
 if($old_vehicle_company_id!=$vehicle_company_id)
	{ 
 ?>
 </optgroup>
 
 <?php

  }
 } ?>
</select>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Value<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="rate" id="rate"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Model Year<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" readonly name="model_year" id="model_year" value="<?php echo date('Y',strtotime(getTodaysDate())); ?>" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Dep Percent<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="dep_percent" id="dep_percent"/>
</td>
</tr>

<?php foreach($loan_percent_slabs as $loan_percent_slab) { ?>
<tr>
<td colspan="2" class="headingAlignment">Details For (<?php echo $loan_percent_slab['from_percent']." - ".$loan_percent_slab['to_percent']; ?>)% Loan</td>
</tr>
<tr>

<td class="firstColumnStyling">
Min ROI <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="roi[<?php echo $loan_percent_slab['slab_id']; ?>]" id="roi<?php echo $loan_percent_slab['slab_id']; ?>" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Max Duration<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="duration[<?php echo $loan_percent_slab['slab_id']; ?>]" id="duration<?php echo $loan_percent_slab['slab_id']; ?>" />
</td>
</tr>
<?php } ?>

<tr>
<td></td>
<td>
<input type="submit" value="Add Model Value" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
	
    <hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Models</h4>
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Company</th>
            <th class="heading">Model Name</th>
             <th class="heading">Value</th>
             <th class="heading">Model year</th>
           <th class="heading">Dep Percent</th>
            <th class="heading"></th>
             <th class="heading"></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$dealers=listModelValues();
		$no=0;
		if(count($dealers)>0)
		{
		foreach($dealers as $agencyDetails)
		{
			$model_value_id = $agencyDetails['model_value_id'];
			$roi_duration_slabs=getRelModelLoanSlabForModelValueId($agencyDetails['model_value_id']);
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
             <td><?php echo $agencyDetails['company_name']; ?></span>
            </td>
             <td><?php echo $agencyDetails['model_name']; ?></span>
            </td>
            <td><?php echo $agencyDetails['value']; ?></span>
            </td>
            <td><?php echo $agencyDetails['from_year']; ?></span>
            </td>
             <td><?php echo $agencyDetails['dep_percent']; ?>%</span>
            </td>
           <td>
           	<?php foreach($roi_duration_slabs as $roi_duration_slab) { echo "<b>(".$roi_duration_slab['from_percent']." - ".$roi_duration_slab['to_percent'].")% Loan </b><br> ROI : ".$roi_duration_slab['min_roi']."% DURATION : ".$roi_duration_slab['max_duration']." mths<br><br>";  }  ?>
           </td> 
           <td>
           <?php $dep_array = getDepreciationChartForModelValueId($agencyDetails['model_value_id'],10);
		   foreach($dep_array as $year => $value)
		   {
			   echo " Year: ".$year." Max Loan: ".round($value)." <br>";
			   
			  }
		    ?>
           </td>
           
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$agencyDetails['model_value_id']; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$agencyDetails['model_value_id']; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }}?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>