<?php $payment_should_be_done_after_date = getTodaysDateTimeBeforeMonthsAndDays(2,0);
$ten_days_befor_date = getTodaysDateTimeBeforeMonthsAndDays(1,0); ?>
<div class="jvp"><?php if(isset($_SESSION['cKankriyaBrokerReport']['agency_id']) && $_SESSION['cKankriyaBrokerReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKankriyaBrokerReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Kankriya Broker Report</h4>
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




<tr>
<td>From Date (Loan Approval date) : </td>
				<td>
				 <input autocomplete="off" type="text" class="datepicker1"  name="start_date_approval" id="from_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['from_approval_date'])) echo $_SESSION['cKankriyaBrokerReport']['from_approval_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (Loan Approval date) : </td>
				<td>
				 <input autocomplete="off" type="text" class="datepicker3"  name="end_date_approval" id="to_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['to_approval_date'])) echo $_SESSION['cKankriyaBrokerReport']['to_approval_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>From Date (Payment date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['to'])) echo $_SESSION['cKankriyaBrokerReport']['to']; ?>" />	
                 </td>
</tr> 

<tr>
<td>To Date (Payment date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['from'])) echo $_SESSION['cKankriyaBrokerReport']['from']; ?>" />	
                 </td>
</tr>




<tr>
<td>From Date (NOC date) : </td>
				<td>
				 <input autocomplete="off" type="text" class="datepicker1"  name="start_date_noc" id="from_noc_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['from_noc_date'])) echo $_SESSION['cKankriyaBrokerReport']['from_noc_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (NOC date) : </td>
				<td>
				 <input autocomplete="off" type="text" class="datepicker3"  name="end_date_noc" id="to_noc_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['to_noc_date'])) echo $_SESSION['cKankriyaBrokerReport']['to_noc_date']; ?>" />	
                 </td>
</tr>


<tr>
<td>Rate of Interest(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="roi_gt" id="roi_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['roi_gt'])) echo $_SESSION['cKankriyaBrokerReport']['roi_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Rate of interest(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="roi_lt" id="roi_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['roi_lt'])) echo $_SESSION['cKankriyaBrokerReport']['roi_lt']; ?>" />	
                 </td>
</tr>
<!--
<tr>
<td>Bucket(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_gt" id="win_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['win_gt'])) echo $_SESSION['cKankriyaBrokerReport']['win_gt']; else echo "0.001"; ?>" />	
                 </td>
</tr>

<tr>
<td>Bucket(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_lt" id="win_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['win_lt'])) echo $_SESSION['cKankriyaBrokerReport']['win_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_gt" id="emi_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['emi_gt'])) echo $_SESSION['cKankriyaBrokerReport']['emi_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_lt" id="emi_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['emi_lt'])) echo $_SESSION['cKankriyaBrokerReport']['emi_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_gt" id="balance_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['balance_gt'])) echo $_SESSION['cKankriyaBrokerReport']['balance_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_lt" id="balance_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['balance_lt'])) echo $_SESSION['cKankriyaBrokerReport']['balance_lt']; ?>" />	
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cKankriyaBrokerReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cKankriyaBrokerReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cKankriyaBrokerReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cKankriyaBrokerReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cKankriyaBrokerReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cKankriyaBrokerReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
                             <?php } 
						  }
							 ?>
                    </select>
                            </td>
