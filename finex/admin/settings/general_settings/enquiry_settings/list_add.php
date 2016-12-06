<?php

?>
<a href="<?php echo  WEB_ROOT ?>admin/reports/fin_enquiry_reports/custom"><button class="btn btn-success">View / Search Enquries</button></a>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Enquiry</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data">

<?php if(is_numeric($customer_id) && isset($customerDetails['customer_name'])) { ?>
<input type="hidden" name="customer_id" value="<?php echo $customer_id ?>"  />
<?php } ?>


<hr class="firstTableFinishing" />

<table id="insertCustomerTable" class="insertTableStyling no_print">

<tr>

<td width="220px" class="firstColumnStyling">
Enquiry Date<span class="requiredField">* </span> : 
</td>

<td> 
<input type="text" name="enquiry_date" id="enquiry_date" placeholder="Enquiry Date" value="<?php echo date('d/m/Y', strtotime(getTodaysDate())); ?>" />

</td>
</tr>


<tr>

<td width="220px" class="firstColumnStyling">
Customer's Name<span class="requiredField">* </span> : 
</td>

<td> 
<input type="text" name="customer_name"  autofocus="autofocus" id="customer_name" class="customer_name" placeholder="Only Letters"/>

</td>
</tr>

<tr>

<td width="220px" class="firstColumnStyling">
Phone No Primary : 
</td>

<td>
<input type="text" id="phone_no_primary" name="phone_no_primary"  placeholder="Only Digits!"/>
</td>
</tr>

<tr>

<td width="220px" class="firstColumnStyling">
Phone No Secondary : 
</td>

<td>
<input type="text" id="phone_no" name="phone_no_secondary"  placeholder="Only Digits!"/>
</td>
</tr>

<tr>
<td width="220px" class="firstColumnStyling"> 
Address : 
</td>

<td>
<textarea id="address" class="address" name="address"  cols="5" rows="6"></textarea>
</td>
</tr>

<tr>
<td width="220px" class="firstColumnStyling"> Status : </td>
<td>
					<select  name="status_id">
                        <!--<option value="0"> --Please Select-- </option>-->
                        <?php
                            $status = listStatus();
							
                            foreach($status as $s)
                              {
                             ?>
                             
                           <option value="<?php echo $s['status_id'] ?>"><?php echo $s['status'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>


<tr>
<td width="220px" class="firstColumnStyling"> 
Note : 
</td>

<td>
<textarea id="note" class="note" name="note"  cols="5" rows="6"></textarea>
</td>
</tr>

</table>



<table>
<tr>
<td width="250px"></td>
<td>
<input type="submit" value="Add Enquiry" class="btn btn-warning">
<!-- onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();" -->
</td>
</tr>
</table>
</form>

</div>

<div class="clearfix"></div>



<script>

$( "#enquiry_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy'
 });
 </script>


