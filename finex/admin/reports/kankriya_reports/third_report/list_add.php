<?php $payment_should_be_done_after_date = getTodaysDateTimeBeforeMonthsAndDays(3,20);
$payment_should_be_done_before_date = getTodaysDateTimeBeforeMonthsAndDays(2,0); ?>
<div class="jvp"><?php if(isset($_SESSION['cKankriyaFourthReport']['agency_id']) && $_SESSION['cKankriyaFourthReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKankriyaFourthReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Collection Report (Step 4)</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<!--<tr>
<td>From Date (Payment date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['from'])) echo $_SESSION['cKankriyaFourthReport']['from']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (Payment date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['to'])) echo $_SESSION['cKankriyaFourthReport']['to']; ?>" />	
                 </td>
</tr> -->
<input type="hidden" name="start_date" value="<?php echo  date('d/m/Y',strtotime($payment_should_be_done_before_date)); ?>"  />
<input type="hidden" name="end_date" value="<?php echo date('d/m/Y',strtotime($payment_should_be_done_after_date)); ?>" />

<tr>
<td>From Date (EMI date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_emi_date" id="from_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaFourthReport']['from_emi_date'])) echo $_SESSION['cKankriyaFourthReport']['from_emi_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (EMI date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_emi_date" id="to_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaFourthReport']['to_emi_date'])) echo $_SESSION['cKankriyaFourthReport']['to_emi_date']; ?>" />	
                 </td>
</tr>



<tr>
<td>Bucket(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_gt" id="win_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['win_gt'])) echo $_SESSION['cKankriyaFourthReport']['win_gt']; else echo "0.001"; ?>" />	
                 </td>
</tr>

<tr>
<td>Bucket(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_lt" id="win_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['win_lt'])) echo $_SESSION['cKankriyaFourthReport']['win_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_gt" id="emi_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['emi_gt'])) echo $_SESSION['cKankriyaFourthReport']['emi_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_lt" id="emi_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['emi_lt'])) echo $_SESSION['cKankriyaFourthReport']['emi_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_gt" id="balance_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['balance_gt'])) echo $_SESSION['cKankriyaFourthReport']['balance_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_lt" id="balance_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaFourthReport']['balance_lt'])) echo $_SESSION['cKankriyaFourthReport']['balance_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>City : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city"   onchange="createDropDownAreaReports(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cKankriyaFourthReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cKankriyaFourthReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area : </td>
				<td>
					<select name="area[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						  if(isset($_SESSION['cKankriyaFourthReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cKankriyaFourthReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cKankriyaFourthReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cKankriyaFourthReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
                             <?php } 
						  }
							 ?>
                    </select>
                            </td>
</tr>

