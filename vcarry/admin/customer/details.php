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
	$shipping_locations = listShippingLocationForCustomerId($customer_id);
	$owner =getOwnerDetailsByCustomerId($customer_id);
	$account_settings = getAccountsSettingsForOC($oc_id);	
	$remarks=listRemainderForCustomer($customer_id);
	$vehicles = listVehiclesForCustomer($customer_id);
	$delivery_challans = listDeliveryChallansForCustomer($customer_id);
	$vehicle_invoices = listVehicleInvoicesForCustomer($customer_id);
	$job_cards = listJobCardsForCustomer($customer_id);
	$latest_transaction_next_day_date = getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']));
	$all_transaction=getAllTransactionsForLedgerId('C'.$customer_id,NULL,$account_settings['ac_starting_date'],        $latest_transaction_next_day_date);
	$sales = generateSalesReport(NULL,NULL,NULL,NULL,$customer_id,0,4);
	
	$ac_delivery_challans = getAllACDeliveryChallansForCustomerId($customer_id);
	$customer_groups = getGroupsForCustomerId($customer_id);
	$broker_groups = getBrokersForCustomerId($customer_id);
	$contact_person_details_array = getAllContactPersonDetailsForCustomerId($customer_id);
	
	$all_trips = getAllTripsForCustomer($customer_id,implode(",",getAllUnfinishedTripStatuses()));
	$finished_trips = getAllTripsForCustomer($customer_id,implode(",",getAllfinishedTripStatuses()));
	$cancelled_trips = getAllTripsForCustomer($customer_id,implode(",",getAllCancelledTripStatuses()));
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
	$trip_id = $_SESSION['ack']['trip_id'];
	
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
	if(is_numeric($trip_id))
		$_SESSION['ack']['trip_id']="";
}

?>

<div class="addDetailsBtnStyling no_print"> 
<?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/delivery_challan/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning">Sell Vehicle / Make Delivery Challan</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning">+ Add Vehicle</button></a> <?php } ?> 
<!-- 
<a href="<?php echo WEB_ROOT; ?>admin/customer/receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Receipt</button></a> -->

<?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/financer_receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Financer Receipt</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle_receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Vehicle Receipt</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle_payment/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning"> Vehicle Payment </button></a>
<?php } ?>
<!--
<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/delivery_challan/index.php?cid=<?php echo $customer_id; ?>"><button class="btn btn-success"><?php if(TAX_MODE==1) { ?> Order <?php } else { ?> Delivery Challan <?php } ?> </button></a>

 <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php?cid=<?php echo $customer_id; ?>"><button class="btn btn-success"><?php if(TAX_MODE==1) { ?> Invoice <?php } else { ?> Sale <?php } ?> </button></a> -->
 <!--
 <?php  if(defined('EDMS_MODE') && EDMS_MODE==0) { ?>
 <a href="<?php echo WEB_ROOT; ?>admin/customer/payment/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/jv/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> JV </button></a>
 <a href="<?php echo 'index.php?action=deleteCustomer&lid='.$customer_id; ?>"><button title="Delete this entry" class="btn delBtn btn-danger">Delete Customer</button></a>  
 <?php } ?>  
 -->
 <a href="<?php echo WEB_ROOT; ?>admin/customer/shipping_locations/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning"> Add Shipping Location</button></a>
 <?php if(!$owner) { ?>
 <a href="<?php echo WEB_ROOT; ?>admin/customer/owner/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning"> Add Owner / Key Person</button></a>
 <?php } ?>
  <a href="<?php echo WEB_ROOT; ?>admin/customer/contact_person/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-warning"> Add Contact Person</button></a>
 <a href="<?php echo 'index.php?action=deleteCustomer&lid='.$customer_id; ?>"><button title="Delete this entry" class="btn delBtn btn-danger">Delete Customer</button></a>  
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
Customer No: 
</td>

<td>

                             <?php echo $customer['customer_no']; ?>					
                            
</td>
</tr>


<tr>

<td width="150px" class="firstColumnStyling">
Date of Joining : 
</td>

<td>

                             <?php echo date('d/m/Y',strtotime($customer['date_of_joining'])); ?>					
                            
</td>
</tr>


<tr>

<td width="150px" class="firstColumnStyling">
Name: 
</td>

