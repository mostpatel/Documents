<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Select Dates</h4>
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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_POST) && validateForNull($_POST['from_date'])) { echo $_POST['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_POST) && validateForNull($_POST['to_date'])) { echo $_POST['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
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
	
		if(isset($_SESSION['cTourEndDateReport']['tourEndDate_array']) && is_array($_SESSION['cTourEndDateReport']['tourEndDate_array']))
		{
			$purchaseDates=$_SESSION['cTourEndDateReport']['tourEndDate_array'];
		$i=0;
?>

<div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Follow Up Date</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Discussion</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Customer Name</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Product</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Extra Details</label> 
         <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Amount</label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Phone No</label> 
        <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Handled By</label> 
          
    </div>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Tour End Date List</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
           <th class="heading">Tour End Date</th>
          <th class="heading">Customer Name</th>
            <th class="heading">Tour</th>
            <th class="heading">Phone No.</th>
            <th class="heading">Handled By</th>
             
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($purchaseDates as $purchaseDateDetails)
		{
			$enquiry_form_id = $purchaseDateDetails['enquiry_form_id'];
			
			$isBoughtVariable = $purchaseDateDetails['is_bought'];
			
			$tour_end_date = $purchaseDateDetails['tour_ending_date'];
			
			$next_follow_up_date_string = $lead['next_follow_up_date'];
			
			$followUpAdminArray = explode(' # ', $next_follow_up_date_string);
			$next_follow_up_date_string = $followUpAdminArray[0];
			$handled_by = $followUpAdminArray[1];
			$followUpArray = explode(' ^ ', $next_follow_up_date_string);
			
			$follow_up_date = $followUpArray[0];
			$follow_up_details = $followUpArray[1];
			
			
		 ?>
          <tr class="resultRow">
          
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
          
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName">
			
            <?php 
			 
			 $tour_end_date = date('d/m/Y',strtotime($tour_end_date));
			 
			 if($tour_end_date == "01/01/1970")
			 {
				 echo "NA";
			  }
			 else
			 echo $tour_end_date;
			 
			 ?>
            </span>
            </td>
            
             <td><span  class="editLocationName">
			 <?php 
			 
			 echo $purchaseDateDetails['customer_name']; ?></span>
             </td>
            
           
            
             
            
            <td><span  class="editLocationName">
			<?php
			echo $purchaseDateDetails['products'];
			?>
            </span>
            </td>

            
            
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $purchaseDateDetails['contact_no'];  ?>
            
            </span>
            </td>
            
            
            <td><?php echo $purchaseDateDetails['admin_name']; ?></td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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