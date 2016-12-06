<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

if(isset($_GET['bulk']) && $_GET['bulk']==1)
{
	$notice_id_array = getNoticesForBulkNoticeId($_GET['id']);
}
else
{
$notice_id=$_GET['id'];
$notice_id_array = array($notice_id);
$notice=getNoticeById($notice_id);
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/notice/index.php?id=<?php echo $notice['file_id']; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<?php
foreach($notice_id_array as $notice_id)
{
$notice=getNoticeById($notice_id);
$file_id=$notice['file_id'];
$file=getFileDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
$loan=getLoanDetailsByFileId($file_id);
$bucket_details=getBucketDetailsForLoan($loan['loan_id'],$notice['notice_date']);
$bucket_amount = getTotalBucketAmountForLoan($loan['loan_id'],$notice['notice_date']);
$bucket_string = "";

 if(isset($bucket_details) && $bucket_details!=0 && is_array($bucket_details) && count($bucket_details)>0) 
 { 
 foreach($bucket_details as $e=>$corr_bucket) 
 { 
	 $whole = floor($corr_bucket);      // 1
	 $fraction = $corr_bucket - $whole; // .25
	 $whole_bucket_amount = 0;
	 $whole_bucket_amount =  $whole_bucket_amount + ($e * $whole);
	 $bucket_string = $bucket_string." રૂ.".$e."/- ના એક એવા ".number_format($whole,0);
	 if(count($bucket_details)>1)
	 $bucket_string=$bucket_string." | ";
 } 
 $fraction_bucket_amount = $bucket_amount - $whole_bucket_amount;
 
 if($fraction_bucket_amount>0)
 $bucket_string = $bucket_string." +  રૂ.".$fraction_bucket_amount;
// $bucket_string = $bucket_string." (= કુલ રૂ.".$bucket_amount." ) ";
 }
 ?>

<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div id="page_fold" style="position:absolute;top:500px;left:-20px;display:none;">___</div>
<div  style="float:left; ">

</div>
<div  style="float:Right; ">

</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>
<?php if(is_numeric($file['agency_id']))  { ?>
<div style="text-align:center;padding-top:10px" >
<?php ?>Franchisee of <?php echo getAgencyNameFromFileId($file_id); ?> 
</div>
<?php } ?>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">NOTICE By U.P.C</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">date: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?> </div>
                                                                    <div class="date" style="float:left;padding-right:20px;">Agreement No: <?php echo $file['file_agreement_no']; ?> </div>
                                                                    <div class="clearfix"></div>
                                                                     <div style="padding-left:100px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:20px;">

<div class="customer_address" style="float:left;width:48%;">
To.,<br>
Shri/ Smt.
<br>
<?php echo $notice['customer_name']; ?>,<br /><pre><?php echo $notice['customer_address'] ?> </pre></div>   
<!--<div class="guarantor_address" style="float:right;widht:50%;"><?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div> <div style="clear:both;"></div>    -->
</div><br /><br />       <div style="clear:both;"></div>
<div style="text-align:center; font-weight:bolder; line-height:20px; width:100%;"> SUB  :  NOTICE FOR DEFAULT IN REPAYMENT</div>
 Dear Customer.,<br>
You have obtained Auto Finance from our company and purchased <?php  $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo $company['company_name']; ?>  	<?php echo getModelNameById($vehicle['model_id']); ?>  vehicle bearing Registration  No.   <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span><br><br>
You have made default in making repayment of loan and as on date there is overdue of Rs. <span class="special_text"><?php echo number_format($notice['bucket_amount'],2); ?></span>/- *(without any fine charge & penal interest) in your account.<br><br>
Hereby you are advised to make payment of Rs.<span class="special_text"><?php echo number_format($notice['bucket_amount'],2); ?></span>/-* (without any fine charge & penal interest) within 15 days., failing which our company will take possession of vehicle and will proceed further to settle your account by selling the vehicle.

                          
                                                                     <div class="lee">For, <?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?><br /><br />  Authorized Signatory
</div> <br />       
*NOTE : <br />
As per the agreement you are also liable to pay cheque return charges, penal Interest etc. at the time of making payments.

</div>
<div style="page-break-after:always;"></div>
<?php } ?>
</div>
<div class="clearfix"></div>