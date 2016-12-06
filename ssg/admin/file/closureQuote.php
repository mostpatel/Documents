<?php
if(!isset($_POST['file_id']))
header("Location: ".WEB_ROOT."admin/search");

$file_id=$_POST['file_id'];
$loan_id=$_POST['loan_id'];
$percent=$_POST['closure_percent'];
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
	$customer=getCustomerDetailsByFileId($file_id);
	$contact_nos=$customer['contact_no'];
	$vehicle=getVehicleDetailsByFileId($file_id);
	$customer_id=$customer['customer_id'];

	$closure_amount=getClosureAmountForPercent($loan_id,$percent);
	
	$penalty_days_left=getPenaltyDaysLeftForLoan($loan_id);
	$penalty_amount=$penalty_days_left*25;
	$total_amount=$closure_amount+1500;
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="<?php echo WEB_ROOT; ?>admin/search"><button class="btn btn-warning">Go to Search</button></a></div>
<div class="interest_certificate_container">
<div class="prati">TO,</div>
<div class="saheb_shri"><?php echo $customer['customer_name']; ?>
<br>
<pre><?php echo $customer['customer_address']; ?></pre>
Contact No: <?php foreach($contact_nos as $contact_no) echo $contact_no[0]."<br>";  ?>
</div>
             <div style="margin-top:50px;margin-bottom:50px;" class="subject"> <b >Subject: Pre-Payment of your Loan Account No <?php
							 
							 echo $file['file_number'];
							 ?>	</b>                                                                                   
 </div>
 <div class="main_para" style="margin-bottom:10px;">Dear, <?php echo $customer['customer_name'].",<br>"; 
  ?></div><div> We value your relationship with <?php  echo getOurCompanyNameByID($_SESSION['adminSession']['oc_id']); ?> In response to your request for pre-payment of above mentioned loan no, Please find the details as mentioned below: </div>
<table style="width:90%;padding:5%;margin-top:40px;" class="closureTable">
<tr>
<th style="border:1px solid #000;" align="center">Description</th>
<th style="border:1px solid #000;" align="center">Amount Receivable</th>
</tr>
<tr>
<td style="border:1px solid #000;" align="left">Balance With Interest Uptill Date</td>
<td style="border:1px solid #000;" align="right"><?php echo "Rs. ".$closure_amount; ?></td>
</tr>
<tr>
<td style="border:1px solid #000;" align="left">Processing Charge</td>
<td style="border:1px solid #000;" align="right"><?php echo "Rs. 1500"; ?></td>
</tr>
<tr>
<td style="border:1px solid #000;" align="left"><b>Total Amount</b></td>
<td style="border:1px solid #000;" align="right"><b><?php echo "Rs. ".$total_amount; ?></b></td>
</tr>
</table>        

                                                                                                                                                      


</div>
</div>
<div class="clearfix"></div>