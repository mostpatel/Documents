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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/EMI/index.php?view=details&id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
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
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;"></div>
                                       <div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">Post-Intimation</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">Date : <?php echo date('d/m/Y'); ?> </div>
                                                                     <div style="line-height:28px;font-size:19px;top:0px;position:relative;padding-top:40px;">
<div class="prati" style="padding-bottom:0;">To,<br />
The Police Commissioner /SP./  Dy.SP. Office
,<br />
___________________ City<br>
AHMEDABAD
<br /><br /><br />
<br />
<div style=" font-weight:bolder; line-height:20px; width:100%;"> 
Sub : Intimation for likely actions to be taken against the defaulting Borrower/ Owner of Vehicle No. <br><br>
<span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span>
Engine  No.  <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_engine_no'])) echo $vehicle['vehicle_engine_no']; ?></span>   Chassis  No.  <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_chasis_no'])) echo $vehicle['vehicle_chasis_no']; ?></span>
<br>
<br>


</div>

<br />Respected Sir,<br /><br /></div>
Our  company  has given a  loan  on  <?php  $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo $company['company_name']; ?>  	<?php echo getModelNameById($vehicle['model_id']); ?> bearing  Registration   No.  <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span>  to  <?php echo $customer['customer_name']; ?>  residing  at    <?php echo $customer['customer_address']; ?>.<br><br>                                                                           
 He  has  made  default  in  repaying  the  said  loan .   Thus  we  have  repossessed  the  said  vehicle .  we  have  noticed  that  the  said  vehicle  is  being  played  in  your  jurisdiction  and  thus  we  would  like  to  inform  you  to  avoid  confusion  in case any person come to you to lodge a comlaint against us or any authorised person.   
<br><br>
DATE : <?php echo date('d/m/Y'); ?>
<br>
PLACE : AHMEDABAD
  <div class="lee" style="float:right">For, <?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?><br /><br /> <br> Authorized Signatory
</div>

</div>
</div>
</div>
<div class="clearfix"></div>