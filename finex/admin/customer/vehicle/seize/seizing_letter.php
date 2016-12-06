<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$file_id=$_GET['id'];
$file=getFileDetailsByFileId($file_id);
$customer = getCustomerDetailsByFileId($file_id);
$loan = getLoanDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/notice/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div  style="float:left; ">
Mob: 9426060148 
</div>
<div  style="float:Right; ">
Landline: 079-25454626
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
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">Seizing Letter</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">Date : <?php echo date('d/m/Y'); ?> </div>
                                                                     <div style="line-height:28px;font-size:19px;top:0px;position:relative;padding-top:40px;">
<div class="prati" style="padding-bottom:0;">To,<br />
The Inspector of Police,<br />
<br /><br /><br />


Dear Sirs,
<br />
Sub : Information regarding the Repossession of Hired Vehicle No.
<br /><br /><br /></div>
<div class="customer_address" style="float:left;width:100%;">Customer Name : <?php echo $customer['customer_name']; ?>
<br />
Date of Finance and Hire : <?php echo date('d/m/Y',strtotime($loan['loan_approval_date'])); ?>
<br />
Vehicle Regn. No. and Make : <?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; echo " ".getVehicleCompanyNameById($vehicle['vehicle_company_id'])." ".getModelNameById($vehicle['model_id']);  ?>
<br /><br /><br />
</div>  
 
</div><br /><br /><br /><br />       
<div style="clear:both;font-size:18px;line-height:28px;">
                               The above referred client has defaulted on Scheduled repayment of his finance agreement as per details above and has thereby failed to comply with the terms and condition of the Hire Purchase Agreement.
<br /><br />
I accordance with our rights under the above mentioned agreement, we have repossessed the above mentioned vehicle.
<br /><br />
This repossession is taken within the guidelines laid down by the honorable supreme court. 
<br /><br />
The vehicle is surrendered freely and peacefully and hirer has checked and vacated it completely.
<br /><br />
This communication is for your records and to prevent any confusion that may arise from any complaint that the client or any other person may lodge with your regarding the said vehicle being stolen and otherwise.
<br /><br />
The vehicle is in our safe custody and we request you to kindly do not entertain any such complaint.
<br /><br />
Thanking you,
<br /><br />
Truly Yours,
<br />
</div>
</div>
</div>
<div class="clearfix"></div>