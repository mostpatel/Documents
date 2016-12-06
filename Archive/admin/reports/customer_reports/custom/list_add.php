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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['cReport']['from_date']) && validateForNull($_SESSION['cReport']['from_date']))
		 { echo $_SESSION['cReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To DOB Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['cReport']['to_date']) && validateForNull($_SESSION['cReport']['to_date']))
		 { echo $_SESSION['cReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
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
  <?php if(isset($_SESSION['cReport']['city_id']) && is_array($_SESSION['cReport']['city_id']))
		 { if(in_array($city['city_id'], $_SESSION['cReport']['city_id'])) { ?> selected="selected" <?php } }?>><?php echo $city['city_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
</tr>

<tr>
<td> Data From : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="data_from_id[]">
       
       <?php
	                 $dataFroms = listDataFrom();
						 
						 foreach($dataFroms as $dataFrom)
						 {
							 
						?>
  <option value="<?php echo $dataFrom['data_from_id'] ?>" 
  <?php if(isset($_SESSION['cReport']['data_from_id']) && is_array($_SESSION['cReport']['data_from_id']))
		 { if(in_array($dataFrom['data_from_id'], $_SESSION['cReport']['data_from_id'])) { ?> selected="selected" <?php } }?>><?php echo $dataFrom['data_from'] ?></option>
                            
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
<input type="text" id="datepicker3" size="12" autocomplete="off"  name="from_date_added" class="datepicker1 datepick" value="<?php if(isset($_SESSION['cReport']['from_date_added']) && validateForNull($_SESSION['cReport']['from_date_added']))
		 { echo $_SESSION['cReport']['from_date_added'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date Added : 
</td>

<td>
<input type="text" id="datepicker4" size="12" autocomplete="off"  name="to_date_added" class="datepicker2 datepick" value="<?php if(isset($_SESSION['cReport']['to_date_added']) && validateForNull($_SESSION['cReport']['to_date_added']))
		 { echo $_SESSION['cReport']['to_date_added'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
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

<h4 class="headingAlignment"> Customer List</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>	
        	<th class="heading no_sort"></th>
        	<th class="heading no_sort">No</th>
            <th class="heading">Name</th>
            <th class="heading">Phone</th>
            <th class="heading">Email</th>
            <th class="heading">Address</th>
            <th class="heading">City</th>
            <th class="heading">Data From</th>
            <!--<th class="heading">DOB</th>-->
            <th class="heading">By</th>
            <th class="heading">Date Added</th>
           
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	
		if(isset($_SESSION['cReport']['customer_array']) && is_array($_SESSION['cReport']['customer_array']))
		{
			$customers=$_SESSION['cReport']['customer_array'];
		$i=0;
		foreach($customers as $customer)
		{
			
			
			$customer_id = $customer['customer_id'];
		
			$contactNumbers=getCustomerContactNo($customer_id);
			
			$dob = $customer['customer_dob'];
			
			$customer_name =  $customer['customer_name'];
			
			
		 ?>
          <tr class="resultRow">
          	<td></td>
        	<td>
			     
			<?php echo ++$i; ?>
            </td>
            
            
            <td>
          
            <?php echo $customer_name ?>
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
                      			echo $c[0].", <br> ";				
                              } ?>
            
            </span>
            </td>
            
             <td><span  class="editLocationName"><?php echo $customer['customer_email'];?></span>
            </td>
            
            <td><span  class="editLocationName"><?php echo $customer['customer_address'];?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php 
			$cityId = $customer['city_id'];
			 if($cityId==NULL)
								   echo "NOT AVAILBALE";
								   else
								   {
								   $cityDetails = getCityByID($cityId);
								   echo $cityDetails['city_name'];
								   }
			?>
            </span>
            </td>
            
            
            <td><span  class="editLocationName">
			<?php 
			$dataFromId = $customer['data_from_id'];
			 if($dataFromId==NULL)
								   echo "NOT AVAILBALE";
								   else
								   {
								   $dataFromDetails = getDataFromById($dataFromId);
								   echo $dataFromDetails['data_from'];
								   }
			?>
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
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>
