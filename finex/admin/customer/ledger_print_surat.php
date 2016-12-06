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

if(is_array($file) && $file!="error")
{
	$settle_file=getSettleFileDetails($file_id);
	$customer=getCustomerDetailsByFileId($file_id);
	$guarantor=getGuarantorDetailsByFileId($file_id);
	if($guarantor=="error")
	$guarantor=NULL;
	$loan=getLoanDetailsByFileId($file_id);
	$vehicle=getVehicleDetailsByFileId($file_id);
	$cheque_return_detais=getChequeReturnDetailsForFileId($file_id);
	$customer_id=$customer['customer_id'];
	$agency_participation_details=getLoanSchemeAgency($loan['loan_id']);
	$noc = getNOCByFileId($file_id);
	$no_of_notice=getNumberOfNoticesForFileID($file_id);
	if($no_of_notice>0)
	$latest_notice_date=getLatestNoticeDateForFile($file_id);
	
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
		}
	else
	{
		$seize="error";
		}	
	if($file!="error")
	{
	$remarks=listRemarksForFile($file_id);
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
<div class="insideCoreContent adminContentWrapper wrapper" style="width:95%;min-width:95%;margin-left:5%;">

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
<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT ?>admin/customer/welcome/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-success">Issue Welcome Letter</button></a> <a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div id="companyTitle">    <?php
							 
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
							 ?>	 </div>

<h5 class="" style="width:92%;margin-left:8%;"> BROKER :   <?php echo getBrokerNameFromBrokerId($file['broker_id'])." "; ?>	
 
<SPAN style="float:right;margin-right:30PX;font-size:24px"><?php echo $file['file_number']; ?>
                             </SPAN>							
                             <SPAN style="float:right;margin-right:30PX;font-size:18px"><?php echo "(".$file['file_agreement_no'].") " ?></SPAN>
                             </h5>
<div style="display:none;" class="detailStyling" >


<h5 class="">Customer's Details</h5>

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php if(validateForNull($customer['secondary_customer_name'])) echo $customer['secondary_customer_name']; else echo $customer['customer_name']; ?>					
                            
</td>
</tr>

<tr>
<td >
Address : 
</td>

<td style="max-width:300px;">

                             <?php if(validateForNull($customer['secondary_customer_address'])) echo $customer['secondary_customer_address']; else echo $customer['customer_address']; ?>					
                            
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

        

</table>
</div>



<?php   if(is_array($guarantor) && isset($guarantor['guarantor_id']) && is_numeric($guarantor['guarantor_id'])) { ?>
<div class="detailStyling" style="display:none;" >

<h5 class="">Guranteer's Details</h5>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
 Name : 
</td>

<td>
                             <?php if(validateForNull($guarantor['secondary_guarantor_name'])) echo $guarantor['secondary_guarantor_name']; else echo $guarantor['guarantor_name']; ?>					
                             
</td>
</tr>

<tr>
<td>
Guranteer's Address : 
</td>

<td style="max-width:300px;">

                             <?php if(validateForNull($guarantor['secondary_guarantor_address'])) echo $guarantor['secondary_guarantor_address']; else echo $guarantor['guarantor_address']; ?>					
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
 

            
 </table>
</div>

<?php } ?>
<div class="detailStyling" style="width:100%;">
<table width="99%" border="1">
<tr>
	<th style="padding:5px;padding-bottom:5px;text-align:left;">Name of Party : <?php echo $customer['customer_name']; ?></th>
    <th style="padding:5px;padding-bottom:5px;text-align:left;">Vehicle No. : <?php echo $vehicle['vehicle_reg_no']; ?></th>
</tr>
</table>
</div>
<div class="detailStyling" style="width:100%;display:none;">
<table width="99%" border="1">
<tr>
	<th>New or Used</th>
    <th>Model</th>
    <th>Chasis No</th>
    <th>Engine No</th>
    <th>Make</th>
    <th>Reg No</th>
</tr>
<tr>
	<td align="center"> <?php if($vehicle['vehicle_condition']==1) echo "NEW"; else echo "OLD"; ?></td>
    <td align="center"><?php echo getModelNameById($vehicle['model_id'])." <br>".$vehicle['vehicle_model']; ?></td>
    <td align="center"><?php echo $vehicle["vehicle_chasis_no"]; ?></td>
    <td align="center"><?php echo $vehicle["vehicle_engine_no"]; ?></td>
    <td align="center"><?php  $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo $company['company_name']; ?></td>
    <td align="center"><?php if($vehicle!="error") {  $reg_no=$vehicle['vehicle_reg_no']; $reg_no=strtoupper($reg_no); echo "<b>".$reg_no."</b>"; } else echo "NOT ADDED"; ?></td>
</tr>
</table>
<table width="100%" style="margin-top:10px;">
<tr>
<td>

  <?php
							 $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo $loan['loan_duration']." Instalment at "."Rs. ".number_format($loan['emi']);
							  else
							  {
								  foreach($emi as $e)
								  {
									  echo $e['duration']." Instalment at "."Rs. ".number_format($e['emi'])."<br>";
									  }
								  
								  } ?>			

</td>
<td>Loan Approval Date : 

 
                             <?php
							 echo date('d/m/Y',strtotime($loan['loan_approval_date']));
				
							  ?>					
                           
</td>
<td>
Loan Ending Date: 


                             <?php  echo date('d/m/Y',strtotime($loan['loan_ending_date'])); ?>					
                           
</td>

<td>  <?php 
							 $total_collection =  getTotalCollectionForLoan($loan['loan_id']);
							  echo "Rs. ".number_format($loan['loan_amount'])."<br>";
							  echo "RS. ".number_format(getProfitForLoan($loan['loan_id']))."<hr style='margin:0'>"; 			
							 echo  "Rs. ".number_format($total_collection); ?>		</td>
</tr>
</table>

<?php  if(is_array($insurance)) {?>



<table width="100%">

<tr>
<td>Insurance Company :
					<?php  $comp=getInsuranceCompanyById($insurance['insurance_company_id']); echo $comp[1]; ?>
                            </td>



<td>Insurance Issue Date : 
					<?php echo date('d/m/Y',strtotime($insurance['insurance_issue_date'])); ?>
                            </td>

<td>Insurance Expiry Date : 
					<?php echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); ?>
                            </td>

</tr>



</table>
<?php } ?>
</div>




