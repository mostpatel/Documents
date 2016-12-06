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
<div  style="text-align:center; font-size:17px;">
<span style="padding-left:100px;padding-right:100px;padding-bottom:5px;border-bottom:1px solid #000;">Subject To Pali Jurisdiction
Subject To Realization Of Cheque/Draft To Ahmedabad
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>
<div  style="float:left; ">

</div>
<div  style="float:Right; ">

</div>

<?php if(is_numeric($file['agency_id']))  { ?>
<div style="text-align:center;padding-top:10px" >
<?php ?>Franchisee of <?php echo getAgencyNameFromFileId($file_id); ?> 
</div>
<?php } ?>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;"></div>
                                        <div class="date" style="float:left;">From : </div>
                                                                    <div class="date" style="float:right;padding-right:20px;">Date : <?php echo date('d/m/Y'); ?> </div>
                                                                     <div style="line-height:28px;font-size:19px;top:0px;position:relative;padding-top:40px;">
<div class="prati" style="padding-bottom:0;">To,<br />
______________________________________________________<br />
______________________________________________________<br />
______________________________________________________<br />


Dear Sirs,
<br />
We Hereby authorize you to repossess the following vehicle financed by us and currently under Hire Purchase Agreement with our Co. Due to default in payment of installmants, Hires is herewith advised to handover the vehicle to the authorised person.</div>
<div class="customer_address" style="float:left;width:100%;"><b>1. Name of Hirer : </b><?php echo $customer['customer_name']; ?><br />
<b>2. Address of the Hirer : </b><pre><?php echo $customer['customer_address']; ?></pre>
<br />
<b>3. Agreement No / Date : </b><?php echo $file['file_number']." | "; echo date('d/m/Y',strtotime($loan['loan_approval_date'])); ?>
<br />
 &nbsp;&nbsp;  (a) Vehicle Regn. No. : <?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no'];   ?>
   
<br />
&nbsp;&nbsp;	(b) Make : <?php echo getVehicleCompanyNameById($vehicle['vehicle_company_id']); ?>
<br />
&nbsp;&nbsp;	(c) Model : <?php echo getModelNameById($vehicle['model_id']);  ?><br />
&nbsp;&nbsp;    (d) Engine No : <?php echo $vehicle['vehicle_engine_no'];  ?><br />
&nbsp;&nbsp;    (e) Chassis No : <?php echo $vehicle['vehicle_chasis_no'];  ?><br />
</div>  
 
</div><br /><br /><br /><br />       
<div style="clear:both;font-size:18px;line-height:28px;">
                               In this connection, we also authorise you to lodge any complaints to the police on our behalf and to obtain police help. if so required.
<br /><br />
it is advised the hirer to surrender the vehicle only after checked and evacuted completely. If vehicle is not surrendered freely and peacefully, legal and severe actions will be taken against you. 
<br /><br />
N.B. : Payment should be made only by Draft and not by cash on any account or to anyone. It is advised strictly to make payment at our above mentioned office only. 
<br /><br />
<div style="float:right;padding-right:20pxl">Specimen Signature of the person Authorised</div>
<br /><br />
Thanking you,
<br /><br />
Truly Yours,
<br />
</div>
</div>
</div>
<div class="clearfix"></div>