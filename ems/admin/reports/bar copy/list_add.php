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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['cLeadEfficiencyReport']['from_date']) && validateForNull($_SESSION['cLeadEfficiencyReport']['from_date']))
		 { echo $_SESSION['cLeadEfficiencyReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['cLeadEfficiencyReport']['to_date']) && validateForNull($_SESSION['cLeadEfficiencyReport']['to_date']))
		 { echo $_SESSION['cLeadEfficiencyReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
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
                            <option value="<?php echo $adminUser['admin_id'] ?>" <?php if(isset($_SESSION['cLeadEfficiencyReport']['user_id']) && is_array($_SESSION['cLeadEfficiencyReport']['user_id']))
		 { if(in_array($adminUser['admin_id'], $_SESSION['cLeadEfficiencyReport']['user_id'])) { ?> selected="selected" <?php } }?>><?php echo $adminUser['admin_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
</tr>

<!--<tr>
<td> Team : </td>

<td>
 <select name="stream" onchange="createDropDown('getRelatedTeamMembers.php?catId='+this.value,'admin_id',null)">
<option value="-1" >--Please Select--</option>
<?php
    $teamList = listTeams();
	foreach($teamList as $team)
	  {
	 ?>
     
     <option value="<?php echo $team['team_id'] ?>"><?php echo $team['team_name'] ?></option>
     <?php } ?>
      }
 
    </select> 
    </td>
   </tr>
    
    <tr>
    
  <td> Team Members : </td> 
  <td>
   <select name="admin_id" id="admin_id">
  <option value="-1" >--Please Select--</option>
  
  
   </select>
   </td>
   </tr>
 -->




<tr id="productTr">
<td> <?php echo PRODUCT_GLOBAL_VAR ?> : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="product[]"  multiple="multiple">
       
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
                            <option value="<?php echo $sub_cat_id?>" <?php if(isset($_SESSION['cLeadEfficiencyReport']['product']) && is_array($_SESSION['cLeadEfficiencyReport']['product']))
		 { if(in_array($sub_cat_id, $_SESSION['cLeadEfficiencyReport']['product'])) { ?> selected="selected" <?php } }?>> <?php echo $sub_cat_name?> </option>
                            
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
<td></td>
<td>
<input type="submit" value="Generate" class="btn btn-warning">
</td>
</tr>


</table>
</form>
 <?php
	
		if(isset($_SESSION['cLeadEfficiencyReport']['leads_array']) && is_array($_SESSION['cLeadEfficiencyReport']['leads_array']))
		{
			
			$leadEfficiencies = $_SESSION['cLeadEfficiencyReport']['leads_array'];
			
			
		$i=0; 
?>
     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Total Enquiries</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Successful</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Unsuccessful</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Ongoing</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">New</label> 
     </div>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> Enquiry Efficiency Analysis </h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Total Enquiries</th>
             <th class="heading">New</th>
              <th class="heading">Ongoing</th>
            <th class="heading">Successful</th>
            <th class="heading">Unsuccessful</th>
         </tr>
    </thead>
    <tbody>
        
        <?php
		
        $total = $leadEfficiencies['total_enquiry'];
		$success = $leadEfficiencies['successful_enquiries'];
		$unsuccess = $leadEfficiencies['unsuccessful_enquiries'];
		$ongoing = $leadEfficiencies['ongoing_enquiries'];
		$new = $leadEfficiencies['new_enquiries'];
		
		$success_percentage = round((($success/$total)*100), 2);
		$unsuccess_percentage = round((($unsuccess/$total)*100), 2);
		$ongoing_percentage = round((($ongoing/$total)*100), 2);
		$new_percentage = round((($new/$total)*100), 2);
		
		?>
      
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	
            <td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $total; 
			?>
            </span>
            </td>
            
             <td>
            <span  class="editLocationName">
			<?php 
			echo $new. " "."(".$new_percentage."%".")";  
			?>
            </span>
            </td>
            
             <td>
            <span  class="editLocationName">
			<?php 
			echo $ongoing. " "."(".$ongoing_percentage."%".")"; 
			?>
            </span>
            </td>
            
            
            <td class="shantiRow"><span  class="editLocationName">
			<?php
			echo $success. " ". "(".$success_percentage."%".")";
			?>
            </span>
            </td>

            
            <td class="dangerRow">
            <span  class="editLocationName">
			<?php 
			echo $unsuccess. " "."(".$unsuccess_percentage."%".")";
			?>
            </span>
            </td>
            
            
            
           
            
             
            
            
          
  
        </tr>
      
            </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
         <?php 
		 }?>
      
</div>
<div class="clearfix"></div>