<?php if(isset($loan) && $loan!="error") {
	
	$loan_emi_id_unpaid=getOldestUnPaidEmi($loan['loan_id']);
	

?>
<div class="clearfix"></div>
<h4 class="headingAlignment">Emi Details</h4>

<style>
#ledger_view_table tr td{
	padding:3px;
	}
</style>
    <div class="" style="width:95%;margin-left:5%">
    <table border="1" id="ledger_view_table" style="width:100%;">
    <thead>
    	<tr height="20px">
        	<td>No</th>
             
            <th class="heading date">EMI Date</td>
           <td>Amount</td>
            <td>Payment</td>
             <td>Rasid No</td>
              <td>Payment Date</td>
            <td>Balance</td>
           
            
           
         
            <td width="20%">Remarks</td>
           
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
         
         <tr   >
        	<td style="padding-top:10px;padding-bottom:10px;"><?php echo ++$no; ?>
            </td>
            
            
            
            <td><?php echo date('d/m/Y',strtotime($emiDetails['actual_emi_date'])); ?>
            </td>
            
            <td><?php echo  number_format($emiDetails['emi_amount']); ?>
            </td>
            
            <td><?php if($paymentDetails==false) { $balance=$emiDetails['emi_amount'];} else{ $totalPaid=0; foreach($paymentDetails as $paymentDetail){ if($paymentDetail['payment_mode']==1)$payment_mode="CS"; else $payment_mode="CQ"; echo number_format($paymentDetail['payment_amount'])."(".$payment_mode.")<br/>"; $totalPaid=$totalPaid+$paymentDetail['payment_amount']; $balance=$emiDetails['emi_amount']-$totalPaid;} // echo "<hr class='inTableHr'>".number_format($totalPaid); 
			  }  ?>
            </td>
            
           
            
              <td><?php if($paymentDetails==false) echo ""; else{ foreach($paymentDetails as $paymentDetail){ echo $paymentDetail['rasid_no']."<br/>";}}  ?>
            </td>
            
             <td><?php if($paymentDetails==false) echo ""; else{ foreach($paymentDetails as $paymentDetail){ echo date('d/m/Y',strtotime($paymentDetail['payment_date']))."<br/>";}}  ?>
            </td>
            
          
           <td></td>
            
          
            
          
            
             <td><?php  if($paymentDetails==false) echo ""; else{ foreach($paymentDetails as $paymentDetail){  $penalty_payment = getPenaltyAmountByEmiPaymentId($paymentDetail['emi_payment_id']); if(is_numeric($penalty_payment)) echo "<li> Penalty : ".$penalty_payment."</li>"; } foreach($paymentDetails as $paymentDetail){  if($paymentDetail['remarks']=="" && ($paymentDetail['remainder_date']=="0000-00-00" || $paymentDetail['remainder_date']=="1970-01-01")){ echo ""; }else if($paymentDetail['remarks']!="" && ($paymentDetail['remainder_date']=="0000-00-00" || $paymentDetail['remainder_date']=="1970-01-01")){ echo " <li> ".$paymentDetail['remarks']."</li>";}else if($paymentDetail['remarks']!="" && $paymentDetail['remainder_date']!=null && $paymentDetail['remainder_date']!="1970-01-01" && $paymentDetail['remainder_date']!="0000-00-00"){  echo " <li> ".$paymentDetail['remarks']." | ".$paymentDetail['remainder_date']."</li>";}else if($paymentDetail['remarks']=="" && $paymentDetail['remainder_date']!=null && $paymentDetail['remainder_date']!="1970-01-01" && $paymentDetail['remainder_date']!="0000-00-00"){  echo " <li> ".$paymentDetail['remainder_date']."</li>";}}}    ?>
            </td>
            
            
             	
        </tr>

         <?php }
		 for($no;$no<36;$no)
		 {
		 ?>
           <tr   >
        	<td style="padding-top:10px;padding-bottom:10px;"><?php echo ++$no; ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
             <td></td>
            <td></td>
            <td></td>
            <td></td>
         <?php } ?>
         </tbody>
    </table>
    
	</div>  
 
