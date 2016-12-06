<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

if(!isset($_GET['access']) && $_GET['access']="approved")
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
$file_id=$_GET['id'];
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
		
	$vehicle=getVehicleDetailsByFileId($file_id);
	$vehicle_docs = getVehicleDocsForVehicleId($vehicle['vehicle_id']);
	$rto_agent_work=getRtoAgentWork($vehicle_docs['rto_agent_id'],$vehicle['model_id']);
	$rto_work_array_for_vehicle=getRtoWorkIdArrayForVehicleId($vehicle['vehicle_id']);
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Agent Work Details</h4>

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
<form id="addLocForm" onsubmit="return submitOurVehicle();" action="<?php echo $_SERVER['PHP_SELF'].'?action=editVehicle'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurVehicle()">

<input name="lid" value="<?php echo $vehicle['vehicle_id']; ?>" type="hidden" />
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<input id="vehicle_model" name="vehicle_model" value="<?php echo $vehicle['model_id']; ?>" type="hidden" />


<table style="margin-top:0px;margin-bottom:10px;" class="insertTableStyling no_print">

<tr>
<td width="220px">RTO Agent<span class="requiredField">* </span> : </td>
				<td>
					<select id="rto_agent" name="rto_agent" onchange="createDropDownRtoAgentWork()">
                        <option value="-1" selected >--Please Select Agent--</option>
                        <?php
                            $companies = listRtoAgents();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['rto_agent_id'] ?>" <?php if($super['rto_agent_id']==$vehicle_docs['rto_agent_id']) { ?> selected <?php } ?>><?php echo $super['rto_agent_name'] ?></option					>
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
                    		<?php foreach($rto_agent_work as $rtw) { ?>
                            <option value="<?php echo $rtw['rto_work_id'] ?>" <?php if(in_array($rtw['rto_work_id'],$rto_work_array_for_vehicle)) { ?> selected="selected" <?php } ?> ><?php echo $rtw['rto_work_name']." - ".$rtw['rate']." Rs"; ?></option>
                            <?php  } ?>
                            </select> 
                            </td>
</tr>
<?php } ?>

<tr>
<td class="firstColumnStyling">
Work Given Date<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" onChange="changeDocsToogle()"  placeholder="Click to select Date!" name="work_given_date" id="work_given_date" class="datepicker3"  value="<?php $work_given_date = date('d/m/Y',strtotime($vehicle_docs['work_given_date'])); if($work_given_date!="01/01/1970") echo $work_given_date; ?>"/><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Completion Date<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" onChange="changeDocsToogle()" placeholder="Click to select Date!" id="work_completion_date" name="work_completion_date" class="datepicker3"  value="<?php  $completion_date=date('d/m/Y',strtotime($vehicle_docs['work_completion_date'])); if($completion_date!="01/01/1970") echo $completion_date; ?>"/><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>
<?php if($completion_date!="01/01/1970") { ?>
</table>
<h4 class="headingAlignment">Customer Given Details</h4>
<table class="insertTableStyling no_print" >
<tr>
<td  width="220px" class="firstColumnStyling">
Customer Given Date<span class="requiredField">* </span> : 
</td>

<td>
<input type="text"  onChange="changeDocsToogle()" placeholder="Click to select Date!" id="customer_given_date" name="customer_given_date" class="datepicker3"  value="<?php $customer_given_date= date('d/m/Y',strtotime($vehicle_docs['customer_given_date'])); if($customer_given_date!="01/01/1970") echo $customer_given_date; ?>"/><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>
<?php } ?>
<?php if($customer_given_date!="01/01/1970" && isset($customer_given_date)) { ?>
<tr>
<td class="firstColumnStyling">
Customer Received Date<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" onChange="changeDocsToogle()" placeholder="Click to select Date!" id="customer_received_date" name="customer_received_date" class="datepicker3"  value="<?php  $completion_date=date('d/m/Y',strtotime($vehicle_docs['customer_received_date'])); if($completion_date!="01/01/1970") echo $completion_date; ?>"/><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>
<?php } ?>
</table>

