<?php if(!isset($_GET['id']) || !isset($_GET['state']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$file_id=$_GET['id'];
$vehicle_id=$_GET['state'];
if(!checkForNumeric(getVehicleIdByFileId($file_id)))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}

$vehicle=getVehicleDetailsByFileId($file_id);
$others_company_id = getOthersVehicleCompanyId();
$others_model_id =getOthersModelByCompanyId($others_company_id);
$others_dealer_id = getOthersDealerIdFromCompanyId($others_company_id);
$others_vehicle_type_id = getOthersVehicleTypeId();
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Add Vehicle Docs Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurVehicle()" >

<input name="vehicle_id" value="<?php echo $vehicle_id; ?>" type="hidden" />
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<input id="vehicle_model" name="vehicle_model" value="<?php echo $vehicle['model_id']; ?>" type="hidden" />
<h4 class="headingAlignment">RTO Agent and work Details</h4>
<table style="margin-top:0px;margin-bottom:10px;" class="insertTableStyling no_print">
<tr>
<td width="220px">RTO Agent<span class="requiredField">* </span> : </td>
				<td>
					<select id="rto_agent" name="rto_agent" onchange="createDropDownRtoAgentWork()">
                        <option value="-1" >--Please Select Agent--</option>
                        <?php
                            $companies = listRtoAgents();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['rto_agent_id'] ?>"><?php echo $super['rto_agent_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>
<?php if(RTO_WORK==1) { ?>
<tr>
<td width="220px">Work Given<span class="requiredField">* </span> : </td>
				<td>
					<select id="rto_work" name="rto_work[]" onchange="" multiple class="selectpicker">
                        <option value="-1" >--Please Select Work--</option>
                    
                            </select> 
                            </td>
</tr>
<?php } ?>
<tr>
<td class="firstColumnStyling">
Work Given Date<span class="requiredField">* </span> : 
</td>

<td>
<input type="text"  placeholder="Click to select Date!" name="work_given_date" class="datepicker3" id="work_given_date"  value="" onChange="changeDocsToogle()"/><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Completion Date<span class="requiredField">* </span> : 
</td>

<td>
<input type="text"  placeholder="Click to select Date!" name="work_completion_date" class="datepicker3" id="work_completion_date"  value="" onChange="changeDocsToogle()"/><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>
</table>
<h4 class="headingAlignment">Vehicle Documents Status</h4>
<table style="margin-top:0px;margin-bottom:10px;" class="insertTableStyling no_print">
<tr>
       <td width="220px">R.C Book<span class="requiredField">* </span> :</td>
           
           
        <td>
        <select class="agent customer" name="rto">
            <option value="3">Originals given to customer</option>
              <option value="2">Work Given to agent</option>
              <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
        	
          
        
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Passing<span class="requiredField">* </span> :</td>
           
           
        <td>
               <select class="agent customer" name="passing">
       		 <option value="3">Originals given to customer</option>
              <option value="2">Work Given to agent</option>
              <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
        	
          
        
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Permit<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="agent customer" name="permit">
       		 <option value="3">Originals given to customer</option>
              <option value="2">Work Given to agent</option>
              <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
        	
          
        
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Insurance<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="agent customer"
               name="insurance">
       		 <option value="3">Originals given to customer</option>
              <option value="2">Work Given to agent</option>
              <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
        	
          
        
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>HP<span class="requiredField">* </span> :</td>
           
           
        <td>
        <select class="agent customer" name="hp">
       		 <option value="3">Originals given to customer</option>
              <option value="2">Work Given to agent</option>
              <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Bill<span class="requiredField">* </span> :</td>
           
           
        <td>
             <select class="customer" name="bill">
              <option value="3">Originals given to customer</option>
              <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Key<span class="requiredField">* </span> :</td>
           
           
        <td>
             <select class="customer" name="key">
                 <option value="3">All given to customer</option>
                 <option value="1">With us</option>
       		<option value="0" selected>Not Added</option>
            <option value="4">Not Applicable</option>
        </select>
        </td>
 </tr>
  <tr>
<td class="firstColumnStyling">
Document Remarks : 
</td>

<td>
<textarea id="document_remarks" name="remarks"></textarea>

</td>
</tr>
</table>

<table style="margin-top:0px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Proof" id="addVehicleProofBtn"/></td>
</tr>     
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Vehicle Details"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>

<script type="text/javascript">

function changeDocsToogle()
{
	var work_given_date = document.getElementById('work_given_date').value;
	var work_completion_date = document.getElementById('work_completion_date').value;
	
	if(work_completion_date!="" && work_completion_date!=null)
	{
		$('.customer').val("1");
	}
	else if(work_given_date!="" && work_given_date!=null)
	{
		$('.agent').val("2");
	}
	
	
}

</script>