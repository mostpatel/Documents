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
Mob: 7818818825
</div>
<div  style="float:Right; ">
Landline: 079-25466250
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
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">NOTICE</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">Date : <?php echo date('d/m/Y',strtotime($notice['sale_notice_date'])); ?> </div>
<div class="prati" style="padding-bottom:0;">Customer : </div>
<div class="customer_address" style="float:left;width:48%;"><?php echo $notice['customer_name']." (".$file['file_number'].")"; ?>,<br /><pre><?php echo $notice['customer_address'] ?></pre></div>   
<div class="guarantor_address" style="float:right;widht:50%;">Guarantor : <br /><?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div> <div style="clear:both;"></div>           
<table class="notice_details"  border="1" cellpadding="10px" cellspacing="10px">
	<tr>
    	<th colspan="2">Details</th>
        
    </tr>
	<tr>
    	<td width="400px;">Vehicle Number : </td>
		<td><?php echo $vehicle['vehicle_reg_no']; ?></td>
    </tr>
    <tr>
    	<td>Loan Approval Date : </td>
		<td><?php echo date('d/m/Y',strtotime($loan['loan_approval_date'])); ?></td>
    </tr>
    <tr>
    	<td>Loan Amount : </td>
		<td><?php echo number_format($loan['loan_amount'])." Rs"; ?></td>
    </tr>
    <tr>
    	<td>Hire Charge : </td>
		<td><?php echo number_format($profit)." Rs"; ?></td>
    </tr>
    
    <tr>
    	<td>Total Agrrement Value : </td>
		<td><?php echo number_format($total_agreement_value)." Rs"; ?></td>
    </tr>
    
    <tr>
    	<td>Installment Scheme : </td>
		<td><?php  $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo $loan['emi']."  X ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {
									  echo number_format($e['emi'])." X ".$e['duration']."<br>";
									  }
								  
								  } ?>	 </td>
    </tr>
    
    <tr>
    	<td>Total Amount Paid Till Today (<?php echo date('d/m/Y',strtotime($notice['sale_notice_date']));  ?>) : </td>
		<td><?php echo number_format($notice['total_amount_paid'])." Rs"; ?></td>
    </tr>
    
</table>    

<ul>
	<li >NOTE :
    <ol>
    	<li style="margin:20px;">Not Paying Installments.</li>
        <?php if($seize!="error")
{ ?>
        <li style="margin:20px;">Vehicle is seized</li>
         <li style="margin:20px;">Issue Sale Notice</li>
        <?php }
		else { ?>
         <li style="margin:20px;">Issue Collection notice</li>
        <?php } ?>
    </ol></li>
</ul>                           
<ul>
	<li>Others Problems : <?php echo $notice['remarks']; ?></li>
</ul>
</div>
</div>
<div class="clearfix"></div>