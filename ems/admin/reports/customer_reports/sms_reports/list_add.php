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
From DOB Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['cSmsReport']['from_date']) && validateForNull($_SESSION['cSmsReport']['from_date']))
		 { echo $_SESSION['cSmsReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To DOB Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['cSmsReport']['to_date']) && validateForNull($_SESSION['cSmsReport']['to_date']))
		 { echo $_SESSION['cSmsReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>







<tr>
<td> Select City : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="city_id[]">
       
       <?php
	                 $cities = listCities();
						 
						 foreach($cities as $city)
						 {
							 
						?>
  <option value="<?php echo $city['city_id'] ?>" 
  <?php if(isset($_SESSION['cSmsReport']['city_id']) && is_array($_SESSION['cSmsReport']['city_id']))
		 { if(in_array($city['city_id'], $_SESSION['cSmsReport']['city_id'])) { ?> selected="selected" <?php } }?>><?php echo $city['city_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
</tr>


<tr>

<td class="firstColumnStyling">
From Date Added : 
</td>

<td>
<input type="text" id="datepicker3" size="12" autocomplete="off"  name="from_date_added" class="datepicker1 datepick" value="<?php if(isset($_SESSION['cSmsReport']['from_date_added']) && validateForNull($_SESSION['cSmsReport']['from_date_added']))
		 { echo $_SESSION['cSmsReport']['from_date_added'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date Added : 
</td>

<td>
<input type="text" id="datepicker4" size="12" autocomplete="off"  name="to_date_added" class="datepicker2 datepick" value="<?php if(isset($_SESSION['cSmsReport']['to_date_added']) && validateForNull($_SESSION['cSmsReport']['to_date_added']))
		 { echo $_SESSION['cSmsReport']['to_date_added'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td> Customer Group : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="customer_group_id[]">
       
       <?php
	                 $customerGroups = listCustomerGroups();
						 
						foreach($customerGroups as $customerGroup)
                              {
							 
						?>
                            <option value="<?php echo $customerGroup['customer_group_id'] ?>" <?php if(isset($_SESSION['cLeadReport']['customer_group_id']) && is_array($_SESSION['cLeadReport']['customer_group_id']))
		 { if(in_array($customerGroup['customer_group_id'], $_SESSION['cLeadReport']['customer_group_id'])) { ?> selected="selected" <?php } }?>><?php echo $customerGroup['customer_group_name'] ?></option>
                            
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

<hr class="firstTableFinishing" />


<form action="index.php?action=sendsms" method="post">
      <table id="insertCustomerTable" class="insertTableStyling no_print">
      <tr>
<td> 
SMS : 
</td>

<td>
<textarea id="smsContent" class="smsContent" name="smsContent"  cols="10" rows="5"></textarea>
</td>
</tr>
    
     
   </table>
   
<table style="margin-bottom:35px;">
<tr>
<td width="70"></td>
<td>
<input type="submit" value="Send" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();" class="btn btn-warning">
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
            
           
            <!--<th class="heading">DOB</th>-->
            <th class="heading">By</th>
            <th class="heading">Date Added</th>
           
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	
		if(isset($_SESSION['cSmsReport']['customer_array']) && is_array($_SESSION['cSmsReport']['customer_array']))
		{
			$customers=$_SESSION['cSmsReport']['customer_array'];
			
		$i=0;
		foreach($customers as $customer)
		{
			
			$customer_id = $customer['customer_id'];
			$contactNumbers=getCustomerContactNo($customer_id);
			$dob = $customer['customer_dob'];	
		 ?>
          <tr class="resultRow">
          	 <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $customer_id; ?>" /></td>
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo $customer['customer_name'];?></span>
            </td>
            
            
  
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                            for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." <br> ";				
                              } ?>
            
            </span>
            </td>
            
             
            
            
            
            
            
            <!--<td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($dob))?></span>
            </td>-->
            
            <td>
            <span  class="editLocationName">
			<?php 
			$admin_id = $customer['created_by'];
			
			$adminDetails =  getAdminUserByID($admin_id);
			echo $adminDetails['admin_name'];
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($customer['date_added']))?></span>
            </td>
            
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer//index.php?view=customerDetails&id=".$customer_id;?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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