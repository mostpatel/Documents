<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$customer_id=$_GET['id'];
$customer=getCustomerDetailsByCustomerId($customer_id);
if(is_array($customer) && $customer!="error")
{
	if($customer!="error")
	{
	$oc_id = $customer['oc_id'];
	$account_settings = getAccountsSettingsForOC($oc_id);	
	$remarks=listRemainderForCustomer($customer_id);
	$vehicles = listVehiclesForCustomer($customer_id);
	$delivery_challans = listDeliveryChallansForCustomer($customer_id);
	$vehicle_invoices = listVehicleInvoicesForCustomer($customer_id);
	$job_cards = listJobCardsForCustomer($customer_id);
	$latest_transaction_next_day_date = getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']));
	$all_transaction=getAllTransactionsForLedgerId('C'.$customer_id,NULL,$account_settings['ac_starting_date'],        $latest_transaction_next_day_date);
	$sales = generateSalesReport(NULL,NULL,NULL,NULL,$customer_id);
	$ac_delivery_challans = getAllACDeliveryChallansForCustomerId($customer_id);
	$customer_groups = getGroupsForCustomerId($customer_id);
	$broker_groups = getBrokersForCustomerId($customer_id);
	}
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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

<div class="addDetailsBtnStyling no_print"> 
<?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/delivery_challan/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning">Sell Vehicle / Make Delivery Challan</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning">+ Add Vehicle</button></a> <?php } ?> 

<a href="<?php echo WEB_ROOT; ?>admin/customer/receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Receipt</button></a> 

<?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/financer_receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Financer Receipt</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle_receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Vehicle Receipt</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle_payment/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning"> Vehicle Payment </button></a>
<?php } ?>

<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/delivery_challan/index.php?cid=<?php echo $customer_id; ?>"><button class="btn btn-success"><?php if(TAX_MODE==1) { ?> Order <?php } else { ?> Delivery Challan <?php } ?> </button></a>

 <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php?cid=<?php echo $customer_id; ?>"><button class="btn btn-success"><?php if(TAX_MODE==1) { ?> Invoice <?php } else { ?> Sale <?php } ?> </button></a>
 <?php  if(defined('EDMS_MODE') && EDMS_MODE==0) { ?>
 <a href="<?php echo WEB_ROOT; ?>admin/customer/payment/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/jv/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> JV </button></a>
 <a href="<?php echo 'index.php?action=deleteCustomer&lid='.$customer_id; ?>"><button title="Delete this entry" class="btn delBtn btn-danger">Delete Customer</button></a>  
 <?php } ?> 
 </div>
 <?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
 

<div class="addDetailsBtnStyling no_print">  <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add / View Reminder</button></a> <span class="noOfRemainders"><b><?php if($remarks!=false) echo count($remarks)." Pending Reminders!"; ?></b></span> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=customerGroup&id=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add to group</button></a>  <a href="<?php echo 'index.php?action=deleteCustomer&lid='.$customer_id; ?>"><button title="Delete this entry" class="btn delBtn btn-danger">Delete Customer</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/payment_for_customer/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning"> Give Payment For Customer</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/payment/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/jv/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> JV </button></a> <a href="<?php echo WEB_ROOT; ?>admin/reports/job_card_reports/custom_customer_wise/index.php?customer_id=<?php echo $customer_id; ?>"><button class="btn btn-success"> JobCard Reports </button></a></div>
<?php } 
// if not in edms mode
else {  ?>

<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=customerGroup&id=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add to group</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=brokerGroup&id=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add to <?php echo BROKER_NAME; ?> Group</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add / View Reminder</button></a> <span class="noOfRemainders"><b><?php if($remarks!=false) echo count($remarks)." Pending Reminders!"; ?></b></span>   </div>
<?php } ?>



<div class="detailStyling" style="min-height:300px">

<h4 class="headingAlignment">Customer's Details</h4>

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">

<tr>

<td width="150px" class="firstColumnStyling">
Customer No : 
</td>

<td>

                             <?php echo $customer['customer_no']; ?>					
                            
</td>
</tr>


<tr>

<td width="150px" class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php echo $customer['customer_name']; ?>					
                            
</td>
</tr>

<tr>
<td>
Address : 
</td>

<td>

                             <?php echo $customer['customer_address']; ?>					
                            
</td>
</tr>


