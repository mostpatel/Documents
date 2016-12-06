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
<div class="insideCoreContent adminContentWrapper wrapper" style="width:95%;min-width:95%">

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

<h5 class="" style="width:100%"> BROKER :   <?php echo getBrokerNameFromBrokerId($file['broker_id'])." "; ?>	
 
<SPAN style="float:right;margin-right:30PX;font-size:24px"><?php echo $file['file_number']; ?>
                             </SPAN>							
                             <SPAN style="float:right;margin-right:30PX;font-size:18px"><?php echo "(".$file['file_agreement_no'].") " ?></SPAN>
                             </h5>
<div class="detailStyling" >


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
<div class="detailStyling" >

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
<div class="detailStyling" style="width:100%">
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


    <div class="" style="width:95%;margin-left:5%">
    <table id="" class="adminContentTable">
    <thead>
    	<tr height="20px">
        	<th class="heading">No</th>
             
            <th class="heading date">EMI Date</th>
           <th class="heading">Amount</th>
            <th class="heading">Payment</th>
             <th class="heading">Rasid No</th>
              <th class="heading date">Payment Date</th>
            <th class="heading">Balance</th>
           
            
           
         
            <th class="heading" width="20%">Remarks</th>
           
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
         
         <tr  class="resultRow <?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $acBal<0) { ?> dangerRow<?php } ?> <?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $acBal==0) { ?> shantiRow<?php } ?>" >
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

         <?php }?>
         </tbody>
    </table>
    
	</div>  
 
<?php	
	} ?>
    

<div class="clearfix"></div>
<script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/customer_shortcuts.js" >