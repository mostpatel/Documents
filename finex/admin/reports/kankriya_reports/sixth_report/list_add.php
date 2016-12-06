<?php $payment_should_be_done_after_date = getTodaysDateTimeBeforeMonthsAndDays(0,0);
$payment_should_be_done_before_date = getTodaysDateTimeBeforeMonthsAndDays(0,0); ?>
<div class="jvp"><?php if(isset($_SESSION['cKankriyaSeventhReport']['agency_id']) && $_SESSION['cKankriyaSeventhReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKankriyaSeventhReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Legal Case Report (Step 7)</h4>
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
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['from'])) echo $_SESSION['cKankriyaSeventhReport']['from']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (Payment date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['to'])) echo $_SESSION['cKankriyaSeventhReport']['to']; ?>" />	
                 </td>
</tr> -->

<tr>
<td>From Date (emi date) : </td>
				<td>
				 <input class="datepicker2" autocomplete="off" type="text"  name="from_emi_date" id="from_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['from_emi_date'])) echo $_SESSION['cKankriyaSeventhReport']['from_emi_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (emi date) : </td>
				<td>
				 <input class="datepicker2" autocomplete="off" type="text"  name="to_emi_date" id="to_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['to_emi_date'])) echo $_SESSION['cKankriyaSeventhReport']['to_emi_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>From Date (case date) : </td>
				<td>
				 <input class="datepicker2" autocomplete="off" type="text"  name="from_case_date" id="from_case_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['from_case_date'])) echo $_SESSION['cKankriyaSeventhReport']['from_case_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (case date) : </td>
				<td>
				 <input class="datepicker2" autocomplete="off" type="text"  name="to_case_date" id="to_case_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['to_case_date'])) echo $_SESSION['cKankriyaSeventhReport']['to_case_date']; ?>" />	
                 </td>
</tr>


<!--
<tr>
<td>Bucket(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_gt" id="win_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['win_gt'])) echo $_SESSION['cKankriyaSeventhReport']['win_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Bucket(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_lt" id="win_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['win_lt'])) echo $_SESSION['cKankriyaSeventhReport']['win_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_gt" id="emi_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['emi_gt'])) echo $_SESSION['cKankriyaSeventhReport']['emi_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_lt" id="emi_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['emi_lt'])) echo $_SESSION['cKankriyaSeventhReport']['emi_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_gt" id="balance_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['balance_gt'])) echo $_SESSION['cKankriyaSeventhReport']['balance_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_lt" id="balance_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cKankriyaSeventhReport']['balance_lt'])) echo $_SESSION['cKankriyaSeventhReport']['balance_lt']; ?>" />	
                 </td>