<td>

                             <?php echo getPrefixNameById($customer['prefix_id'])." ".$customer['customer_name']; ?>					
                            
</td>
</tr>

<tr>
<td>
Address Line 1: 
</td>

<td>

                             <?php echo $customer['customer_address']; ?>					
                            
</td>
</tr>

<tr>
<td>
Address Line 2: 
</td>

<td>

                             <?php echo $customer['customer_address2']; ?>					
                            
</td>
</tr>

<tr>
<td>Area: </td>
				<td>

                             <?php $cid = $customer['area_id'];
							 		
							       $cityDetails = getAreaByID($cid);
								   echo $cityDetails['area_name'];
							?>
                            </td>
</tr>

<tr>
<td>City: </td>
				<td>

                             <?php $cid = $customer['city_id'];
							 		
							       $cityDetails = getCityByID($cid);
								   echo $cityDetails['city_name'];
							?>
                            </td>
</tr>

<tr>
<td>Pincode: </td>
<td>

                             <?php if($customer['customer_pincode']!=0) echo $customer['customer_pincode']; else echo "NA"; ?>					
                          
</td>
</tr>

 <tr id="addcontactTrCustomer">
                <td>
             Company Contact No: 
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
<td>Notes: </td>
<td><?php if(validateForNull($customer['notes'])) echo $customer['notes'];  else echo "NA"; ?></td>
</tr>

<tr>
<td>Customer Groups: </td>
<td><?php if(is_array($customer_groups)) { foreach($customer_groups as $customer_group) echo $customer_group['group_name']."<br>"; }  else echo "NOT ADDED"; ?></td>
</tr>

<tr>
<td><?php echo BROKER_NAME; ?> Groups: </td>
<td><?php if(is_array($broker_groups)) { foreach($broker_groups as $customer_group) echo $customer_group['ledger_name']."<br>"; }  else echo "NOT ADDED"; ?></td>
</tr>
       
