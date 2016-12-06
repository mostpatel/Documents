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

<table  class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
From Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" value="<?php echo date('d/m/Y', strtotime(getTodaysDate())); ?>" readonly="readonly" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
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
<td> Select Super Category : </td>
<td>
<select id="super_cat_list" class="selectpic selectpic1 show-tick form-control" multiple data-live-search="true" name="super_cat_id[]" onchange="loadAttrType()"  multiple="multiple">
       
       <?php
	                 $superCats = listSuperCategories();
						 
						 foreach($superCats as $superCat)
						 {
							 
						?>
                      <option value="<?php echo $superCat['super_cat_id'] ?>"><?php echo $superCat['super_cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>


    </td>
    
    
    
</tr>

<tr>
<td> Select Category : </td>
<td>
<select id="category_list" class="selectpic selectpic2 show-tick form-control" multiple data-live-search="true" name="cat_id[]" onchange="loadAttrType()" multiple="multiple">
       
       <?php
	                 $Cats = listCategories();
						 
						 foreach($Cats as $Cat)
						 {
							 
						?>
                      <option value="<?php echo $Cat['cat_id'] ?>"><?php echo $Cat['cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    
</tr>

<tr id="productTr">
<td> <?php echo PRODUCT_GLOBAL_VAR ?> : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="product[]" onchange="loadAttrType()"  multiple="multiple">
       
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
                            <option value="<?php echo $sub_cat_id?>" <?php if(isset($_SESSION['dLeadReport']['product']) && is_array($_SESSION['dLeadReport']['product']))
		 { if(in_array($sub_cat_id, $_SESSION['dLeadReport']['product'])) { ?> selected="selected" <?php } }?>> <?php echo $sub_cat_name?> </option>
                            
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
 

<tr id="insertBeforeTr">
<td> Lead status : </td>
				<td>
                	<table>
                    <tr>
                    <td width="0px"><input type="checkbox" name="leadStatus[]" value="0" id="new_status" <?php if(isset($_SESSION['dLeadReport']['leadStatus']) && is_array($_SESSION['dLeadReport']['leadStatus']))
		 { if(in_array(0,$_SESSION['dLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td>
                    <td><label for="new_status">New</label></td>
                    </tr>
				 <tr> 
                <td><input type="checkbox" name="leadStatus[]" value="3" id="ongoing" <?php if(isset($_SESSION['dLeadReport']['leadStatus']) && is_array($_SESSION['dLeadReport']['leadStatus']))
		 { if(in_array(3,$_SESSION['dLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td><td><label for="ongoing"> Ongoing </label></td></tr>
                <tr><td><input type="checkbox" name="leadStatus[]" value="1" id="converted" <?php if(isset($_SESSION['dLeadReport']['leadStatus']) && is_array($_SESSION['dLeadReport']['leadStatus']))
		 { if(in_array(1,$_SESSION['dLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td><td> <label for="converted">Converted </label></td> </tr>
               <tr><td> <input type="checkbox" name="leadStatus[]" value="2" id="not_converted" <?php if(isset($_SESSION['dLeadReport']['leadStatus']) && is_array($_SESSION['dLeadReport']['leadStatus']))
		 { if(in_array(2,$_SESSION['dLeadReport']['leadStatus'])) { ?> checked="checked" <?php } }?>></td><td> <label for="not_converted">Not Converted </label></td>	</tr>
               </table>
              
                </td>
</tr>

<!--<tr>
<td> Minimum Amount : </td>
				<td>
				<input type="text" name="min_amount" id="txtName" value="<?php if(isset($_SESSION['dLeadReport']['min_amount']) && validateForNull($_SESSION['dLeadReport']['min_amount']))
		 { echo $_SESSION['dLeadReport']['min_amount'];} ?>" />	
                </td>
</tr>

<tr>
<td> Maximum Amount : </td>
				<td>
				<input type="text" name="max_amount" id="txtName" value="<?php if(isset($_SESSION['dLeadReport']['max_amount']) && validateForNull($_SESSION['dLeadReport']['max_amount']))
		 { echo $_SESSION['dLeadReport']['max_amount'];} ?>"/>	
                </td>
</tr>-->


<tr>
<td> Select User : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="user_id[]">
       
       <?php
	                 $adminUsers = listAdminUsers();
						 
						 foreach($adminUsers as $adminUser)
						 {
							 
						?>
                            <option value="<?php echo $adminUser['admin_id'] ?>" <?php if(isset($_SESSION['dLeadReport']['user_id']) && is_array($_SESSION['dLeadReport']['user_id']))
		 { if(in_array($adminUser['admin_id'], $_SESSION['dLeadReport']['user_id'])) { ?> selected="selected" <?php } }?>><?php echo $adminUser['admin_name'] ?></option>
                            
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
                            <option value="<?php echo $customerType['customer_type_id'] ?>" <?php if(isset($_SESSION['dLeadReport']['customer_type_id']) && is_array($_SESSION['dLeadReport']['customer_type_id']))
		 { if(in_array($customerType['customer_type_id'], $_SESSION['dLeadReport']['customer_type_id'])) { ?> selected="selected" <?php } }?>><?php echo $customerType['customer_type'] ?></option>
                            
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
	
		if(isset($_SESSION['dLeadReport']['leads_array']) && is_array($_SESSION['dLeadReport']['leads_array']))
		{
			
			$leads=$_SESSION['dLeadReport']['leads_array'];
			
			
		$i=0; 
?>
     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Enquiry Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Follow Up Date</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Lead Status</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Customer Name</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6"><?php echo PRODUCT_GLOBAL_VAR ?></label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Extra Details</label> 
         <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Amount</label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Phone No</label> 
        <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Handled By</label> 
          
    </div>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> Lead List</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Enquiry Date</th>
            <th class="heading">Follow Up Date</th>
            <th class="heading">Lead Status</th>
            <th class="heading">Customer Name</th>
            <th class="heading"><?php echo PRODUCT_GLOBAL_VAR ?></th>
             <th class="heading">Extra Details</th>
             <?php
			if($show_amount==1)
			{
			?>
            <th class="heading">Amount</th>
            <?php
			}
			?>
            <th class="heading">Phone No.</th>
            <th class="heading">Handled By</th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
        
       <?php
		foreach($leads as $lead)
		{
			
			
			$enquiry_form_id = $lead['enquiry_form_id'];
			
			$isBoughtVariable = $lead['is_bought'];
			
			
			
		 ?>
          <tr class="resultRow <?php if($lead['is_imp']==1){ ?> shantiRow <?php }?>">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($lead['enquiry_date']))?></span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			$follow_Up_date_for_lead = date('d/m/Y',strtotime($lead['next_follow_up_date'])); 
			
			if($follow_Up_date_for_lead== "01/01/1970")
			{
			echo "NA";	
			}
			else
			echo $follow_Up_date_for_lead;
			
			?>
            </span>
            
            
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
			<?php echo $lead['attribute_types_sub_cat_wise']; ?>
            </span>
            </td>
            
            
            <?php
			if($show_amount==1)
			{
			?>
             <td>
            <span  class="editLocationName">
			<?php echo $lead['customer_price']; ?>
            </span>
            </td>
            <?php
			}
			?>
            
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $lead['contact_no'];  ?>
            
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php echo $lead['admin_name']; ?>
            </span>
            </td>
            
            <td class="no_print"> 
             
             <a href="<?php echo WEB_ROOT."admin/customer/follow_up/index.php?id=".$enquiry_form_id?>" target="_blank">
             <input type="button" value="+F" class="btn btn-success" /> 
             </a>
            </td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>" target="_blank"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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
