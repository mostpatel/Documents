<?php $payment_should_be_done_after_date = getTodaysDateTimeBeforeMonthsAndDays(2,15); $ten_days_befor_date = getTodaysDateTimeBeforeMonthsAndDays(2,0);	
$one_month_five_days_before_date = getTodaysDateTimeBeforeMonthsAndDays(2,0); ?>
<div class="jvp"><?php if(isset($_SESSION['cEMIReportPaymentDate']['agency_id']) && $_SESSION['cEMIReportPaymentDate']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cEMIReportPaymentDate']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Office Notice Reports (Due)</h4>
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
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['from'])) echo $_SESSION['cEMIReportPaymentDate']['from']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (Payment date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['to'])) echo $_SESSION['cEMIReportPaymentDate']['to']; ?>" />	
                 </td>
</tr> -->
<input type="hidden" name="start_date" value="<?php echo  date('d/m/Y',strtotime($one_month_five_days_before_date)); ?>"  />
<input type="hidden" name="end_date" value="<?php echo date('d/m/Y',strtotime($payment_should_be_done_after_date)); ?>" />

<tr>
<td>From Date (EMI date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_emi_date" id="from_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['from_emi_date'])) echo $_SESSION['cEMIReportPaymentDate']['from_emi_date']; ?>" />	
                 </td>
</tr>

<tr>
<td>To Date (EMI date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_emi_date" id="to_emi_date" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['to_emi_date'])) echo $_SESSION['cEMIReportPaymentDate']['to_emi_date']; ?>" />	
                 </td>
</tr>



<tr>
<td>Bucket(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_gt" id="win_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['win_gt'])) echo $_SESSION['cEMIReportPaymentDate']['win_gt']; else echo "0.001"; ?>" />	
                 </td>
</tr>

<tr>
<td>Bucket(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_lt" id="win_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['win_lt'])) echo $_SESSION['cEMIReportPaymentDate']['win_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_gt" id="emi_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['emi_gt'])) echo $_SESSION['cEMIReportPaymentDate']['emi_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_lt" id="emi_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['emi_lt'])) echo $_SESSION['cEMIReportPaymentDate']['emi_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_gt" id="balance_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['balance_gt'])) echo $_SESSION['cEMIReportPaymentDate']['balance_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_lt" id="balance_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReportPaymentDate']['balance_lt'])) echo $_SESSION['cEMIReportPaymentDate']['balance_lt']; ?>" />	
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cEMIReportPaymentDate']['city_id'])){ if( $super['city_id'] == $_SESSION['cEMIReportPaymentDate']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cEMIReportPaymentDate']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cEMIReportPaymentDate']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cEMIReportPaymentDate']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cEMIReportPaymentDate']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cEMIReportPaymentDate']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cEMIReportPaymentDate']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cEMIReportPaymentDate']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cEMIReportPaymentDate']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
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
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['cEMIReportPaymentDate']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['cEMIReportPaymentDate']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                            </td>
</tr>


