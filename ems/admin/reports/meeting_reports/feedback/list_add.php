<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Select Appropriate Filters</h4>
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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['fCustomerReport']['from_date']) && validateForNull($_SESSION['fCustomerReport']['from_date']))
		 { echo $_SESSION['fCustomerReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['fCustomerReport']['to_date']) && validateForNull($_SESSION['fCustomerReport']['to_date']))
		 { echo $_SESSION['fCustomerReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
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

<hr class="firstTableFinishing" />


<form action="index.php?action=sendDirectorsms" method="post">
      
   
<table style="margin-bottom:35px;">
<tr>
<td></td>
<td>
<input type="submit" value="Send Director SMS" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();" class="btn btn-warning">
</td>
</tr>
</table>
   
      
   
<h4 class="headingAlignment"> Customer List</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    
   
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading no_print no_sort"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
        	<th class="heading no_sort">No</th>
            <th class="heading">Name</th>
            <th class="heading">Phone</th>
            <th class="heading">Visit Date</th>
            <th class="heading">Handled By</th>
           
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	
		if(isset($_SESSION['fCustomerReport']['customer_array']) && is_array($_SESSION['fCustomerReport']['customer_array']))
		{
			$customerData=$_SESSION['fCustomerReport']['customer_array'];
		$i=0;
		foreach($customerData as $customer)
		{
			
			$enquiry_form_id = $customer['enquiry_form_id'];
			$customer_id = $customer['customer_id'];
			$customer_name = $customer['customer_name'];
			$contact_no = $customer['contact_no'];
			$products = $customer['products'];
			$visit_date = $customer['visit_date'];
			$admin_name = $customer['admin_name'];
			
				
		 ?>
          <tr class="resultRow">
          	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $customer_id; ?>" /></td>
        	<td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName"><?php echo $customer_name; ?></span>
            </td>
            
            <td>
            <span  class="editLocationName"><?php echo $contact_no; ?></span>
            </td>
            
            <td>
            <span  class="editLocationName"><?php echo date('d/m/Y', strtotime($visit_date)); ?></span>
            </td>
            
            
            <td>
            <span  class="editLocationName"><?php echo $admin_name; ?></span>
            </td>
            
            <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id;?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
          </tr>
         <?php }} ?>
         </tbody>
    </table>
    </form>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>
<script>
$('#selectAllTR').change(function(e) {
  
	if($("#selectAllTR").prop("checked")==true)
	{
		$('#adminContentTable .selectTR').prop('checked','checked');
		}
	else
	{
		$('#adminContentTable .selectTR').prop('checked',false);
		
		}
});
</script>