<tr>
<td>Opening Balance: </td>
<td><?php echo $customer['opening_balance'];  if(isset($customer['opening_cd']) && $customer['opening_cd']==0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>

<tr>
<td>Current Balance (<?php echo date('d/m/Y',strtotime(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); ?>): </td>
<td <?php
$current_balance = getOpeningBalanceForLedgerForDate('C'.$customer['customer_id'],getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id'])));
 if(isset($current_balance) && $current_balance>0) { ?> style="color:#E26C6E;font-size:20px;" <?php } else { ?> style="color:#66D581" <?php } ?>><?php  if($current_balance>=0) echo round($current_balance,2); else echo round(-$current_balance,2);  if(isset($current_balance) && $current_balance>=0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>
 

<tr>
	<td></td>
  <td class="no_print"> 
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&id='.$customer_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>
<?php $cp=0; foreach($contact_person_details_array as $contact_person_details) { ?>
<div class="detailStyling">

<h4 class="headingAlignment">Contact Person Details </h4>


<table class="insertTableStyling detailStylingTable">

<tr>
<td>Contact Person: </td>
<td><?php if(validateForNull($contact_person_details['cp_name'])) echo getPrefixNameById($contact_person_details['prefix_id'])." ".$contact_person_details['cp_name']; else echo "NA"; ?></td>
</tr>


<tr>
<td>Contact Person No: </td>
<td><?php if(validateForNull($contact_person_details['cp_contact_no_1'])) echo $contact_person_details['cp_contact_no_1'];  else echo "NA"; ?></td>
</tr>


<tr>
<td>Email: </td>
<td><?php if(validateForNull($contact_person_details['cp_email'])) echo $contact_person_details['cp_email'];  else echo "NA"; ?></td>
</tr>


<tr>
<td>Contact Person DOB: </td>
<td><?php if(validateForNull($contact_person_details['cp_dob']) && $contact_person_details['cp_dob']!="1900-01-01") echo date('d/m/Y',strtotime($contact_person_details['cp_dob']));  else echo "NA"; ?></td>
</tr>

<tr>
<td>Anniversary: </td>
<td><?php if(validateForNull($contact_person_details['cp_anniversary']) && $contact_person_details['cp_anniversary']!="1900-01-01") echo date('d/m/Y',strtotime($contact_person_details['cp_anniversary']));  else echo "NA"; ?></td>
</tr>

<?php if($contact_person_details['primary_cp']==0) { ?>
 <tr>
	<td></td>
  <td class="no_print">
            
             <a href="<?php echo 'contact_person/index.php?view=edit&id='.$contact_person_details['cp_id']; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>   
 <?php } ?>
 
</table>
</div>
<?php $cp++; } ?>
<?php if(isset($owner['owner_name'])) { ?>
<div class="detailStyling">

<h4 class="headingAlignment">Owner / Key Person Details </h4>


<table class="insertTableStyling detailStylingTable">

<tr>
<td>Owner: </td>
<td><?php if(validateForNull($owner['owner_name'])) echo getPrefixNameById($owner['prefix_id'])." ".$owner['owner_name']; else echo "NA"; ?></td>
</tr>


<tr>
<td>Contact No: </td>
<td><?php if(validateForNull($owner['owner_contact_no_1'])) echo $owner['owner_contact_no_1'];  else echo "NA"; ?></td>
</tr>


<tr>
<td>Email: </td>
<td><?php if(validateForNull($owner['owner_email'])) echo $owner['owner_email'];  else echo "NA"; ?></td>
</tr>



<tr>
<td>DOB: </td>
<td><?php if(validateForNull($owner['owner_dob']) && $owner['owner_dob']!="1900-01-01") echo date('d/m/Y',strtotime($owner['owner_dob']));  else echo "NA"; ?></td>
</tr>

<tr>
<td>Anniversary: </td>
<td><?php if(validateForNull($owner['owner_anniversary']) && $owner['owner_anniversary']!="1900-01-01") echo date('d/m/Y',strtotime($owner['owner_anniversary']));  else echo "NA"; ?></td>
</tr>
 
 <tr>
	<td></td>
  <td class="no_print">
            
             <a href="<?php echo 'owner/index.php?view=edit&id='.$customer_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>     
 
 
</table>
</div>
<?php } ?>
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
   <?php  if($remark['date']=='1970-01-01' || $remark['date']=='0000-00-00')  {?> Remark: <?php } else {  echo date('d/m/Y',strtotime($remark['date'])); } ?> 
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
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />
<h4 class="headingAlignment">List of Shipping Locations</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Name</th>
              <th class="heading">Address</th>
               <th class="heading">Area</th>
             <th class="heading">City</th>
             
              <th class="heading">CP Name</th>
              <th class="heading">Contact</th>
              <th class="heading">Recess</th>
              <th class="heading">Goods Type</th>
              <th class="heading">Weight Range</th>
               <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($shipping_locations as $receipt)
		{
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo $receipt['shipping_name']; ?>
            </td>
            <td><?php echo $receipt['shipping_address']."<br>".$receipt['shipping_address2']; ?>
            </td>
             <td><?php echo $receipt['area_name']; ?></td>
            <td><?php echo $receipt['city_name']; ?>
            </td>
          	
             <td><?php echo $receipt['cp_name']; ?>
            </td>
            <td><?php echo $receipt['cp_contact_no']; ?>
            </td>
             <td><?php echo $receipt['recess_timings_from']." - ".$receipt['recess_timings_to']; ?>
            </td>
             <td><?php echo $receipt['goods_type']; ?>
            </td>
            <td><?php echo $receipt['goods_weight_range']; ?>
            </td>
          <td class="no_print">  <a href="<?php echo WEB_ROOT.'admin/customer/trip/index.php?id='.$customer_id.'&state='.$receipt['shipping_location_id']; ?>"><button title="Add trip from this location" class="btn btn-warning">Add From Trip</button></a>
            </td>
            <td class="no_print">  <a href="<?php echo WEB_ROOT.'admin/customer/shipping_locations/index.php?view=edit&id='.$receipt['shipping_location_id']; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <?php if($receipt['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/shipping_locations/index.php?action=delete&lid='.$receipt['shipping_location_id'].'&customer_id='.$customer_id;  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a><?php } ?>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<?php if($all_trips && is_array($all_trips) && count($all_trips)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Unfinished Trips</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable1" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
              <th class="heading">From</th>
             <th class="heading">To</th>
             <th class="heading">Trip Date</th>
              <th class="heading">Fare</th>
             <th class="heading">Status</th>
            <th class="heading">Vehicle Type</th>
              <th class="heading">Driver</th>
              <th class="heading">Trip Created By</th>
              <th class="heading no_print btnCol"></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($all_trips as $trip)
		{
		$vehicle_type = getVehicleTypeNameById($trip['vehicle_type_id']);
		$driver_name=getDriverNameFromDriverId($trip['driver_id']);
		
		 ?>
          <tr class="resultRow <?php if(!$driver_name) echo "dangerRow"; ?>">
        	<td><?php echo ++$no; ?>
            </td>
           
           
            <td><?php echo $trip['from_shipping_location']; echo "<br>(".getAreaNameByID($trip['from_area_id']).")"; ?>
            </td>
            <td><?php echo $trip['to_shipping_location']; echo "<br>(".getAreaNameByID($trip['to_area_id']).")"; ?>
            </td>
             <td><?php echo date('d/m/Y H:i:s',strtotime($trip['trip_datetime'])); ?>
            </td>
             
             <td align="center"><?php echo $trip['fare']; ?>
            </td>
              <td align="center"><?php echo $trip['status']; ?>
            </td>
            <td class="no_print" width="120px;"> 
            
            <?php echo $vehicle_type; ?>
            </td>
            <td><?php  if($driver_name) echo $driver_name; else echo "NA"; ?></td>
             <td>
             <?php   echo getAdminUserNameByID($trip['last_modified_by']); ?>
             </td>
            <td class="no_print">
           <?php if($trip['trip_status']==1) { ?> 	 <a href="<?php echo 'trip/index.php?view=updateDriver&id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-warning">Update Driver</button></a> <?php } else if($trip['trip_status']>1  && $trip['trip_status']<6) { ?>
  <a href="<?php echo 'trip/index.php?view=updateStatus&id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-danger">Update Status</button></a>
  <?php } ?>
            </td> 
     
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/trip/index.php?view=details&id='.$trip['trip_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"><?php if($vehicle['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/index.php?view=editVehicle&id='.$vehicle['vehicle_id'].'&access=approved'; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a><?php } ?>
            </td>
           <!-- <td class="no_print"> 
           <?php if($vehicle['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?action=deleteVehicle&lid='.$vehicle['vehicle_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a><?php } ?>
            </td> -->
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print1" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>

<?php if($finished_trips && is_array($finished_trips) && count($finished_trips)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Finished Trips</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable2" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
              <th class="heading">From</th>
             <th class="heading">To</th>
             <th class="heading">Trip Date</th>
              <th class="heading">Fare</th>
             <th class="heading">Status</th>
            <th class="heading">Vehicle Type</th>
              <th class="heading">Driver</th>
              <th class="heading">Trip Created By</th>
              <th class="heading no_print btnCol"></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($finished_trips as $trip)
		{
		$vehicle_type = getVehicleTypeNameById($trip['vehicle_type_id']);
		$driver_name=getDriverNameFromDriverId($trip['driver_id']);
		
		 ?>
          <tr class="resultRow <?php if(!$driver_name) echo "dangerRow"; ?>">
        	<td><?php echo ++$no; ?>
            </td>
           
           
            <td><?php echo $trip['from_shipping_location']; echo "<br>(".getAreaNameByID($trip['from_area_id']).")"; ?>
            </td>
            <td><?php echo $trip['to_shipping_location']; echo "<br>(".getAreaNameByID($trip['to_area_id']).")"; ?>
            </td>
             <td><?php echo date('d/m/Y H:i:s',strtotime($trip['trip_datetime'])); ?>
            </td>
             
             <td align="center"><?php echo $trip['fare']; ?>
            </td>
              <td align="center"><?php echo $trip['status']; ?>
            </td>
            <td class="no_print" width="120px;"> 
            
            <?php echo $vehicle_type; ?>
            </td>
            <td><?php  if($driver_name) echo $driver_name; else echo "NA"; ?></td>
             <td>
             <?php   echo getAdminUserNameByID($trip['last_modified_by']); ?>
             </td>
            <td class="no_print">
           <?php if($trip['trip_status']==1) { ?> 	 <a href="<?php echo 'trip/index.php?view=updateDriver&id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-warning">Update Driver</button></a> <?php } else if($trip['trip_status']>1  && $trip['trip_status']<6) { ?>
  <a href="<?php echo 'trip/index.php?view=updateStatus&id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-danger">Update Status</button></a>
  <?php } else if($trip['trip_status']==6) { 
  $sales_id=getSalesIdForTripId($trip['trip_id']);
  if(is_numeric($sales_id)) {
  ?>
   <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory?view=invoice&id='.$sales_id.'&cid='.$customer_id.'&trip_id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-success">View invoice</button></a>
  <?php } else { ?>
   <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory?cid='.$customer_id.'&trip_id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-danger">Create invoice</button></a>
  <?php }} ?>
            </td> 
     
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/trip/index.php?view=details&id='.$trip['trip_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"><?php if($vehicle['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/index.php?view=editVehicle&id='.$vehicle['vehicle_id'].'&access=approved'; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a><?php } ?>
            </td>
           <!-- <td class="no_print"> 
           <?php if($vehicle['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?action=deleteVehicle&lid='.$vehicle['vehicle_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a><?php } ?>
            </td> -->
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print2" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>


<?php if($cancelled_trips && is_array($cancelled_trips) && count($cancelled_trips)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Cancelled Trips</h4>
<div class="printBtnDiv no_print"><button class="printSectionBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
              <th class="heading">From</th>
             <th class="heading">To</th>
             <th class="heading">Trip Date</th>
              <th class="heading">Fare</th>
             <th class="heading">Status</th>
            <th class="heading">Vehicle Type</th>
              <th class="heading">Driver</th>
              <th class="heading">Trip Created By</th>
              <th class="heading no_print btnCol"></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($cancelled_trips as $trip)
		{
		$vehicle_type = getVehicleTypeNameById($trip['vehicle_type_id']);
		$driver_name=getDriverNameFromDriverId($trip['driver_id']);
		
		 ?>
          <tr class="resultRow <?php if(!$driver_name) echo "dangerRow"; ?>">
        	<td><?php echo ++$no; ?>
            </td>
           
           
            <td><?php echo $trip['from_shipping_location']; echo "<br>(".getAreaNameByID($trip['from_area_id']).")"; ?>
            </td>
            <td><?php echo $trip['to_shipping_location']; echo "<br>(".getAreaNameByID($trip['to_area_id']).")"; ?>
            </td>
             <td><?php echo date('d/m/Y H:i:s',strtotime($trip['trip_datetime'])); ?>
            </td>
             
             <td align="center"><?php echo $trip['fare']; ?>
            </td>
              <td align="center"><?php echo $trip['status']; ?>
            </td>
            <td class="no_print" width="120px;"> 
            
            <?php echo $vehicle_type; ?>
            </td>
            <td><?php  if($driver_name) echo $driver_name; else echo "NA"; ?></td>
             <td>
             <?php   echo getAdminUserNameByID($trip['last_modified_by']); ?>
             </td>
            <td class="no_print">
           <?php if($trip['trip_status']==1) { ?> 	 <a href="<?php echo 'trip/index.php?view=updateDriver&id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-warning">Update Driver</button></a> <?php } else if($trip['trip_status']>1  && $trip['trip_status']<6) { ?>
  <a href="<?php echo 'trip/index.php?view=updateStatus&id='.$trip['trip_id']; ?>"><button title="Edit this entry" class="btn editBtn btn-danger">Update Status</button></a>
  <?php } ?>
            </td> 
     
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/trip/index.php?view=details&id='.$trip['trip_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"><?php if($vehicle['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/index.php?view=editVehicle&id='.$vehicle['vehicle_id'].'&access=approved'; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a><?php } ?>
            </td>
           <!-- <td class="no_print"> 
           <?php if($vehicle['primary_location']==0) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?action=deleteVehicle&lid='.$vehicle['vehicle_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a><?php } ?>
            </td> -->
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print3" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>

<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />
   <h4 class="headingAlignment">List of <?php echo SALES_NAME; ?></h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable4" class="adminContentTable">
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
            <td><?php echo round($total_amount,2)." Rs"; ?>
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
       <table id="to_print4" class="to_print adminContentTable"></table> 
       </div>
          
</div>
<div class="clearfix"></div>
<style>
.printSectionBtn{
	float:right;
	}
</style>
<script type="application/javascript">

<?php if(checkForNumeric($trip_id)) { 

$trip=getTripById($trip_id);
?>

writeTripStartData('<?php echo $trip_id; ?>','<?php echo $trip['from_shipping_location']; ?>','<?php echo $trip['to_shipping_location']; ?>');
//document.location.href="index.php?view=details&id=<?php echo $customer_id; ?>";
<?php
 } ?>

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
document.no_of_tables=4;
</script>
