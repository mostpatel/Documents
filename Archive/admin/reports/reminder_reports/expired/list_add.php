<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Expired Reminder Reports</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF']."?action=add"; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
From Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['eLeadReminderReport']['from_date']) && validateForNull($_SESSION['eLeadReminderReport']['from_date']))
		 { echo $_SESSION['eLeadReminderReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" value="<?php echo date('d/m/Y', strtotime(getTodaysDate())); ?>" readonly="readonly" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>

</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Generate" class="btn btn-warning">
</td>
</tr>

</table>
</form>

<?php
	
		if(isset($_SESSION['eLeadReminderReport']['leadsReminder_array']) && is_array($_SESSION['eLeadReminderReport']['leadsReminder_array']))
		{
			$leadsReminder=$_SESSION['eLeadReminderReport']['leadsReminder_array'];
		$i=0;
?>

<div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reminder Date</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Remarks</label> 
         <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Status</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Customer Name</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6"><?php echo PRODUCT_GLOBAL_VAR ?></label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Phone No</label> 
        <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Handled By</label> 
          
    </div>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> Daliy Reminder List</h4>

<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>

	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
           <th class="heading">Reminder Date</th>
            <th class="heading">Remarks</th>
            <th class="heading">Status</th>
          <th class="heading">Customer Name</th>
            <th class="heading"><?php echo PRODUCT_GLOBAL_VAR ?></th>
             
            
            <th class="heading">Phone No.</th>
            <th class="heading">Handled By</th>
             
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($leadsReminder as $lead)
		{
			$enquiry_form_id = $lead['enquiry_form_id'];
			
			$isBoughtVariable = $lead['is_bought'];
			
			$reminder_date = $lead['date'];
			
			
			$handled_by_id = $lead['current_lead_holder'];
			$handled_by = getAdminUserNameByID($handled_by_id);
			
			
		 ?>
          <tr class="resultRow">
          
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
          
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName">
			
            <?php 
			 
			 $reminder_date = date('d/m/Y',strtotime($reminder_date));
			 echo $reminder_date;
			 
			 ?>
            </span>
            </td>
            
             <td><span  class="editLocationName"><?php echo $lead['remarks']; ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php 
			
			 $lead_status = $lead['remainder_status']; 
			 if($lead_status==0)
			 {
				echo "Not Done";
			 }
			 else if($lead_status==1)
			 {
				 echo "Done";
			 }
			
			?>
            </span>
            </td>
           
            
             <td><span  class="editLocationName"><?php echo $lead['customer_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $lead['sub_cat_name'];
			?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $lead['contact_no'];  ?>
            
            </span>
            </td>
            
            
            <td><?php echo $handled_by; ?></td>
            
            
           
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."/admin/customer/index.php?view=addRemainder&id=".$enquiry_form_id?>" target="_blank">
            
             <button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
      <?php }
		 ?>
            </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
         <?php 
		 }?>
</div>
<div class="clearfix"></div>