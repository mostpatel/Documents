<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Select Dates to generate</h4>
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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['finEnquiries']['from_date']) && validateForNull($_SESSION['finEnquiries']['from_date']))
		 { echo $_SESSION['finEnquiries']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['finEnquiries']['to_date']) && validateForNull($_SESSION['finEnquiries']['to_date']))
		 { echo $_SESSION['finEnquiries']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>

</tr>

<tr>
<td> Status : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="enquiry_group_id[]">
       
       <?php
	                 $enquiryGroups = listStatus();
						 
						foreach($enquiryGroups as $enquiryGroup)
                              {
							 
						?>
                            <option value="<?php echo $enquiryGroup['enquiry_group_id'] ?>" <?php if(isset($_SESSION['cLeadReport']['enquiry_group_id']) && is_array($_SESSION['cLeadReport']['enquiry_group_id']))
		 { if(in_array($enquiryGroup['enquiry_group_id'], $_SESSION['cLeadReport']['enquiry_group_id'])) { ?> selected="selected" <?php } }?>><?php echo $enquiryGroup['enquiry_group_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
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
	
		if(isset($_SESSION['finEnqReport']['finEnquiries_array']) && is_array($_SESSION['finEnqReport']['finEnquiries_array']))
		{
			$finEnquiries=$_SESSION['finEnqReport']['finEnquiries_array'];
		$i=0;
?>



<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> Enquiry List</h4>

<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>

	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
           <th class="heading">Enquiry Date</th>
         
          <th class="heading">Customer Name</th>
               <th class="heading">Phone No.</th>
            <th class="heading">Status</th>
            <th class="heading">Address</th>
            <th class="heading">Note</th>
            
              
           
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($finEnquiries as $lead)
		{
			$enquiry_date = $lead['enquiry_date'];
			
			$name = $lead['name'];
			
			$phone_primary = $lead['phone_primary']. " , ".$lead['phone_secondary'];
			
			$status_id = $lead['status_id'];
			
			$address = $lead['address'];
			
			$note = $lead['note'];
			
			
			
			
		 ?>
          
          
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
          
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName">
			
            <?php 
			 
			 echo date('d/m/Y', strtotime($enquiry_date));
			 
			 ?>
            </span>
            </td>
            
             <td><span  class="editLocationName">
			 <?php 
			 
			 echo $name; ?></span>
             </td>
            
           
            
             <td><span  class="editLocationName"><?php echo $phone_primary; ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			
			echo getStatusById($status_id);
			
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName">
			 <?php 
			 
			 echo $address; ?></span>
             </td>
             
             <td><span  class="editLocationName">
			 <?php 
			 
			 echo $note; ?></span>
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