<?php
require_once("../lib/cg.php");
require_once("../lib/bd.php");
require_once("../lib/report-functions.php");
require_once("../lib/vehicle-functions.php");
require_once("../lib/financer-functions.php");
$selectedLink="home";
require_once("../inc/header.php");

 ?>
<div class="insideCoreContent adminContentWrapper wrapper"> 
 <div class="widgetContainer">
 
   <div class="notificationCenter">
       Notification Center
   </div>
   
  <?php ?>
  
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']))
{
	$report_rights=$_SESSION['edmsAdminSession']['report_rights'];
	}
 if(defined('EDMS_MODE') && EDMS_MODE==1) {	
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
      
     <div class="Column">
     
        
        <h4 class="widgetTitle"> Unfinished JobCards </h4>
         
         <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
             <th class="heading">Job No</th>
             <th class="heading">Date</th>
          
             <th class="heading">Reg No</th>
             <th class="heading">Amount</th>
             <th class="heading">Amount Received</th>
            <th class="heading">Amount Left</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$job_cards = listUnfinalizedJobCards();
		$no=0;
		foreach($job_cards as $job_card)
		{
		$vehicle_id = $job_card['vehicle_id'];
		$vehicle = getVehicleById($vehicle_id);	
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$customer = getCustomerDetailsByCustomerId($job_card['customer_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card['job_card_id']);
		$receipt_amount = getReceiptAmountAndKasarAmountForJobCardId($job_card['job_card_id']);
		$kasar = $receipt_amount[1];
		$receipt_amount=$receipt_amount[0];
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           	<td><?php echo $customer['customer_name']; ?></td>
            <td><?php echo $job_card['job_card_no']; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
          
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td>Rs.<?php echo round($job_card['total_amount']); ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
             <td align="center">Rs.<?php echo round($job_card['total_amount']-$receipt_amount-$kasar); ?>
              <br />
             Kasar(<?php echo round($kasar); ?>)
            
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    
            <td class=""><?php echo $invoice_no;  ?> <a href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card['job_card_id']; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card['job_card_id']; ?>"><button title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice<?php } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card['job_card_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card['job_card_id']; ?>"><button title="Edit this entry" class="btn "><span class="delete">E</span></button></a>
            </td>
          
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/EMI_reports/custom/index.php?action=fromHomeUpcoming"><div class="more">View all Jobcards..</div></a>

<div style="clear:both"></div>
</div>

<?php 
			}
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
      
     <div class="Column">
     
        
        <h4 class="widgetTitle"> Today's finished JobCards </h4>
         
         <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Name</th>
             <th class="heading">Job No</th>
             <th class="heading">Date</th>
            
             <th class="heading">Reg No</th>
             <th class="heading">Amount</th>
             <th class="heading">Amount Received</th>
            <th class="heading">Amount Left</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$job_cards = listFinalizedJobCards();
		$no=0;
		foreach($job_cards as $job_card)
		{
		$vehicle_id = $job_card['vehicle_id'];
		$vehicle = getVehicleById($vehicle_id);	
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$customer = getCustomerDetailsByCustomerId($job_card['customer_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card['job_card_id']);
		$receipt_amount = getReceiptAmountAndKasarAmountForJobCardId($job_card['job_card_id']);
		$kasar = $receipt_amount[1];
		$receipt_amount=$receipt_amount[0];
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo $job_card['job_card_no']; ?>
            </td>
             	<td><?php echo $customer['customer_name']; ?></td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
         
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td>Rs.<?php echo round($job_card['total_amount']); ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
             <td align="center">Rs.<?php echo round($job_card['total_amount']-$receipt_amount-$kasar); ?>
             <br />
             Kasar(<?php echo round($kasar); ?>)
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    
            <td class=""> <?php echo $invoice_no;  ?> <a href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card['job_card_id']; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card['job_card_id']; ?>"><button title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice<?php } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card['job_card_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card['job_card_id']; ?>"><button title="Edit this entry" class="btn "><span class="delete">E</span></button></a>
            </td>
          
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/EMI_reports/custom/index.php?action=fromHomeUpcoming"><div class="more">View all Jobcards..</div></a>

<div style="clear:both"></div>
</div>
       
  <?php 
			}
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
      
     <div class="Column">
     
        
        <h4 class="widgetTitle"> Upcoming Service Dates </h4>
         
         <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
             <th class="heading">Job No</th>
             <th class="heading">Date</th>
           <th class="heading">Next Service </th>
             <th class="heading">Reg No</th>
           <th class="heading">Contact No</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$job_cards = generalJobCardReportsByNextServiceDate(date('d/m/Y'),date('d/m/Y',strtotime(getTodaysDateTimeAfterDays(3))));
		$no=0;
		foreach($job_cards as $job_card)
		{
		$vehicle_id = $job_card['vehicle_id'];
		$vehicle = getVehicleById($vehicle_id);	
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$customer = getCustomerDetailsByCustomerId($job_card['customer_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card['job_card_id']);
		$receipt_amount = getReceiptAmountAndKasarAmountForJobCardId($job_card['job_card_id']);
		$kasar = $receipt_amount[1];
		$receipt_amount=$receipt_amount[0];
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           	<td><?php echo $customer['customer_name']; ?></td>
            <td><?php echo $job_card['job_card_no']; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
          <td><?php echo date('d/m/Y',strtotime($job_card['max_next_service_date'])); ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td><?php echo $job_card['contact_no']; ?>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card['job_card_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card['job_card_id']; ?>"><button title="Edit this entry" class="btn "><span class="delete">E</span></button></a>
            </td>
          
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>



<div style="clear:both"></div>
</div>

<?php 
			}     } 
  ?>
  
  <?php
 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(105,$report_rights) || in_array(199,$report_rights)))
			{ ?>
   
    <div class="Column">
     
        
        <h4 class="widgetTitle"> Upcoming Reminders </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">Date</th>
            <th class="heading">Remarks</th>
            <th class="heading">Name</th>
            <th class="heading">Contact No.</th>
             <th class="heading">Reg No.</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 $upcomingEmis=generalRemianderReportsWidget(date('d/m/Y'));
			$mj=0;
			if(is_array($upcomingEmis) && count($upcomingEmis)>0)
			{
		    foreach($upcomingEmis as $upEmi)
			{
			
		?>
        
         <tr class="resultRow">
        	
            
            <td><?php echo date('d/m/Y',strtotime($upEmi['date'])); ?>
            </td>
            
            <td><?php echo $upEmi['remarks']; ?>
            </td>
            
             <td><?php echo $upEmi['customer']['customer_name']; ?>
            </td>
            
            
            <td><?php echo $upEmi['customer']['contact_no'][0][0]; ?>
            </td>
            
            
             <td><?php if($upEmi['reg_no']=="" || $upEmi['reg_no']==null) echo "NA"; else echo $upEmi['reg_no']; ?>
            </td>
            
           
          
            
             	<td class="no_print"><a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=addRemainder&id=<?php echo $upEmi['customer']['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
                					
            </td>
            
  
        </tr>
        <?php
		$mj++;
		if($mj==5) break;
			}
		}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/remainder_reports/custom/index.php?action=fromHomeUpcoming"><div class="more">View all Remainders..</div></a>

<div style="clear:both"></div>
</div>

 <div class="Column">
     
        
        <h4 class="widgetTitle"> Expired Reminders </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
           
        	<th class="heading">Date</th>
            <th class="heading">Remarks</th>
            <th class="heading">Name</th>
            
            <th class="heading">Contact No.</th>
              <th class="heading">Reg No.</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 
		 $upcomingEmis=generalRemianderReportsWidget(null,date('d/m/Y'));
		 
			$mj=0;
			if(is_array($upcomingEmis) && count($upcomingEmis)>0)
			{
		    foreach($upcomingEmis as $upEmi)
			{
			
		?>
        
         <tr class="resultRow">

            <td><?php  if(date('d/m/Y',strtotime($upEmi['date']))=='01/01/1970') echo "NA"; else echo date('d/m/Y',strtotime($upEmi['date'])); ?>
            </td>
            
            <td><?php echo $upEmi['remarks']; ?>
            </td>
            
             <td><?php echo $upEmi['customer']['customer_name']; ?>
            </td>
            
            
            <td><?php echo $upEmi['customer']['contact_no'][0][0]; ?>
            </td>
            
             <td><?php if($upEmi['reg_no']=="" || $upEmi['reg_no']==null) echo "NA"; else echo $upEmi['reg_no']; ?>
            </td>
            
            
            
            
             	<td class="no_print"><a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=addRemainder&id=<?php echo $upEmi['customer']['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
                					
            </td>
            
             
          
  
        </tr>
        <?php
		$mj++;
		if($mj==5) break;
			}
			}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/remainder_reports/custom/index.php?action=fromHomeExpired"><div class="more">View all Remainders..</div></a>

<div style="clear:both"></div>
</div>
<?php }
?>   
<?php if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>

 <?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>      
        
        <div class="Column">
        <h4 class="widgetTitle"> 6.) Soon to be expired Insurance </h4>
        
         <table class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">Expiry Date</th>
            <th class="heading">Insurance Company</th>
            <th class="heading">Name</th>
            <th class="heading">Contact No.</th>
             <th class="heading">Vehicle No.</th>
              <th class="heading">File No.</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
     <tbody>
         <?php
		 $soonInsurances=generalInsuranceReportsWidget();
		
		 if(is_array($soonInsurances) && count($soonInsurances)>0)
			{
		    for($i=0; $i<count($soonInsurances); $i++)
			{
			$insurance=$soonInsurances[$i];
		?>
        
         <tr class="resultRow">
        	
            <td><?php echo $insurance['insurance_expiry_date']; ?>
            </td>
            
            <td><?php  $comp = getInsuranceCompanyById($insurance['insurance']['insurance_company_id']); echo $comp[1]; ?>
            </td>
            
             <td><?php echo $insurance['customer']['customer_name']; ?>
            </td>
            
            
            <td><?php echo $insurance['customer']['contact_no'][0][0]; ?>
            </td>
            
            
             <td><?php if($insurance['reg_no']=="" || $insurance['reg_no']==null) echo "NA"; else echo $insurance['reg_no']; ?>
            </td>
            
             <td><?php echo $upEmi['file_no']; ?>
            </td>
          
           
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $insurance['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
        <?php
			}
			}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/Insurance_reports/custom/index.php?action=fromHomeUpcoming"><div class="more">View all Insurances..</div></a>

<div style="clear:both"></div>
</div>
       
        
        
        <div class="Column">
        <h4 class="widgetTitle"> 7.) Already Expired Insurance </h3>
        
         <table class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">Expiry Date</th>
            <th class="heading">Insurance Company</th>
            <th class="heading">Name</th>
            <th class="heading">Contact No.</th>
             <th class="heading">Vehicle No.</th>
              <th class="heading">File No.</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		     $expiredInsurances=expiredInsuranceReportsWidget();
			if(is_array($expiredInsurances) && count($expiredInsurances)>0)
			{
		    for($i=0; $i<count($expiredInsurances); $i++)
			{
			$eInsurance=$expiredInsurances[$i];
			
		?>
        
         <tr class="resultRow">
        	
            <td><?php echo $eInsurance['insurance_expiry_date']; ?>
            </td>
            
            <td><?php  $comp = getInsuranceCompanyById($eInsurance['insurance']['insurance_company_id']); echo $comp[1]; ?>
            </td>
            
             <td><?php echo $eInsurance['customer']['customer_name']; ?>
            </td>
            
            
            <td><?php echo $eInsurance['customer']['contact_no'][0][0]; ?>
            </td>
            
            
             <td><?php if($eInsurance['reg_no']=="" || $eInsurance['reg_no']==null) echo "NA"; else echo $eInsurance['reg_no']; ?>
            </td>
            
            <td><?php echo $upEmi['file_no']; ?>
            </td>
          
            
             	<td class="no_print"> <a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $eInsurance['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
        <?php
			}
			}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/Insurance_reports/expired/index.php?action=fromHomeExpired"><div class="more">View all Insurances..</div></a>

<div style="clear:both"></div>
</div> 
       
<?php } ?>    
    
    <div class="Column">
     
        
        <h4 class="widgetTitle"> UnPaid Financer Receipts </h4>
         
         <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
             <th class="heading">Amount</th>
             <th class="heading">Financer</th>
             <th class="heading">Customer Name</th>
             <th class="heading">Reg No</th>
            
        </tr>
    </thead>
    <tbody>
        
        <?php
		$job_cards = getUnPaidReceiptsBeforeDate();
		$no=0;
		foreach($job_cards as $job_card)
		{
		
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($job_card['trans_date'])); ?>
            </td>
            <td><?php echo $job_card['amount']; ?>
            </td>
            <td><?php echo $job_card['ledger_name']; ?>
            </td>
            <td><?php echo $job_card['customer_name']; ?>
            </td>
            <td><?php echo getVehicleRegNoStringForCustomer($job_card['customer_id']); ?></td>
          
            
            
          
  
        </tr>
         <?php }?>
         
         </tbody>
    </table>

<!-- <a href="<?php echo WEB_ROOT ?>admin/reports/EMI_reports/custom/index.php?action=fromHomeUpcoming"><div class="more">View all Jobcards..</div></a> -->

<div style="clear:both"></div>
</div>
    <?php } ?>
 </div>
 </div>
 <div class="clearfix"></div>
<?php
require_once("../inc/footer.php");
 ?> 