<?php	
	} ?>
    
<div style="page-break-after:always;"></div>

<h5 class="" style="width:92%;margin-left:8%;"> BROKER :   <?php echo getBrokerNameFromBrokerId($file['broker_id'])." "; ?>	
 
<SPAN style="float:right;margin-right:30PX;font-size:24px"><?php echo $file['file_number']; ?>
                             </SPAN>							
                             <SPAN style="float:right;margin-right:30PX;font-size:18px"><?php echo "(".$file['file_agreement_no'].") " ?></SPAN>
                             </h5>
<div  class="detailStyling" style="width:45%;margin-left:5%;"  >

<table border="1" id="insertCustomerTable" class="insertTableStyling detailStylingTable" width="100%;">

<tr>
<td height="50px">Date of Agreement :</td>
<td><?php echo date('d/m/Y',strtotime($loan['loan_approval_date'])); ?></td>
</tr>

<tr>
<td>Registration No. :</td>
<td><?php if(isset($vehicle['vehicle_reg_no']) && $vehicle['vehicle_reg_no']!="NA") echo $vehicle['vehicle_reg_no'] ?></td>
</tr>

<tr>
<td>Engine No. :</td>
<td><?php if(isset($vehicle['vehicle_engine_no']) && $vehicle['vehicle_engine_no']!="NA") echo $vehicle['vehicle_engine_no'] ?></td>
</tr>

<tr>
<td>Chasis No. :</td>
<td><?php if(isset($vehicle['vehicle_chasis_no']) && $vehicle['vehicle_chasis_no']!="NA") echo $vehicle['vehicle_chasis_no'] ?></td>
</tr>


<tr>
<td>Chasis No. :</td>
<td><?php if(isset($vehicle['vehicle_chasis_no']) && $vehicle['vehicle_chasis_no']!="NA") echo $vehicle['vehicle_chasis_no'] ?></td>
</tr>

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
<td>Vehicle Model Year : </td>
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
</table>
</div>
<div  class="detailStyling" >

<table border="1" id="insertCustomerTable" class="insertTableStyling detailStylingTable" width="100%;">

<tr>
<td colspan="2" align="center">Name & Address of the HIRER</td>
</tr>

<tr>

<td width="100px;" class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php if(validateForNull($customer['secondary_customer_name'])) echo $customer['secondary_customer_name']; else echo $customer['customer_name']; ?>					
                            
</td>
</tr>

<tr>
<td >
Address : 
</td>

<td style="max-width:300px;">

                             <?php if(validateForNull($customer['secondary_customer_address'])) echo $customer['secondary_customer_address']; else echo $customer['customer_address']; ?>					
                            
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

        

</table>








<table width="100%;" border="1" id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td colspan="2" align="center">Name & Address of the GUARANTOR</td>
</tr>

<tr>

<td width="100px;" class="firstColumnStyling">
 Name : 
</td>

<td>
                             <?php if(validateForNull($guarantor['secondary_guarantor_name'])) echo $guarantor['secondary_guarantor_name']; else if(validateForNull($guarantor['guarantor_name'])) echo $guarantor['guarantor_name']; ?>					
                             
</td>
</tr>