<h4 class="headingAlignment">Vehicle Documents With us</h4>
<table class="insertTableStyling no_print">
<tr>
       <td width="220px;">R.C Book<span class="requiredField">* </span> :</td>
           
           
        <td>
         <select class="customer agent" name="rto">
         <option value="3" <?php if($vehicle_docs['rto_papers']==3) { ?> selected="selected" <?php } ?>>Originals given to customer</option>
         <option value="2" <?php if($vehicle_docs['rto_papers']==2) { ?> selected="selected" <?php } ?>>Work Given to agent</option>
          <option value="1" <?php if($vehicle_docs['rto_papers']==1) { ?> selected="selected" <?php } ?>>With us</option>
            
       		<option value="0" <?php if($vehicle_docs['rto_papers']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        
            <option value="4" <?php if($vehicle_docs['rto_papers']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Passing<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="customer agent" name="passing">
               <option value="3" <?php if($vehicle_docs['passing']==3) { ?> selected="selected" <?php } ?>>Originals given to customer</option>
               <option value="2" <?php if($vehicle_docs['passing']==2) { ?> selected="selected" <?php } ?>>Work Given to agent</option>
           <option value="1" <?php if($vehicle_docs['passing']==1) { ?> selected="selected" <?php } ?>>With us</option>
            
       		<option value="0" <?php if($vehicle_docs['passing']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        	
            <option value="4" <?php if($vehicle_docs['passing']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Permit<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="customer agent" name="permit">
               <option value="3" <?php if($vehicle_docs['permit']==3) { ?> selected="selected" <?php } ?>>Originals given to customer</option>
                <option value="2" <?php if($vehicle_docs['permit']==2) { ?> selected="selected" <?php } ?>>Work Given to agent</option>
           <option value="1" <?php if($vehicle_docs['permit']==1) { ?> selected="selected" <?php } ?>>With us</option>
           
       		<option value="0" <?php if($vehicle_docs['permit']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        	
            <option value="4" <?php if($vehicle_docs['permit']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Insurance<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="customer agent" name="insurance">
              <option value="3" <?php if($vehicle_docs['insurance']==3) { ?> selected="selected" <?php } ?>>Originals given to customer</option>
               <option value="2" <?php if($vehicle_docs['insurance']==2) { ?> selected="selected" <?php } ?>>Work Given to agent</option>
            <option value="1" <?php if($vehicle_docs['insurance']==1) { ?> selected="selected" <?php } ?>>With us</option>
           
       		<option value="0" <?php if($vehicle_docs['insurance']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        	
            <option value="4" <?php if($vehicle_docs['insurance']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
 
  <tr>
       <td>HP<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="customer agent" name="hp">
              <option value="3" <?php if($vehicle_docs['hp']==3) { ?> selected="selected" <?php } ?>>Originals given to customer</option>
               <option value="2" <?php if($vehicle_docs['hp']==2) { ?> selected="selected" <?php } ?>>Work Given to agent</option>
            <option value="1" <?php if($vehicle_docs['hp']==1) { ?> selected="selected" <?php } ?>>With us</option>
           
       		<option value="0" <?php if($vehicle_docs['hp']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        	
            <option value="4" <?php if($vehicle_docs['hp']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Bill<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="customer" name="bill">
                <option value="3" <?php if($vehicle_docs['bill']==3) { ?> selected="selected" <?php } ?>>Original given to customer</option>
                <option value="1" <?php if($vehicle_docs['bill']==1) { ?> selected="selected" <?php } ?>>With us</option>
           
       		<option value="0" <?php if($vehicle_docs['bill']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        	
          
            <option value="4" <?php if($vehicle_docs['bill']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
 <tr>
       <td>Key<span class="requiredField">* </span> :</td>
           
           
        <td>
              <select class="customer" name="key">
               <option value="3" <?php if($vehicle_docs['vehicle_key']==3) { ?> selected="selected" <?php } ?>>All given to customer</option>
               <option value="1" <?php if($vehicle_docs['vehicle_key']==1) { ?> selected="selected" <?php } ?>>With us</option>
           
       		<option value="0" <?php if($vehicle_docs['vehicle_key']==0) { ?> selected="selected" <?php } ?>>Not Added</option>
        	
            <option value="4" <?php if($vehicle_docs['vehicle_key']==4) { ?> selected="selected" <?php } ?>>Not Applicable</option>
        </select>
        </td>
 </tr>
  <tr>
<td class="firstColumnStyling">
Document Remarks : 
</td>

<td>
<textarea id="document_remarks" name="remarks"><?php echo $vehicle_docs['remarks']; ?></textarea>

</td>
</tr>
</table>



<table>
<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Edit Documents Details"  id="disableSubmit" class="btn btn-warning">
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

	if(document.getElementById('work_completion_date'))
	var work_completion_date = document.getElementById('work_completion_date').value;
	else
	var work_completion_date=null;
	
	if(document.getElementById('customer_given_date'))
	var customer_given_date = document.getElementById('customer_given_date').value;
	else
	var customer_given_date=null;
	
	if(document.getElementById('customer_received_date'))
	var customer_received_date = document.getElementById('customer_received_date').value;
	else
	var customer_received_date=null;
	
	
	
	if(customer_received_date!="" && customer_received_date!=null)
	{
		$('.customer').val("1");
	}
	else if(customer_given_date!="" && customer_given_date!=null)
	{
		$('.customer').val("3");
	}
	else if(work_completion_date!="" && work_completion_date!=null)
	{
		$('.customer').val("1");
	}
	else if(work_given_date!="" && work_given_date!=null)
	{
		$('.agent').val("2");
	}
	
	
	
}

</script>