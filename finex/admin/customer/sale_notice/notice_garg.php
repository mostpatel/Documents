<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$notice_id=$_GET['id'];
$notice=getSaleNoticeById($notice_id);
$file_id=$notice['file_id'];
$file=getFileDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
$loan_id = getLoanIdFromFileId($file_id);
$loan = getLoanById($loan_id);
$profit = getProfitForLoan($loan_id);
$total_agreement_value = $loan['loan_amount'] + $profit;
if($file!="error")
	{
		$seize=getVehicleSeizeDetailsByFileId($file_id);
	}
?>
<style>
.notice_details
{
	margin:30px;
	width:90%;
	}
.notice_details tr td{
	
	padding:2px;
	padding-left:10px;
	font-weight:bold;
	}
</style>
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="../legal_notice/index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/sale_notice/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div  style="float:left; ">

</div>
<div  style="float:Right; ">

</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyNameByID($file['oc_id']); ?>
</div>
<?php if(is_numeric($file['agency_id']))  { ?>
<div style="text-align:center;padding-top:10px" >
<?php ?>Franchisee of <?php echo getAgencyNameFromFileId($file_id); ?> 
</div>
<?php } ?>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">NOTICE  BY  R.P.A.D                </div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">Date : <?php echo date('d/m/Y',strtotime($notice['sale_notice_date'])); ?> </div>
                                                                    <div class="date" style="float:left;padding-right:20px;">Agreement No: <?php echo $file['file_agreement_no']; ?> </div>
                                                                    <div class="clearfix"></div>
<div class="prati" style="padding-bottom:0;font-size:18px;line-height:24px;">TO,<br>
SHRI /SMT.  </div>
<div class="customer_address" style="float:left;width:48%;line-height:24px;font-size:18px;">
<?php echo $notice['customer_name']; ?>,<br /><pre><?php echo $notice['customer_address'] ?> </pre></div>   
 <div class="clearfix"></div>
   <div style="text-align:center; font-weight:bolder; line-height:20px; width:100%;"> SUB  :  NOTICE FOR DEFAULT IN REPAYMENT</div>
 Dear Customer.,<br>
 You   have  obtained  auto  Finance  from  our  company  and  purchased
<?php  $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo $company['company_name']; ?>  	<?php echo getModelNameById($vehicle['model_id']); ?>    vehicle  bearing  Registration  No.  <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span>
Engine  No.  <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_engine_no'])) echo $vehicle['vehicle_engine_no']; ?></span> &  Chassis  No.  <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_chasis_no'])) echo $vehicle['vehicle_chasis_no']; ?></span>
You  have  made  default  in making  repayment  of  loan  and  Rs. <?php echo number_format($total_agreement_value - $notice['total_amount_paid'],2); ?> /-
(without  any  fine  charge ,  penal  interest  and  seizing  charge )  is
Out  standing  as  on  date  in  your  account.  Due  to  defauli  your  vehicle  was  repossessed  
By   the  company .
Hereby  you  are  advised  to make  payment  of  Rs.  <?php echo number_format($total_agreement_value - $notice['total_amount_paid'],2); ?> / - (without  any  fine  charge , penal  interest   and  seizing  charge  )  within  7  days ,  failing  which  our  company  will  sale  out  the  vehicle  and  no  claim  from  your  side  will  sustain .


<div class="lee">With  Regards ,<br>For, <?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?><br /><br />  Authorized Signatory
</div> 
</div>
</div>
<div class="clearfix"></div>