<tr>
<td width="220px">Agency Name : </td>
				<td>
					<select id="agency_id" name="agency_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $agencies = listAgencies();
							$companies = listOurCompanies();
                            foreach($agencies as $super)
							
                              {
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cKankriyaFourthReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cKankriyaFourthReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cKankriyaFourthReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cKankriyaFourthReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
<td>Broker Name : </td>
				<td>
					<select name="broker[]" class="broker selectpicker" multiple="multiple"  id="broker" >
                    
                          <?php
						  $brokers=listBrokers();
						  
                          
                            foreach($brokers as $broker)
                              {
                             ?>
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['cKankriyaFourthReport']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['cKankriyaFourthReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                      <input type="button" class="select_all" name="select_all" value="Select All">
                            </td>
</tr>




<tr>
<td>Vechicle Type</td>
<td>
	<select id="type_rasid" name="vehicle_type[]" class="city_area selectpicker" multiple="multiple" >
                       <?php $vehicle_types= listVehicleTypes(); foreach($vehicle_types as $vehicle_type) { ?>
                            
                             <option value="<?php echo $vehicle_type['vehicle_type_id']; ?>" <?php if(isset($_SESSION['cKankriyaFourthReport']['vehicle_type_array'])){ if(in_array($vehicle_type['vehicle_type_id'],$_SESSION['cKankriyaFourthReport']['vehicle_type_array']))  { ?> selected="selected" <?php }} ?>><?php echo $vehicle_type['vehicle_type']; ?></option>
                             
                             
                  			<?php } ?>
                            </select> 
</td>
</tr>


<tr>
	<td>File Status :</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cKankriyaFourthReport']['file_status'])){ if(  $_SESSION['cKankriyaFourthReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cKankriyaFourthReport']['file_status'])){ if( $_SESSION['cKankriyaFourthReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cKankriyaFourthReport']['file_status'])){ if( $_SESSION['cKankriyaFourthReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cKankriyaFourthReport']['file_status'])){ if( $_SESSION['cKankriyaFourthReport']['file_status']==6 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(isset($_SESSION['cKankriyaFourthReport']['file_status']) && ($_SESSION['cKankriyaFourthReport']['file_status']!=1 && $_SESSION['cKankriyaFourthReport']['file_status']!=2 && $_SESSION['cKankriyaFourthReport']['file_status']!=5 && $_SESSION['cKankriyaFourthReport']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
    </td>
</tr>


<tr>
	<td>Show Seized Vehicles :</td>
    <td>
    	<input  type="radio" name="seized" id="yes" value="1"  <?php if(isset($_SESSION['cKankriyaFourthReport']['seized'])){ if(  $_SESSION['cKankriyaFourthReport']['seized']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="yes">Yes</label>
		<input  type="radio" name="seized" id="no" value="0" <?php if(isset($_SESSION['cKankriyaFourthReport']['seized'])){ if( $_SESSION['cKankriyaFourthReport']['seized']==0 ) { ?> checked="checked" <?php }} else { ?> checked="checked"  <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="no">No</label>
       
    </td>
</tr>

<tr>
	<td>View :</td>
    <td>
    	<input  type="radio" name="view_type" id="view_type_normal"  value="1"  <?php if(isset($_SESSION['cKankriyaFourthReport']['view_type'])){ if(  $_SESSION['cKankriyaFourthReport']['view_type']==1 ) { ?> checked="checked" <?php }} ?> checked="checked" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="view_type_normal">TVR View</label>
		<input  type="radio" name="view_type" id="view_type_print"  value="0" <?php if(isset($_SESSION['cKankriyaFourthReport']['view_type'])){ if( $_SESSION['cKankriyaFourthReport']['view_type']==0 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="view_type_print">Collection Notice View</label>
       
    </td>
</tr>

<tr>
<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>

</table>


  
<hr class="firstTableFinishing" />
 


 <?php if(isset($_SESSION['cKankriyaFourthReport']['emi_array']))
{
	$emi_array=$_SESSION['cKankriyaFourthReport']['emi_array'];
$ten_days_befor_month = date('m',strtotime($ten_days_befor_date));
	$one_month_five_days_before_date = getTodaysDateTimeBeforeMonthsAndDays(1,5);
	$one_month_five_days_before_month = date('m',strtotime($one_month_five_days_before_date));	
	if($_SESSION['cKankriyaFourthReport']['view_type']==1)
	{	
		
		
	 ?>    
     	<div class="no_print">
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
         <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Last Payment Date</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">EMI</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Bucket</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Name</label> 
          <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Gaurantor Name</label> 
           <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Broker</label> 
           <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Last Notice Date</label>
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
          
       <th class="heading no_print no_sort"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
        	<th class="heading">No</th>
            <th class="heading file">File No
            (Agr No)</th>
            <th class="heading">Reg No</th>
            <th class="heading date">Last Payment Date</th>
            <th class="heading numeric">EMI</th>
            <th class="heading">Bucket (Balance)</th>
            <th class="heading" >Name - No</th>
			<th class="heading">Gau Name - No</th>
			<th class="heading">Broker</th>
            <th class="heading">Last List Date</th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total_no_agencies=getTotalNoOfAgencies();
		$total=0;
		foreach($emi_array as $emi)
		{
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);			$extraCustomer=getExtraCustomerDetailsByFileId($emi['file_id']);
			
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			
			$last_notice_date = getLatestCollectionDateForFileId($emi['file_id']);
			
			if(($_SESSION['cKankriyaFourthReport']['seized']==1 && $seieze) || !$seieze)
			{
		 ?>
         <tr class="resultRow  <?php if(strtotime($last_notice_date)>strtotime($emi['payment_date'])) echo "shantiRow"; ?>">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['file_id']; ?>" /><?php $last_payment_month=date('m',strtotime($emi['payment_date'])); if($emi['non_starter']) echo "(NS)"; ?></td>
        	<td><?php echo ++$i; ?></td>
            <td><span style="display:none"><?php 
			if(is_numeric($emi['agency_id'])) {
				$prefix=$emi['agnecy_id'];}
			else if(is_numeric($emi['oc_id']))
			{$prefix=$total_no_agencies+$emi['oc_id']; }
			
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_no']); ?></span> <?php if($seieze) echo "(S)<br>";   echo  $emi['file_no'];  ?> <br /> (<?php  echo $emi['file_agreement_number']; ?>)
            </td>
            
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA";  ?>
            </td>
           
             <td><?php   $last_payment_date=date('d/m Y',strtotime($emi['payment_date'])); if($last_payment_date!='01/01/1970') echo $last_payment_date; else echo "NA"; ?>
            </td>
           <td width="160px"><p><?php  $total_bucket=0; if(isset($emi['bucket_details']) && $emi['bucket_details']!=0 && is_array($emi['bucket_details']) && count($emi['bucket_details'])>1 && $emi['file_status']!=4) { foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e." X ".$corr_bucket." <br>";} }else if($emi['file_status']!=4){ foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e;}  } else if($emi['file_status']==4 && $emi['loan_scheme']!="error"){ if(is_array($emi['loan_scheme'])) {  foreach($emi['loan_scheme'] as $scheme){ echo $scheme['emi']." X ".$scheme['duration']."<br>"; } } }else{
			  
			  $oldest_unpaid_emi = getOldestUnPaidEmi(getLoanIdFromFileId($emi['file_id']));
			  echo getEmiForLoanEmiId($oldest_unpaid_emi);
			  }  ?>
          </p></td>
            <td><?php
				
			  if($emi['file_status']!=4) echo $total_bucket; else echo 0.0; ?>
             <br />(<?php   echo $emi['balance']; 
				$total=$total+$emi['balance'];
				?>)
            </td>
          
            <td   style="word-break:break-all;min-width:150px;"><?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) {
				echo "Next : ";
				if(validateForNull($extraCustomer['secondary_extra_customer_name'])) echo $extraCustomer['secondary_extra_customer_name']; else echo $extraCustomer['extra_customer_name'];
				   ?>
                  <br /><hr style="margin:5px 0px" />
                  <?php 
							 $contactArray = $extraCustomer['contact_no'];
							 
						
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." <br> ";	
							}	
			
			 	?>  <br /><hr style="margin:5px 0px" />
				  <?php }
				   { if(validateForNull($emi['customer']['secondary_customer_name'])) echo $emi['customer']['secondary_customer_name']; else echo $emi['customer']['customer_name']; } ?><br /><hr style="margin:5px 0px" /><?php   
						 $contactArray = $emi['customer']['contact_no']; 
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." <br> ";	
							}	
							
			 	?></td>
               <td  style="word-break:break-all;min-width:150px;"><?php  if(isset( $emi['guarantor']['guarantor_name'])) { if(validateForNull($emi['guarantor']['secondary_guarantor_name'])) echo $emi['guarantor']['secondary_guarantor_name']; else if(validateForNull($emi['guarantor']['guarantor_name'])) echo $emi['guarantor']['guarantor_name']; else echo "-"; } ?><br /><hr style="margin:5px 0px" /><?php if(isset( $emi['guarantor']['guarantor_name'])) {  $contactArray = $emi['guarantor']['contact_no']; 
			 			
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." <br> ";	
							}	
			 }
							
			 	?></td>
            
                 <td><?php   echo $emi['broker_name']; ?></td>
                 <td><?php   $last_notice_date=date('d/m/Y',strtotime($last_notice_date)); if($last_notice_date!='01/01/1970') echo $last_notice_date; ?></td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" type="button" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
         <?php } } ?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cKankriyaFourthReport']['from']) && $_SESSION['cKankriyaFourthReport']['from']!="") echo $_SESSION['cKankriyaFourthReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cKankriyaFourthReport']['to']) && $_SESSION['cKankriyaFourthReport']['to']!="") echo $_SESSION['cKankriyaFourthReport']['to']; else echo "NA"; ?></td>
    	<td> Bucket(>=) : <?php if(isset($_SESSION['cKankriyaFourthReport']['win_gt']) && $_SESSION['cKankriyaFourthReport']['win_gt']!="") echo $_SESSION['cKankriyaFourthReport']['win_gt']; else echo "NA"; ?></td>
        <td> Bucket(<=) : <?php if(isset($_SESSION['cKankriyaFourthReport']['win_lt']) && $_SESSION['cKankriyaFourthReport']['win_lt']!="") echo $_SESSION['cKankriyaFourthReport']['win_lt']; else echo "NA"; ?></td>
        <td> EMI(>=) : <?php if(isset($_SESSION['cKankriyaFourthReport']['emi_gt']) && $_SESSION['cKankriyaFourthReport']['emi_gt']!="") echo $_SESSION['cKankriyaFourthReport']['emi_gt']; else echo "NA"; ?></td>
        <td> EMI(<=) : <?php if(isset($_SESSION['cKankriyaFourthReport']['emi_lt']) && $_SESSION['cKankriyaFourthReport']['emi_lt']!="") echo $_SESSION['cKankriyaFourthReport']['emi_lt']; else echo "NA"; ?></td>
        <td> Balance(>=) : <?php if(isset($_SESSION['cKankriyaFourthReport']['balance_gt']) && $_SESSION['cKankriyaFourthReport']['balance_gt']!="") echo $_SESSION['cKankriyaFourthReport']['balance_gt']; else echo "NA"; ?></td>
        <td> balance(<=) : <?php if(isset($_SESSION['cKankriyaFourthReport']['balance_lt']) && $_SESSION['cKankriyaFourthReport']['balance_lt']!="") echo $_SESSION['cKankriyaFourthReport']['balance_lt']; else echo "NA"; ?></td>
        <td> City : <?php if(isset($_SESSION['cKankriyaFourthReport']['city_id']) && $_SESSION['cKankriyaFourthReport']['city_id']!="") {$city=getCityByID($_SESSION['cKankriyaFourthReport']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       
        <td> Agency : <?php if(isset($_SESSION['cKankriyaFourthReport']['agency_id']) && $_SESSION['cKankriyaFourthReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKankriyaFourthReport']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['cKankriyaFourthReport']['file_status']) && $_SESSION['cKankriyaFourthReport']['file_status']!="") { if($_SESSION['cKankriyaFourthReport']['file_status']==1) echo "OPEN";else if($_SESSION['cKankriyaFourthReport']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total">Total Amount : <?php if(isset($total)) echo number_format($total); ?></span>
      
</div>
<?php } else {  ?> 
<div style="position:relative;width:100%;">
<?php 

foreach($emi_array as $emi)
		{
			
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);			$extraCustomer=getExtraCustomerDetailsByFileId($emi['file_id']);
			$loan = $loan=getLoanDetailsByFileId($emi['file_id']);
			$totalEMIsPaid=number_format(getTotalEmiPaidForLoan($loan['loan_id']),2);
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			if(!isset($old_customer_area))
			$old_customer_area=$emi['customer']['area_name'];
			if(($_SESSION['cKankriyaFourthReport']['seized']==1 && $seieze) || !$seieze)
			{
?>				

<div style="width:98%;page-break-inside:avoid;<?php if($old_customer_area!=$emi['customer']['area_name']) { ?> margin-top:50px; <?php } ?>">
	<table style="width:100%" border="1">
    	<tr>
        	<td width="10%" valign="top" align="center"><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?><br>
            	<?php  ?>
            </td>
            <td>
            	<table style="width:100%" border="1">
                	<tr>
                    	<td><?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?> આગામી <?php } ?> પાર્ટી એરીયા : </td>
                        <td><?php  if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?> <?php
						echo $extraCustomer['secondary_area_name']." (".$extraCustomer['area_name'].")";
						$old_customer_area=$extraCustomer['area_name'];
						 } else { echo $emi['customer']['secondary_area_name']." (".$emi['customer']['area_name'].")";
						$old_customer_area=$emi['customer']['area_name'];
						}
							        ?></td>
                        <td>જમીનદાર એરીયા :</td>
                        <td><?php echo  $emi['guarantor']['secondary_area_name']." (".$emi['guarantor']['area_name'].")"
							 		 ?></td>
                    </tr>
                	<tr>
                    	<td width="17%"><?php  $company=getVehicleCompanyById($emi['vehicle_company_id']); echo $company['company_name']; ?></td>
                        <td width="33%"><?php echo getModelNameById($emi['model_id'])." (".$emi['vehicle_model'].")"; ?></td>
                        <td width="17%">એગ્રીમેન્ટ નં.:</td>
                        <td width="33%"><?php  echo  $emi['file_no']; if($seieze) echo "(S)";  echo " ".$emi['file_agreement_number']; ?></td>
                    </tr>
                    
                    	<tr>
                    	<td >કુલ હપ્તા </td>
                        <td >  <?php
							
							 $loan_emi=getEmiForLoanId($emi['loan_id']); // amount if even loan or loan structure if loan is uneven
							 
							 if($loan['loan_scheme']==1)
							  echo number_format($loan['emi'])." X ".$loan['loan_duration'];
							  else
							  {
								  foreach($loan_emi as $e)
								  {
									  echo number_format($e['emi'])." X ".$e['duration']." | ";
								  }
								  
							   }
							    $total_collection =  getTotalCollectionForLoan($loan['loan_id']);
							 echo  " = ".number_format($total_collection); ?>	</td>
                        <td >ભરેલ હપ્તા:</td>
                        <td><?php  echo number_format($totalEMIsPaid,2)." હપ્તા = "; 	echo "Rs. ".number_format(getTotalPaymentForLoan($loan['loan_id'])); ?></td>
                    </tr>
                    
                    <tr>
                    	<td><?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?> આગામી <?php } ?> પાર્ટી :</td>
                        <td><?php
						   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) {  
						   if(validateForNull($extraCustomer['secondary_extra_customer_name'])) echo $extraCustomer['secondary_extra_customer_name']; else echo $extraCustomer['extra_customer_name'];
						   }
						   else if(validateForNull($emi['customer']['secondary_customer_name'])) echo $emi['customer']['secondary_customer_name']; else echo $emi['customer']['customer_name']; ?></td>
                        <td>જમીનદાર :</td>
                        <td><?php if(validateForNull($emi['guarantor']['secondary_guarantor_name'])) echo $emi['guarantor']['secondary_guarantor_name']; else echo $emi['guarantor']['guarantor_name']; ?></td>
                    </tr>
                    <tr>
                    	<td> <?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?> આગામી પાર્ટી <?php } ?> સરનામું :</td>
                        <td><?php
						if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { 
						if(validateForNull($extraCustomer['secondary_extra_customer_address'])) echo $extraCustomer['secondary_extra_customer_address']; else echo $extraCustomer['extra_customer_address'];
						 }
						 else if(validateForNull($emi['customer']['secondary_customer_address'])) echo $emi['customer']['secondary_customer_address']; else echo $emi['customer']['customer_address']; ?></td>
                        <td>સરનામું :</td>
                        <td><?php if(validateForNull($emi['guarantor']['secondary_guarantor_address'])) echo $emi['guarantor']['secondary_guarantor_address']; else echo $emi['guarantor']['guarantor_address']; ?></td>
                    </tr>
                    <tr>
                    	<td>મોબાઈલ  :</td>
                        <td><?php 
						if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) {
							 $contactArray = $extraCustomer['contact_no'];
							 }
						else
						 $contactArray = $emi['customer']['contact_no']; 
			 			
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." , ";	
							}	 ?></td>
                            <td>મોબાઈલ  :</td>
                        <td><?php  $contactArray = $emi['guarantor']['contact_no']; 
			 			
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." , ";	
							}	 ?></td>
                    </tr>
                    <tr>
                    	<td>ચડતર હપ્તા  :</td>
                        <td> <p>હપ્તા : <?php  $total_bucket=0; if(isset($emi['bucket_details']) && $emi['bucket_details']!=0 && is_array($emi['bucket_details']) && count($emi['bucket_details'])>1 && $emi['file_status']!=4) { foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e." X ".$corr_bucket." <br>";} }else if($emi['file_status']!=4){ foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e;}  } else if($emi['file_status']==4 && $emi['loan_scheme']!="error"){ if(is_array($emi['loan_scheme'])) {  foreach($emi['loan_scheme'] as $scheme){ echo $scheme['emi']." X ".$scheme['duration']."<br>"; } } }; ?> ચડતર : <?php
				
			  if($emi['file_status']!=4) echo $total_bucket; else echo 0.0; ?> (<?php echo $emi['balance']; ?>)
          </p>	 </td>
          <td>છેલ્લા ભર્યા હપ્તાની તા :</td>
                        <td> <?php	$last_payment_date=date('d/m/Y',strtotime($emi['payment_date'])); if($last_payment_date!='01/01/1970') echo $last_payment_date; else echo "NA"; ?> </td>
                    </tr>
                    <tr>
                    	<td>એન્જિન નં.:</td>
                        <td><?php if($emi['engine_no']!=null && $emi['engine_no']!="") echo $emi['engine_no']; else echo "NA"; ?></td>
                        <td>ચેસીસ નં :</td>
                        <td><?php if($emi['chasis_no']!=null && $emi['chasis_no']!="") echo $emi['chasis_no']; else echo "NA"; ?></td>
                    </tr>
                     <tr>
                    	<td height="40px;" >To Pay Date :</td>
                        <td></td>
                        <td>Customer Sign :</td>
                        <td></td>
                    </tr>
                    
                    <?php 
						if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?>
                         <tr>
                    	<td height="40px;" > પાર્ટી :</td>
                        <td><?php
						 if(validateForNull($emi['customer']['secondary_customer_name'])) echo $emi['customer']['secondary_customer_name']; else echo $emi['customer']['customer_name'];
						 ?></td>
                        <td><?php if(validateForNull($emi['customer']['secondary_customer_address'])) echo $emi['customer']['secondary_customer_address']; else echo $emi['customer']['customer_address']; ?></td>
                        <td><?php
						 $contactArray = $emi['customer']['contact_no']; 
			 			
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." , ";	
							}	
						 ?></td>
                    </tr>
                        
						<?php } ?>	
                    
                </table>
            </td>
           
        </tr>
    </table>
</div>
<?php 

}
}
?>
</div> 
</div>

<?php		
}
}
else
{ 
?>
</div>
<?php } ?>
</form>
<div class="clearfix"></div>