<tr>
<td>City : </td>
				<td>

                             <?php $cid = $customer['city_id'];
							 		
							       $cityDetails = getCityByID($cid);
								   echo $cityDetails['city_name'];
							?>
                            </td>
</tr>

<tr>
<td>Pincode : </td>
<td>

                             <?php if($customer['customer_pincode']!=0) echo $customer['customer_pincode']; else echo "NA"; ?>					
                          
</td>
</tr>

 <tr id="addcontactTrCustomer">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                <?php
                            $contactNumbers = $customer['contact_no'];
							
                           for($z=0;$z<count($contactNumbers);$z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?>
                </td>
            </tr>
<tr>
<td>PAN No : </td>
<td><?php if(validateForNull($customer['pan_no'])) echo $customer['pan_no']; else echo "NA"; ?></td>
</tr>


<tr>
<td>TIN No : </td>
<td><?php if(validateForNull($customer['tin_no'])) echo $customer['tin_no'];  else echo "NA"; ?></td>
</tr>

<tr>
<td>CST No : </td>
<td><?php if(validateForNull($customer['cst_no'])) echo $customer['cst_no']; else echo "NA"; ?></td>
</tr>


<tr>
<td>Service Tax No : </td>
<td><?php if(validateForNull($customer['service_tax_no'])) echo $customer['service_tax_no'];  else echo "NA"; ?></td>
</tr>

<tr>
<td>Opening Balance : </td>
<td><?php echo $customer['opening_balance'];  if(isset($customer['opening_cd']) && $customer['opening_cd']==0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>

<tr>
<td>Current Balance (<?php echo date('d/m/Y',strtotime(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); ?>): </td>
<td><?php $current_balance = getOpeningBalanceForLedgerForDate('C'.$customer['customer_id'],getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); if($current_balance>=0) echo round($current_balance,2); else echo round(-$current_balance,2);  if(isset($current_balance) && $current_balance>=0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>

<tr>
<td>Notes : </td>
<td><?php if(validateForNull($customer['notes'])) echo $customer['notes'];  else echo "NA"; ?></td>
</tr>

<tr>
<td>Customer Groups : </td>
<td><?php if(is_array($customer_groups)) { foreach($customer_groups as $customer_group) echo $customer_group['group_name']."<br>"; }  else echo "NOT ADDED"; ?></td>
</tr>

<tr>
<td><?php echo BROKER_NAME; ?> Groups : </td>
<td><?php if(is_array($broker_groups)) { foreach($broker_groups as $customer_group) echo $customer_group['ledger_name']."<br>"; }  else echo "NOT ADDED"; ?></td>
</tr>
        

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=customerDetails&id='.$customer_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&id='.$customer_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>
<?php if(defined('BIG_CUST_BAL') && BIG_CUST_BAL==1) { ?>
<div class="detailStyling">

<h4 class="headingAlignment">Customer Current Balance </h4>


<table class="insertTableStyling detailStylingTable">
<tr>
<td style="font-size:22px;">Current Balance (<?php echo date('d/m/Y',strtotime(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); ?>): </td>
<td style="font-size:22px;"><?php $current_balance = getOpeningBalanceForLedgerForDate('C'.$customer['customer_id'],getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); if($current_balance>=0) echo round($current_balance,2); else echo round(-$current_balance,2);  if(isset($current_balance) && $current_balance>=0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>

</table>
</div>
<?php } ?>
<?php if($remarks!=false && is_array($remarks) && count($remarks)>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Remarks Details </h4>


<table class="insertTableStyling detailStylingTable">

<?php foreach($remarks as $remark)
{
?> 
<tr>
    <td class="firstColumnStyling">
   <?php  if($remark['date']=='1970-01-01' || $remark['date']=='0000-00-00')  {?> Remark : <?php } else {  echo date('d/m/Y',strtotime($remark['date'])); } ?> 
    </td>
    
    <td>
     
                                 <?php echo $remark['remarks']; ?>					
                               
    </td>
</tr>

<?php
}
 ?>
 
 
 
 

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/party/index.php?view=addRemainder&id=<?php echo $customer_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 




</table>
</div>

 
<?php } ?>
 <?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<?php if($vehicles && is_array($vehicles) && count($vehicles)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Vehicles</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable1" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            
              <th class="heading">Model</th>
             <th class="heading">Reg No</th>
             <th class="heading">Chasis No </th>
             <th class="heading">Insurance Exp Date</th>
              <th class="heading">Nxt Service Date</th>
             <th class="heading">Sold</th>
            <th class="heading">Job Card</th>
              <th class="heading">Balance</th>
              <th class="heading">Purcahse Balance </th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($vehicles as $vehicle)
		{
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$insurance = getLatestInsuranceDetailsForVehicleID($vehicle['vehicle_id']);
			
		$remaining_balance = getRemainingBalanceForVehicleId($vehicle['vehicle_id']);
	    $next_service_date=getNextServiceDateForVehicleId($vehicle['vehicle_id']);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
           
            <td><?php echo $vehicle_model['model_name']; ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_chasis_no']; ?>
            </td>
             
             <td align="center"><?php if($insurance) echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); else echo "-"; ?>
             <br />
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/insurance/index.php?&id='.$vehicle['vehicle_id'].'&state='.$customer_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning no_print"><span class="">Add Insurance</span></button></a>
            </td>
              <td align="center"><?php if($next_service_date) echo date('d/m/Y',strtotime($next_service_date)); else echo "-"; ?></td>
             <td><?php if( $vehicle['is_sold_by_customer']==1) echo "YES"; else echo "NO"; ?>
            </td>
            <td class="no_print" width="120px;"> 
            
            <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?&id='.$vehicle['vehicle_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Job Card</span></button></a>
            </td>
             <td>
             <?php  echo number_format($remaining_balance); if($remaining_balance>=0) echo " DR"; else echo " CR"; ?>
             </td>
             <td><?php  $purchase_balance=getRemainingPurchaseBalanceForVehicleId($vehicle['vehicle_id']); if($purchase_balance>=0) echo number_format($purchase_balance); else echo number_format(-$purchase_balance); if($purchase_balance>=0) echo " DR"; else echo " CR"; ?>
            </td>
     
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/index.php?view=details&id='.$vehicle['vehicle_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/index.php?view=editVehicle&id='.$vehicle['vehicle_id'].'&access=approved'; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/index.php?action=deleteVehicle&lid='.$vehicle['vehicle_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print1" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>

<?php if($job_cards && is_array($job_cards) && count($job_cards)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Job Cards</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Job No</th>
             <th class="heading">Date</th>
             <th class="heading">Model</th>
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
		
		$no=0;
		foreach($job_cards as $job_card)
		{
		$vehicle_id = $job_card['vehicle_id'];
		$vehicle = getVehicleById($vehicle_id);	
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card['job_card_id']);
		$receipt_amount = getReceiptAmountForJobCardId($job_card['job_card_id']);
		
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo $job_card['job_card_no']; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
            <td><?php echo $vehicle_model['model_name']; ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td>Rs.<?php echo round($job_card['total_amount']); ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
             <td align="center">Rs.<?php echo round(round($job_card['total_amount'])-$receipt_amount); ?>
             <br />
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    
            <td class=""> <?php echo $invoice_no;  ?> <a class="no_print" href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card['job_card_id']; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card['job_card_id']; ?>"><button title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice <?php  } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card['job_card_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card['job_card_id']; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?action=delete&id='.$job_card['job_card_id'];?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print3" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>


<?php if($delivery_challans && is_array($delivery_challans) && count($delivery_challans)>0) { ?>


<div class="detailStyling" style="width:100%;">


<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Delivery Challans</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
  <table id="adminContentTable" class="adminContentTable">

    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
             <th class="heading">Challan No</th>
             <th class="heading">Model</th>
            <th class="heading">Engine No </th>
             <th class="heading">Chasis No </th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($delivery_challans as $delivery_challan)
		{
			$vehicle_id = $delivery_challan['vehicle_id'];
			$vehicle = getVehicleById($vehicle_id);
			$invoice = getVehicleInvoiceByDeliveryChallanId($delivery_challan['delivery_challan_id']);
			$form21 = getSaleCertByDeliveryChallanId($delivery_challan['delivery_challan_id']);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($delivery_challan['delivery_date'])); ?>
            </td>
            <td><?php echo $delivery_challan['challan_no']; ?>
            </td>
            <td><?php echo getModelNameById($vehicle['model_id']); ?>
            </td>
            <td><?php echo $vehicle['vehicle_engine_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_chasis_no']; ?>
            </td>
              <td class="no_print" width="120px;"> <a href="<?php if($invoice){ echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.$delivery_challan['delivery_challan_id'];} else echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?id='.$delivery_challan['delivery_challan_id']; ?>"><button style="width:120px;" title="View this entry" class="btn <?php if($invoice){ ?> btn-success<?php }else { ?> btn-warning<?php } ?>"><span class=""><?php if($invoice){ ?> View Invoice<?php } else { ?>Add Invoice<?php } ?></span></button></a> 
              <a style="margin-top:10px;position:relative;display:block;" href="<?php if($form21){echo WEB_ROOT.'admin/customer/vehicle_form21/index.php?view=details&id='.$delivery_challan['delivery_challan_id'];} else echo WEB_ROOT.'admin/customer/vehicle_form21/index.php?id='.$delivery_challan['delivery_challan_id']; ?>"><button style="width:120px;" title="View this entry" class="btn<?php if($form21){ ?> btn-success <?php }else{ ?> btn-warning<?php } ?>"><span class=""><?php if($form21){ ?> View Form  21<?php }else { ?> Add Form 21 <?php } ?></span></button></a>
            </td>
             
              <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?view=details&id='.$delivery_challan['delivery_challan_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?view=edit&id='.$delivery_challan['delivery_challan_id']; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?action=delete&id='.$delivery_challan['delivery_challan_id']."&state=".$customer_id;; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>

<?php if($vehicle_invoices && is_array($vehicle_invoices) && count($vehicle_invoices)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Vehicle Invoices</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div id="vehicle_invoices" class="no_print">
    <table id="adminContentTable2" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
             <th class="heading">Invoice No</th>
             <th class="heading">Model</th>
            <th class="heading">Reg No </th>
             <th class="heading">Engine No </th>
             <th class="heading">Chasis No </th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($vehicle_invoices as $delivery_challan)
		{
			
			$vehicle_id = $delivery_challan['vehicle_id'];
			$vehicle = getVehicleById($vehicle_id);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($delivery_challan['invoice_date'])); ?>
            </td>
            <td><?php echo $delivery_challan['invoice_no']; ?>
            </td>
            <td><?php echo getModelNameById($vehicle['model_id']); ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
            <td><?php echo $vehicle['vehicle_engine_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_chasis_no']; ?>
            </td>
     
              <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.$delivery_challan['delivery_challan_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=edit&id='.$delivery_challan['delivery_challan_id'].'&state='.$vehicle_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?action=delete&id='.$delivery_challan['vehicle_invoice_id']."&state=".$customer_id;  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
    
       <table id="to_print2" class="to_print adminContentTable"></table> 
       
     
</div> 
<?php } ?>
 <?php } ?>
  <div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of <?php echo DELIVERY_CHALLAN_NAME; ?> </h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable5" class="adminContentTable">
    <thead>
    	<tr>
        	 <th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading">Company</th>
              <th class="heading"><?php echo DELIVERY_CHALLAN_NAME; ?> No</th>
              <th class="heading">Debit</th>
               <th class="heading">Print</th>
              <th class="heading"><?php echo SALES_NAME; ?></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($ac_delivery_challans as $receipt)
		{
			if(is_numeric($receipt['to_ledger_id']))
			{
				$debit_name = getLedgerNameFromLedgerId($receipt['to_ledger_id']);
			}
			else 
			{
				$debit_name = getCustomerNameByCustomerId($receipt['to_customer_id']);
				
			}
			$our_company=getOurCompanyByID($receipt['oc_id']);
			$our_company_name = $our_company['our_company_name'];
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $our_company_name; ?>
            </td>
            <td><?php echo $receipt['challan_no']; ?>
            </td>
            <td><?php echo $debit_name; ?>
            </td>
          	   <td class="no_print"> <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=delivery_challan&id='.$receipt['delivery_challan_id']; ?>"><button title="Print this entry" class="btn viewBtn">Print</button></a>
            </td>
             <td class="no_print"> <a href="<?php if(is_numeric($receipt['sales_id'])) echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$receipt['sales_id']; else echo  WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?id='.$receipt['delivery_challan_id']; ?>"><button title="View this entry" class="btn viewBtn <?php if(is_numeric($receipt['sales_id'])) echo "btn-success"; else echo "btn-warning"; ?>"><?php if(is_numeric($receipt['sales_id'])) echo "View"; else echo "Create"; ?> Invoice</button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=details&id='.$receipt['delivery_challan_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=edit&id='.$receipt['delivery_challan_id']; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?action=delete&lid='.$receipt['delivery_challan_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print5" class="to_print adminContentTable"></table> 
       </div> 
 <div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />
   <h4 class="headingAlignment">List of <?php echo SALES_NAME; ?></h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
             <th class="heading">Due Days</th>
              <th class="heading">Type</th>
              <th class="heading">Amount</th>
              <th class="heading">Company</th>
              <th class="heading">Debit</th>
              <th class="heading">Credit</th>
              <th class="heading">Received</th>
              <th class="heading">Balance</th>
              <th class="heading">Invoice</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($sales as $receipt)
		{
			$sales_id=$receipt['sales_id'];
			$sales=getSaleById($sales_id);
			$receipt_amount = getReceiptAmountForSalesId($sales_id);
			$tax_amount = getTotalTaxForSalesId($sales_id);
			$total_amount = $receipt['amount'] + $tax_amount;
			$remaining_amount = $total_amount - $receipt_amount;
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
              <td>
            <?php echo floor((strtotime(getTodaysDateTime()) - strtotime($receipt['trans_date']) ) / (60*60*24) );  ?>
            </td>
             <td><?php $invoice_type= getInvoiceTypeById($sales['retail_tax']); echo $invoice_type['invoice_type']; ?></td>
            <td><?php echo $total_amount." Rs"; ?>
            </td>
            
             <td><?php echo $receipt['our_company_name']; ?>
            </td>
            <td><?php if(is_numeric($receipt['from_ledger_id']))echo $receipt['from_ledger_name']; else echo $receipt['from_customer_name']; ?>
            </td>
          	 <td><?php if(is_numeric($receipt['to_ledger_id'])) echo $receipt['to_ledger_name']; else echo $receipt['customer_name']; ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=allReceipts&id='.$sales_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Receipt</span></button></a>
            </td>
             <td align="center" ><?php echo number_format($remaining_amount)." Rs"; ?>  <br /> <?php if($remaining_amount>0) { ?> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?sales_id='.$receipt['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-warning">Add Receipt</button></a> <?php } ?>
            </td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=invoice&id='.$receipt['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-success">Print Invoice</button></a>
             <?php echo $sales['invoice_no'] ?>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$receipt['sales_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=edit&id='.$receipt['sales_id']; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?action=delete&lid='.$receipt['sales_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print3" class="to_print adminContentTable"></table> 
       </div>
       
   
</div>
<div class="clearfix"></div>
<style>
.printSectionBtn{
	float:right;
	}
</style>
<script>
$('.printSectionBtn').click(function(e) {

    var div_el = $(this).parent().next();
	
	  var htmlToPrint = $("#adminContentTable").html(); 
		if(!htmlToPrint)
		 var htmlToPrint = $("#adminContentReport").html();
		 if(!htmlToPrint)
		 var htmlToPrint = $("#accountContentTable").html();
			
	
		if(document.getElementById('to_print'))
 		document.getElementById('to_print').innerHTML=htmlToPrint;
		
			 
		
		var no_tables =document.no_of_tables;

for(var o=1;o<=no_tables;o++)
{

	var htmlToPrint = $("#adminContentTable"+o).html(); 
	
	if(htmlToPrint)
	document.getElementById('to_print'+o).innerHTML=htmlToPrint;
	
	
}
	
	
	printSection(div_el[0]);
});
function printSection(prtContent)
{
var head_content = $('head')[0].innerHTML;	

var pre_content = "<html><head>"+head_content+"</head><body>";      
var WinPrint = window.open('', '', 'left=0,top=0,width=1200,height=900,toolbar=0,scrollbars=0,status=0');
var print_content=prtContent.innerHTML;
var after_content = "</body></html>";
var print_content = pre_content+print_content+after_content;

WinPrint.document.write(print_content);
WinPrint.document.close();
WinPrint.focus();
sleep(2000);
WinPrint.print();
WinPrint.close();
}
function sleep(ms) {
    var unixtime_ms = new Date().getTime();
    while(new Date().getTime() < unixtime_ms + ms) {}
}
document.no_of_tables=3;
</script>