<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$file_id=$_GET['id'];
$noc = getNOCByFileId($file_id);
$customer = getCustomerDetailsByFileId($file_id);
$file=getFileDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
$loan_id = getLoanIdFromFileId($file_id);
$loan = getLoanById($loan_id);
$admin_rights=$_SESSION['adminSession']['admin_rights'];

if(!in_array(11,$admin_rights))
exit;
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
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:250px;width:90%;">
<div  style="float:right;font-size:18px;line-height:25px;text-align:right; ">
Loan No <?php echo $file['file_agreement_no']; ?>
<br>
(Valid for 60 days only from date of issue)
<br>
NO OBJECTION CERTIFICATE (NOC)
</div>
<div  style="float:left;font-size:34px;font-weight:bold; ">

</div>
<div  style="text-align:center; font-size:34px;clear:both">

</div>

<div class="notice_address" style="text-align:center;padding-bottom:0px;">

</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;"></div>

                                     								 
                                                                    
                                                                    <div class="date" style="float:left;padding-right:20px;font-size:20px;">Date : <?php echo date('d/m/Y',strtotime($noc['noc_date'])); ?><br /><br>
   TO,
   THE RTO OFFICER <br>
   REGIONAL TRANSPORT OFFICE<br>
   AHMEDABAD                                                                
                                                                
                                                                     </div>
<div style="clear:both;padding-bottom:30px;"></div>  
<b style="position:relative;width:100%;text-align:center;display:block;"> <i> </i></b>                                                                   
<div class="prati" style="padding-bottom:0;">
<br>Dear Sir, <br>
<span style="font-weight:bold;text-align:center;width:100%;font-size:22px;">This is to certify that the following vehicle was under Hypothecation with us :</span>
<br>
Name of borrower : <?php echo strtoupper($customer['customer_name']); ?> <br>
Particular of vehicle : <?php  $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo strtoupper($company['company_name']);  ?> <?php echo strtoupper(getModelNameById($vehicle['model_id'])); ?><br>
Chasis no : <?php echo $vehicle['vehicle_chasis_no']; ?><br>
Vehicle no :  <?php echo $vehicle['vehicle_reg_no']; ?><br>
 </div>

<div style="font-size:20px;line-height:26px;padding-top:100px;line-height:30px;">

The borrower has cleared / settled his/ their liability with us and therefore, we have no objection if the  Hypothecation charge in favour of AADHYA FINANCE SERVICES is cancelled from today i.e 
<?php echo date('d/m/Y',strtotime($noc['noc_date'])); ?> in your records.                                           

</div>
<div style="width:48%;float:left;font-size:20px;line-height:26px;font-weight:bold;padding-top:100px;">
<pre style="font-size:20px;line-height:26px;font-weight:bold">
Thanking you,
<br><br><br>
Checked by,
</pre>
</div>
<div style="width:48%;float:left;font-size:20px;line-height:26px;font-weight:bold;text-align:right;padding-top:100px;">
<pre style="font-size:20px;line-height:26px;font-weight:bold">
Yours faithfully
<br><br><br>
Authorized signatory
</pre>
</div>
<!--<div style="width:50%;float:right;font-size:20px;line-height:26px;font-weight:bold">For your reference, we have printed the specimen 
	Signature of the authorized signatory as below:<br />
    <img src="<?php echo WEB_ROOT."images/noc_kp.jpg"; ?>" />
</div> -->
<div style="clear:both;"></div>


<div style="page-break-after:always"></div>
<?php for($i=0;$i<2;$i++) { ?>
<div >

<span style="float:right;font-size:18px;height:18px;">Ref. No. <?php echo $file['file_agreement_number']; ?></span>
<div style="clear:both;"></div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">FORM 35</div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">(See Rule 61)</div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">(To be submitted in duplicate)</div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">
Notice of Termination of an Agreement of hire purchase / Lease/ Hypothecation </div>
<div class="prati" style="padding-bottom:0;"><pre style="font-size:18px;line-height:22px;">To,
The Registering Authority 
<?php    $cid = $customer['city_id'];
							 		
							       $cityDetails = getCityByID($cid);
								   echo $cityDetails['city_name'];
							 ?>

</pre>

</div>

<div style="font-size:18px;line-height:24px;">
We hereby declare that the agreement o hire purchase / lease / hypothecation entered into between us has terminated. We, therefore, request that the note endorsed in the certificate of Registration <b ><?php echo $customer['customer_name'] ?></b><br />

<pre style="text-align:center;font-weight:bold;padding-top:5px;font-size:20px;padding-bottom:5px;">Vehicle No. <?php echo $vehicle['vehicle_reg_no']; ?>   	  Classis No. <?php echo $vehicle['vehicle_chasis_no']; ?> 	    Engine No. <?php echo $vehicle['vehicle_engine_no']; ?> </pre>

in respected of the said Agreement between us be cancelled.<br />
The Certificate of Registration is enclosed
</div>
<span style="font-size:18px;">Date:_______________</span>
<span style="float:right;padding-right:300px;height:0px;top:-45px;position:relative;">X</span>
<pre style="float:right;text-align:right;font-size:18px;line-height:26px;"><b>Signature of the Registered Owner 
(Formally) <?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?></b>


Signature of the Financier 
Authorized Signatory</pre>
<div style="clear:both;width:100%;padding-top:5px;"></div>
<div style="font-size:18px;line-height:23px;text-align:center">FOR OFFICE USE ONLY</div>
<div style="font-size:18px;line-height:23px;text-align:center">FORM OF INTIMATION TO THE FINANCER</div>
<div style="font-size:18px;line-height:23px;">
R.N. ___________________<br />
The cancellation of the entry of an agreement as requested above is recorded in this office registration record in Form ______________________ and Registration Certificate on____________________(date)
<br /><br /><br /><br />


Date: ___________________							<div style="float:right">Signature of the Registration Authority </div>
<br /><br />
Note: The duplicate application form with the above columns filled in shall be send either to the financier by registered post to be delivered to be financier under proper acknowledgement.  
</div>
<?php if($i==0) { ?><div style="border-bottom:2px solid #000;padding-top:10px;"></div><?php } ?>
<?php } ?>
</div>
</div>
</div>
</div>
<div class="clearfix"></div>