<tr>
	<td>File Status :</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cEMIReportPaymentDate']['file_status'])){ if(  $_SESSION['cEMIReportPaymentDate']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cEMIReportPaymentDate']['file_status'])){ if( $_SESSION['cEMIReportPaymentDate']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cEMIReportPaymentDate']['file_status'])){ if( $_SESSION['cEMIReportPaymentDate']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cEMIReportPaymentDate']['file_status'])){ if( $_SESSION['cEMIReportPaymentDate']['file_status']==6 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(!isset($_SESSION['cEMIReportPaymentDate']['file_status']) || ($_SESSION['cEMIReportPaymentDate']['file_status']!=1 && $_SESSION['cEMIReportPaymentDate']['file_status']!=2 && $_SESSION['cEMIReportPaymentDate']['file_status']!=5 && $_SESSION['cEMIReportPaymentDate']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
    </td>
</tr>

<tr>
<td>Vechicle Type</td>
<td>
	<select id="type_rasid" name="vehicle_type[]" class="city_area selectpicker" multiple="multiple" >
                       <?php $vehicle_types= listVehicleTypes(); foreach($vehicle_types as $vehicle_type) { ?>
                            
                             <option value="<?php echo $vehicle_type['vehicle_type_id']; ?>" <?php if(isset($_SESSION['cEMIReportPaymentDate']['vehicle_type_array'])){ if(in_array($vehicle_type['vehicle_type_id'],$_SESSION['cEMIReportPaymentDate']['vehicle_type_array']))  { ?> selected="selected" <?php }} ?>><?php echo $vehicle_type['vehicle_type']; ?></option>
                             
                             
                  			<?php } ?>
                            </select> 
</td>
</tr>

<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>

<tr>
	<td>Show Seized Vehicles :</td>
    <td>
    	<input  type="radio" name="seized" id="yes" value="1"  <?php if(isset($_SESSION['cEMIReportPaymentDate']['seized'])){ if(  $_SESSION['cEMIReportPaymentDate']['seized']==1 ) { ?> checked="checked" <?php }} ?> checked="checked" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="yes">Yes</label>
		<input  type="radio" name="seized" id="no" value="0" <?php if(isset($_SESSION['cEMIReportPaymentDate']['seized'])){ if( $_SESSION['cEMIReportPaymentDate']['seized']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="no">No</label>
       
    </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['cEMIReportPaymentDate']['emi_array']))
{
	
	$emi_array=$_SESSION['cEMIReportPaymentDate']['emi_array'];
	
	$ten_days_befor_month = date('m',strtotime($ten_days_befor_date));
	
	$one_month_five_days_before_month = date('m',strtotime($one_month_five_days_before_date));	
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Last EMI Date</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Last Payment Date</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">EMI</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Bucket</label> 
         <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Balance</label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Name</label> 
         <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Address</label> 
          <input class="showCB" type="checkbox" id="11" checked="checked"  /><label class="showLabel" for="11">Contact No</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">File No</th>
            <th class="heading">Reg No</th>
            <th class="heading date">Last EMI Date</th>
            <th class="heading date">Last Payment Date</th>
            <th class="heading numeric">EMI</th>
            <th class="heading">Bucket</th>
            <th class="heading">Balance</th>
            <th class="heading">Name</th>
            <th width="10%" class="heading">Address</th>
            <th class="heading">Contact No</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total_no_agencies=getTotalNoOfAgencies();
		$total=0;
		foreach($emi_array as $emi)
		{
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);
			$last_payment_month=date('m',strtotime($emi['payment_date'])); 
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			if((($_SESSION['cEMIReport']['seized']==1 && $seieze) || !$seieze) )
			{
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /><?php  ?></td>
        	<td><?php echo ++$i; ?></td>
            <td><span style="display:none"><?php 
			if(is_numeric($emi['agency_id'])) {
				$prefix=$emi['agnecy_id'];}
			else if(is_numeric($emi['oc_id']))
			{$prefix=$total_no_agencies+$emi['oc_id']; }
			
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; if($seieze) echo "(S)"; ?>
            </td>
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
            </td>
            <td><?php  $last_emi_date=date('d/m/Y',strtotime($emi['emi_date'])); if($last_emi_date!='01/01/1970') echo $last_emi_date; else echo "NA"; ?>
            </td>
             <td><?php   $last_payment_date=date('d/m/Y',strtotime($emi['payment_date'])); if($last_payment_date!='01/01/1970') echo $last_payment_date; else echo "NA"; ?>
            </td>
           <td width="160px"><p><?php  $total_bucket=0; if(isset($emi['bucket_details']) && $emi['bucket_details']!=0 && is_array($emi['bucket_details']) && count($emi['bucket_details'])>1 && $emi['file_status']!=4) { foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e." X ".$corr_bucket." <br>";} }else if($emi['file_status']!=4){ foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e;}  } else if($emi['file_status']==4 && $emi['loan_scheme']!="error"){ if(is_array($emi['loan_scheme'])) {  foreach($emi['loan_scheme'] as $scheme){ echo $scheme['emi']." X ".$scheme['duration']."<br>"; } } }; ?>
          </p></td>
            <td><?php
				
			  if($emi['file_status']!=4) echo $total_bucket; else echo 0.0; ?>
            </td>
           <td>
           		<?php   echo $emi['balance']; 
				$total=$total+$emi['balance'];
				?>
            </td>
            <td><?php   echo $emi['customer']['customer_name']; ?></td>
             <td><?php   echo $emi['customer']['customer_address']; ?></td>
             <td><?php   $contactArray = $emi['customer']['contact_no']; 
			 			
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
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
         <?php } } }?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cEMIReportPaymentDate']['from']) && $_SESSION['cEMIReportPaymentDate']['from']!="") echo $_SESSION['cEMIReportPaymentDate']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cEMIReportPaymentDate']['to']) && $_SESSION['cEMIReportPaymentDate']['to']!="") echo $_SESSION['cEMIReportPaymentDate']['to']; else echo "NA"; ?></td>
    	<td> Bucket(>=) : <?php if(isset($_SESSION['cEMIReportPaymentDate']['win_gt']) && $_SESSION['cEMIReportPaymentDate']['win_gt']!="") echo $_SESSION['cEMIReportPaymentDate']['win_gt']; else echo "NA"; ?></td>
        <td> Bucket(<=) : <?php if(isset($_SESSION['cEMIReportPaymentDate']['win_lt']) && $_SESSION['cEMIReportPaymentDate']['win_lt']!="") echo $_SESSION['cEMIReportPaymentDate']['win_lt']; else echo "NA"; ?></td>
        <td> EMI(>=) : <?php if(isset($_SESSION['cEMIReportPaymentDate']['emi_gt']) && $_SESSION['cEMIReportPaymentDate']['emi_gt']!="") echo $_SESSION['cEMIReportPaymentDate']['emi_gt']; else echo "NA"; ?></td>
        <td> EMI(<=) : <?php if(isset($_SESSION['cEMIReportPaymentDate']['emi_lt']) && $_SESSION['cEMIReportPaymentDate']['emi_lt']!="") echo $_SESSION['cEMIReportPaymentDate']['emi_lt']; else echo "NA"; ?></td>
        <td> Balance(>=) : <?php if(isset($_SESSION['cEMIReportPaymentDate']['balance_gt']) && $_SESSION['cEMIReportPaymentDate']['balance_gt']!="") echo $_SESSION['cEMIReportPaymentDate']['balance_gt']; else echo "NA"; ?></td>
        <td> balance(<=) : <?php if(isset($_SESSION['cEMIReportPaymentDate']['balance_lt']) && $_SESSION['cEMIReportPaymentDate']['balance_lt']!="") echo $_SESSION['cEMIReportPaymentDate']['balance_lt']; else echo "NA"; ?></td>
        <td> City : <?php if(isset($_SESSION['cEMIReportPaymentDate']['city_id']) && $_SESSION['cEMIReportPaymentDate']['city_id']!="") {$city=getCityByID($_SESSION['cEMIReportPaymentDate']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       
        <td> Agency : <?php if(isset($_SESSION['cEMIReportPaymentDate']['agency_id']) && $_SESSION['cEMIReportPaymentDate']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cEMIReportPaymentDate']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['cEMIReportPaymentDate']['file_status']) && $_SESSION['cEMIReportPaymentDate']['file_status']!="") { if($_SESSION['cEMIReportPaymentDate']['file_status']==1) echo "OPEN";else if($_SESSION['cEMIReportPaymentDate']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total">Total Amount : <?php if(isset($total)) echo number_format($total); ?></span>
<?php  ?>      
</div>
<div class="clearfix"></div>
