<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$file_id=$_GET['id'];
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
	$customer=getCustomerDetailsByFileId($file_id);
	$guarantor=getGuarantorDetailsByFileId($file_id);
	$loan=getLoanDetailsByFileId($file_id);
	$vehicle=getVehicleDetailsByFileId($file_id);
	$customer_id=$customer['customer_id'];
	if($file['file_status']==4)
	{
		$closureDetails=getPrematureClosureDetails($file_id);
		}
	if($loan!="error")
	{
		$totalPayment=getTotalPaymentForLoan($loan['loan_id']);
		$balance_left=getBalanceForLoan($loan['loan_id']); 
		$total_collection = getTotalCollectionForLoan($loan['loan_id']);
		$paid_emis=getTotalEmiPaidForLoan($loan['loan_id']);
		
	    $duration=$loan['loan_duration'];
		$emi_without_interest=$loan['loan_amount']/$duration;
		$total_interet=$total_collection-$loan['loan_amount'];
		$interest=$total_interet/$duration;
		
	};
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="index.php?view=search"><button class="btn btn-warning">Go to Search</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<div class="interest_certificate_container" style="padding-top:0px;">
  <div style="padding-left:100px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:20px;">

<div class="customer_address" style="float:right;width:48%;"><?php echo $customer['customer_name']; ?> <br /><pre><?php echo $customer['customer_address'] ?> </pre>   
<br>
<br>
Policy No :  <?php echo $insurance['policy_no']; ?>
<br>
<?php echo date('d/m/Y',strtotime($insurance['insurance_issue_date'])); ?> TO <?php echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); ?>
</div>
</div>
<div style="clear:both;"></div>
<div class="prati">લિબર્ટી વીડીયોકોન જનરલ ઇન્સો.કં.લી.,</div>
<div class="saheb_shri">મેનેજર શ્રી.  ,</div>
<div style="position:relative;width:100%;text-align:center;font-size:24px;padding-top:20px;padding-bottom:20px;">વિષય : પોલીસીમાં HP દાખલ કરવા બાબત.</div>
             <div class="main_para">


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; સવીનય સાથે જણાવવાનુ કે આપની કંપનીમાં મારો વીમો ચાલુ છે. તેમાં HP દાખલ કરી આપવા વિનંતી છે.
આ સાથે મારી વીમાની કોપી અને ગાડીનું બીલ અથવા RC બુકની કોપી સાથે આપેલ છે.

અપનો વિસ્વસું                                                                                                                                                                                                                                                              
 </div>
     

                                                                         
                                                                       <div class="visvasu"> આપનો વિશ્વાસુ </div>                                                                            


</div>
</div>
<div class="clearfix"></div>