<tr>
<td>
 Address : 
</td>

<td style="max-width:300px;">

                             <?php if(validateForNull($guarantor['secondary_guarantor_address'])) echo $guarantor['secondary_guarantor_address']; else if(validateForNull($guarantor['guarantor_address'])) echo $guarantor['guarantor_address']; ?>					
                             </td>
</tr>

 <tr id="addcontactTrGuarantor">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                	<?php
                            $contactNos = $guarantor['contact_no'];
							if(is_array($contactNos))
							{
                            foreach($contactNos as $c)
                              {
                       ?>
                             
                             <?php echo $c[0]." <br> "; ?>					
                             <?php }} ?>
                </td>
            </tr>
 <tr>

<td width="100px;" class="firstColumnStyling">
 C.N.G : 
</td>

<td>
</td>
</tr>
<td width="100px;" class="firstColumnStyling">
 Kit No. : 
</td>

<td></td>
</tr>
                           			
                             
</td>
<td width="100px;" class="firstColumnStyling">
 Cy No. : 
</td>

<td></td>
</tr>
                           			
                             
</td>


</tr>

            
 </table>
</div>

<div  class="detailStyling" style="width:95%;margin-left:5%;margin-top:200px;" >

<table border="1" id="insertCustomerTable" class="insertTableStyling detailStylingTable" width="100%;">
<tr>
	<td width="20%;">Tax</td>
    <td width="20%;"></td>
    <td width="20%;"></td>
    <td width="20%;"></td>
    <td width="20%;"></td>
</tr>

<tr>
	<td>Fitness</td>
    <td><?php if(validateForNull($vehicle['passing_exp_date']) && $vehicle['passing_exp_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle['passing_exp_date'])) ?></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
	<td>Permit</td>
    <td><?php if(validateForNull($vehicle['permit_exp_date']) && $vehicle['permit_exp_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle['permit_exp_date'])) ?></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
</table>
</div>

<div  class="detailStyling" style="width:95%;margin-left:5%;"  >

<table border="1" id="insertCustomerTable" class="insertTableStyling detailStylingTable" width="100%;">
<tr>
  <?php 
							 $total_collection =  getTotalCollectionForLoan($loan['loan_id']);
                             
							  echo " <td>Amount</td><td> Rs. ".number_format($loan['loan_amount']); ?></td>
                              </tr>
                              <tr>
                              <?php
							  echo " <td></td><td>Rs. ".number_format(getProfitForLoan($loan['loan_id'])); ?> </td>
                              </tr>
                              <tr>
                              <?php 			
							 echo  "<td>Total </td><td> Rs. ".number_format($total_collection); ?>		</td>
</tr>
</table>
</div>
<div class="detailStyling" style="width:95%;margin-left:5%;margin-top:100px;">
<table width="99%" border="1">
<tr>
	<th style="padding:5px;padding-bottom:5px;text-align:left;">Name of the company</th>
    <th style="padding:5px;padding-bottom:5px;text-align:left;">Policy No</th>
    <th style="padding:5px;padding-bottom:5px;text-align:left;">Sum Insured</th>
    <th style="padding:5px;padding-bottom:5px;text-align:left;">Premium Paid</th>
    <th style="padding:5px;padding-bottom:5px;text-align:left;">Expiry Date</th>
</tr>
<?php  if(is_array($insurance)) {?>
<tr>
	<td><?php  $comp=getInsuranceCompanyById($insurance['insurance_company_id']); echo $comp[1]; ?></td>
    <td><?php echo $insurance['policy_no']; ?></td>
    <td><?php echo $insurance['idv']; ?></td>
   <td> <?php echo $insurance['insurance_premium']; ?></td>

   <td> <?php echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); ?></td>
</tr>
<?php } else { ?>
<tr>
<td height="30px;"></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php } ?>
<tr>
<td height="30px;"></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr><tr>
<td height="30px;"></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
</table>
<div style="font-size:20px;font-weight:bold;font-family:myFontBold;padding:10px;margin-top:50px;">Add: Hire Purchase Charges @ ____________% For <?php echo $loan['loan_duration']; ?> Months</div>
</div>
<div class="clearfix"></div>
<style>
table{
	
	border:3px solid #000;
	}
table tr td,table tr th {height:50px; font-size:20px;font-weight:bold;font-family:myFontBold;padding:10px;	border:3px solid #000;}
#ledger_view_table tr td{height:20px; font-size:16px;padding:0;	border:3px solid #000;padding-left:3px;}
</style>
<script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/customer_shortcuts.js" >