</tr>
-->
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cKankriyaBrokerReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cKankriyaBrokerReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cKankriyaBrokerReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cKankriyaBrokerReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
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
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['cKankriyaBrokerReport']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['cKankriyaBrokerReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                     <input type="button" class="select_all" name="select_all" value="Select All">
                            </td>
</tr>



<tr>
<td>Vechicle Condition :</td>
<td>
	<select id="type_rasid" name="vehicle_condition" class="vehicle_condition"  >
     <option <?php if($_SESSION['cKankriyaBrokerReport']['vehicle_condition']==1) { ?> selected="selected" <?php } ?> value="1">New</option>
                       <option <?php if($_SESSION['cKankriyaBrokerReport']['vehicle_condition']==0) { ?> selected="selected" <?php } ?> value="0">Old</option>
                      
                       <option <?php if($_SESSION['cKankriyaBrokerReport']['vehicle_condition']==2) { ?> selected="selected" <?php } else if(!isset($_SESSION['cKankriyaBrokerReport']['vehicle_condition'])) { ?> selected="selected" <?php } ?> value="2">Both</option>
                            </select> 
</td>
</tr>


<tr>
<td>Vechicle Type :</td>
<td>
	<select id="type_rasid" name="vehicle_type[]" class="city_area selectpicker" multiple="multiple" >
                       <?php $vehicle_types= listVehicleTypes(); foreach($vehicle_types as $vehicle_type) { ?>
                            
                             <option value="<?php echo $vehicle_type['vehicle_type_id']; ?>" <?php if(isset($_SESSION['cKankriyaBrokerReport']['vehicle_type_array'])){ if(in_array($vehicle_type['vehicle_type_id'],$_SESSION['cKankriyaBrokerReport']['vehicle_type_array']))  { ?> selected="selected" <?php }} ?>><?php echo $vehicle_type['vehicle_type']; ?></option>
                             
                             
                  			<?php } ?>
                            </select> 
</td>
</tr>

<tr>
	<td>File Status :</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cKankriyaBrokerReport']['file_status'])){ if(  $_SESSION['cKankriyaBrokerReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cKankriyaBrokerReport']['file_status'])){ if( $_SESSION['cKankriyaBrokerReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cKankriyaBrokerReport']['file_status'])){ if( $_SESSION['cKankriyaBrokerReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cKankriyaBrokerReport']['file_status'])){ if( $_SESSION['cKankriyaBrokerReport']['file_status']==6 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(isset($_SESSION['cKankriyaBrokerReport']['file_status']) && ($_SESSION['cKankriyaBrokerReport']['file_status']!=1 && $_SESSION['cKankriyaBrokerReport']['file_status']!=2 && $_SESSION['cKankriyaBrokerReport']['file_status']!=5 && $_SESSION['cKankriyaBrokerReport']['file_status']!=6)){  ?> checked="checked" <?php }  else  { ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
    </td>
</tr>

<tr>
	<td>Show Seized Vehicles :</td>
    <td>
    	<input  type="radio" name="seized" id="yes" value="1"  <?php if(isset($_SESSION['cKankriyaBrokerReport']['seized'])){ if(  $_SESSION['cKankriyaBrokerReport']['seized']==1 ) { ?> checked="checked" <?php }}else { ?> checked="checked" <?php } ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="yes">Yes</label>
		<input  type="radio" name="seized" id="no" value="0" <?php if(isset($_SESSION['cKankriyaBrokerReport']['seized'])){ if( $_SESSION['cKankriyaBrokerReport']['seized']==0 ) { ?> checked="checked" <?php }}  ?>  /> <label style="display:inline-block;top:3px;position:relative;" for="no">No</label>
       
    </td>
</tr>

<tr>
	<td>Show Legal Cases :</td>
    <td>
    	<input  type="radio" name="show_legal" id="legal_yes" value="1"  <?php if(isset($_SESSION['cKankriyaBrokerReport']['show_legal'])){ if(  $_SESSION['cKankriyaBrokerReport']['show_legal']==1 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php } ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="legal_yes">Yes</label>
		<input  type="radio" name="show_legal" id="legal_no" value="0" <?php if(isset($_SESSION['cKankriyaBrokerReport']['show_legal'])){ if( $_SESSION['cKankriyaBrokerReport']['show_legal']==0 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="legal_no">No</label>
       
    </td>
</tr>

<tr>
	<td>Noc generated :</td>
    <td>
    	<input  type="radio" name="noc_status" id="noc_status_yes" value="2"  <?php if(isset($_SESSION['cKankriyaBrokerReport']['noc_status'])){ if(  $_SESSION['cKankriyaBrokerReport']['noc_status']==2 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php } ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="noc_status_yes">Yes</label>
		<input  type="radio" name="noc_status" id="noc_status_no" value="0" <?php if(isset($_SESSION['cKankriyaBrokerReport']['noc_status'])){ if( $_SESSION['cKankriyaBrokerReport']['noc_status']==0 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="noc_status_no">No</label>
        <input  type="radio" name="noc_status" id="noc_status_only" value="1" <?php if(isset($_SESSION['cKankriyaBrokerReport']['noc_status'])){ if( $_SESSION['cKankriyaBrokerReport']['noc_status']==1 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="noc_status_only">Only</label>
       
    </td>
</tr>

<tr>
	<td>View :</td>
    <td>
    	<input  type="radio" name="view_type" id="view_type_normal"  value="1"  <?php if(isset($_SESSION['cKankriyaBrokerReport']['view_type'])){ if(  $_SESSION['cKankriyaBrokerReport']['view_type']==1 ) { ?> checked="checked" <?php }} ?> checked="checked" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="view_type_normal">TVR View</label>
		<input  type="radio" name="view_type" id="view_type_print"  value="0" <?php if(isset($_SESSION['cKankriyaBrokerReport']['view_type'])){ if( $_SESSION['cKankriyaBrokerReport']['view_type']==0 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="view_type_print">Collection Notice View</label>
       
    </td>
</tr>

<tr>
<td>% Our Interest : </td>
				<td>
				 <input autocomplete="off" type="text" class=""  name="our_roi" id="our_roi" placeholder=""  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['our_roi'])) echo $_SESSION['cKankriyaBrokerReport']['our_roi']; ?>" />	
                 </td>
</tr>

<tr>
<td>% for D/c : </td>
				<td>
				 <input autocomplete="off" type="text" class=""  name="dc" id="dc" placeholder=""  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['dc'])) echo $_SESSION['cKankriyaBrokerReport']['dc']; else echo 0; ?>" />	
                 </td>
</tr>

<tr>
<td>% for L/R : </td>
				<td>
				 <input autocomplete="off" type="text" class=""  name="lr" id="lr" placeholder=""  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['lr'])) echo $_SESSION['cKankriyaBrokerReport']['lr']; else echo 0; ?>" />	
                 </td>
</tr>

<tr>
	<td>L/R Type :</td>
    <td>
    	<input  type="radio" name="penalty_type" id="penalty_type_percent" value="1"  <?php if(isset($_SESSION['cKankriyaBrokerReport']['penalty_type'])){ if(  $_SESSION['cKankriyaBrokerReport']['penalty_type']==1 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php } ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="penalty_type_percent">Percent</label>
		<input  type="radio" name="penalty_type" id="penalty_type_fixed" value="0" <?php if(isset($_SESSION['cKankriyaBrokerReport']['penalty_type'])){ if( $_SESSION['cKankriyaBrokerReport']['penalty_type']==0 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="penalty_type_fixed">Fixed</label>
       
    </td>
</tr>


<tr>
<td>% Agent Participation : </td>
				<td>
				 <input autocomplete="off" type="text" class=""  name="participation" id="participation" placeholder=""  value="<?php if(isset($_SESSION['cKankriyaBrokerReport']['participation'])) echo $_SESSION['cKankriyaBrokerReport']['participation']; else echo 0; ?>" />	
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
 

	
 <?php if(isset($_SESSION['cKankriyaBrokerReport']['emi_array']))
{
	
	
	$emi_array=$_SESSION['cKankriyaBrokerReport']['emi_array'];
	$lr=$_SESSION['cKankriyaBrokerReport']['lr'];
	$dc=$_SESSION['cKankriyaBrokerReport']['dc'];
	$our_roi=$_SESSION['cKankriyaBrokerReport']['our_roi'];
	$participation=$_SESSION['cKankriyaBrokerReport']['participation'];	
	$penalty_type = $_SESSION['cKankriyaBrokerReport']['penalty_type'];
	
	if($_SESSION['cKankriyaBrokerReport']['view_type']==1)
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
           <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Reminder Date</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        
       <th class="heading  no_sort"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
        	<th class="heading">No</th>
            <th class="heading file">File No (Agr No)</th>
            <th class="heading">Reg No</th>
            <th class="heading date">Approval Date</th>
             <th class="heading ">Condition</th>
              <th class="heading ">Type</th>
               <th class="heading numeric">Loan Amount</th>
              <th class="heading numeric">Interest rate</th>
            <th class="heading numeric">EMI Paid</th>
            <th class="heading numeric">% Int amt</th>
            <th class="heading" width="50px" >% D/c</th>
			<th class="heading">L/R</th>
			<th class="heading">Participation</th>
            <th class="heading">Total</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total_no_agencies=getTotalNoOfAgencies();
		$total_loan_amount = 0;
		$total=0;
		$int_total = 0;
		$dc_total =0;
		$lr_total =0 ;
		$participation_total =0;
		foreach($emi_array as $emi)
		{
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);
			$reminder_date = getLatestReminderDateForFile($emi['file_id']);
			$extraCustomer=getExtraCustomerDetailsByFileId($emi['file_id']);
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			$file_charges = getFileChargesById($emi['file_id']);
			if(is_array($file_charges))
			$file_charges = $file_charges['file_charges'];
			else
			$file_charges=NULL;
			$net_roi = ($emi['roi'] - $our_roi);
			$net_loan_interest_to_pay_per_year = ($emi['loan_amount']*$net_roi)/100;
			$net_loan_interest_to_pay_per_emi = $net_loan_interest_to_pay_per_year/12;
			$file_status = $emi['file_status'];
			$penalty_amount=getTotalPenaltyAmountPaidForLoan($emi['loan_id']);
			
			if(($_SESSION['cKankriyaBrokerReport']['seized']==1 && $seieze) || !$seieze)
			{
		 ?>
         <tr class="resultRow <?php if($seieze) echo "dangerRow";  ?>">
         	<td class=""><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['file_id']; ?>" /><?php $last_payment_month=date('m',strtotime($emi['payment_date'])); if($emi['non_starter']) echo "(NS)"; if($file_status==4) echo "(FC)"; ?></td>
        	<td><?php echo ++$i; ?></td>
            <td><span style="display:none"><?php 
			if(is_numeric($emi['agency_id'])) {
				$prefix=$emi['agnecy_id'];}
			else if(is_numeric($emi['oc_id']))
			{$prefix=$total_no_agencies+$emi['oc_id']; }
			
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_no']); ?></span> <?php  if($seieze) echo "(S)<br>";  echo  $emi['file_no']; d ?> <br /> (<?php  echo $emi['file_agreement_number']; ?>)
            </td>
            
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA";  ?>
            </td>
           
             <td><?php   $last_payment_date=date('d/m/Y',strtotime($emi['loan_approval_date'])); if($last_payment_date!='01/01/1970') echo $last_payment_date; else echo "NA"; ?>
            </td>
            <td><?php  if($emi['vehicle_condition']==0) echo "OLD"; else if($emi['vehicle_condition']==1) echo "NEW"; else "NA"; ?></td>
            <td><?php if(is_numeric($emi['vehicle_type_id'])) echo getVehicleTypeNameById($emi['vehicle_type_id']); else echo "Not Added"; ?></td>
            <td><?php echo $emi['loan_amount']; $total_loan_amount = $total_loan_amount + $emi['loan_amount']; ?></td>
             <td><?php echo round($emi['roi'],2); ?></td>
             <td><b><?php if($file_status!=3 && $file_status!=4) {$emi_paid = round($emi['emi_paid'],2); } else if($file_status==4 && !(isset($_SESSION['cKankriyaBrokerReport']['to'])) &&  !(isset($_SESSION['cKankriyaBrokerReport']['from']))) {$emi_to_be_paid = $emi['emi_to_be_paid']; $emi_date_to_be_paid = $emi['emi_date_to_be_paid']; if($emi_to_be_paid<$emi['loan_duration']) {  $today = getTodaysDate(); $today = strtotime($today);  $emi_date_to_be_paid = strtotime($emi_date_to_be_paid); $datediff = $today - $emi_date_to_be_paid;  $days_int = ($datediff / (60 * 60 * 24 * 30)); $emi_paid = $emi_to_be_paid + $days_int;  $emi_paid = round($emi_paid,2); }    } else {   $emi_paid = round($emi['emi_paid'],2);  } echo $emi_paid; ?></b></td>
           
            <td><b><?php
			$int_paid=0;
			$net_file_charge_amt=0;
			$penalty_amt=0;
			$participation_amt=0;
				$int_paid =  round($emi_paid*$net_loan_interest_to_pay_per_emi,0,PHP_ROUND_HALF_UP); if($our_roi>0) { echo $int_paid; $int_total = $int_total + $int_paid; } else { echo 0; $int_paid=0; }
			  ?></b>
            </td>
          
            <td   style=""><b><?php  
			 
					if($file_charges==NULL) echo "NOT ADDED"; else { if(is_numeric($file_charges) && $file_charges>0 && $file_charges<=100) {  $net_file_charge_percent = $file_charges - $dc;  $net_file_charge_amt = ($net_file_charge_percent*$emi['loan_amount'])/100; if($dc>0) { echo $net_file_charge_amt; $dc_total = $dc_total + $net_file_charge_amt; } else { echo 0; $net_file_charge_amt=0; }  } }		
			 	?></b></td>
            
               <td  style=""><b><?php   
			  if($penalty_type==1) { $lr_give = 100 - $lr;  $penalty_amt = round(($lr_give/100)*$penalty_amount,2); echo $penalty_amt; if($lr>0) { echo $penalty_amt;	$lr_total = $lr_total + $penalty_amt;} else { echo 0; $penalty_amt=0; } } else if($penalty_type==0) {  $penalty_amt = -round($penalty_amount,2); if($lr>0) { echo $penalty_amt;	$lr_total = $lr_total + $penalty_amt;} else { echo 0; $penalty_amt=0; }}		
			 	?></b></td>
            
                 <td><b><?php  $participation_amt = round(($emi['loan_amount']*$participation)/100,0); echo $participation_amt; $participation_total=$participation_total + $participation_amt; ?></b></td>
                  <td><b><?php $total_all =  $int_paid + $net_file_charge_amt + $penalty_amt + $participation_amt;  echo $total_all; $total = $total + $total_all;    ?></b>
            </td>
             <td class="no_print" style="min-width:100px;"> 
             <a class="no_print" target="_blank" href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" type="button" class="btn viewBtn no_print"><span class="view">V</span></button></a>
             
             <a class="no_print" target="_blank" href="<?php echo WEB_ROOT.'admin/customer/index.php?view=addRemainder&id='.$emi['file_id']; ?>"><button title="Add Reminder" type="button" class="btn viewBtn no_print"><span class="view">R</span></button></a>
             						
             
            </td>
   
        </tr>
         <?php  } }?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cKankriyaBrokerReport']['from']) && $_SESSION['cKankriyaBrokerReport']['from']!="") echo $_SESSION['cKankriyaBrokerReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cKankriyaBrokerReport']['to']) && $_SESSION['cKankriyaBrokerReport']['to']!="") echo $_SESSION['cKankriyaBrokerReport']['to']; else echo "NA"; ?></td>
    	<td> Bucket(>=) : <?php if(isset($_SESSION['cKankriyaBrokerReport']['win_gt']) && $_SESSION['cKankriyaBrokerReport']['win_gt']!="") echo $_SESSION['cKankriyaBrokerReport']['win_gt']; else echo "NA"; ?></td>
        <td> Bucket(<=) : <?php if(isset($_SESSION['cKankriyaBrokerReport']['win_lt']) && $_SESSION['cKankriyaBrokerReport']['win_lt']!="") echo $_SESSION['cKankriyaBrokerReport']['win_lt']; else echo "NA"; ?></td>
        <td> EMI(>=) : <?php if(isset($_SESSION['cKankriyaBrokerReport']['emi_gt']) && $_SESSION['cKankriyaBrokerReport']['emi_gt']!="") echo $_SESSION['cKankriyaBrokerReport']['emi_gt']; else echo "NA"; ?></td>
        <td> EMI(<=) : <?php if(isset($_SESSION['cKankriyaBrokerReport']['emi_lt']) && $_SESSION['cKankriyaBrokerReport']['emi_lt']!="") echo $_SESSION['cKankriyaBrokerReport']['emi_lt']; else echo "NA"; ?></td>
        <td> Balance(>=) : <?php if(isset($_SESSION['cKankriyaBrokerReport']['balance_gt']) && $_SESSION['cKankriyaBrokerReport']['balance_gt']!="") echo $_SESSION['cKankriyaBrokerReport']['balance_gt']; else echo "NA"; ?></td>
        <td> balance(<=) : <?php if(isset($_SESSION['cKankriyaBrokerReport']['balance_lt']) && $_SESSION['cKankriyaBrokerReport']['balance_lt']!="") echo $_SESSION['cKankriyaBrokerReport']['balance_lt']; else echo "NA"; ?></td>
        <td> City : <?php if(isset($_SESSION['cKankriyaBrokerReport']['city_id']) && $_SESSION['cKankriyaBrokerReport']['city_id']!="") {$city=getCityByID($_SESSION['cKankriyaBrokerReport']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       
        <td> Agency : <?php if(isset($_SESSION['cKankriyaBrokerReport']['agency_id']) && $_SESSION['cKankriyaBrokerReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKankriyaBrokerReport']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['cKankriyaBrokerReport']['file_status']) && $_SESSION['cKankriyaBrokerReport']['file_status']!="") { if($_SESSION['cKankriyaBrokerReport']['file_status']==1) echo "OPEN";else if($_SESSION['cKankriyaBrokerReport']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
    <span class="Total">Total Loan Amount : <?php if(isset($total_loan_amount)) echo number_format($total_loan_amount)." Rs "; ?></span>
     <span class="Total">Total Interest : <?php if(isset($int_total)) echo number_format($int_total)." Rs "; ?></span>
       <span class="Total">Total D/c : <?php if(isset($dc_total)) echo number_format($dc_total)." Rs "; ?></span>
         <span class="Total">Total L/r : <?php if(isset($lr_total)) echo number_format($lr_total)." Rs "; ?></span>
           <span class="Total">Total Participation : <?php if(isset($participation_total)) echo number_format($participation_total)." Rs "; ?></span>
   <span class="Total"><b>Total Amount : <?php if(isset($total)) echo number_format($total)." Rs "; ?></b></span>
   </div>
  <style>
  .Total {
	  margin:10px;
	  }
  </style>
<?php } else {  ?> 
<div style="position:relative;width:100%;">
<?php 

foreach($emi_array as $emi)
		{
			
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);
			$loan = $loan=getLoanDetailsByFileId($emi['file_id']);
			$totalEMIsPaid=number_format(getTotalEmiPaidForLoan($loan['loan_id']),2);
			$extraCustomer=getExtraCustomerDetailsByFileId($emi['file_id']);
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			
			if(!isset($old_customer_area))
			$old_customer_area=$emi['customer']['area_name'];
			
			if(($_SESSION['cKankriyaBrokerReport']['seized']==1 && $seieze) || !$seieze)
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