</tr>
-->
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cKankriyaSeventhReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cKankriyaSeventhReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cKankriyaSeventhReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cKankriyaSeventhReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cKankriyaSeventhReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cKankriyaSeventhReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cKankriyaSeventhReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cKankriyaSeventhReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cKankriyaSeventhReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cKankriyaSeventhReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
<td>Broker Name : </td>
				<td>
					<select name="broker[]" class="broker selectpicker" multiple="multiple"  id="broker" >
                    	 <option value="-1" disabled="disabled">--Please Select--</option>
                          <?php
						  $brokers=listBrokers();
						  
                          
                            foreach($brokers as $broker)
                              {
                             ?>
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['cKankriyaSeventhReport']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['cKankriyaSeventhReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                            </td>
</tr>


<tr>
	<td>File Status :</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cKankriyaSeventhReport']['file_status'])){ if(  $_SESSION['cKankriyaSeventhReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cKankriyaSeventhReport']['file_status'])){ if( $_SESSION['cKankriyaSeventhReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cKankriyaSeventhReport']['file_status'])){ if( $_SESSION['cKankriyaSeventhReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cKankriyaSeventhReport']['file_status'])){ if( $_SESSION['cKankriyaSeventhReport']['file_status']==6 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(isset($_SESSION['cKankriyaSeventhReport']['file_status']) && ($_SESSION['cKankriyaSeventhReport']['file_status']!=1 && $_SESSION['cKankriyaSeventhReport']['file_status']!=2 && $_SESSION['cKankriyaSeventhReport']['file_status']!=5 && $_SESSION['cKankriyaSeventhReport']['file_status']!=6)){  ?> checked="checked" <?php } else { ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
    </td>
</tr>

<tr>
	<td>Reg Ad :</td>
    <td>
    	<input  type="radio" name="reg_ad" id="reg_ad_yes" value="1"  <?php if(isset($_SESSION['cKankriyaSeventhReport']['reg_ad'])){ if(  $_SESSION['cKankriyaSeventhReport']['reg_ad']==1 ) { ?> checked="checked" <?php }}  ?>  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="reg_ad_yes">Yes</label>
		<input  type="radio" name="reg_ad" id="reg_ad_no" value="0" <?php if(isset($_SESSION['cKankriyaSeventhReport']['reg_ad'])){ if( $_SESSION['cKankriyaSeventhReport']['reg_ad']==0 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="reg_ad_no">No</label>
        <input  type="radio" name="reg_ad" id="reg_ad_all" value="2" <?php if(isset($_SESSION['cKankriyaSeventhReport']['reg_ad'])){ if( $_SESSION['cKankriyaSeventhReport']['reg_ad']==2 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php }  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="reg_ad_all">All</label>
        
       
    </td>
</tr>

<tr>
	<td>Case No :</td>
    <td>
    	<input  type="radio" name="case_no" id="case_no_yes" value="1"  <?php if(isset($_SESSION['cKankriyaSeventhReport']['case_no'])){ if(  $_SESSION['cKankriyaSeventhReport']['case_no']==1 ) { ?> checked="checked" <?php }} ?>  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="case_no_yes">Yes</label>
		<input  type="radio" name="case_no" id="case_no_no" value="0" <?php if(isset($_SESSION['cKankriyaSeventhReport']['case_no'])){ if( $_SESSION['cKankriyaSeventhReport']['case_no']==0 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="case_no_no">No</label>
        <input  type="radio" name="case_no" id="case_no_all" value="2" <?php if(isset($_SESSION['cKankriyaSeventhReport']['case_no'])){ if( $_SESSION['cKankriyaSeventhReport']['case_no']==2 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php }  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="case_no_all">All</label>
        
       
    </td>
</tr>


<tr>
	<td>Warrant Status :</td>
    <td>
    	<input  type="radio" name="warrant" id="warrant_yes" value="2"  <?php if(isset($_SESSION['cKankriyaSeventhReport']['warrant'])){ if(  $_SESSION['cKankriyaSeventhReport']['warrant']==2 ) { ?> checked="checked" <?php }} ?>  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="warrant_yes">Received</label>
		<input  type="radio" name="warrant" id="warrant_no" value="1" <?php if(isset($_SESSION['cKankriyaSeventhReport']['warrant'])){ if( $_SESSION['cKankriyaSeventhReport']['warrant']==1 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="warrant_no">Not Receievd</label>
          <input  type="radio" name="warrant" id="warrant_na" value="0" <?php if(isset($_SESSION['cKankriyaSeventhReport']['warrant'])){ if( $_SESSION['cKankriyaSeventhReport']['warrant']==0 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="warrant_na">NA</label>
        <input  type="radio" name="warrant" id="warrant_all" value="3" <?php if(isset($_SESSION['cKankriyaSeventhReport']['warrant'])){ if( $_SESSION['cKankriyaSeventhReport']['warrant']==3 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php }  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="warrant_all">All</label>
        
       
    </td>
</tr>



<tr>
	<td>Show Seized Vehicles :</td>
    <td>
    	<input  type="radio" name="seized" id="yes" value="1"  <?php if(isset($_SESSION['cKankriyaSeventhReport']['seized'])){ if(  $_SESSION['cKankriyaSeventhReport']['seized']==1 ) { ?> checked="checked" <?php }} else { ?> checked="checked" <?php } ?>  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="yes">Yes</label>
		<input  type="radio" name="seized" id="no" value="0" <?php if(isset($_SESSION['cKankriyaSeventhReport']['seized'])){ if( $_SESSION['cKankriyaSeventhReport']['seized']==0 ) { ?> checked="checked" <?php }}  ?> /> <label style="display:inline-block;top:3px;position:relative;" for="no">No</label>
       
    </td>
</tr>

<tr>
<td>Vehicle Type</td>
<td>
	<select id="type_rasid" name="vehicle_type[]" class="city_area selectpicker" multiple="multiple" >
                       <?php $vehicle_types= listVehicleTypes(); foreach($vehicle_types as $vehicle_type) { ?>
                            
                             <option value="<?php echo $vehicle_type['vehicle_type_id']; ?>" <?php if(isset($_SESSION['cKankriyaSeventhReport']['vehicle_type_array'])){ if(in_array($vehicle_type['vehicle_type_id'],$_SESSION['cKankriyaSeventhReport']['vehicle_type_array']))  { ?> selected="selected" <?php }} ?>><?php echo $vehicle_type['vehicle_type']; ?></option>
                             
                             
                  			<?php } ?>
                            </select> 
</td>
</tr>


<tr>
<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>




</table>

</form>

  
<hr class="firstTableFinishing" />
 

	
 <?php if(isset($_SESSION['cKankriyaSeventhReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cKankriyaSeventhReport']['emi_array'];
		
		$cheque_return_customer=getLatestChequeReturnDateForFileId($emi['file_id'],0);
			$cheque_return_guarantor=getLatestChequeReturnDateForFileId($emi['file_id'],1);
			$legal_notice = listLegalNoticesForFileID($emi['file_id']);
		
	 ?> 
     <div class="no_print">   
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Prev Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Court</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Case Type</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Petetionar</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Advocate</label> 
         <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Case No</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Stage</label> 
         <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Next Date</label> 
            <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Customer Name</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
            <th class="heading no_sort no_print">Print</th>
        	<th class="heading no_sort no_sort">No</th>
            <th class="heading no_sort">Prev Date</th>
            <th class="heading no_sort">Court</th>
            <th class="heading no_sort">Case Type</th>
            <th class="heading no_sort">Petetionar</th>
            <th class="heading no_sort">Advocate</th>
            <th class="heading">Case No</th>
            <th class="heading">Case Yr</th>
            <th class="heading">Stage</th>
            <th class="heading date default_sort">Next Date</th>
            <th class="heading no_sort">Cust or Gua Name</th>   
            <th class="heading no_sort no_print btnCol"></th>
            <th class="heading no_sort">Warrant Status</th>  
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total_no_agencies=getTotalNoOfAgencies();
		$total=0;
		if(isset($_SESSION['cKankriyaSeventhReport']['from_case_date']) && validateForNull($_SESSION['cKankriyaSeventhReport']['from_case_date']))
		{
			
			$from_date = $_SESSION['cKankriyaSeventhReport']['from_case_date'];
			if(isset($from_date) && validateForNull($from_date))
{
	    $from_date = str_replace('/', '-', $from_date);
		$from_date=date('Y-m-d',strtotime($from_date));
	}

		}
			else
			$from_date = "1970-01-01";
			if(isset($_SESSION['cKankriyaSeventhReport']['to_case_date']) && validateForNull($_SESSION['cKankriyaSeventhReport']['to_case_date']))
			{
			$to_date = $_SESSION['cKankriyaSeventhReport']['to_case_date'];
			
			if(isset($to_date) && validateForNull($to_date))
{
	$to_date = str_replace('/', '-', $to_date);
		$to_date=date('Y-m-d',strtotime($to_date));
	}
			}
			else
			$to_date = "2060-01-01";
			
			$show_case_no=$_SESSION['cKankriyaSeventhReport']['case_no'];
			$show_reg_ad = $_SESSION['cKankriyaSeventhReport']['reg_ad'];
			$warrant_status = $_SESSION['cKankriyaSeventhReport']['warrant'];
			
		foreach($emi_array as $emi)
		{
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);			
			$extraCustomer=getExtraCustomerDetailsByFileId($emi['file_id']);
			
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			
			$admins=listLegalNoticesForFileID($emi['file_id']);
			$cheque_return_customer=getLatestChequeReturnDateForFileId($emi['file_id'],0);
			$cheque_return_guarantor=getLatestChequeReturnDateForFileId($emi['file_id'],1);
			if(($_SESSION['cKankriyaSeventhReport']['seized']==1 && $seieze) || !$seieze)
			{
			$customer_notice=0;
			$guarantor_notice=0;	
			foreach($admins as $admin)
			{
				if($admin['type']==0)
				$customer_notice=1;
				else if($admin['type']==1)
				$guarantor_notice=1;
			}	
			
         foreach($admins as $admin)
		{
			
			if(checkForNumeric($admin['cheque_return_id']))
			{
			if($admin['type']==0)	
			$cheque_return_customer = getChequeReturnDetailsForId($admin['cheque_return_id']);	
			else
			$cheque_return_guarantor = getChequeReturnDetailsForId($admin['cheque_return_id']);	;
			}
			else
			{
			$cheque_return=NULL;
			$cheque_return_customer=getLatestChequeReturnDateForFileId($emi['file_id'],0);
			$cheque_return_guarantor=getLatestChequeReturnDateForFileId($emi['file_id'],1);
			}
			
			if($admin['type']==0)
			{
			if(isset($cheque_return_customer['slip_no']))
			$slip_no = $cheque_return_customer['slip_no'];
			else
			$slip_no = NULL;	
			}
			else if($admin['type']==1)
			{
			if(isset($cheque_return_guarantor['slip_no']))
			$slip_no = $cheque_return_guarantor['slip_no'];
			else
			$slip_no = NULL;	
			}
			$warrant_status_cond = ($admin['warrant'] == $warrant_status) || $warrant_status==3;
			if(strtotime($admin['next_date'])>=strtotime($from_date) && strtotime($admin['next_date'])<=strtotime($to_date) && (($show_case_no==0 && $admin['case_no']=="NA") || ($show_case_no==1 && validateForNull($admin['case_no']) && $admin['case_no']!="NA") || !isset($show_case_no) || $show_case_no==2) && ($show_reg_ad==2 || ($show_reg_ad==1 && validateForNull($slip_no)) || ($show_reg_ad==0&& $slip_no=="")) && $warrant_status_cond)
			{
				
			$latest_payment_date = getLatestPaymentDateForLoan($emi['loan_id']);
			$settlement_file = false;
			$settlement_file = strtotime($latest_payment_date) >= strtotime($admin['notice_date']);
		 ?>
         <tr class="resultRow <?php if(strtotime(getTodaysDate())>strtotime($admin['next_date']) && !$settlement_file) { ?> dangerRow <?php }  else if( $settlement_file) echo "warningRow";  ?>">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['file_id']; ?>" /></td>
        	<td><?php echo ++$no; if($settlement_file) echo "(sm)"; ?> 
            </td>
            <td><?php if(!validateForNull($admin['prev_date'])) echo date('d/m Y',strtotime($admin['notice_date'])); else echo date('d/m Y',strtotime($admin['prev_date'])); ?>
            </td>
             <td><?php echo $admin['court']; ?>
            </td>
             <td><?php echo $admin['case_type']; ?><br>
			 <?php  
			 if($admin['type']==0) 
			 { 
			 	if(isset($cheque_return_customer['cheque_amount'])) 
					{ 
						echo "(".$cheque_return_customer['cheque_amount']." Rs)"; 
						$total = $total + $cheque_return_customer['cheque_amount']; 
					} 
			} 
			else 
			{ 
				if(isset($cheque_return_guarantor['cheque_amount'])) 
				{ 
					echo "(".$cheque_return_guarantor['cheque_amount']." Rs)"; 
					$total = $total + $cheque_return_customer['cheque_amount'];
				}
			} ?>
            </td>
              <td><?php echo $admin['case_petetionar']; ?>
            </td>
             <td><?php echo $admin['advocate_name']; ?>
            </td>
           
             <td><?php $case_array=explode("/",$admin['case_no']); echo $case_array[0]."/<br>";  ?>
            </td>
             <td><?php echo $case_array[1].")"; ?></td>
            <td><?php echo $admin['stage']; ?></td>
           
            
             <td><?php echo date('d/m/Y',strtotime($admin['next_date'])); ?>
            </td>
            
            <td>
            <?php if($admin['type']==0) echo "C:- "; else echo "G:- "; 
			if($admin['type']==0)
			 { 
			 echo $emi['customer']['customer_name']; 
             } 
			 else 
			 {
              echo $emi['guarantor']['guarantor_name']; 
			  } ?>
             <br>
             <?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
              <br>(<?php echo $emi['file_no'] ?>)
             <?php  ?>
             </td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" type="button" class="btn viewBtn"><span class="view">V</span></button></a>
             <br>
             <a href="<?php echo WEB_ROOT.'admin/customer/legal_notice/index.php?view=edit&id='.$admin['legal_notice_id'] ?>"><button title="Edit this entry" type="button" class="btn viewBtn"><span class="view">E</span></button></a>
              <a href="<?php echo WEB_ROOT.'admin/customer/cheque_return/index.php?id='.$admin['file_id'] ?>"><button title="Edit this entry" type="button" class="btn viewBtn"><span class="view">C</span></button></a>
            
             <?php if((isset($cheque_return_customer) && $emi['payment_date']>=$cheque_return_customer['cheque_date']) || !$cheque_return_customer) { ?>
             						<a href="<?php echo WEB_ROOT.'admin/customer/cheque_return/index.php?id='.$emi['file_id']."&type=0"; ?>" target="_blank" ><button style="margin:5px;margin-left:0;margin-right:0;" type="button" title="View this entry" class="btn btn-warning viewBtn">Chq Rtn Cust</button></a>
                                    <?php } else if($admin['type']==1 && $customer_notice==0){ ?>
                                    <a href="<?php echo WEB_ROOT.'admin/customer/legal_notice/index.php?id='.$emi['file_id']."&type=0&cheque_return_id=".$cheque_return_customer['cheque_return_id']; ?>" target="_blank" ><button style="margin:5px;margin-left:0;margin-right:0;" type="button" title="View this entry" class="btn btn-danger viewBtn">Case Cust</button></a>
                                    
                                    <?php } ?>
                                    <?php 
									if(is_numeric($emi['guarantor']['guarantor_id']))
									{
									if((isset($cheque_return_guarantor) && $emi['payment_date']>=$cheque_return_guarantor['cheque_date']) || !$cheque_return_guarantor) { ?>
                                    <a href="<?php echo WEB_ROOT.'admin/customer/cheque_return/index.php?id='.$emi['file_id']."&type=1"; ?>" target="_blank"><button type="button" title="View this entry" class="btn btn-warning viewBtn">Chq Rtn Guar</button></a>
                                    <?php } else if($admin['type']==0 && $guarantor_notice==0){ ?>
                                    <a href="<?php echo WEB_ROOT.'admin/customer/legal_notice/index.php?id='.$emi['file_id']."&type=1&cheque_return_id=".$cheque_return_guarantor['cheque_return_id']; ?>" target="_blank" ><button style="margin:5px;margin-left:0;margin-right:0;" type="button" title="View this entry" class="btn btn-danger viewBtn">Case Guar</button></a>
                                    
                                    <?php }} ?>
              
            </td>
   		 <td><?php  if($admin['warrant']==0) echo "NA"; else if($admin['warrant']==1) echo "NOT Received"; else if($admin['warrant']==2) echo "Received"; ?>
            </td>
        </tr>
         <?php }} } } ?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cKankriyaSeventhReport']['from']) && $_SESSION['cKankriyaSeventhReport']['from']!="") echo $_SESSION['cKankriyaSeventhReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cKankriyaSeventhReport']['to']) && $_SESSION['cKankriyaSeventhReport']['to']!="") echo $_SESSION['cKankriyaSeventhReport']['to']; else echo "NA"; ?></td>
    	<td> Bucket(>=) : <?php if(isset($_SESSION['cKankriyaSeventhReport']['win_gt']) && $_SESSION['cKankriyaSeventhReport']['win_gt']!="") echo $_SESSION['cKankriyaSeventhReport']['win_gt']; else echo "NA"; ?></td>
        <td> Bucket(<=) : <?php if(isset($_SESSION['cKankriyaSeventhReport']['win_lt']) && $_SESSION['cKankriyaSeventhReport']['win_lt']!="") echo $_SESSION['cKankriyaSeventhReport']['win_lt']; else echo "NA"; ?></td>
        <td> EMI(>=) : <?php if(isset($_SESSION['cKankriyaSeventhReport']['emi_gt']) && $_SESSION['cKankriyaSeventhReport']['emi_gt']!="") echo $_SESSION['cKankriyaSeventhReport']['emi_gt']; else echo "NA"; ?></td>
        <td> EMI(<=) : <?php if(isset($_SESSION['cKankriyaSeventhReport']['emi_lt']) && $_SESSION['cKankriyaSeventhReport']['emi_lt']!="") echo $_SESSION['cKankriyaSeventhReport']['emi_lt']; else echo "NA"; ?></td>
        <td> Balance(>=) : <?php if(isset($_SESSION['cKankriyaSeventhReport']['balance_gt']) && $_SESSION['cKankriyaSeventhReport']['balance_gt']!="") echo $_SESSION['cKankriyaSeventhReport']['balance_gt']; else echo "NA"; ?></td>
        <td> balance(<=) : <?php if(isset($_SESSION['cKankriyaSeventhReport']['balance_lt']) && $_SESSION['cKankriyaSeventhReport']['balance_lt']!="") echo $_SESSION['cKankriyaSeventhReport']['balance_lt']; else echo "NA"; ?></td>
        <td> City : <?php if(isset($_SESSION['cKankriyaSeventhReport']['city_id']) && $_SESSION['cKankriyaSeventhReport']['city_id']!="") {$city=getCityByID($_SESSION['cKankriyaSeventhReport']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       
        <td> Agency : <?php if(isset($_SESSION['cKankriyaSeventhReport']['agency_id']) && $_SESSION['cKankriyaSeventhReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKankriyaSeventhReport']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['cKankriyaSeventhReport']['file_status']) && $_SESSION['cKankriyaSeventhReport']['file_status']!="") { if($_SESSION['cKankriyaSeventhReport']['file_status']==1) echo "OPEN";else if($_SESSION['cKankriyaSeventhReport']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total">Total Amount : <?php if(isset($total)) echo number_format($total); ?></span>
<?php } ?>      
</div>
<div class="clearfix"></div>
