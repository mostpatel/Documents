<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$file_id=$_GET['id'];
if(!checkIfFileInAdminId($file_id,$_SESSION['adminSession']['admin_id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$file=getFileDetailsByFileId($file_id);
if(isset($_SESSION['adminSession']['admin_rights']))
$admin_rights=$_SESSION['adminSession']['admin_rights'];
if(is_array($file) && $file!="error")
{
	$loan_cheques = ListChequesForFileId($file_id);
	$settle_file=getSettleFileDetails($file_id);
	$customer=getCustomerDetailsByFileId($file_id);
	$guarantors=getAllGuarantorDetailsByFileId($file_id);
   //$guarantor=getGuarantorDetailsByFileId($file_id);
	$extraCustomer=getExtraCustomerDetailsByFileId($file_id);
	$loan=getLoanDetailsByFileId($file_id);
	$vehicle=getVehicleDetailsByFileId($file_id);
	if(is_numeric($vehicle['vehicle_id']))
	{
	$vehicle_docs = getVehicleDocsForVehicleId($vehicle['vehicle_id']);
	$rto_work_array = getRtoWorkForVehicleId($vehicle['vehicle_id']);
	}
	else
	$vehicle_docs=false;
	
	$cheque_return_detais=getChequeReturnDetailsForFileId($file_id);
	$customer_id=$customer['customer_id'];
	$agency_participation_details=getLoanSchemeAgency($loan['loan_id']);
	$noc = getNOCByFileId($file_id);
	$no_of_notice=getNumberOfNoticesForFileID($file_id);
	if($no_of_notice>0)
	$latest_notice_date=getLatestNoticeDateForFile($file_id);
	$paymentDetails=getAdditionalPaymentDetailsForLoan($loan['loan_id']);
	$unreceived_welcome_letters = listUnreceivedWelcomesForFileID($file_id);
	$customer_image = getCustomerImageByCustomerID($customer_id);
	$no_of_legal_notice=getNumberOfLegalNoticesForFileID($file_id);
	
	if($no_of_legal_notice>0)
	$latest_legal_notice_date=getLatestLegalNoticeDateForFile($file_id);
	
	if($file['file_status']==4)
	{
		$closureDetails=getPrematureClosureDetails($file_id);
	}
	
	if($loan!="error")
	{
		$emiTable=getLoanTableForLoanId($loan['loan_id']);
		$totalPayment=getTotalPaymentForLoan($loan['loan_id']);
		$totalEMIsPaid=number_format(getTotalEmiPaidForLoan($loan['loan_id']),2);
		$balance_left=getBalanceForLoan($loan['loan_id']); 	
	};
	
	if($file!="error")
	{
		
		$seize=getVehicleSeizeDetailsByFileId($file_id);
		if(!$seize)
		$seize="error";
		}
	else
	{
		
		$seize="error";
	}	
	
	if($file!="error")
	{
	$remarks=listRemarksForFile($file_id);
	$paymentReminder = listPaymentRemindersForFile($file_id);
	$insurance=getInsurancesForFileID($file_id);
	$insurance=$insurance[0]; //latest insurance
	}
	else
	{
		$insurance="error";
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
<?php
if($seize!="error")
{
?>
<div class="alert alert-error"><b>VEHICLE SEIZED <?php if($seize['godown_id']>0) echo " at ".getVehicleGodownNameById($seize['godown_id']); if($seize['sold']==1) echo " AND SOLD"; ?></b></div>
<?php	
}
 ?>
 <?php
if(is_numeric($no_of_legal_notice) && $no_of_legal_notice>0)
{
?>
<div class="alert alert-error"><b>Legal / Court Case </b><a class="alert-error" style="text-decoration:underline;font-size:14px;margin-left:25px;" href="legal_notice/index.php?id=<?php echo $file_id; ?>">Click here to view</a></div>
<?php	
	}
 ?>
<div class="addDetailsBtnStyling no_print"><?php if($vehicle=="error"){ ?><a href="vehicle/index.php?id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add vehicle</button></a><?php  } else if(!$vehicle_docs) { ?><a href="vehicle/docs/index.php?id=<?php echo $file_id; ?>&state=<?php echo $vehicle['vehicle_id']; ?>"><button class="btn btn-success">+ Add vehicle Docs</button></a><?php } ?> <a href="vehicle/insurance/index.php?id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add Insurance</button></a> <?php if((!is_array($guarantors)) || count($guarantors)<NO_OF_GUARANTOR) { ?> <a href="index.php?view=addGuarantor&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add Guarantor</button></a> <?php } ?><?php if(isset($vehicle) && $seize=="error") { ?> <a href="<?php echo WEB_ROOT; ?>admin/customer/vehicle/seize/index.php?view=seize&id=<?php echo $file_id; ?>&state=<?php echo $vehicle['vehicle_id']; ?>"><button class="btn btn-danger">+ Seize Vehicle</button></a> <?php } ?> <?php if(($file['file_status']!=4 && $file['file_status']!=3) && (($file['file_status']==5) || $file['file_status']==1)) {?> <a href="<?php echo WEB_ROOT ?>admin/file/index.php?view=closeFile&id=<?php echo $file_id; ?>"><button class="btn btn-danger">Close File</button></a> <?php } ?> <?php if(($file['file_status']==2 || $file['file_status']==4) && !$noc && (in_array(11,$admin_rights) || in_array(7,$admin_rights)  )) { ?> <a href="noc/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-danger">Issue NOC</button></a> <?php } ?> <?php if(is_numeric($file['agency_id']) && $file['agency_id']!=null && $settle_file=="error") { ?> <a href="<?php echo WEB_ROOT ?>admin/file/settle/index.php?view=add&id=<?php echo $file_id; ?>"><button class="btn btn-danger">Settle File</button></a><?php } ?> <a href="<?php echo WEB_ROOT; ?>admin/customer/payment/additional_charges/index.php?view=payments&id=<?php echo $file_id; ?>"><button class="btn btn-warning">Additional Payments</button></a> <a href="<?php echo WEB_ROOT; ?>admin/search/"><button class="btn btn-warning">Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/EMI/index.php?view=details&id=<?php echo $file_id; ?>"><button class="btn btn-warning">EMI Details</button></a>
<?php if(FILE_CHARGES==1) { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=fileCharges&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Update File Charges</button></a>
<?php } ?>
</div>
<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add / View Reminder</button></a> <span class="noOfRemainders"><b><?php if($remarks!=false) echo count($remarks)." Pending Reminders!"; ?></b></span> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=customerGroup&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add to group</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=ledgerView&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Ledger View</button></a>
<?php if(!is_array($extraCustomer) && !is_numeric($extraCustomer['extra_customer_id'])) { ?> <a href="index.php?view=addExtraCustomer&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">+ Add Next Customer</button></a> <?php } ?>
<?php if(!is_array($loan_cheques) && !is_numeric($loan_cheques['cheque_details_id'])) { ?> <a href="<?php echo WEB_ROOT; ?>admin/customer/loan_cheques/index.php?&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Add Loan Cheques</button></a> <?php } ?>
 </div>

<div class="detailStyling">

<h4 class="headingAlignment">File Details</h4>

<table class="insertTableStyling detailStylingTable">

<tr>
<td>File Status : </td>
				<td>
				
                             <?php   if($file['file_status']==1) echo "OPEN"; else if($file['file_status']==5) echo "CLOSED & UNPAID"; else if($file['file_status']==3) echo "DELETED";else if($file['file_status']==4 && (strtotime($loan['loan_ending_date'])<=strtotime($closureDetails['file_close_date']))) echo "FORCED CLOSURE";else if($file['file_status']==4 && (strtotime($loan['loan_ending_date'])>strtotime($closureDetails['file_close_date']))) echo "PREMATURE CLOSURE"; else echo "CLOSED"; if($settle_file!="error")
									echo " (Settled)"; ?>					
                            

                 </td>
</tr>



<tr>
<td>Agency Name : </td>
				<td>
				
                             <?php
							 
							  $id =  $file['agency_id']; 
							 if($id!=null)
							 {
							        $agencyDetails=getAgencyById($id);
									echo $agencyDetails['agency_name'];
									
									
							 }
							 else
							 {
								 $id=$file['oc_id'];
								 echo getOurCompanyNameByID($id);
								 }
							 ?>					
                             
                </td>
                    
                    
                  
</tr>

<tr>
<td>
File Agreement No : 
</td>
<td>
 
                             <?php echo $file['file_agreement_no'] ?>					
                             

</td>
</tr>

<tr>
<td>File Number : </td>
				<td>
				
                             <?php echo $file['file_number']; ?>					
                            

                 </td>
</tr>

<?php if(MEM_NO==1) { ?>
<tr>
<td>Membership Number : </td>
				<td>
				 <?php echo $file['mem_no']; ?>
                 </td>
</tr>
<?php } ?>

<tr>
<td class="firstColumnStyling">
Registration Number : 
</td>

<td>
<?php if($vehicle!="error") {  $reg_no=$vehicle['vehicle_reg_no']; $reg_no=strtoupper($reg_no); echo $reg_no; } else echo "NOT ADDED"; ?>
</td>
</tr>

<tr>
<td>Broker : </td>
				<td>
				
                             <?php echo getBrokerNameFromBrokerId($file['broker_id']); ?>					
                            

                 </td>
</tr>




<tr>

<td class="firstColumnStyling">
Total Collection : 
</td>

<td>

                             <?php 
							 $total_collection =  getTotalCollectionForLoan($loan['loan_id']);
							 echo  "Rs. ".number_format($total_collection); ?>					
                           
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Total Payments Received : 
</td>

<td>

                             <?php  
							 		echo "Rs. ".number_format(getTotalPaymentForLoan($loan['loan_id']));
									if($file['file_status']==4)
									{
										$prematureClosureAmount=getPrematureClosureAmount($file_id);
										if($prematureClosureAmount>0)
											{
											echo " + ".number_format($prematureClosureAmount)."(Closure Amount)";
											}
										}
							 ?>					
                           
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Total Payments Left : 
</td>

<td>

                             <?php  
							 		if($file['file_status']==4)
									{
										echo 0;
										}
									else
									{	
							 		$balance_left=getBalanceForLoan($loan['loan_id']); 
									
									closeFileIfBalanceZero($loan['loan_id']);
							 		echo "Rs. ".number_format(-$balance_left);
									}
							 ?>					
                           
</td>
</tr>
<?php

 if($agency_participation_details!="error" && is_numeric($agency_participation_details[0]['agency_emi']) && is_numeric($agency_participation_details[0]['agency_duration']))
{
 ?>
<tr>

<td class="firstColumnStyling">
Agency Loan Amount : 
</td>

<td>

                              <?php  
							 		if($agency_participation_details!="error")
									{
										echo "RS. ".number_format($loan['agency_loan_amount']);
										}
									
							 ?>		
                           
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Agency EMI X Durarion : 
</td>

<td>

                             <?php  
							 		if($agency_participation_details!="error")
									{ foreach($agency_participation_details as $agency_participation_detail)
										echo $agency_participation_detail['agency_emi']." X ".$agency_participation_detail['agency_duration']."<br>";
										}
									
							 ?>					
                           
</td>
</tr>



<tr>

<td class="firstColumnStyling">
Total Collection For Agency : 
</td>

<td>

                             <?php  
							 		if($agency_participation_details!="error")
									{
										$tot=0;
										foreach($agency_participation_details as $agency_participation_detail)
										$tot=$tot+($agency_participation_detail['agency_emi']*$agency_participation_detail['agency_duration']);
										echo "RS. ".number_format($tot);
										}
									
							 ?>					
                           
</td>
</tr>
<?php } ?>
 <tr>

<td class="firstColumnStyling">
Interest : 
</td>

<td>

                             <?php
							 
							  if($agency_participation_details!="error")
									{
										echo "RS. ".number_format($total_collection-$tot);
										}
									else
									echo "RS. ".number_format(getProfitForLoan($loan['loan_id'])); ?>					
                           
</td>
</tr> 

<?php if(HO_OPENING_DATE==1) {  ?>
<tr>
<td>HO Opening Date : </td>
				<td>
				 <?php if($file['ho_opening_date']!="1970-01-01") echo date('d/m/Y',strtotime($file['ho_opening_date'])); ?>
                 </td>
</tr>
<?php } ?>
<?php if(SHOW_CUSTOMER_IMAGE==1) { ?>
<tr>
<td>Customer's Photo</td>
<td > <?php if($customer_image!="error") { ?>  <a href="<?php echo WEB_ROOT."images/customer_proof/".$customer_image; ?>"><img style="height:100px;" src="<?php echo WEB_ROOT."images/customer_proof/".$customer_image; ?>" /></a></td> <?php } ?>
</tr>

<?php } ?>
<tr>

	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=fileDetails&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editFile&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
              <a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteFile&id='.$file_id ?>"><button title="Delete this File" class="btn delBtn editBtn btn-danger">Delete file</button></a>
            </td>
</tr>      


      



</table>

</div>

<div class="detailStyling" style="min-height:570px;">

<h4 class="headingAlignment">Loan Details </h4>


<table class="insertTableStyling detailStylingTable">

<tr>

<td class="firstColumnStyling">
Total Loan Amount : 
</td>

<td>

                             <?php echo "Rs. ".number_format($loan['loan_amount']); ?>					
                           
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Loan Duration (In months) : 
</td>

<td>
                             <?php echo $loan['loan_duration']; ?>					
                          
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Loan Type : 
</td>

<td>
                             <?php if($loan['loan_type']==1) echo "FLAT"; else if($loan['loan_type']==2) echo "REDUCING"; ?>					
                          
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Loan Structure : 
</td>

<td>
                             <?php if($loan['loan_scheme']==1) echo "EVEN"; else if($loan['loan_scheme']==2) echo "UNEVEN"; ?>					
                          
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Flat Rate of Interest (annually in %) : 
</td>

<td>

                             <?php echo number_format($loan['roi'],1)." % "; ?>					
                            
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Reducing Rate of Interest (annually in %) : 
</td>

<td>

                             <?php echo number_format($loan['reducing_roi'],1)." % "; ?>					
                            
</td>
</tr>

<tr>

<td class="firstColumnStyling">
IRR (Internal Rate of Return) : 
</td>

<td>

                             <?php echo number_format($loan['IRR'],1); ?>					
                            
</td>
</tr>


<tr>

<td class="firstColumnStyling">
EMI : 
</td>

<td>

                             <?php
							 $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo "Rs. ".number_format($loan['emi']);
							  else
							  {
								  foreach($emi as $e)
								  {
									  echo number_format($e['emi'])." X ".$e['duration']."<br>";
									  }
								  
								  } ?>					
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Total EMIs Paid : 
</td>

<td>

                             <?php echo number_format($totalEMIsPaid,2)." / ".$loan['loan_duration']; ?>					
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Total EMIs Left : 
</td>

<td>

                             <?php echo number_format($loan['loan_duration']-$totalEMIsPaid,2)." / ".$loan['loan_duration']; ?>					
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Bucket : 
</td>

<td>

                             <?php
							 $bucket_details=getBucketDetailsForLoan($loan['loan_id']);
							 $buket_amount = getTotalBucketAmountForLoan($loan['loan_id']);
							 if(is_array($bucket_details))
							 {
							foreach($bucket_details as $emi_amount=>$bucket_for_corresponding_emi)
							{
								echo $emi_amount." X ".$bucket_for_corresponding_emi."<br>";
								}
							 }
							 else
							 echo number_format(0,2);
							 echo "(".number_format($buket_amount).")";
							// $actualEMis=getNoOfEmiBeforeDateForLoanId($loan['loan_id'],date('Y-m-d'));
							 //$bucket=$actualEMis-$totalEMIsPaid;
							//if($bucket>0)  echo number_format($bucket,1); else echo "0"; ?>					
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Loan Approval Date : 
</td>

<td>
 
                             <?php
							 echo date('d/m/Y',strtotime($loan['loan_approval_date']));
				
							  ?>					
                           
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Loan Starting Date : 
</td>

<td>
 
                             <?php
							 echo date('d/m/Y',strtotime($loan['loan_starting_date']));
				
							  ?>					
                           
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Loan Ending Date: 
</td>

<td>

                             <?php  echo date('d/m/Y',strtotime($loan['loan_ending_date'])); ?>					
                           
</td>
</tr>

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=loanDetails&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editLoan&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>  


</table>
</div>

<div class="detailStyling" style="min-height:300px">

<h4 class="headingAlignment" <?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?> style="color:#F00" <?php } ?>>Customer's Details<?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?> (SOLD) <?php } ?></h4> 

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php echo $customer['customer_name']; ?>					
                            
</td>
</tr>

<?php if(defined('SECONDARY_NAME') && SECONDARY_NAME==1 && defined('SECONDARY_NAME_TITLE') && SECONDARY_NAME_TITLE=="Father's Name") { ?>
<tr>

<td width="150px" class="firstColumnStyling">
<?php if(defined('SECONDARY_NAME_TITLE')) { echo SECONDARY_NAME_TITLE; } else  { ?>Secondary Name<?php } ?> : 
</td>

<td>

                             <?php echo ucfirst(strtolower($customer['secondary_customer_name'])); ?>					
                            
</td>
</tr>
<?php } ?>

<tr>
<td >
Address : 
</td>

<td style="max-width:300px;">

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
<td>Area : </td>
				<td>

                             <?php $cid = $customer['area_id'];
							 		
							       $cityDetails = getAreaByID($cid);
								   echo $cityDetails['area_name'];
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
                      			echo $c[0]." <br> ";				
                              } ?>
                </td>
            </tr>

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=customerDetails&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>



<?php   if(is_array($guarantors) && isset($guarantors[0]['guarantor_id']) && is_numeric($guarantors[0]['guarantor_id'])) {
	$i=0;

	foreach($guarantors as $guarantor)
	{
		
	 ?>
<div class="detailStyling" style="min-height:370px;">

<h4 class="headingAlignment">Guranteer's Details <?php if(NO_OF_GUARANTOR>1) echo "(".++$i.")"; ?></h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
 Name : 
</td>

<td>
                             <?php echo $guarantor['guarantor_name']; ?>					
                             
</td>
</tr>

<?php if(defined('SECONDARY_NAME') && SECONDARY_NAME==1 && defined('SECONDARY_NAME_TITLE') && SECONDARY_NAME_TITLE=="Father's Name") { ?>
<tr>

<td width="150px" class="firstColumnStyling">
<?php if(defined('SECONDARY_NAME_TITLE')) { echo SECONDARY_NAME_TITLE; } else  { ?>Secondary Name<?php } ?> : 
</td>

<td>

                             <?php echo ucfirst(strtolower($guarantor['secondary_guarantor_name'])); ?>					
                            
</td>
</tr>
<?php } ?>

<tr>
<td>
Guranteer's Address : 
</td>

<td style="max-width:300px;">

                             <?php echo $guarantor['guarantor_address']; ?>					
                             </td>
</tr>


<tr>
<td>City : </td>
				<td>
   
                             <?php $gid =  $guarantor['city_id']; 
                             $gCityDetails = getCityByID($gid);
								   echo $gCityDetails['city_name'];	
?>
                            </td>
</tr>

<tr>
<td>Area : </td>
				<td>

                             <?php $cid = $guarantor['area_id'];
							 		
							       $cityDetails = getAreaByID($cid);
								   echo $cityDetails['area_name'];
							?>
                            </td>
</tr>
<td>Pincode : </td>
<td>
   
                             <?php if($guarantor['guarantor_pincode']!=0) echo $guarantor['guarantor_pincode']; else echo "NA"; ?>					
                           

</td>
</tr>



 <tr id="addcontactTrGuarantor">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                	<?php
                            $contactNos = $guarantor['contact_no'];
							
                            foreach($contactNos as $c)
                              {
                       ?>
                             
                             <?php echo $c[0]." <br> "; ?>					
                             <?php } ?>
                </td>
            </tr>
 
 <tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=guarantorDetails&id='.$guarantor['guarantor_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editGuarantor&id='.$guarantor['guarantor_id']  ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            
           
            
 </table>
</div>

<?php }} ?>

<?php   if(is_array($extraCustomer) && isset($extraCustomer['extra_customer_id']) && is_numeric($extraCustomer['extra_customer_id'])) { ?>
<div class="detailStyling" style="min-height:370px;">

<h4 class="headingAlignment">Next Customer's Details</h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Name : 
</td>

<td>
                             <?php echo $extraCustomer['extra_customer_name']; ?>					
                             
</td>
</tr>

<tr>
<td>
Address : 
</td>

<td style="max-width:300px;">

                             <?php echo $extraCustomer['extra_customer_address']; ?>					
                             </td>
</tr>


<tr>
<td>City : </td>
				<td>
   
                             <?php $gid =  $extraCustomer['city_id']; 
                             $gCityDetails = getCityByID($gid);
								   echo $gCityDetails['city_name'];	
?>
                            </td>
</tr>

<tr>
<td>Area : </td>
				<td>

                             <?php $cid = $extraCustomer['area_id'];
							 		
							       $cityDetails = getAreaByID($cid);
								   echo $cityDetails['area_name'];
							?>
                            </td>
</tr>
<td>Pincode : </td>
<td>
   
                             <?php if($extraCustomer['extra_customer_pincode']!=0) echo $extraCustomer['extra_customer_pincode']; else echo "NA"; ?>					
                           

</td>
</tr>



 <tr id="addcontactTrGuarantor">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                	<?php
                            $contactNos = $extraCustomer['contact_no'];
							
                            foreach($contactNos as $c)
                              {
                       ?>
                             
                             <?php echo $c[0]." <br> "; ?>					
                             <?php } ?>
                </td>
            </tr>
 
 <tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=extraCustomerDetails&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editExtraCustomer&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            
           
            
 </table>
</div>

<?php } ?>

<hr class="firstTableFinishing" />
<?php if($vehicle!="error"){ ?>
<div class="detailStyling" style="min-height:300px;">
<h4 class="headingAlignment"> Vehicle Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td>Vehicle Company : </td>
<td><?php  $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo $company['company_name']; ?> </td>
</tr>

<tr>
<td>Vehicle Model : </td>
				<td>
					<?php echo getModelNameById($vehicle['model_id']); ?>
                            </td>
</tr>

<tr>
<td>Vehicle Dealer : </td>
				<td>
					<?php echo getDealerNameFromDealerId($vehicle['vehicle_dealer_id']); ?>
                            </td>
</tr>

<tr>
       <td>Vehicle Condition :</td>
           
           
        <td>
            <?php if($vehicle['vehicle_condition']==1) echo "NEW"; else echo "OLD"; ?>
        </td>
 </tr>
 
 <tr>
<td>Vehicle Model : </td>
				<td>
					<?php echo $vehicle['vehicle_model']; ?>
                            </td>
</tr>

<tr>
<td>Vehicle Type : </td>
				<td>
					<?php $vehicle_type = getVehicleTypeById($vehicle['vehicle_type_id']); echo $vehicle_type['vehicle_type']; ?>	
                </td>
</tr>
 
<tr>
<td class="firstColumnStyling">
Registration Number : 
</td>

<td>
<?php  $reg_no=$vehicle['vehicle_reg_no']; $reg_no=strtoupper($reg_no); echo $reg_no; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<?php echo $vehicle["vehicle_engine_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<?php echo $vehicle["vehicle_chasis_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Fitness Exp Date : 
</td>

<td>
<?php echo date('d/m/Y',strtotime($vehicle["fitness_exp_date"])); ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Permit Exp Date : 
</td>

<td>
<?php echo date('d/m/Y',strtotime($vehicle["permit_exp_date"])); ?>
</td>
</tr>

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo 'vehicle/index.php?view=vehicleDetails&id='.$file_id.'&state='.$vehicle['vehicle_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo 'vehicle/index.php?view=editVehicle&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>   

</table>
</div>



<?php } 
if($vehicle_docs)
{
?>
<div class="detailStyling" style="min-height:300px;">
<h4 class="headingAlignment"> Vehicle Docs Details </h4>  

<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">
<tr>
<td width="220px">RTO Agent : </td>
				<td>
					
                        <?php
                         if(is_numeric($vehicle_docs['rto_agent_id'])) echo getRtoAgentNameFromRtoAgentId($vehicle_docs['rto_agent_id']); ?>
                              
                         
                           
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Given : 
</td>

<td>
<?php if(is_array($rto_work_array)) {  
$total_amount = 0;
foreach($rto_work_array as $rto_work) {echo $rto_work['rto_work_name']." - ".$rto_work['rate']." Rs<br>"; $total_amount=$total_amount+$rto_work['rate'];}
echo "Total: ".$total_amount." Rs";
} ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Given Date : 
</td>

<td>
<?php if($vehicle_docs['work_given_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['work_given_date'])); else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Completion Date : 
</td>

<td>
<?php if($vehicle_docs['work_completion_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['work_completion_date'])); else echo "NA"; ?>
</td>
</tr>
<tr>
<td class="firstColumnStyling">
Customer Given Date : 
</td>

<td>
<?php if($vehicle_docs['customer_given_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['customer_given_date'])); else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Customer Received Date : 
</td>

<td>
<?php if($vehicle_docs['customer_received_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['customer_received_date'])); else echo "NA"; ?>
</td>
</tr>
 <tr>
<td width="220px" class="firstColumnStyling">
Document Remarks : 
</td>

<td>
<?php echo $vehicle_docs['remarks']; ?>

</td>
</tr>
</table>
<table class="document_table" style="width:100%;" border="1" cellpadding="10" >
<tr>
	<td colspan="7">
    Document Status	
    </td>
</tr>
<tr>
       <th >R.C.Book</th>
       <th>Passing </th>
       <th>Permit </th> 
       <th>Insurance </th>
       <th>HP </th>
        <th>Bill </th>
        <th>Key </th>
        </tr>
        <tr>
        <td class="<?php if($vehicle_docs['rto_papers']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>" >
               <?php if($vehicle_docs['rto_papers']==1) { ?> Yes <?php } else if($vehicle_docs['rto_papers']==0) { ?> No <?php } else  if($vehicle_docs['rto_papers']==2) { ?> With Agent <?php } else  if($vehicle_docs['rto_papers']==3) { ?> With Customer <?php } else  if($vehicle_docs['rto_papers']==4) { ?> Not Applicable <?php } ?> 
        </td>
 
      
           
           
        <td class="<?php if($vehicle_docs['passing']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>">
               <?php if($vehicle_docs['passing']==1) { ?> Yes <?php } else if($vehicle_docs['passing']==0) { ?> No <?php } else  if($vehicle_docs['passing']==2) { ?> With Agent <?php } else  if($vehicle_docs['passing']==3) { ?> With Customer <?php } else  if($vehicle_docs['passing']==4) { ?> Not Applicable <?php } ?> 
        </td>
 
       
           
           
        <td class="<?php if($vehicle_docs['permit']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>">
              <?php if($vehicle_docs['permit']==1) { ?> Yes <?php } else if($vehicle_docs['permit']==0) { ?> No <?php } else  if($vehicle_docs['permit']==2) { ?> With Agent <?php } else  if($vehicle_docs['permit']==3) { ?> With Customer <?php } else  if($vehicle_docs['permit']==4) { ?> Not Applicable <?php } ?>
        </td>
 
      
           
           
        <td class="<?php if($vehicle_docs['insurance']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>">
              <?php if($vehicle_docs['insurance']==1) { ?> Yes <?php } else if($vehicle_docs['insurance']==0) { ?> No <?php } else  if($vehicle_docs['insurance']==2) { ?> With Agent <?php } else  if($vehicle_docs['insurance']==3) { ?> With Customer <?php } else  if($vehicle_docs['insurance']==4) { ?> Not Applicable <?php } ?>
        </td>
 
      
           
           
        <td class="<?php if($vehicle_docs['hp']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>">
               <?php if($vehicle_docs['hp']==1) { ?> Yes <?php } else if($vehicle_docs['hp']==0) { ?> No <?php } else  if($vehicle_docs['hp']==2) { ?> With Agent <?php } else  if($vehicle_docs['hp']==3) { ?> With Customer <?php } else  if($vehicle_docs['hp']==4) { ?> Not Applicable <?php } ?>
        </td>
 
      
           
           
        <td class="<?php if($vehicle_docs['bill']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>">
               <?php if($vehicle_docs['bill']==1) { ?> Yes <?php } else if($vehicle_docs['bill']==0) { ?> No <?php } else  if($vehicle_docs['bill']==2) { ?> With agent <?php } else  if($vehicle_docs['bill']==3) { ?> with customer <?php } else  if($vehicle_docs['bill']==4) { ?> Not Applicable <?php } ?>
        </td>
 
       
           
           
        <td class="<?php if($vehicle_docs['vehicle_key']==1) { ?> shantiRow  <?php }else { ?> dangerRow <?php } ?>">
               <?php if($vehicle_docs['vehicle_key']==1) { ?> Yes <?php } else if($vehicle_docs['vehicle_key']==0) { ?> No <?php } else  if($vehicle_docs['vehicle_key']==2) { ?> With Agent <?php } else  if($vehicle_docs['vehicle_key']==3) { ?> With Customer <?php } else  if($vehicle_docs['vehicle_key']==4) { ?> Not Applicable <?php } ?>
        </td>
 </tr>
 
</table>
 <style>
 .document_table
 {
	 border:1px solid #aaa;
	margin-bottom:30px;
	 
	}
	.document_table tr td, .document_table tr th
 {
	 padding:5px;
	 font-family: myFontBold;
	 text-align:left;
 }
 </style>
<table class="insertTableStyling no_print">
<tr>
	<td width="150px"></td>
  <td class="no_print">
            <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/docs/index.php?view=details&id='.$vehicle['vehicle_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
          <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/docs/index.php?view=edit&id='.$file_id.'&state='.$vehicle['vehicle_id'] ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            
            </td>
</tr>   

</table>

</div>    
<?php
}
if($seize!="error")
{
?>
<div class="detailStyling" style="min-height:300px;">
<h4 class="headingAlignment"> Vehicle Seize Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td>Seize Date : </td>
<td><?php    echo date('d/m/Y',strtotime($seize['seize_date'])); ?> </td>
</tr>

<tr>
<td>Vehicle Sold : </td>
<td><?php    if($seize['sold']==0) echo "No"; else echo "Yes"; ?> </td>
</tr>

<tr>
<td>Remarks : </td>
				<td>
					<?php if($seize['remarks']!="") echo $seize['remarks']; else echo "NA"; ?>
                            </td>
</tr>

<tr>
	<td></td>
  <td class="no_print"> 
  <a href="<?php echo 'vehicle/seize/index.php?view=release&id='.$file_id.'&state='."&state=".$seize['seize_id'] ?>"><button title="View this entry" class="btn btn-danger delBtn">Release</button></a>
  <a href="<?php echo 'vehicle/seize/index.php?view=details&id='.$file_id.'&state='.$vehicle['vehicle_id']."&state2=".$seize['seize_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo 'vehicle/seize/index.php?view=edit&id='.$file_id.'&state='.$vehicle['vehicle_id']."&state2=".$seize['seize_id'] ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
              <a href="<?php echo 'vehicle/seize/index.php?action=delete&file_id='.$file_id.'&state='.$vehicle['vehicle_id']."&lid=".$seize['seize_id'] ?>"><button title="Delete this entry" class="btn splEditBtn delBtn"><span class="delete">X</span></button></a>
            </td>
</tr>   

</table>
</div>
<?php } if(is_array($insurance)) {?>
<div class="detailStyling">
<h4 class="headingAlignment"> Insurance Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td>Insurance Company : </td>
				<td>
					<?php  $comp=getInsuranceCompanyById($insurance['insurance_company_id']); echo $comp[1]; ?>
                            </td>
</tr>

<tr>
<td>Insurance Issue Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($insurance['insurance_issue_date'])); ?>
                            </td>
</tr>

<tr>
<td>Insurance Expiry Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); ?>
                            </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Isurance Declared Value (IDV) : 
    </td>
    
    <td>
    <?php echo "Rs. ".number_format($insurance['idv']); ?>
    </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Premium : 
    </td>
    
    <td>
     <?php echo "Rs. ".number_format($insurance['insurance_premium']); ?>
    </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Policy Number : 
    </td>
    
    <td>
     <?php echo $insurance['policy_no']; ?>
    </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Cover Note Number : 
    </td>
    
    <td>
     <?php echo $insurance['cover_note_no']; ?>
    </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Remarks : 
    </td>
    
    <td>
     <?php echo $insurance['remarks']; ?>
    </td>
</tr>


<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/insurance/?view=details&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr>  


</table>
</div>
<?php } ?>
<div class="detailStyling">

<h4 class="headingAlignment">Penalty Details </h4>


<table class="insertTableStyling detailStylingTable">

<tr>
    <td class="firstColumnStyling">
    Total Penalty uptill <?php if($file['file_status']!=4) { ?> today <?php } else echo date('d/m/Y',strtotime($closureDetails['file_close_date'])); ?> : 
    </td>
    
    <td>
     
                                 <?php echo number_format(getTotalPenaltyForLoan($loan['loan_id']));  if(!defined('PENALTY_IN_DAYS') || PENALTY_IN_DAYS==0) 
					echo " Days"; else if(PENALTY_IN_DAYS==1) echo " Months"; ?>					
                               
    </td>
</tr>

<tr>
    <td class="firstColumnStyling">
    Penalty Days Paid: 
    </td>
    
    <td>
     
                                 <?php echo number_format(getTotalPenaltyPaidDaysForLoan($loan['loan_id']));  if(!defined('PENALTY_IN_DAYS') || PENALTY_IN_DAYS==0) 
					echo " Days"; else if(PENALTY_IN_DAYS==1) echo " Months"; ?>					
                               
    </td>
</tr>

<tr>
    <td class="firstColumnStyling">
    Penalty Amount Paid: 
    </td>
    
    <td>
     
                                 <?php echo number_format(getTotalPenaltyAmountPaidForLoan($loan['loan_id']))." Rs"; ?>					
                               
    </td>
</tr>

<?php

 if(SHOW_PENALTY==1){  ?>
<tr>
	<td class="firstColumnStyling">
    Penalty For <?php echo "(".PENALTY_CALC_PERCENT."% / Year)";  ?>
    </td>
    <td>
    	<?php echo number_format(calculatePenaltyForLoanByInterest(PENALTY_CALC_PERCENT,$loan['loan_id'],getTodaysDate()),2); ?>
    </td>
</tr>
<?php } ?>

<?php

 if(PENALTY_IN_MONTHS_LOAN_PERCENT>0 && PENALTY_IN_DAYS==1){  ?>
<tr>
	<td class="firstColumnStyling">
    Penalty For <?php echo "(".PENALTY_IN_MONTHS_LOAN_PERCENT."% / Loan Amount)";  ?>
    </td>
    <td>
    	<?php echo number_format(getTotalPenaltyForLoan($loan['loan_id'])*((PENALTY_IN_MONTHS_LOAN_PERCENT/100) * $loan['loan_amount'])); ?>
    </td>
</tr>
<?php } ?>
<tr>
        <td></td>
      <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=penaltyDetails&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
       </td>        
</tr>  


</table>
</div>
<?php if(isset($paymentDetails[0]) && is_array($paymentDetails[0]) && count($paymentDetails)>0) { ?>
<div class="detailStyling">

<h4 class="headingAlignment">Additional Payment Details </h4>

 <table id="" class="">
    <thead>
    	<tr>
        	<th class="heading" align="left">No</th>
            <th class="heading">Type</th>
            <th class="heading" align="left">Paid</th>
            <th class="heading" align="left">Amount</th>
            <th class="heading" align="left">Payment Date</th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	
		$no=0;
		if(isset($paymentDetails[0]) && is_array($paymentDetails[0]) && count($paymentDetails)>0 )
		{
		foreach($paymentDetails as $payment)
		{	
		
			$balance=0;
		 ?>
         <tr class="resultRow">
        	<td width="10px"><?php echo ++$no; ?>
            </td>
            <td><?php echo $payment['rasid_type_name']; ?>
            </td>
             <td><?php  if($payment['paid']==1) echo "Yes"; else echo "No"; ?> 
			 </td>
             <td><?php  echo number_format($payment['total_amount']); ?> 
            </td>
            
            
           
           
             <td><?php echo date('d/m/Y',strtotime($payment['paid_date']));  ?>
            </td>
           
           
            
            
          
  
        </tr>
         <?php } }?>
         </tbody>
         <tbody>
         <tr>
	
  <td class="no_print" colspan="5"> <a href="<?php echo WEB_ROOT; ?>admin/customer/payment/additional_charges/index.php?view=payments&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 
         </tbody>
</table>
</div>
<?php } ?>
<?php
    if($file['file_status']==4)
	{
		
?>
<div class="detailStyling">

<h4 class="headingAlignment">Premature Closure Details </h4>


<table class="insertTableStyling detailStylingTable">


<tr>
    <td class="firstColumnStyling">
    Closed Date: 
    </td>
    
    <td>
     
                                 <?php echo date("d/m/Y",strtotime($closureDetails['file_close_date'])); ?>					
                               
    </td>
</tr>

<tr>
    <td class="firstColumnStyling">
    Amount Paid: 
    </td>
    
    <td>
     
                                 <?php echo "Rs. ".number_format($closureDetails['amount_paid']); ?>					
                               
    </td>
</tr>

<tr>
    <td class="firstColumnStyling">
    Remarks: 
    </td>
    
    <td>
     
                                 <?php if($closureDetails['remarks']!="") echo $closureDetails['remarks']; else echo "NA"; ?>			
                               
    </td>
</tr>

<tr>
    <td class="firstColumnStyling">
    Closed By: 
    </td>
    
    <td>
     
                                 <?php echo getAdminUserNameByID($closureDetails['closed_by']); ?>					
                               
    </td>
</tr>


 
 

<tr>

	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo WEB_ROOT.'admin/file/index.php?view=editClosure&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
              <a href="<?php echo WEB_ROOT.'admin/file/index.php?action=deleteClosure&id='.$file_id ?>"><button title="Delete this File" class="btn splEditBtn editBtn btn-danger">Delete Closure</button></a>
            </td>
</tr>       




</table>
</div>
<?php		
		}
if($settle_file!="error")
{
 ?>
 
<div class="detailStyling">

<h4 class="headingAlignment">Settlement Details </h4>
 
<table id="rasidTable" class="detailStylingTable insertTableStyling">

<tr>
<td>Payment Amount : </td>
				<td>
					<?php echo "Rs. ".number_format($settle_file['settle_amount']); ?>
                            </td>
</tr>
<tr>
<td>Payment Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($settle_file['settle_date'])); ?>
                            </td>
</tr>

<tr>
<td>Rasid No : </td>
				<td>
					<?php echo $settle_file['receipt_no']; ?>
                            </td>
</tr>

<tr>
<td>Payment Mode : </td>
				<td>
					 <?php if($settle_file['payment_mode']==1) { echo "CASH"; }else echo "CHEQUE"; ?>
                            </td>
</tr>

<tr>
<td>NOC Received Date : </td>
				<td>
					<?php  $noc_date=date('d/m/Y',strtotime($settle_file['noc_received_date'])); if($noc_date!="01/01/1970") echo $noc_date; ?>
                            </td>
</tr>

<tr>
<td>Remarks : </td>
				<td>
					<?php echo $settle_file['remarks']; ?>
                            </td>
</tr>

<tr>

	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/file/settle/index.php?view=details&id='.$file_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo WEB_ROOT.'admin/file/settle/index.php?view=edit&id='.$file_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
              <a href="<?php echo WEB_ROOT.'admin/file/settle/index.php?action=delete&lid='.$file_id ?>"><button title="Delete this File" class="btn splEditBtn editBtn btn-danger">Delete Settlement</button></a>
            </td>
</tr>  
</table> 
</div>
<?php  } ?>
</div>

<?php if($remarks!=false && is_array($remarks) && count($remarks)>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Remarks Details </h4>


<table class="insertTableStyling detailStylingTable" style="color:#da4f49;">

<?php foreach($remarks as $remark)
{
	
?> 
<tr>
    <td class="firstColumnStyling" <?php if($remark['payment_reminder']==1) { ?> style="color:#0963F3" <?php } ?>>
   <?php  if($remark['date']=='1970-01-01' || $remark['date']=='0000-00-00')  {?>  Remark: <?php } else {  echo date('d/m/Y',strtotime($remark['date'])); } ?> 
    </td>
    
    <td <?php if($remark['payment_reminder']==1) { ?> style="color:#0963F3" <?php } ?>>
     
                                 <?php echo $remark['remarks']; ?>					
                               
    </td>
</tr>

<?php
}
 ?>
 

 
 
 

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 




</table>
</div>
<?php } ?>

 
 <?php if($paymentReminder!=false && is_array($paymentReminder) && count($paymentReminder)>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Payment Reminders </h4>


<table class="insertTableStyling detailStylingTable" style="color:#da4f49;">

<?php foreach($paymentReminder as $paymentReminde)
{
	
?> 
<tr>
    <td class="firstColumnStyling" <?php if($paymentReminde['payment_reminder']==1) { ?> style="color:#0963F3" <?php } ?>>
   <?php  if($paymentReminde['date']=='1970-01-01' || $paymentReminde['date']=='0000-00-00')  {?>  Remark: <?php } else {  echo date('d/m/Y',strtotime($paymentReminde['date'])); } ?> 
    </td>
    
    <td <?php if($paymentReminde['payment_reminder']==1) { ?> style="color:#0963F3" <?php } ?>>
     
                                 <?php echo $paymentReminde['remarks']; ?>					
                               
    </td>
</tr>

<?php
}
 ?>
 
 <tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 




</table>
</div>
<?php } ?>

<?php if(is_numeric($no_of_notice) && $no_of_notice>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Notice Details </h4>


<table class="insertTableStyling detailStylingTable">
<tr>
    <td class="firstColumnStyling">
    No of Notices :</td>
    <td> 
		<?php echo $no_of_notice; ?>
    </td>
    <tr>
    <td>
     
   Latest Notice Date: </td>
   <td>                          
   		<?php echo date('d/m/Y',strtotime($latest_notice_date)); ?>					
                               
    </td>
</tr>


<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/notice/index.php?id=<?php echo $file_id; ?>&from=customerhome"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 

</table>
</div>
<?php } ?>

<?php if(is_numeric($no_of_legal_notice) && $no_of_legal_notice>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Legal Notice Details </h4>


<table class="insertTableStyling detailStylingTable">
<tr>
    <td class="firstColumnStyling">
    No of Legal Notices :</td>
    <td> 
		<?php echo $no_of_legal_notice; ?>
    </td>
    <tr>
    <td>
     
   Latest Legal Notice Date: </td>
   <td>                          
   		<?php echo date('d/m/Y',strtotime($latest_legal_notice_date)); ?>					
                               
    </td>
</tr>


<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/legal_notice/index.php?id=<?php echo $file_id; ?>&from=customerhome"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 

</table>
</div>
<?php } ?>

<?php if(is_array($noc) && is_numeric($noc['noc_id']))
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">NOC Details </h4>


<table class="insertTableStyling detailStylingTable">
<tr>
    <td class="firstColumnStyling">
    NOC Date :</td>
    <td> 
		<?php echo date('d/m/Y',strtotime($noc['noc_date'])); ?>		
    </td>
    <tr>
    <td>
     
  Remarks: </td>
   <td>                          
   		<?php echo $noc['remarks']; ?>					
                               
    </td>
</tr>

<?php if((in_array(11,$admin_rights) || in_array(7,$admin_rights)  )) { ?>
<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/noc/index.php?view=noc&id=<?php echo $file_id; ?>&from=customerhome"><button title="View this entry" class="btn viewBtn"><span class="view">Print NOC</span></button></a>
  
  <a href="<?php echo WEB_ROOT; ?>admin/customer/noc/index.php?action=delete&id=<?php echo $file_id; ?>&lid=<?php echo $noc['noc_id']; ?>"><button title="View this entry" class="btn btn-danger delBtn">Delete NOC</button></a>
   </td>        
</tr> 
<?php } ?>
</table>
</div>
<?php } ?>

<?php if(is_array($unreceived_welcome_letters) && is_numeric($unreceived_welcome_letters[0]['welcome_id']))
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Unreceived Welcome Letter Details </h4>


<table class="insertTableStyling detailStylingTable">
<?php foreach($unreceived_welcome_letters as $unreceived_welcome_letter) { ?>
<tr>
    <td class="firstColumnStyling">
    Welcome Date :</td>
    <td> 
		<?php echo date('d/m/Y',strtotime($unreceived_welcome_letter['welcome_date'])); ?>		
    </td>
    <tr>
    <td>
     
  Welcome Type: </td>
   <td>                          
   		<?php  if($unreceived_welcome_letter['welcome_type']==0) echo "Customer"; else echo "Guarantor"; ?>					
                               
    </td>
</tr>

  <tr>
    <td>
     
  Unreceived Reason: </td>
   <td>                          
   		<?php  echo $unreceived_welcome_letter['not_received_type']; ?>					
                               
    </td>
</tr>

<?php } ?>

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/welcome/index.php?&id=<?php echo $file_id; ?>&from=customerhome"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 

</table>
</div>
<?php } ?>

<?php if($cheque_return_detais!="error" && is_array($cheque_return_detais) && count($cheque_return_detais)>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Cheque Return Details </h4>


<table class="insertTableStyling detailStylingTable">


<tr>
    <td class="firstColumnStyling">
    Total Cheque Return: 
    </td>
    
    <td>
     
                                 <?php echo count($cheque_return_detais); ?>					
                               
    </td>
</tr>


 
 
 
 

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=chequeReturnDetails&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 




</table>
</div>
<?php } ?>
<?php if(is_array($loan_cheques) && is_numeric($loan_cheques['cheque_details_id'])) { ?> 
<div class="detailStyling">

<h4 class="headingAlignment">Cheque Received Details </h4>


<table class="insertTableStyling detailStylingTable">
<tr>
<td width="220px">Required Cheques<span class="requiredField">* </span> : </td>
				<td>
					<?php echo $loan_cheques['required_cheques']; ?>
                            </td>
</tr>

<tr>
<td width="220px">Cheques Received<span class="requiredField">* </span> : </td>
				<td>
					  <?php echo $loan_cheques['cheques_received']; ?>
                  
                            </td>
</tr>
<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/loan_cheques/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
   </td>        
</tr> 
</table>
</div>
<?php } ?>
<?php if(isset($loan) && $loan!="error") {
	
	$loan_emi_id_unpaid=getOldestUnPaidEmi($loan['loan_id']);
	

?>
<div class="clearfix"></div>
<h4 class="headingAlignment">Emi Details</h4>

<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button><?php  if($loan_emi_id_unpaid!=false && is_numeric($loan_emi_id_unpaid) && ($file['file_status']==1 || $file['file_status']==5) && (LEGAL_PAYMENT==1 || (!isset($no_of_legal_notice) || $no_of_legal_notice==0)) && ($seize=="error" || SEIZE_PAYMENT==1)) { ?> <a id="payment_anchor" class="no_print" href="payment/index.php?id=<?php echo $file_id; ?>&state=<?php echo $loan_emi_id_unpaid; ?>" style="font-size:12px; color:#d00;"><button title="ALT + P" class="btn btn-success" style="float:right;position:relative;top:10px;margin-left:10px;" >+ Add payment </button></a> <?php } ?> <?php  if($loan_emi_id_unpaid!=false && is_numeric($loan_emi_id_unpaid)  && ($file['file_status']==1 || $file['file_status']==5) && (!isset($no_of_legal_notice) || $no_of_legal_notice==0) && ($seize=="error" || SEIZE_PAYMENT==1)) { ?>  <a class="no_print" href="payment/index.php?view=addMultiple&id=<?php echo $file_id; ?>&state=<?php echo $loan_emi_id_unpaid; ?>" style="font-size:12px; color:#d00;"> <button class="btn btn-success" style="float:right;position:relative;top:10px;" >+ Add Multiple payment</button></a>  <?php } ?>  <a class="no_print" href="index.php?action=reorderPayments&id=<?php echo $file_id; ?>" style="font-size:12px; color:#d00;"> <button class="btn btn-danger" style="float:right;position:relative;top:10px;margin-right:10px;" onclick="return confirm('Are you sure? Changes cannot be UNDONE!')" > Reorder Payments </button></a>
<a class="no_print" href="index.php?view=excelImport&id=<?php echo $file_id; ?>" style="font-size:12px; color:#d00;"> <button class="btn btn-danger" style="float:right;position:relative;top:10px;margin-right:10px;"  > Import Excel </button></a>
 </div>
    <div class="no_print" style="width:100%;">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading date">EMI Date</th>
            <th class="heading">Amount</th>
            <th class="heading">Payment</th>
            <th class="heading">Balance</th>
            <th class="heading date">Payment Date</th>
            <th class="heading" width="10%">Penalty</th>
            <th class="heading" width="10%">Rasid No</th>
          	<th class="heading">Company Paid Date</th>
            <th class="heading" width="20%">Remarks</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($emiTable as $emi)
		{	
			$emiDetails=$emi['loanDetails'];
			$paymentDetails=$emi['paymentDetails'];
			$acBal=getBalanceForEmi($emiDetails['loan_emi_id']);
			
			$balance=0;
		 ?>
         
         <tr class="resultRow <?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $acBal<0 && $file['file_status']!=4) { ?> dangerRow<?php } else if($file['file_status']==4 && strtotime($closureDetails['file_close_date'])>=strtotime($emiDetails['actual_emi_date'])) { ?> dangerRow<?php } ?> <?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $acBal==0) { ?> shantiRow<?php } ?>">
        	<td><?php echo ++$no; ?>
            </td>
            
            <td><?php echo date('d/m/Y',strtotime($emiDetails['actual_emi_date'])); ?>
            </td>
            
            <td><?php echo  number_format($emiDetails['emi_amount']); ?>
            </td>
            
            <td><?php if($paymentDetails==false) { $balance=$emiDetails['emi_amount'];} else{ $totalPaid=0; foreach($paymentDetails as $paymentDetail){ if($paymentDetail['payment_mode']==1)$payment_mode="CS"; else $payment_mode="CQ"; echo number_format($paymentDetail['payment_amount'])."(".$payment_mode.")<br/>"; $totalPaid=$totalPaid+$paymentDetail['payment_amount']; $balance=$emiDetails['emi_amount']-$totalPaid;} // echo "<hr class='inTableHr'>".number_format($totalPaid); 
			  }  ?>
            </td>
            
            <td><?php if($balance>0) echo "-".number_format($balance); else echo $balance; ?></td>
            
             <td><?php if($paymentDetails==false) echo "NA"; else{ foreach($paymentDetails as $paymentDetail){ echo date('d/m/Y',strtotime($paymentDetail['payment_date']))."<br/>";}}  ?>
            </td>
            
            <td>
            	<?php  echo getPenaltyDaysFroEmiId($emiDetails['loan_emi_id']);  if(!defined('PENALTY_IN_DAYS') || PENALTY_IN_DAYS==0) 
					echo " Days"; else if(PENALTY_IN_DAYS==1) echo " Months";   ?>
            </td>
            
            <td><?php if($paymentDetails==false) echo "NA"; else{ foreach($paymentDetails as $paymentDetail){ echo $paymentDetail['rasid_no'];  ?> <a class="no_print" href="<?php echo 'payment/index.php?view=edit&lid='.$paymentDetail['emi_payment_id'].'&id='.$file_id.'&state='.$emiDetails['loan_emi_id']; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a><br><?php }  }  ?>
            </td>
            
            <td><?php if( $emiDetails['company_paid_date']!=null) { echo date('d/m/Y',strtotime($emiDetails['company_paid_date'])); ?><br /><a class="no_print" href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=editCompanyPaidDate&id=<?php echo $file_id; ?>&lid=<?php echo $emiDetails['loan_emi_id']; ?>" style="font-size:12px; color:#d00;">Edit</a> <a class="no_print" onclick="return confirm('Are you sure?')" href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=deleteCompanyPaidDate&id=<?php echo $file_id; ?>&lid=<?php echo $emiDetails['loan_emi_id']; ?>" style="font-size:12px; color:#d00;">Del</a><?php } ?><?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $emiDetails['company_paid_date']==null) { ?><a class="no_print" href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=addCompanyPaidDate&id=<?php echo $file_id; ?>&lid=<?php echo $emiDetails['loan_emi_id']; ?>" style="font-size:12px; color:#d00;">Add</a><?php } ?>
            </td>
            
             <td><?php  if($paymentDetails==false) echo "NA"; else{ foreach($paymentDetails as $paymentDetail){  $penalty_payment = getPenaltyAmountByEmiPaymentId($paymentDetail['emi_payment_id']); if(is_numeric($penalty_payment)) echo "<li> Penalty : ".$penalty_payment."</li>"; } foreach($paymentDetails as $paymentDetail){  if($paymentDetail['remarks']=="" && ($paymentDetail['remainder_date']=="0000-00-00" || $paymentDetail['remainder_date']=="1970-01-01")){ echo ""; }else if($paymentDetail['remarks']!="" && ($paymentDetail['remainder_date']=="0000-00-00" || $paymentDetail['remainder_date']=="1970-01-01")){ echo " <li> ".$paymentDetail['remarks']."</li>";}else if($paymentDetail['remarks']!="" && $paymentDetail['remainder_date']!=null && $paymentDetail['remainder_date']!="1970-01-01" && $paymentDetail['remainder_date']!="0000-00-00"){  echo " <li> ".$paymentDetail['remarks']." | ".$paymentDetail['remainder_date']."</li>";}else if($paymentDetail['remarks']=="" && $paymentDetail['remainder_date']!=null && $paymentDetail['remainder_date']!="1970-01-01" && $paymentDetail['remainder_date']!="0000-00-00"){  echo " <li> ".$paymentDetail['remainder_date']."</li>";}}}    ?>
             <?php if($file['file_status']==4) echo "<br>"; if($file['file_status']==4 && (strtotime($loan['loan_ending_date'])<=strtotime($closureDetails['file_close_date']))) echo "FORCED CLOSURE";else if($file['file_status']==4 && (strtotime($loan['loan_ending_date'])>strtotime($closureDetails['file_close_date']))) echo "PRE CLOSURE"; ?>
            </td>
            
            
             	<td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=EMIdetails&id='.$file_id.'&state='.$emiDetails['loan_emi_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
        </tr>

         <?php }?>
         </tbody>
    </table>
    
	</div>  
 <table style="width:100%;" id="to_print" class="to_print adminContentTable"></table>     
<?php	
	} ?>
    

<div class="clearfix"></div>
<script type="text/javascript" >
jQuery(document).bind("keyup keydown", function(e){
    if(e.altKey && e.keyCode == 80){
	  
		// var hrf=document.getElementById('payment_anchor').getAttribute('href');
		
		window.document.location = "payment/index.php?id=<?php echo $file_id; ?>&state=<?php echo $loan_emi_id_unpaid; ?>";
    }
});
</script>