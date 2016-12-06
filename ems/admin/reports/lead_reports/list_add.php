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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['cLeadReport']['from_date']) && validateForNull($_SESSION['cLeadReport']['from_date']))
		 { echo $_SESSION['cLeadReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['cLeadReport']['to_date']) && validateForNull($_SESSION['cLeadReport']['to_date']))
		 { echo $_SESSION['cLeadReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td> Product : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="product[]">
       
       <?php
	   $categories = listCategories();
	
	   foreach($categories as $category)
	   {
		  $category_id = $category['cat_id'];
		  $category_name = $category['cat_name']; 
	   
	   ?>
                        
                        <optgroup label=<?php echo $category_name; ?>>
                        
                        <?php
						 $subCategories = getSubCategoryByCategory($category_id);
						 
						 foreach($subCategories as $subCategory)
						 {
							 $sub_cat_name = $subCategory['sub_cat_name'];
							 $sub_cat_id = $subCategory['sub_cat_id'];
						?>
                            <option value="<?php echo $sub_cat_id?>" <?php if(isset($_SESSION['cLeadReport']['product']) && is_array($_SESSION['cLeadReport']['product']))
		 { if(in_array($sub_cat_id, $_SESSION['cLeadReport']['product'])) { ?> selected="selected" <?php } }?>> <?php echo $sub_cat_name?> </option>
                            
                          <?php
						 }
						  ?> 
                        </optgroup>
<?php
	 }
?>
                    
		    </select>
    </td>
   
 </tr>
 

<tr>
<td> Lead status : </td>
				<td>
                	<table>
                    <tr>
                    <td width="0px"><input type="checkbox" name="leadStatus[]" value="0" id="new_status" <?php if(isset($_SESSION['cLeadReport']['leadStatus']) && is_array($_SESSION['cLeadReport']['leadStatus']))
		 { if(in_array(0,$_SESSION['cLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td>
                    <td><label for="new_status">New</label></td>
                    </tr>
				 <tr> 
                <td><input type="checkbox" name="leadStatus[]" value="3" id="ongoing" <?php if(isset($_SESSION['cLeadReport']['leadStatus']) && is_array($_SESSION['cLeadReport']['leadStatus']))
		 { if(in_array(3,$_SESSION['cLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td><td><label for="ongoing"> Ongoing </label></td></tr>
                <tr><td><input type="checkbox" name="leadStatus[]" value="1" id="converted" <?php if(isset($_SESSION['cLeadReport']['leadStatus']) && is_array($_SESSION['cLeadReport']['leadStatus']))
		 { if(in_array(1,$_SESSION['cLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td><td> <label for="converted">Converted </label></td> </tr>
               <tr><td> <input type="checkbox" name="leadStatus[]" value="2" id="not_converted" <?php if(isset($_SESSION['cLeadReport']['leadStatus']) && is_array($_SESSION['cLeadReport']['leadStatus']))
		 { if(in_array(2,$_SESSION['cLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td><td> <label for="not_converted">Not Converted </label></td>	</tr>
               </table>
              
                </td>
</tr>

<tr>
<td> Minimum Amount : </td>
				<td>
				<input type="text" name="min_amount" id="txtName" value="<?php if(isset($_SESSION['cLeadReport']['min_amount']) && validateForNull($_SESSION['cLeadReport']['min_amount']))
		 { echo $_SESSION['cLeadReport']['min_amount'];} ?>" />	
                </td>
</tr>

<tr>
<td> Maximum Amount : </td>
				<td>
				<input type="text" name="max_amount" id="txtName" value="<?php if(isset($_SESSION['cLeadReport']['max_amount']) && validateForNull($_SESSION['cLeadReport']['max_amount']))
		 { echo $_SESSION['cLeadReport']['max_amount'];} ?>"/>	
                </td>
</tr>


<tr>
<td> Select User : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="user_id[]">
       
       <?php
	                 $adminUsers = listAdminUsers();
						 
						 foreach($adminUsers as $adminUser)
						 {
							 
						?>
                            <option value="<?php echo $adminUser['admin_id'] ?>" <?php if(isset($_SESSION['cLeadReport']['user_id']) && is_array($_SESSION['cLeadReport']['user_id']))
		 { if(in_array($adminUser['admin_id'], $_SESSION['cLeadReport']['user_id'])) { ?> selected="selected" <?php } }?>><?php echo $adminUser['admin_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
</tr>
 

<tr>
<td> Enquiry Type : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="customer_type_id[]">
       
       <?php
	                 $customerTypes = listCustomerTypes();
						 
						foreach($customerTypes as $customerType)
                              {
							 
						?>
                            <option value="<?php echo $customerType['customer_type_id'] ?>" <?php if(isset($_SESSION['cLeadReport']['customer_type_id']) && is_array($_SESSION['cLeadReport']['customer_type_id']))
		 { if(in_array($customerType['customer_type_id'], $_SESSION['cLeadReport']['customer_type_id'])) { ?> selected="selected" <?php } }?>><?php echo $customerType['customer_type'] ?></option>
                            
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

<h4 class="headingAlignment"> Lead List</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Enquiry Date</th>
            <th class="heading">Follow Up Date</th>
            <th class="heading">Lead Status</th>
            <th class="heading">Customer Name</th>
            <th class="heading">Product</th>
            <th class="heading">Amount</th>
            <th class="heading">Phone No.</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	
		if(isset($_SESSION['cLeadReport']['leads_array']) && is_array($_SESSION['cLeadReport']['leads_array']))
		{
			$leads=$_SESSION['cLeadReport']['leads_array'];
		$i=0;
		foreach($leads as $lead)
		{
			$enquiry_form_id = $lead['enquiry_form_id'];
			$latestFollowUpDate = getLatestFollowUpDateByEnquiryId($enquiry_form_id);
			
			$customerDetails = getCustomerByEnquiryId($enquiry_form_id);
			$customer_id = $customerDetails['customer_id'];
			
			$contactNumbers=getCustomerContactNo($customer_id);
			
			$enquiryDetails = getEnquiryById($enquiry_form_id);
			$total_mrp = $enquiryDetails['total_mrp'];
			$isBoughtVariable = $enquiryDetails['is_bought'];
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($lead['date_added']))?></span>
            </td>
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($latestFollowUpDate))?></span>
            
            
            </td>
            
            <td><span  class="editLocationName">
			<?php 
			if($isBoughtVariable==0)
			{
			 echo "New Enquiry";	
			}
			else if($isBoughtVariable==1)
			{
			 echo "Successful";	
			}
			else if($isBoughtVariable==2)
			{
			 echo "Unsuccessful";	
			}
			else if($isBoughtVariable==3)
			{
			 echo "On Going";	
			}
			?>
            </span>
            </td>
            
             <td><span  class="editLocationName"><?php echo $customerDetails['customer_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			$subCategory = getSubCatFromEnquiryId($enquiry_form_id);
			foreach($subCategory as $sc)
			{
            $sub_cat_id=$sc['sub_cat_id'];
            $subCatNameArray = getsubCategoryById($sub_cat_id);
            $subCatName = $subCatNameArray['sub_cat_name'];
			echo $subCatName." <br/>";
			}
			?>
            </span>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php echo $total_mrp; ?>
            </span>
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
